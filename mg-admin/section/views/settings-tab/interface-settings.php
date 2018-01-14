<?php $sheme = unserialize(stripslashes(getOption('interface'))); ?>
<div class="row">
  <div class="large-12 columns">
    <h4>Настройка внешнего вида сайта</h4>
    <div class="row">
      <div class="large-8 columns inline-label">

        <div class="row">
          <div class="small-12 medium-5 columns">
            <label class="dashed">Основные цвета
            <a href='javascript:void(0);' class='tool-tip-top fa fa-question-circle fl-right' title='Изменяет цвет шапки, кнопок (по умолчанию синих), табы и прочие элементы синего цвета' ></a></label>
          </div>
          <div class="small-12 medium-7 columns">
            <div id="colorMain" class="colorSelector"><div style="background-color: <?php echo $sheme['colorMain'] ?>"></div></div>
          </div>
        </div>

        <div class="row">
          <div class="small-12 medium-5 columns">
            <label class="dashed">Цвет ссылок
            <a href='javascript:void(0);' class='tool-tip-top fa fa-question-circle fl-right' title='Цвет всех ссылок в админке' ></a></label>
          </div>
          <div class="small-12 medium-7 columns">
            <div id="colorLink" class="colorSelector"><div style="background-color: <?php echo $sheme['colorLink'] ?>"></div></div>
          </div>
        </div>

        <div class="row">
          <div class="small-12 medium-5 columns">
            <label class="dashed">Цвет кнопок сохранить
            <a href='javascript:void(0);' class='tool-tip-top fa fa-question-circle fl-right' title='Изменяет цвет кнопок сохранить (зеленые по умолчанию)' ></a></label>
          </div>
          <div class="small-12 medium-7 columns">
            <div id="colorSave" class="colorSelector"><div style="background-color: <?php echo $sheme['colorSave'] ?>"></div></div>
          </div>
        </div>

        <div class="row">
          <div class="small-12 medium-5 columns">
            <label class="dashed">Цвет прочих кнопок (по умолчанию серые)
            <a href='javascript:void(0);' class='tool-tip-top fa fa-question-circle fl-right' title='Изменение цвета всех не основных кнопок (серые по умолчанию)' ></a></label>
          </div>
          <div class="small-12 medium-7 columns">
            <div id="colorSecondary" class="colorSelector"><div style="background-color: <?php echo $sheme['colorSecondary'] ?>"></div></div>
          </div>
        </div>

        <div class="row">
          <div class="small-12 medium-5 columns">
            <label class="dashed">Цвет рамок
            <a href='javascript:void(0);' class='tool-tip-top fa fa-question-circle fl-right' title='Изменение цвета рамок: разделители строк в таблицах, подчеркивания, разделители блоков, обводка текстовых полей и чекбоксов' ></a></label>
          </div>
          <div class="small-12 medium-7 columns">
            <div id="colorBorder" class="colorSelector"><div style="background-color: <?php echo $sheme['colorBorder'] ?>"></div></div>
          </div>
        </div>

        <div class="row">
          <div class="small-12 medium-5 columns">
            <label class="dashed">Текстура фона
            <a href='javascript:void(0);' class='tool-tip-top fa fa-question-circle fl-right' title='Текстура заднего фона сайта' ></a></label>
          </div>
          <div class="small-12 medium-7 columns">
            <div class="background-settings">
              <ul class="color-list">
                <li class="bg_1"></li>
                <li class="bg_2"></li>
                <li class="bg_3"></li>
                <li class="bg_4"></li>
                <li class="bg_5"></li>
                <li class="bg_6"></li>
                <li class="bg_7"></li>
                <li class="bg_8"></li>
                <li class="bg_9"></li>
                <li class="bg_10"></li>
                <li class="bg_11"></li>
                <li class="bg_12"></li>
                <li class="bg_13"></li>
              </ul>
            </div>
            <input type="hidden" name="themeBackground" id="bg" class="option" value="<?php echo $data['interface-settings']['options']['themeBackground']['value'] ?>">
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<div class="widget-footer text-right">
  <button class="button secondary default-interface" title="<?php echo $lang['T_TIP_SAVE_U_CAT'];?>">
    <span>По умолчанию</span>
  </button>
  <button class="button success save-interface" title="<?php echo $lang['T_TIP_SAVE_U_CAT'];?>">
    <span><?php echo $lang['SAVE'];?></span>
  </button>
</div>