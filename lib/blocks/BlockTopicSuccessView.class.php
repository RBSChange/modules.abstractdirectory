<?php
class abstractdirectory_BlockTopicSuccessView extends abstractdirectory_BlockMainView
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
		$templateName = f_util_StringUtils::ucfirst($this->moduleName) . '-Block-Topic-Success';
		
		// Search the template in your extended module, if not found search in abstractdirectory
		if ( ! is_null(TemplateResolver::getInstance()->setPackageName('modules_'.$this->moduleName)->setMimeContentType('html')->getPath($templateName)) )
		{
			$this->setExternalTemplateName('modules_'.$this->moduleName, $templateName, 'html' );
		}
		else 
		{
			$this->setTemplateName('Abstractdirectory-Block-Topic-Success', 'html' );
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
			$message = null;
			if ( ! is_null($this->getParameter('preferenceDocument') ) )
			{
				$message = $this->getParameter('preferenceDocument')->getMsgforemptyfolder();
			}
			$messageContent = ! is_null($message) ? $message : '';
			$this->setAttribute('messageContent', $messageContent);
		}
	
		$this->setAttribute('itemsCount', $itemCount);

	}
}