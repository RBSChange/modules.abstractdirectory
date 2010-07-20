<?php
class abstractdirectory_BlockContextuallistSuccessView extends abstractdirectory_BlockMainView
{
	private $moduleName;
	private $componentName;

	/**
	* The initialize() method of BlockView is always called,
	* even if the View is cached.
	*
	* This is useful for modifying the block's context (page title,
	* stylesheets, etc.) while keeping a cached process and/or content.
	*
	* @param block_BlockContext $context
	* @param block_BlockRequest $request
	*/
	public function initialize($context, $request)
	{
		$this->moduleName = $this->getParameter('moduleName');
		$this->componentName = $this->getParameter('componentName');
	}

	/**
	* Mandatory execute method...
	*
	* @param block_BlockContext $context
	* @param block_BlockRequest $request
	*/
	public function execute($context, $request)
	{
		// Search the template in your extended module, if not found search in abstractdirectory
		$templateName = f_util_StringUtils::ucfirst($this->moduleName) . '-Block-ContextualList-Success';

		if ( ! is_null(TemplateResolver::getInstance()->setPackageName('modules_'.$this->moduleName)->setMimeContentType(K::HTML)->getPath($templateName)) )
		{
			$this->setExternalTemplateName('modules_'.$this->moduleName, $templateName, K::HTML );
		}
		else 
		{
			$this->setTemplateName('Abstractdirectory-Block-ContextualList-Success', K::HTML );
		}
		
		$paginator = $this->getParameter('paginator');

		$itemCount = count($paginator);
		if ( $itemCount > 0 )
		{
			$subBlock = $this->getNewBlockInstance()
				->setPackageName('modules_' . $this->moduleName)
				->setType(ucfirst($this->componentName) . 'List')
				->setParameter('moduleName', $this->moduleName)
				->setParameter('componentName', $this->componentName)
				->setDocumentsParameter($paginator);
			$this->setAttribute('itemsContent', $this->forward($subBlock));
		}
		else
		{
			$this->setAttribute('messageContent', $this->getMessageForEmptyFolder());
		}

		$this->setAttribute('itemsCount', $itemCount);
		$this->setAttribute('documentModel', $this->moduleName.'/'.$this->componentName);
	}
	
	/**
	 * @return string
	 */
	protected function getMessageForEmptyFolder()
	{
		$message = null;
		if ($this->getParameter('preferenceDocument') !== null)
		{
			$message = $this->getParameter('preferenceDocument')->getMsgforemptyfolder();
		}
		else if ($this->getParameter('msgKeyForEmptyFolder'))
		{
			$message = f_Locale::translate('&' . $this->getParameter('msgKeyForEmptyFolder') . ';');
		}
		return $message !== null ? $message : '';
	}
}
