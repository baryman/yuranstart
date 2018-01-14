<div class="row">
  <div class="large-12 columns">
    <h4>Валюта</h4>
    <div class="widget-inner">
      <div class="widget-panel-holder">
        <div class="widget-panel clearfix">
          <div class="buttons-holder fl-left"><a class="add-new-currency button success tip" href="javascript:void(0);" title="Новая валюта" data-open="add-currency-modal"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить валюту</a></div>
        </div>
      </div>
      <div class="table-wrapper">
        <table class="main-table main-settings-list">
          <thead>
            <tr>
              <th>ISO</th>
              <th>Стоимость по отношению к валюте магазина ( <span class="view-value-curr"><?php echo MG::getSetting('currencyShopIso') ?> </span>)</th>
              <th>Сокращение</th>
              <th class="text-right">Действия</th>
            </tr>
          </thead>
          <tbody class="currency-tbody">
          <?php
          if(0 < count($data['currency-settings'])):
          		$currencyShort = MG::getSetting('currencyShort');
              foreach($data['currency-settings'] as $iso => $currency):?>
                  <?php
                  if($iso == MG::getSetting('currencyShopIso') ){
                      $class = 'class="none-edit"';
                  }else{
                      $class = '';
                  }
                  ?>
                  <tr data-iso="<?php echo $iso ?>" <?php echo $class ?>>
                      <td data-iso="<?php $iso ?>">
                          <span class="view-value-curr"><?php echo $iso ?></span>
                          <input type="text" name="currency_iso" data-value="<?php echo $iso ?>" value="<?php echo $iso ?>" class="currency-field" style="display:none"/>
                      </td>
                      <td class="currency-rate">
                          <span class="view-value-curr"> = </span>
                          <span class="view-value-curr"><?php echo number_format($currency['rate'], 2, ',', ' ' ); ?></span>
                          <span class="view-value-curr"><?php echo $currencyShort[MG::getSetting('currencyShopIso')]?></span>
                          <input type="text" name="currency_rate" data-value="<?php echo $currency['rate'] ?>" value="<?php echo $currency['rate'] ?>" class="currency-field" style="display:none"/>
                      </td>
                      <td class="currency-short">
                          <span class="view-value-curr"><?php echo $currency['short'] ?></span>
                          <input type="text" name="currency_short" data-value="<?php echo $currency['short'] ?>" value="<?php echo $currency['short'] ?>" class="currency-field" style="display:none"/>
                      </td>
                      <td class="actions">
                        <ul class="action-list text-right">
                            <li class="save-row" id="<?php echo $iso?>" style="display:none"><a class="tool-tip-bottom fa fa-floppy-o" href="javascript:void(0);" title="<?php echo $lang['SAVE']?>"></a></li>
                            <li class="cancel-row" id="<?php echo $iso?>" style="display:none"><a class="tool-tip-bottom fa fa-times" href="javascript:void(0);" title="<?php echo $lang['CANCEL']?>"></a></li>
                            <li class="edit-row" id="<?php echo $iso ?>"><a href="javascript:void(0)" class="fa fa-pencil edit-currency tool-tip-bottom" title="Редактировать" ></a></li>
                            <?php if($iso != MG::getSetting('currencyShopIso') ){ ?>
                            <li class="delete-row" id="<?php echo $iso ?>"><a class="tool-tip-bottom fa fa-trash" href="javascript:void(0);" title="<?php echo $lang['DELETE'];?>"></a></li>
                             <?php } ?>
                        </ul> 
                      </td>
                  </tr>
              <?php endforeach;
          else:?>
              <tr id="none_delivery"><td class="no-delivery" colspan="4">Отсутствуют валюты</td></tr>
          <?php endif;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>    

