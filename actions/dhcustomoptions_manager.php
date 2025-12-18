<?php

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use DihouseOptions\OptionsManager\OptionsManager;
		
require $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php';
Loader::includeModule("dihouse.options");

$status = 'ok';
$formType='';
$siteId = '';
try {
    $optionsManager = new OptionsManager();

    if ($_POST['ACTION'] && $_POST['ACTION']=="ADD_NEW") {
        $name = $_POST['new_option_name'];
        $type = $_POST['new_option_type'];
        $code = $_POST['new_option_code'];

        $section = $_POST['FORM_TYPE'];

        if(!$name || !$type || !$code) {
            $status = 'error';
            echo 'Введите название и выберите тип настройки';
            throw new Exception('Введите название и выберите тип настройки');
        }

        $optionsManager->addOption($name, $type, $code, $section);
    }

    foreach ($_POST as $option => $value) {
        if ($option == 'FORM_TYPE') {
            $formType = $value;
            continue;
        }
        if ($option == 'SITE_ID') {
            $siteId = $value;
            continue;
        }
        $optionsManager->setOption($option, $value, $siteId);
    }

    foreach ($_FILES as $option => $value) {
        if ($value['error'] == 0) {
            $oldFileId = Option::get("dihouse.options", $option, '',$siteId);
            CFile::Delete($oldFileId);

            $newFile = $value;
            $newFile['MODULE_ID'] = 'dihouse.options';
            $newFileId = CFile::SaveFile($newFile, "dihouse_options");
            $optionsManager->setOption($option, $newFileId, $siteId);
        } elseif ($_POST[$option . '_del']) {
            $oldFileId = Option::get("dihouse.options", $option, '', $siteId);
            CFile::Delete($oldFileId);
            $optionsManager->setOption($option, 'NULL', $siteId);
        }
    }
} catch (Exception $e)
{
    $status = 'error';
    echo $e->getMessage();
}
LocalRedirect("/bitrix/admin/dhcustomoptions.php?mess=".$status."&TYPE=".$formType."&SITE_ID=".$siteId);

