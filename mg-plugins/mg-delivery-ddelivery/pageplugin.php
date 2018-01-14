<!--
Доступны переменные:
  $pluginName - название плагина
  $lang - массив фраз для выбранной локали движка
  $options - набор данного плагина хранимый в записи таблиц mg_setting  
-->
<div class="section-<?php echo $pluginName ?>">
    <!-- $pluginName - задает название секции для разграничения JS скрипта -->
    <!-- Тут начинается верстка видимой части станицы настроек плагина-->
    <div class="widget-table-body">
        <div class="wrapper-entity-setting">
            <!-- Тут начинается  Верстка базовых настроек  плагина (опций из таблицы  setting)-->
            <div class="widget-table-action base-settings">
                <ul class="list-option"><!-- список опций из таблицы setting-->
                    <li class="section"><?php echo $lang['SECTION_SERVICE_SETTINGS']; ?></li>
                    <li><label>
                            <span class="custom-text">API Ключ:</span>
                            <input type="text" name="api_key" value="<?php echo $options['api_key'] ?>"
                                   class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_IKN'] ?>"/>
                        </label></li>
                    <li class="section"><?php echo $lang['SECTION_PARAM_SETTINGS']; ?></li>
                    <li><label>
                            <span class="custom-text"><?php echo $lang['PP_LENGTH_PARAM'] ?>:</span>
                            <select name="lengthPropId" value="<?php echo $options['lengthPropId']; ?>"
                                    class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_LENGTH_PARAM'] ?>">
                                <option value="0"><?php echo $lang['PP_SELECT_PROPERTY']; ?></option>
                                <?php foreach ($data['propList'] as $id => $property):
                                    $selected = ($id == $options['lengthPropId']) ? 'selected="selected"' : ''; ?>
                                    <option
                                        value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $property ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span>По умолчанию:</span>
                            <input type="text" name="defaultLength" value="<?php echo $options['defaultLength'] ?>"/>
                        </label></li>
                    <li><label>
                            <span class="custom-text"><?php echo $lang['PP_WIDTH_PARAM'] ?>:</span>
                            <select name="widthPropId" value="<?php echo $options['widthPropId']; ?>"
                                    class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_WIDTH_PARAM'] ?>">
                                <option value="0"><?php echo $lang['PP_SELECT_PROPERTY']; ?></option>
                                <?php foreach ($data['propList'] as $id => $property):
                                    $selected = ($id == $options['widthPropId']) ? 'selected="selected"' : ''; ?>
                                    <option
                                        value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $property ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span>По умолчанию:</span>
                            <input type="text" name="defaultWidth" value="<?php echo $options['defaultWidth'] ?>"/>
                        </label></li>
                    <li><label>
                            <span class="custom-text"><?php echo $lang['PP_DEPTH_PARAM'] ?>:</span>
                            <select name="depthPropId" value="<?php echo $options['depthPropId']; ?>"
                                    class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_DEPTH_PARAM'] ?>">
                                <option value="0"><?php echo $lang['PP_SELECT_PROPERTY']; ?></option>
                                <?php foreach ($data['propList'] as $id => $property):
                                    $selected = ($id == $options['depthPropId']) ? 'selected="selected"' : ''; ?>
                                    <option
                                        value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $property ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span>По умолчанию:</span>
                            <input type="text" name="defaultDepth" value="<?php echo $options['defaultDepth'] ?>"/>
                        </label></li>
                    <li><label>
                            <span class="custom-text">Вес по умолчанию:</span>
                            <input type="text" name="defaultWeight" value="<?php echo $options['defaultWeight'] ?>"
                                   class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_IKN'] ?>"/>
                        </label></li>
                    <input type="hidden" name="delivery_id" value="<?php echo $options['delivery_id'] ?>"/>
                </ul>
                <div class="link-fail">Все поля настроек являются обязательными для заполнения!</div>
                <div class="clear"></div>
                <button class="tool-tip-bottom base-setting-save save-button custom-btn" data-id=""
                        title="<?php echo $lang['T_TIP_SAVE'] ?>">
                    <span><?php echo $lang['SAVE'] ?></span> <!-- кнопка применения настроек -->
                </button>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>