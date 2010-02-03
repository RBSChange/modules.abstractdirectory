<?php
abstract class abstractdirectory_BlockMainAction extends block_BlockAction
{
	protected $moduleName;
	protected $componentName;
	protected $viewModuleName;

	/**
     * @param block_BlockContext $context
     * @param block_BlockRequest $request
     * @return void
     */
	public function initialize($context, $request)
	{
		$this->moduleName = $this->getParameter('moduleName');
		$this->componentName = $this->getParameter('componentName');
		$this->viewModuleName  = 'abstractdirectory';
	}

	protected function setComponentName($componentName)
	{
		$this->componentName = $componentName;
	}

	protected function setModuleName($moduleName)
	{
		$this->moduleName = $moduleName;
	}

	protected function setViewModuleName($view)
	{
		$this->viewModuleName = $view;
	}

	/**
	 * This is a entry to add some informations to the template without rewrite execute method
	 *
	 */
	protected function setAdditionalParameters()
	{
	}
	
}