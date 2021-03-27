<?php
/** BitrixVars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 */
global $MESS;
IncludeModuleLangFile(str_replace("\\", "/", __FILE__));
if (class_exists('bitfactory_b24crmbik')) {
	return;
}

class prilepa_b24crmbik extends CModule
{
	var $MODULE_ID = "prilepa.b24crmbik";
	var $MODULE_VERSION = '1.0.0';
	var $MODULE_VERSION_DATE = '2021-03-27 12:00:00';
	var $EVENTS_CLASSNAME = '\Prilepa\B24_CrmBik';
	var $PARTNER_NAME;
	var $PARTNER_URI;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $path;

	function __construct()
	{
		$arModuleVersion = [];

		$this->path = str_replace("\\", "/", __FILE__);
		$this->path = substr($this->path, 0, strlen($this->path) - strlen("/index.php"));

		include($this->path . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->PARTNER_NAME = GetMessage("PRILEPA_AUTHOR");
		$this->PARTNER_URI = "https://prilepa.ru/";
		$this->MODULE_NAME = GetMessage("PRILEPA_B24_CRM_BIK_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("PRILEPA_B24_CRM_BIK_DESCRIPTION");
	}

	function DoInstall()
	{
		$this
			->InstallFiles()
			->InstallEvents();
		RegisterModule($this->MODULE_ID);
	}

	function InstallFiles()
	{
		CopyDirFiles(
			$this->path . '/js/',
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/' . $this->MODULE_ID,
			true,
			true
		);
		return $this;
	}

	function InstallEvents()
	{
		RegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, $this->EVENTS_CLASSNAME, "addJs");
		RegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, $this->EVENTS_CLASSNAME, "addJsOldCards");
		return $this;
	}

	function DoUninstall()
	{
		$this
			->UnInstallEvents()
			->UninstallFiles();
		UnRegisterModule($this->MODULE_ID);
	}

	function UninstallFiles()
	{
		DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/' . $this->MODULE_ID);
		return $this;
	}

	function UnInstallEvents()
	{
		global $DB;
		$dbModuleDepends = $DB->Query('SELECT * FROM b_module_to_module WHERE TO_MODULE_ID = "' . $this->MODULE_ID . '"');
		while ($arModuleDepend = $dbModuleDepends->Fetch()) {
			UnRegisterModuleDependences(
				$arModuleDepend['FROM_MODULE_ID'],
				$arModuleDepend['MESSAGE_ID'],
				$arModuleDepend['TO_MODULE_ID'],
				$arModuleDepend['TO_CLASS'],
				$arModuleDepend['TO_METHOD']
			);
		}
		return $this;
	}
}