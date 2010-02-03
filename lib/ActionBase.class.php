<?php
class abstractdirectory_ActionBase extends f_action_BaseAction
{
		
	/**
	 * Returns the abstractdirectory_PreferencesService to handle documents of type "modules_abstractdirectory/preferences".
	 *
	 * @return abstractdirectory_PreferencesService
	 */
	public function getPreferencesService()
	{
		return abstractdirectory_PreferencesService::getInstance();
	}
		
}