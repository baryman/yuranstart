<h4><?php echo $lang['STNG_1C']; ?></h4>
<div class="alert-block warning text-center">Все настройки задаются на стороне 1С. Адрес магазина в настройках подключения: <?php echo SITE.'/exchange1c';?></div>
<div class="row inline-label">
  <div class="large-7 columns">
    <?php foreach ($data['exchange1c-settings'] as $key=>$option) { ?>
    <div class="row">
      <div class="small-12 medium-7 columns">
        <label class='property-name middle with-help dashed'><?php echo $lang[$option['name']]; ?>
          <a href='javascript:void(0);' class='tool-tip-top fa fa-question-circle' title='<?php echo $lang['DESC_' . $option['name']] ?>' ></a>
        </label>
      </div>
      <?php if($key == 'fileLimit1C'):?>
        <div class="small-12 medium-5 columns">
          <div class="input-with-text">
            <span class='property-fields'>
            <div class="link-result images-info-massage link-result">
              <input type="text" class="option medium" name="<?php echo $key;?>" value="<?php echo $option['value'];?>" /><span> байт</span>
            </div>
            </span>
          </div>
        </div>
      <?php else:?>
        <div class="small-2 medium-5 columns">
          <div class="checkbox margin">
            <input id="qq<?php echo $key;?>" type="checkbox" class="option" name="<?php echo $key;?>" value="<?php echo $option['value'];?>" <?php echo ($option['value'] == 'true' ? 'checked=checked' : ''); ?>>
            <label for="qq<?php echo $key;?>"></label>
          </div>
        </div>
      <?php endif;?>
    </div>
    <?php } ?>
  </div>
</div>
<div class="row">
  <div class="small-12 columns">
    <button class="save-settings button success fl-right"><span><?php echo $lang['SAVE'] ?></span></button>
  </div>
</div>

