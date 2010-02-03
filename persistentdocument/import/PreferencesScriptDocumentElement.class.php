<?php
class abstractdirectory_PreferencesScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return abstractdirectory_persistentdocument_preferences
     */
    protected function initPersistentDocument()
    {
    	$document = ModuleService::getInstance()->getPreferencesDocument('abstractdirectory');
    	if ($document !== null)
    	{
    		return $document;
    	}
    	return abstractdirectory_PreferencesService::getInstance()->getNewDocumentInstance();
    }
}