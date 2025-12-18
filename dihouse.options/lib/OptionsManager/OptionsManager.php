<?php
namespace DihouseOptions\OptionsManager;

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_admin_before.php');

class OptionsManager
{
    public string $moduleId = 'dihouse.options';

    /**
     * Метод устанавливает свойство с переданным значением
     * @param string $name Код свойства
     * @param $value
     * @return void
     */
    public function setOption(string $name, $value, $siteId)
    {
        if(!$value) return;
        Option::set($this->moduleId, $name, $value, $siteId);
    }

    /**
     * Метод возвращает значение свойства
     * @param string $name Код свойства
     * @return bool|string|null
     */
    public function getOption(string $name, $siteId)
    {
        $optionType = $this->getOptionType($name);
        switch ($optionType){
            case "string" || "file":
                return Option::get($this->moduleId, $name, '', $siteId);
            case "bool":
                $value =  Option::get($this->moduleId, $name, '', $siteId);
                return $value=='Y';
            case "html":
                return htmlspecialchars_decode(Option::get($this->moduleId, $name, '', $siteId));
            default:
                return null;
        }

    }

    /**
     * Метод возвращает тип свойства
     * @param string $name Код свойства
     * @return string
     */
    public function getOptionType(string $name): string
    {
        $optionList = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/local/modules/dihouse.options/lib/option_list.json'));
        foreach ($optionList as $section){
            foreach ($section['ITEMS'] as $option){
                if($option['OPTION'] == $name){
                    return $option['TYPE'];
                }
            }
        }
        return '';
    }

    /**
     * Метод возвращает фильтр для списка товаров, в соответсвии с заданными чекбоксами в настройках каталога
     * @return array
     */
    public function getCatalogFilter(): array
    {
        $filter = [];
        $noPicture = $this->getOption('hide_nopicture_goods');
        $noPrice = $this->getOption('hide_noprice_goods');
        $noQuantity = $this->getOption('hide_noquantity_goods');

        if($noPicture){ $filter[] = [
            "LOGIC" => "OR",
            [">PREVIEW_PICTURE" => 0],
            [">DETAIL_PICTUURE" => 0]
        ];
        }
        if($noQuantity){ $filter['>CATALOG_QUANTITY'] =  0;}

        if($noPrice){$filter['>CATALOG_PRICE_1'] = 0;}

        return $filter;
    }

    public function addOption(string $name, $type, $code, $section)
    {
        $optionList = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/local/modules/dihouse.options/lib/option_list.json'), true);

        $optionList[$section]['ITEMS'][] = [
            'NAME' => $name,
            'TYPE' => $type,
            'OPTION' => $code
        ];

        $json = json_encode($optionList);

        $file = $_SERVER["DOCUMENT_ROOT"].'/local/modules/dihouse.options/lib/option_list.json';

        file_put_contents($file, $json);
    }
}
?>
