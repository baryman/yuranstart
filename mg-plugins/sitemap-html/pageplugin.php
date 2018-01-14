
<div class="section-<?php echo self::$pluginName ?>"><!-- $pluginName - задает название секции для разграничения JS скрипта -->

  <!-- Тут начинается верстка видимой части станицы настроек плагина-->
  <div class="widget-table-body">
    <div class="sitemap-html-setting">

      <!-- Тут начинается  Верстка базовых настроек  плагина (опций из таблицы  setting)-->
      <div class="widget-table-action base-settings">
        <h3>Настройки плагина</h3>
        <span class="custom-text">Выводить товары в карте:</span> 
        <input type="checkbox" class="option-map" name="" <?php if(self::$isShow['isShowProduct'] == 'true') echo 'checked' ?>>
        <br>
        <span class="custom-text">Выводить страницы фильтров:</span> 
        <input type="checkbox" class="option-filterPage" name="" <?php if(self::$isShow['isShowFilterPage'] == 'true') echo 'checked' ?>>

        <button class="tool-tip-bottom base-setting-save save-button custom-btn button success" id="save-button-map">
          <span><i class="fa fa-floppy-o"></i> Сохранить</span> <!-- кнопка применения настроек -->
        </button>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>