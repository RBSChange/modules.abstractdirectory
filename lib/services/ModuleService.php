<?php
class abstractdirectory_ModuleService extends BaseService
{
	/**
	 * @var abstractdirectory_ModuleService
	 */
	private static $instance;

	/**
	 * @return abstractdirectory_ModuleService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	public function initModuleBuilderModule($documentName)
	{
		$tm = f_persistentdocument_TransactionManager::getInstance();
		try
		{
			$tm->beginTransaction();
			$moduleName = $documentName."directory";

			$rootFolderId = ModuleService::getInstance()->getRootFolderId("modulebuilder");
			$moduleService = modulebuilder_ModuleService::getInstance();
			$module = $moduleService->getNewDocumentInstance();
			$module->setLabel($moduleName);
			$module->setUsetopic(true);
			$module->save($rootFolderId);

			$this->createDocuments($documentName, $moduleName, $module);
			$this->createBlocks($documentName, $moduleName, $module);
			$this->createTemplates($documentName, $moduleName, $module);
			$this->createStylesheets($documentName, $moduleName, $module);

			$tm->commit();
		}
		catch (Exception $e)
		{
			$tm->rollback($e);
			throw $e;
		}
	}

	/**
	 * @param String $documentName
	 * @param String $moduleName
	 * @param modulebuilder_persistentdocument_module $module
	 */
	private function createDocuments($documentName, $moduleName, $module)
	{
		$docdef = modulebuilder_DocdefService::getInstance()->getNewDocumentInstance();
		$docdef->setLabel($documentName);
		$docdef->setName($documentName);
		$docdef->setActions("| deactivated reactivate");
		$docdef->setIndexable(true);
		$docdef->setpickable(true);
		$docdef->setHiddenprops("actions,name,indexable,pickable");
		$docdef->save($module->getDocumentFolder()->getId());
		
		$prefdoc = modulebuilder_DocdefService::getInstance()->getNewDocumentInstance();
		$prefdoc->setLabel("preferences");
		$prefdoc->setName("preferences");
		$prefdoc->setExtend("modules_abstractdirectory/preferences");
		$prefdoc->setIndexable(false);
		$prefdoc->setHiddenprops("actions,name,indexable,pickable");
		$prefdoc->save($module->getDocumentFolder()->getId());
	}

	/**
	 * @param String $documentName
	 * @param String $moduleName
	 * @param modulebuilder_persistentdocument_module $module
	 */
	private function createTemplates($documentName, $moduleName, $module)
	{
		$templateFolder = $module->getTemplateFolder();

		$templateService = modulebuilder_TemplateService::getInstance();
		$template = $templateService->getNewDocumentInstance();
		$template->setLabel("Detail");
		$template->setName(ucfirst($moduleName)."-Block-".ucfirst($documentName)."-Success");
		$template->setContent('<h3 class="title" tal:attributes="id item/getId" tal:content="item/getLabel" />
<!-- Rajouter ici les autres attributs -->');
		$template->setHiddenprops("name,navigator,navigatorversion,format");
		$template->save($templateFolder->getId());
	}

	/**
	 * @param String $documentName
	 * @param String $moduleName
	 * @param modulebuilder_persistentdocument_module $module
	 */
	private function createBlocks($documentName, $moduleName, $module)
	{
		$blockFolder = $module->getBlockFolder();
			
		// detail block
		$detailBlock = modulebuilder_BlockService::getInstance()->getNewDocumentInstance();
		$detailBlock->setLabel("Detail");
		$detailBlock->setName("Detail");
		$detailBlock->setDropable(false);
		$detailBlock->setCode('class '.$moduleName.'_Block'.ucfirst($documentName).'Action extends abstractdirectory_BlockItemAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName(\''.$moduleName.'\');
		$this->setComponentName(\''.$documentName.'\');
	}
}');
		$detailBlock->setHiddenprops("code");
		$detailBlock->save($blockFolder->getId());
			
		// contextual list block
		$contextualListBlock = modulebuilder_BlockService::getInstance()->getNewDocumentInstance();
		$contextualListBlock->setLabel("Contextuallist");
		$contextualListBlock->setName("Contextuallist");
		$contextualListBlock->setDropable(true);
		$contextualListBlock->setCode('class '.$moduleName.'_BlockContextuallistAction extends abstractdirectory_BlockContextuallistAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName(\''.$moduleName.'\');
		$this->setComponentName(\''.$documentName.'\');
	}
}');
		$contextualListBlock->setHiddenprops("code");
		$contextualListBlock->save($blockFolder->getId());
			
		// list block
		$listBlock = modulebuilder_BlockService::getInstance()->getNewDocumentInstance();
		$listBlock->setLabel("List");
		$listBlock->setName("List");
		$listBlock->setDropable(false);
		$listBlock->setCode('class '.$moduleName.'_Block'.ucfirst($documentName).'ListAction extends abstractdirectory_BlockListAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName(\''.$moduleName.'\');
		$this->setComponentName(\''.$documentName.'\');
	}
}');
		$listBlock->setHiddenprops("code");
		$listBlock->save($blockFolder->getId());
			
		// topic block
		$topicBlock = modulebuilder_BlockService::getInstance()->getNewDocumentInstance();
		$topicBlock->setLabel("Topic");
		$topicBlock->setName("Topic");
		$topicBlock->setDropable(false);
		$topicBlock->setCode('class '.$moduleName.'_BlockTopicAction extends abstractdirectory_BlockTopicAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName(\''.$moduleName.'\');
		$this->setComponentName(\''.$documentName.'\');
	}
}');
		$topicBlock->setHiddenprops("code");
		$topicBlock->save($blockFolder->getId());
	}
	
	/**
	 * @param String $documentName
	 * @param String $moduleName
	 * @param modulebuilder_persistentdocument_module $module
	 */
	private function createStylesheets($documentName, $moduleName, $module)
	{
		$styleFolder = $module->getStyleFolder();
		$stylesheet = modulebuilder_StylesheetService::getInstance()->getNewDocumentInstance();
		$stylesheet->setLabel("frontoffice");
		$frontofficeContent = "";
		foreach ($module->getBlockFolder()->getChildrenPublishedBlocks() as $block)
		{
			$frontofficeContent .= "div.modules-".$module->getName()."-".$block->getName()." {\n}\n";
		}
		$stylesheet->setContent($frontofficeContent);
		$stylesheet->save($styleFolder->getId());
	}
}
?>