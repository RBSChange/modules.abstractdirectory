<?php
class abstractdirectory_BlockDetailAction extends abstractdirectory_BlockMainAction
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
		
		$this->setAdditionalParameters();
		
		if ( $context->inBackofficeMode() )
		{
			return array($this->viewModuleName, 'Detail', block_BlockView::DUMMY);
		}
		else 
		{
			return array($this->viewModuleName, 'Detail', block_BlockView::SUCCESS);
		}
		
	}

}