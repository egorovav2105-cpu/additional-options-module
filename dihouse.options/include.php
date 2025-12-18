<?php

Bitrix\Main\Loader::registerAutoloadClasses(
    "dihouse.options",
    array(
        "DihouseOptions\\OptionsManager\\OptionsManager" => "/lib/OptionsManager/OptionsManager.php",
        "DihouseOptions\\FormPainter\\FormPainter" => "/lib/FormPainter/FormPainter.php",
        "DihouseOptions\\EventHandler\\EventHandler" => "/lib/EventHandler/EventHandler.php",
    ));