<?php
abstract class abstractdirectory_BlockMainView extends block_BlockView 
{
	
	/**
	 * Set the template name for the current view
	 *
	 * @param string $templateName
	 * @param string $mimeType
	 * @throws BlockException
	 */
	public final function setExternalTemplateName($packageName, $templateName, $mimeType = 'html')
	{
	    $templateLoader = Loader::getInstance('template')->setMimeContentType($mimeType);

		$templateLoader->setDirectory('templates');

		try
		{
    		try
    		{
    			$this->template = $templateLoader->setPackageName($packageName)->load($templateName);
    		}
    		catch (TemplateNotFoundException $e)
    		{
    			$this->template = $templateLoader->setPackageName('modules_' . 'generic')->load($templateName);
    		}
		}
		catch (TemplateNotFoundException $e)
		{
			throw new BlockException(
               sprintf(
                   'Cannot render block %s_%s : template "%s" not found in %s',
                   $this->getPackageName(),
                   $this->getType(),
                   $templateName,
                   get_class($this)
               )
            );
		}
	}

}