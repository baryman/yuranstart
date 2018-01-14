<div class="section-<?php echo $pluginName ?>">
  <div class="widget-table-body">    
    <div class="widget-table-action">
      <a href="javascript:void(0);" class="show-property-order tool-tip-top" title="<?php echo $lang['T_TIP_SHOW_PROPERTY'];?>"><span><?php echo $lang['SHOW_PROPERTY'];?></span></a>
      <a href="javascript:void(0);" class="custom-btn tool-tip-top clear-cache-btn" title="<?php echo $lang['CLEAR_CACHE'];?>"><span><?php echo $lang['CLEAR_CACHE'];?></span></a>
      <div class="clear"></div>
    </div>      
           
    <div class="property-order-container">    
      <h2><?php echo $lang['SETTINGS_CACHE'];?>:</h2>
      <div class="base-setting">       
        <ul class="list-option">     
          <li><label><span><?php echo $lang['CACHE_PAGE'];?>:</span> <input type="checkbox" name="enable_cache" value="<?php echo $options["enable_cache"] ?>" <?php echo ($options["enable_cache"] && $options["enable_cache"] != 'false') ? 'checked=cheked' : '' ?>></label></li>
         
          <li>
            <span><?php echo $lang['TIME_CACHE'];?>:</span>
            <select name="time">
              <?php
              $timeArray = array(
                300 =>'5 минут', 
                600 =>'10 минут', 
                1800 =>'30 минут', 
                3600 => '1 час',
                7200 => '2 часа',
                10800 => '3 часа',
                21600 => '6 часов',
                43200 => '12 часов',
                86400 => '1 день', 
                172800 => '2 дня',
                604800 =>'1 неделя',
                2592000 =>'1 месяц',
                31536000 =>'1 год',
              );
              foreach ($timeArray as $time => $value) {
                $selected = '';
                if($time == $options["time"]){
                  $selected = 'selected="selected"';
                }
                echo '<option value="'.$time.'" '.$selected.' >'.$value.'</option>';
              }
              ?>
            </select>
          </li>
          
          <li><span class="textarea-text"><?php echo $lang['EXEPTION_CACHE'];?>:</span><textarea type="text" name="no_cache"><?php echo $options["no_cache"] ?></textarea></li>      
         </ul>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
        <a href="javascript:void(0);" class="base-setting-save custom-btn"><span><?php echo $lang['SAVE'];?></span></a>
        <div class="clear"></div>
      </div>
    </div>
  </div>