<?php
class abstractdirectory_BlockContextuallistAction extends abstractdirectory_BlockMainAction
{

	/**
   * @param block_BlockContext $context
   * @param block_BlockRequest $request
   * @return String view name
   */
	public final function execute($context, $request)
	{
		// Get the container
		$container = $this->getContainer($context, $request);
		f_util_TypeValidator::check($container, 'website_persistentdocument_topic');
		$this->setParameter('container', $container);

		$permissionName = $this->getPermissionName();
		if (!is_null($permissionName))
		{
			$user = users_UserService::getInstance()->getCurrentFrontEndUser();
		}
		else
		{
			$user = null;
		}

		$items = array();

		if (is_null($permissionName) || $this->getPermissionService()->hasPermission($user, $permissionName, $container->getId()))
		{
			$items = $this->getOrderedItems($container);

			// Get the preference of module
			$preferenceDocument = ModuleService::getInstance()->getPreferencesDocument($this->moduleName);
			if ( !is_null( $preferenceDocument) )
			{
				$this->setParameter('preferenceDocument', $preferenceDocument);
				$nbItemPerPage = ! is_null($preferenceDocument->getNbitemperpage()) ? $preferenceDocument->getNbitemperpage() : 10;
			}
			else
			{
				$nbItemPerPage = 10;
			}

			$this->manageNavigation($container, $context);

		}

		// Set the paginator
		$paginator = new paginator_Paginator($this->moduleName, $request->getParameter(paginator_Paginator::REQUEST_PARAMETER_NAME, 1), $items, $nbItemPerPage);
		$this->setParameter('paginator', $paginator);
		$this->setParameter('moduleName', $this->moduleName);
		$this->setParameter('componentName', $this->componentName);

		$this->setAdditionalParameters();

		if (!$this->hasParameter('displaycontainerlabel'))
		{
			$this->setParameter('displaycontainerlabel', true);
		}
		else 
		{
			// TODO: fix data types earlier according to declared parameters in blocks.xml
			$this->setParameter('displaycontainerlabel', f_util_Convert::fixDataType($this->getParameter('displaycontainerlabel')));
		}
		return array($this->viewModuleName, ucfirst($this->getType()), block_BlockView::SUCCESS);
	}

	/**
	 * Return the name of the permission can be checked or null for public item
	 * @return String
	 */
	protected function getPermissionName()
	{
		return null;
	}

	/**
	 * @return f_permission_PermissionService
	 */
	protected final function getPermissionService()
	{
		return f_permission_PermissionService::getInstance();
	}

	/**
	 * @param website_persistentdocument_topic
	 * @return Array f_persistentdocument_PersistentDocument
	 */
	protected function getOrderedItems($container)
	{
		$query = $this->getPersistentProvider()->createQuery('modules_' . $this->moduleName . '/' . $this->componentName);
		$query->add(Restrictions::childOf($container->getId()))
			->add(Restrictions::published());

		if ( $this->hasParameter('order') && $this->getParameter('order') == 'alpha' )
		{
			// Construct the query to get the list of items
			$query->addOrder(Order::asc('document_label'));
		}

		return $query->find();
	}

	/**
	 * This method is use to retrieve the container. The container must be of website_persistentdocument_topic type
	 *
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
	 * @return website_persistentdocument_topic
	 */
	protected function getContainer($context, $request)
	{
		// Get the parent topic
		$ancestor = $context->getAncestors();
        $topicId = f_util_ArrayUtils::lastElement($ancestor);

        return DocumentHelper::getDocumentInstance($topicId);
	}

	/**
	 * This method is used to manage the navigation in block. By default one navigation on the right of block.
	 *
	 * @param website_persistentdocument_topic $container
	 * @param block_BlockContext $context
	 */
	protected function manageNavigation($container, $context)
	{
		// Check if navigation is required
		if ( $this->hasParameter('navigation') && $this->getParameter('navigation') == 'navigation' )
		{
			$this->generateNavigationMenu($container, $context);
		}
	}

	/**
	 * Generate the default menu to display in right of block
	 *
	 * @param website_persistentdocument_topic $container
	 * @param block_BlockContext $context
	 */
	protected function generateNavigationMenu($container, $context)
	{
		// Get TreeService instance
		$treeService = TreeService::getInstance();

		// Load the list of document
		$containerNode = $treeService->getInstanceByDocument($container);

		// Create a menu
		$menu = new Menu();
		$pp = $this->getPersistentProvider();
		$modelName = 'modules_' . $this->moduleName . '/'.$this->componentName;

		$permissionName = $this->getPermissionName();
		if (!is_null($permissionName))
		{
			$permissionService = $this->getPermissionService();
			$user = users_UserService::getInstance()->getCurrentFrontEndUser();
		}

		foreach ($containerNode->getChildren('modules_website/topic') as $node)
		{
			if (!is_null($permissionName) && !$permissionService->hasPermission($user, $permissionName, $node->getId()))
			{
				continue;
			}
			$query = $pp->createQuery($modelName);
			$query->setProjection(Projections::rowCount())
				->add(Restrictions::descendentOf($node->getId()))
				->add(Restrictions::published());
			$rows = $query->find();

			if ($rows[0]['rowcount'] > 0)
			{
				$menuItem = new website_MenuItem();
				$menuItem->setId($node->getId())
					->setLabel($node->getlabel() . ' (' . $rows[0]['rowcount'] . ')')
					->setUrl( $this->getUrlForSubNavigation($container, $context, $node) );

				$menu->append($menuItem);
			}

		}

		$this->setParameter('menu', $menu);
	}

	/**
	 * Return the Url of page to display the sublist of element
	 *
	 * @param website_persistentdocument_topic $container
	 * @param block_BlockContext $context
	 * @param f_persistentdocument_PersistentTreeNode $subContainer
	 * @return String
	 */
	protected function getUrlForSubNavigation($container, $context, $subContainer)
	{
		$tagName = 'functional_' . $this->moduleName . '_' . $this->componentName . '-list';
		$page = $this->getPersistentProvider()->createQuery('modules_website/page')
			->add(Restrictions::published())
			->add(Restrictions::childOf($subContainer->getId()))
			->add(Restrictions::hasTag($tagName))->findUnique();

		return LinkHelper::getUrl($page, $this->getLang(), array($this->moduleName.'Param' => array('page' => 1)));
	}
}