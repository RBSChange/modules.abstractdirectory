<?php
class abstractdirectory_BlockTopicAction extends abstractdirectory_BlockContextuallistAction
{
	
	/**
	 * This method is use to retrieve the container. The container must be of website_persistentdocument_topic type
	 *
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
	 * @return website_persistentdocument_topic
	 */
	protected function getContainer($context, $request)
	{
		// Define the source container
		$sourceContainerId = $this->getDocumentIdParameter();
		$this->setParameter('sourceContainerId', $sourceContainerId);
		
		// Define the current container
		if ( $request->hasParameter('container') )
		{
			$containerId = $request->getParameter('container');
		}
		else
		{
			$containerId = $sourceContainerId;
		}
		return DocumentHelper::getDocumentInstance($containerId);
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
			$this->generateHorizontalMenu($container, $context);
		}
	}
	
	/**
	 * Generate the specific horizontal menu use on the top of block
	 *
	 * @param website_persistentdocument_topic $container
	 * @param block_BlockContext $context
	 */
	protected function generateHorizontalMenu($container, $context)
	{
		$sourceContainerId = $this->getParameter('sourceContainerId');
		
		// Get TreeService instance
		$treeService = TreeService::getInstance();

		// Load the list of document
		$containerNode = $treeService->getInstanceByDocument($container);

		// If $containerId != $sourceContainerId display a link to go to back
		if ($container->getId() != $sourceContainerId)
		{
			$containerParent = $container->getDocumentService()->getParentOf($container);
			$this->setParameter('containerParentParam', array($this->moduleName.'Param' => array('container' => $containerParent->getId(), 'page' => 1) ));

			// Create a horizontal menu
			$horizontalMenu = new Menu();

			// Get the container ancestor node
			$ancestorsNode = $containerNode->getAncestors();

			// Reverse the ancestor array to begin the foreach by the end of ancestor
			$ancestorsNode = array_reverse($ancestorsNode);

			// Add the current without link
			$menuItem = new website_MenuItem();
			$menuItem->setId($containerNode->getId())
				->setLabel($containerNode->getlabel())
				->setUrl(null);
			$horizontalMenu->append($menuItem);

			foreach ($ancestorsNode as $node)
			{
				$menuItem = new website_MenuItem();
				$menuItem->setId($node->getId())
					->setLabel($node->getlabel())
					->setUrl( $this->getUrlForSubNavigation($container, $context, $node) );

				$horizontalMenu->append($menuItem);

				if ( $node->getId() == $treeService->getInstanceByDocumentId($sourceContainerId)->getId() )
				{
					break;
				}
			}

			// Reverse the final menu
			$horizontalMenu = array_reverse($horizontalMenu->getArrayCopy());

			$this->setParameter('horizontalMenu', $horizontalMenu);

		}

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
		return LinkHelper::getDocumentUrl($context->getPersistentPage(), $context->getLang(), array($this->moduleName.'Param' => array('container' => $subContainer->getId(), 'page' => 1)));
	}
}