<?php
/**
 * @date Thu, 21 Jun 2007 16:05:01 +0200
 * @author intessit
 */
class abstractdirectory_PreferencesService extends f_persistentdocument_DocumentService
{
	/**
	 * @var abstractdirectory_PreferencesService
	 */
	private static $instance;

	/**
	 * @return abstractdirectory_PreferencesService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return abstractdirectory_persistentdocument_preferences
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_abstractdirectory/preferences');
	}

	/**
	 * Create a query based on 'modules_abstractdirectory/preferences' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_abstractdirectory/preferences');
	}
}