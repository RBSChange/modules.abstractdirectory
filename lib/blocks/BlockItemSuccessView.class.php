<?php
class abstractdirectory_BlockItemSuccessView extends block_BlockView
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
    	$this->moduleName = $this->getModuleName();
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
    	$this->setTemplateName(f_util_StringUtils::ucfirst($this->moduleName) . '-Block-' . f_util_StringUtils::ucfirst($this->componentName) . '-Success');
    	$this->setAttribute( 'item', $this->getDocumentParameter());
    }
}