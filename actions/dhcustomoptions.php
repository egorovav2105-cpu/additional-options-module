<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_admin_before.php');

Loader::includeModule('dihouse.options');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if($_REQUEST["mess"] == "ok"){
    CAdminMessage::ShowMessage(array("MESSAGE"=>"Данные сохранены", "TYPE"=>"OK"));
}
if($_REQUEST["mess"] == "error") {
    CAdminMessage::ShowMessage(array("MESSAGE" => "Ошибка записи свойств", "TYPE" => "ERROR"));
}

if($_GET['SITE_ID']){
    $siteId = $_GET['SITE_ID'];
}else{
    $sites = CSite::GetList($by="sort", $order="desc", array("DOMAIN"=>$_SERVER['SERVER_NAME']));
    while ($site = $sites->Fetch())
    {
        $siteId = $site["LID"];
    }
}
$formPainter = new \DihouseOptions\FormPainter\FormPainter();
$formPainter->paintForm($_GET['TYPE'], $siteId);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
