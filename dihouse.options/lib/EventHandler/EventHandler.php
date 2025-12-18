<?php
namespace DihouseOptions\EventHandler;


class EventHandler
{
static function addMenuItem(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;

        if ($USER->IsAdmin()) {

            $aGlobalMenu['global_menu_dihouse_options'] = [
                'menu_id' => 'dihouse_options',
                'text' => 'diHouse настройки',
                'title' => 'diHouse настройки',
                "icon" => "dihouse_option_icon",
                "page_icon" => "dihouse_option_icon",
                'url' => 'settingss.php?lang=ru',
                'sort' => 90,
                'items_id' => 'global_menu_dihouse_options',
                'help_section' => 'dihouse_options',
                'items' => [
                    [
                        'parent_menu' => 'global_menu_dihouse_options',
                        'sort'        => 10,
                        'url'         => 'dhcustomoptions.php?TYPE=MAIN',
                        'text'        => 'Основные настройки',
                        'title'       => 'Основные настройки',
                        "icon" => "sys_menu_icon",
                        "page_icon" => "sys_menu_icon",
                        'items_id'    => 'menu_dihouse_options',
                    ],
                    [
                        'parent_menu' => 'global_menu_dihouse_options',
                        'sort'        => 20,
                        'url'         => 'dhcustomoptions.php?TYPE=CONTACTS',
                        'text'        => 'Контакты',
                        'title'       => 'Контакты',
                        "icon" => "user_menu_icon",
                        "page_icon" => "user_menu_icon",
                        'items_id'    => 'menu_dihouse_options',
                    ],
                    [
                        'parent_menu' => 'global_menu_dihouse_options',
                        'sort'        => 30,
                        'url'         => 'dhcustomoptions.php?TYPE=ORDERS',
                        'text'        => 'Заказы',
                        'title'       => 'Заказы',
                        "icon" => "sale_menu_icon_orders",
                        "page_icon" => "sale_menu_icon_orders",
                        'items_id'    => 'menu_dihouse_options',
                    ],
                    [
                        'parent_menu' => 'global_menu_dihouse_options',
                        'sort'        => 40,
                        'url'         => 'dhcustomoptions.php?TYPE=CATALOG',
                        'text'        => 'Каталог',
                        'title'       => 'Каталог',
                        "icon" => "sale_menu_icon_marketplace",
                        "page_icon" => "sale_menu_icon_marketplace",
                        'items_id'    => 'menu_dihouse_options',
                    ],
                    [
                        'parent_menu' => 'global_menu_dihouse_options',
                        'sort'        => 50,
                        'url'         => 'dhcustomoptions.php?TYPE=MAIL',
                        'text'        => 'Настройки почтовых шаблонов',
                        'title'       => 'Настройки почтовых шаблонов',
                        "icon" => "subscribe_menu_icon",
                        "page_icon" => "subscribe_menu_icon",
                        'items_id'    => 'menu_dihouse_options',
                    ],
                    [
                        'parent_menu' => 'global_menu_dihouse_options',
                        'sort'        => 60,
                        'url'         => 'dhcustomoptions.php?TYPE=INFO',
                        'text'        => 'Информационные сообщения',
                        'title'       => 'Информационные сообщения',
                        "icon" => "form_menu_icon",
                        "page_icon" => "form_menu_icon",
                        'items_id'    => 'menu_dihouse_options',
                    ],
                ],
            ];

        }
    }
}