<?php
namespace DihouseOptions\FormPainter;
use Bitrix\Main\Config\Option;

class FormPainter
{
    /**
     * Метод отрисовывает форму редактирования свойств
     * @param string $formType Код раздела меню
     * @return void
     */
    public function paintForm(string $formType, string $siteId)
    {?>
        <?php
        $optionList = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/local/modules/dihouse.options/lib/option_list.json'), true);
        $formTitle = $optionList[$formType]['TITLE'];
        $sites = \CSite::GetList($by="sort", $order="desc", []);

        \CJSCore::init("color_picker");?>
        <form method="POST" action="/bitrix/admin/dhcustomoptions_manager.php?TYPE=MAIL" enctype="multipart/form-data" id="optionsForm">
			<?=bitrix_sessid_post()?>
            <input type="hidden" value="<?=$formType?>" name = "FORM_TYPE">
            <input type="hidden" value="<?=$siteId?>" name = "SITE_ID">
            <div class="adm-detail-tabs-block" id="tabControl_tabs" style="left: 0px;">
                <?php while ($site = $sites->Fetch()):?>
                    <span class="adm-detail-tab <?php if($site['SITE_ID'] == $siteId):?>adm-detail-tab-active<?php endif;?>" onclick="document.location.href = '/bitrix/admin/dhcustomoptions.php?TYPE=<?=$formType?>&SITE_ID=<?=$site['SITE_ID']?>'"><?=$site['NAME']?></span>
                <?php endwhile;?>
            </div>
            <div class="adm-detail-block" id="tabControl_layout">
                <div class="adm-detail-tabs-block" id="tabControl_tabs">
                </div>
                <div class="adm-detail-content-wrap">
                    <div class="adm-detail-content">
                        <div class="adm-detail-title"><?=$formTitle?></div>
                        <div class="adm-detail-content-item-block">
                            <table class="adm-detail-content-table edit-table">
                                <tbody>
                                    <?php
                                    $colorPickers=[];
                                    foreach ($optionList[$formType]['ITEMS'] as $option):?>
                                        <?php if($option['IS_HEADING']):?>
                                            <tr class="heading" id="tr_IBLOCK_ELEMENT_PROP_VALUE">
                                                <td colspan="2">
                                                    <?=$option['NAME']?>:
                                                </td>
                                            </tr>
                                        <?php else:?>
                                        <?php $optionValue = Option::get("dihouse.options", $option['OPTION'],'', $siteId);?>
                                        <tr>
                                            <td valign="top" width="50%" class="adm-detail-content-cell-l"><?=$option['NAME']?> [<?=$option['OPTION']?>]</td>
                                            <td valign="top" width="50%" class="adm-detail-content-cell-r">
                                                <?php switch ($option['TYPE']){
                                                    case 'bool':?>
                                                        <input type="checkbox"  class="adm-designed-checkbox-label" <?=($optionValue=='Y') ? 'checked' : ''?> name="<?=$option['OPTION']?>" data-type="<?=$option['TYPE']?>">
                                                    <?php break;
                                                    case 'html':?>
                                                        <textarea name="<?=$option['OPTION']?>" cols="80" rows="<?=($option['ROWS']) ? $option['ROWS'] : '20'?>" data-type="<?=$option['TYPE']?>"><?=($optionValue) ? htmlspecialchars_decode($optionValue) : ''?></textarea>
                                                        <?php break;
                                                    case 'file':?>
                                                        <?=\CFile::InputFile($option['OPTION'], 20, $optionValue);?>
                                                        <?php echo "<br>";
                                                        if($optionValue){
                                                            $path = \CFile::GetPath($optionValue);?>
                                                            <img src="<?=$path?>" style = "max-height: 200px; max-width: 200px;">
                                                        <?php }?>
                                                        <?php break;
                                                    case 'color':?>
                                                        <?php $colorPickers[]=$option['OPTION'];?>
                                                        <input type="text" name="<?=$option['OPTION']?>" size="10" value="<?=($optionValue) ? $optionValue : '#ffffff'?>"> <button type="button" id="<?=$option['OPTION']?>">Выбрать цвет</button><p>
                                                        <?php break;
                                                    default:?>
                                                        <input type="text" value="<?=($optionValue) ? $optionValue : ''?>" size="<?=($option['WIDTH']) ? $option['WIDTH'] : '20'?>" name="<?=$option['OPTION']?>" data-type="<?=$option['TYPE']?>">
                                                    <?}?>
                                                <?php if ($option['DESCRIPTION']):?>
                                                    <div class="adm-info-message-wrap">
                                                        <div class="adm-info-message">
                                                            <?=$option['DESCRIPTION']?>
                                                        </div>
                                                    </div>
                                                <?endif;?>

                                            </td>
                                        </tr>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="adm-detail-content-btns-wrap" id="tabControl_buttons_div">
                        <div class="adm-detail-content-btns">
                            <input type="submit" name="save" value="Сохранить">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form method="POST" action="/bitrix/admin/dhcustomoptions_manager.php" enctype="multipart/form-data" id="optionsForm">
            <?=bitrix_sessid_post()?>
            <input type="hidden" value="<?=$formType?>" name = "FORM_TYPE">
            <input type="hidden" value="ADD_NEW" name = "ACTION">
            <div class="adm-detail-block" id="tabControl_layout">
                <div class="adm-detail-tabs-block" id="tabControl_tabs">
                </div>
                <div class="adm-detail-content-wrap">
                    <div class="adm-detail-content">
                        <div class="adm-detail-title">Создать новую настройку</div>
                        <div class="adm-detail-content-item-block">
                            <table class="adm-detail-content-table edit-table">
                                <tbody>
                                        <tr>
                                            <td valign="top" width="50%" class="adm-detail-content-cell-l">Название</td>
                                            <td valign="top" width="50%" class="adm-detail-content-cell-r">
                                                <input type="text" value="" size="20" name="new_option_name" placeholder="Название">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="50%" class="adm-detail-content-cell-l">Символьный код</td>
                                            <td valign="top" width="50%" class="adm-detail-content-cell-r">
                                                <input type="text" value="" size="20" name="new_option_code" placeholder="Код">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" width="50%" class="adm-detail-content-cell-l">Тип</td>
                                            <td valign="top" width="50%" class="adm-detail-content-cell-r">
                                                <select name="new_option_type">
                                                    <option value="string">Строка</option>
                                                    <option value="html">HTML</option>
                                                    <option value="file">Файл</option>
                                                    <option value="color">Цвет</option>
                                                    <option value="bool">Да/Нет</option>
                                                </select>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="adm-detail-content-btns-wrap" id="tabControl_buttons_div">
                        <div class="adm-detail-content-btns">
                            <input type="submit" name="save" value="Сохранить">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            (function() {
                "use strict";
                let picker = new BX.ColorPicker({
                    bindElement: null,
                    defaultColor: "#000000",
                    popupOptions: {
                        offsetTop: 10,
                        offsetLeft: 10,
                        angle: true,
                        events: {
                            onPopupClose: function() {
                                console.log("closed");
                            },
                            onPopupShow: function() {
                                console.log("open");
                            }
                        }
                    }
                });
                <?php foreach($colorPickers as $colorPicker):?>
                    BX.bind(BX("<?=$colorPicker?>"), "click", onButtonClick);
                <?php endforeach;?>

                function onButtonClick(event)
                {
                    let target = event.target;
                    let input = target.previousElementSibling;
                    picker.open({
                        selectedColor: BX.type.isNotEmptyString(input.value) ? input.value : null,
                        bindElement: target,
                        onColorSelected: onColorSelected.bind(input)
                    });
                }

                function onColorSelected(color, picker)
                {
                    this.value = color;
                }
            })();
        </script>
    <?php }
}
