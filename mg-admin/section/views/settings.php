<div class="section-settings">

<div class="row">
  <div class="large-12 columns">
    <div class="widget settings">
      <div class="widget-header clearfix"><i class="fa fa-cogs" aria-hidden="true"></i> Настройки сайта</div>
      <div class="widget-body">
        <div class="widget-panel-holder">
          <div class="widget-panel" style="border-bottom:0;padding-bottom:0;">
            <ul class="tabs custom-tabs system-tabs">
              <li class="tabs-title is-active tabs-title-settings" id="tab-shop"><a href="javascript:void(0)" data-target="#tab-shop-settings" title="<?php echo $lang['T_TIP_TAB_SHOP'];?>"><?php echo $lang['STNG_TAB_SHOP'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="tab-system"><a href="javascript:void(0)" data-target="#tab-system-settings" title="<?php echo $lang['T_TIP_TAB_SYSTEM'];?>"><?php echo $lang['STNG_TAB_SYSTEM'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="tab-template"><a href="javascript:void(0)" data-target="#tab-template-settings" title="<?php echo $lang['T_TIP_TAB_TEMPLATE'];?>"><?php echo $lang['STNG_TAB_TEMPLATE'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="interface"><a href="javascript:void(0)" data-target="#interface-settings" title="<?php echo $lang['T_TIP_TAB_INTERFACE'];?>"><?php echo $lang['STNG_TAB_INTERFACE'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="tab-userField"><a href="javascript:void(0)" data-target="#tab-userField-settings" title="<?php echo $lang['T_TIP_TAB_USERFIELDS'];?>"><?php echo $lang['STNG_USER_FIELD'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="tab-currency"><a href="javascript:void(0)" data-target="#tab-currency-settings" title="<?php echo $lang['T_TIP_CURRENCY_SHOP'];?>"><?php echo $lang['STNG_CURRENCY_SHOP'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="tab-deliveryMethod"><a href="javascript:void(0)" data-target="#tab-deliveryMethod-settings" title="<?php echo $lang['T_TIP_TAB_DELIVERY'];?>"><?php echo $lang['STNG_TAB_DELIVERY'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="tab-paymentMethod"><a href="javascript:void(0)" data-target="#tab-paymentMethod-settings" title="<?php echo $lang['T_TIP_TAB_PAYMENT'];?>"><?php echo $lang['STNG_TAB_PAYMENT'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="tab-SEOMethod"><a href="javascript:void(0)" data-target="#SEOMethod-settings"  title="<?php echo $lang['T_TIP_TAB_SEO'];?>"><?php echo $lang['STNG_TAB_SEO'];?></a></li>
              <li class="tabs-title tabs-title-settings" id="tab-1C"><a href="javascript:void(0)" data-target="#1C-settings" title="<?php echo $lang['T_TIP_TAB_1C'];?>"><?php echo $lang['STNG_TAB_1C'];?></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="tabs-content tabs-content-settings">
        <div class="tabs-panel is-active main-settings-container" id="tab-shop-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/tab-shop-settings.php'; ?>         
        </div>
        <div class="tabs-panel main-settings-container" id="tab-system-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/tab-system-settings.php'; ?>
        </div>
        <div class="tabs-panel main-settings-container" id="tab-template-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/tab-template-settings.php'; ?>
        </div>
        <div class="tabs-panel main-settings-container" id="interface-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/interface-settings.php'; ?>
        </div>
        <div class="tabs-panel main-settings-container" id="tab-userField-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/tab-userfield-settings.php'; ?>    
        </div>
        <div class="tabs-panel main-settings-container" id="tab-currency-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/tab-currency-settings.php'; ?>    
        </div>
        <div class="tabs-panel main-settings-container" id="tab-deliveryMethod-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/tab-deliverymethod-settings.php'; ?>    
        </div>
        <div class="tabs-panel main-settings-container" id="tab-paymentMethod-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/tab-paymentmethod-settings.php'; ?>    
        </div>
        <div class="tabs-panel main-settings-container" id="SEOMethod-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/seomethod-settings.php'; ?>    
        </div>
        <div class="tabs-panel main-settings-container" id="1C-settings">
          <?php include_once ADMIN_DIR.'/section/views/settings-tab/1c-settings.php'; ?>    
        </div>
        <!--  -->
      </div>
    </div>
  </div>
</div>

<!--Раздел настроек пользовательских полей-->
<!--Содержимое, показываемое при удачном загрузке архива с обновлением-->
<div id="hiddenMsg" style="display:none">
    <?php echo $lang['SETTING_LOCALE_10']?> <b><span id="lVer"></span></b> <?php echo $lang['SETTING_LOCALE_11']?><br>
    <a href="javascript:void(0);" rel="postDownload" class="button"><span><?php echo $lang['SETTING_LOCALE_12']?></span></a>
</div>

</div>
<script>
$('.memcache-conection').hide();
$('input[name="cacheHost"]').parents('li').hide();
$('input[name="cachePort"]').parents('li').hide();
$('input[name="cachePrefix"]').parents('li').hide();

if($('.section-settings  select[name="cacheMode"]').val()=="MEMCACHE"){
  $('.memcache-conection').show();
  $('input[name="cacheHost"]').parents('li').show();
  $('input[name="cachePort"]').parents('li').show();
  $('input[name="cachePrefix"]').parents('li').show();
}
if ($('.section-settings  input[name="smtp"]').val()=="false"){
  $('.section-settings  input.smtpSettings').parents('li').hide();
}
if ($('.section-settings #tab-shop-settings input[name=sessionToDB]').val() != 'true') {
 $('.section-settings #tab-shop-settings input[name=sessionLifeTime]').parents('li').hide();
}

$("body").foundation();
</script>