<?php
class abstractdirectory_BlockListSuccessView extends abstractdirectory_BlockMainView
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
		$templateName = f_util_StringUtils::ucfirst($this->moduleName) . '-Block-' . f_util_StringUtils::ucfirst($this->componentName) . 'List-Success';

		// Search the template in your extended module, if not found search in abstractdirectory
		if ( ! is_null(TemplateResolver::getInstance()->setPackageName('modules_'.$this->moduleName)->setMimeContentType(K::HTML)->getPath($templateName)) )
		{
			$this->setExternalTemplateName('modules_'.$this->moduleName, $templateName, K::HTML );
		}
		else 
		{
			$this->setTemplateName('Abstractdirectory-Block-List-Success', K::HTML );
		}
		
		$this->setAttribute( 'items', $this->getDocumentsParameter() );
		$this->setAttribute( 'moduleName', $this->moduleName );
		$this->setAttribute( 'componentName', $this->componentName );
	}
}
