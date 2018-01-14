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
          <li>
            <label class="oik-delivery-dellin">
              <span class="custom-text" style="width:100px; float:left"><?php echo $lang['PP_API_KEY'];?>:</span>
              <input type="text" name="api_key" 
                     value="<?php echo $options['api_key']?>" 
                     style="width:200px;" 
                     title="<?php echo $lang['T_TIP_PP_API_KEY'] ?>" />
            </label>
          </li>
          <li class="section"><?php echo $lang['SECTION_PARAM_SETTINGS']; ?></li>
          <li>
            <label>
              <span class="custom-text" style="float: left;width: 150px;"><?php echo $lang['PP_LENGTH_PARAM'] ?>  (м):</span>
              <select name="lengthPropId" style="width:200px; float:left;"  value="<?php echo $options['lengthPropId']; ?>"
                      class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_LENGTH_PARAM'] ?>">
                <option value="0"><?php echo $lang['PP_SELECT_PROPERTY']; ?></option>
                <?php foreach ($data['propList'] as $id => $property):
                  $selected = ($id == $options['lengthPropId']) ? 'selected="selected"' : '';
                  ?>
                  <option
                    value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $property ?></option>
                <?php endforeach; ?>
              </select>
              <span style="float:left; width:150px">По умолчанию:</span>
              <input type="text" name="defaultLength" style="width:200px;"  value="<?php echo $options['defaultLength'] ?>"/>
            </label>
          </li>
          <li>
            <label>
              <span class="custom-text" style="float:left; width:150px"><?php echo $lang['PP_WIDTH_PARAM'] ?>  (м):</span>
              <select name="widthPropId" value="<?php echo $options['widthPropId']; ?>"
                      class="tool-tip-right" style="width:200px;float:left;"  title="<?php echo $lang['T_TIP_PP_WIDTH_PARAM'] ?>">
                <option value="0"><?php echo $lang['PP_SELECT_PROPERTY']; ?></option>
                <?php foreach ($data['propList'] as $id => $property):
                  $selected = ($id == $options['widthPropId']) ? 'selected="selected"' : '';
                  ?>
                  <option
                    value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $property ?></option>
                <?php endforeach; ?>
              </select>
              <span style="float:left; width:150px">По умолчанию:</span>
              <input type="text" name="defaultWidth" value="<?php echo $options['defaultWidth'] ?>" style="width:200px;" />
            </label>
          </li>
          <li>
            <label>
              <span class="custom-text" style="float:left; width:150px"><?php echo $lang['PP_DEPTH_PARAM'] ?> (м) :</span>
              <select name="depthPropId" value="<?php echo $options['depthPropId']; ?>"
                      class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_DEPTH_PARAM'] ?>" style="width:200px;float:left;" >
                <option value="0"><?php echo $lang['PP_SELECT_PROPERTY']; ?></option>
                <?php foreach ($data['propList'] as $id => $property):
                  $selected = ($id == $options['depthPropId']) ? 'selected="selected"' : '';?>
                  <option
                    value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $property ?></option>
                <?php endforeach; ?>
              </select>
              <span style="float:left; width:150px">По умолчанию:</span>
              <input type="text" name="defaultDepth" value="<?php echo $options['defaultDepth'] ?>" style="width:200px;" />
            </label>
          </li>
          <li>
            <label>
              <span class="custom-text" style="float:left; width:150px">Вес (кг):</span>
              <input type="text" name="defaultWeight" value="<?php echo $options['defaultWeight'] ?>"
                     class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_IKN'] ?>" style="width:200px;" /> 
            </label>
          </li>
          <li class="section" style="">Фиксированная стоимость доставки:</li>
          <li>
            <label>
              <span class="custom-text" style="float:left; width:150px">Курьерская доставка:</span>
              <input type="text" name="courier_cost" value="<?php echo $options['courier_cost'] ?>"
                     class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_IKN'] ?>" style="width:200px;" />
            </label>
          </li>
          <li>
            <label>
              <span class="custom-text" style="float:left; width:150px">Самовывоза:</span>
              <input type="text" name="office_cost" value="<?php echo $options['office_cost'] ?>"
                     class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_IKN'] ?>" style="width:200px;" />
            </label>
          </li>
          <li>
            <label>
              <span class="custom-text" style="float:left; width:150px">Почта:</span>
              <input type="text" name="post_cost" value="<?php echo $options['post_cost'] ?>"
                     class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_IKN'] ?>" style="width:200px;" />
            </label>
          </li>
          <li class="section">Дополнительные настройки:</li>
          <li>
            <label>
              <span class="custom-text" style="float:left;">Увеличивать сроки доставки на:</span>
              <input type="text" name="delivery_margin" value="<?php echo $options['delivery_margin'] ?>"
                     class="tool-tip-right" title="<?php echo $lang['T_TIP_PP_IKN'] ?>" style="width:200px; float:left;" /> дня(ей)
            </label>
          </li>
          <input type="hidden" name="delivery_id" value="<?php echo $options['delivery_id'] ?>" style="width:200px;" />
        </ul>
        <div class="link-fail">Все поля настроек являются обязательными для заполнения!</div>
        <div class="clear"></div>
        <button class="tool-tip-bottom base-setting-save save-button custom-btn button success" style="margin: 10px;" data-id=""
                title="<?php echo $lang['T_TIP_SAVE'] ?>">
            <i class="fa fa-floppy-o"><span><?php echo $lang['SAVE'] ?></span></i> <!-- кнопка применения настроек -->
        </button>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>