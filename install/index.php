<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);
class Dihouse_Options extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'dihouse.options';
        $this->MODULE_NAME = Loc::getMessage('DIH_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DIH_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('DIH_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://di-house.ru';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installFiles();
        $this->installDB();
        $this->installEvents();
    }

    public function doUninstall()
    {
        $this->unInstallFiles();
        $this->uninstallDB();
        $this->unInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/dihouse.options/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/dihouse.options/install/themes", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes", true,true);
        return true;
    }

    public function unInstallFiles()
    {

        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/dihouse.options/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"]."/local/modules/dihouse.options/install/themes", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes");
        DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"]."/upload/dihouse_options");

        return true;
    }

    function installEvents()
    {
        RegisterModuleDependences('main', 'OnBuildGlobalMenu', 'dihouse.options', '\\DihouseOptions\\EventHandler\\EventHandler', 'addMenuItem');
    }

    function unInstallEvents()
    {
        UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', 'dihouse.options', '\\DihouseOptions\\EventHandler\\EventHandler', 'addMenuItem');
    }

}
