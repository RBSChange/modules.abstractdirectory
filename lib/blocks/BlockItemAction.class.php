<?php
class abstractdirectory_BlockItemAction extends abstractdirectory_BlockMainAction
{

	/**
   * @param block_BlockContext $context
   * @param block_BlockRequest $request
   * @return String view name
   */
	public function execute($context, $request)
	{
		$this->setParameter('componentName', $this->componentName);
		
		$this->setAdditionalParameters();
		
		return array($this->viewModuleName, 'Item', block_BlockView::SUCCESS);
	}
}