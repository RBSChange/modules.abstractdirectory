<?php
class abstractdirectory_BlockListAction extends abstractdirectory_BlockMainAction
{


	/**
     * @param block_BlockContext $context
     * @param block_BlockRequest $request
     * @return String view name
     */
	public function execute($context, $request)
	{
		$this->setParameter('moduleName', $this->moduleName);
		$this->setParameter('componentName', $this->componentName);
		return array($this->viewModuleName, 'List', block_BlockView::SUCCESS);
	}
}