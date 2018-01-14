<div class="reveal-overlay" style="display:none;">
  <div class="reveal lsmall" id="add-deliveryMethod-wrapper" style="display:block;">
    <button class="close-button closeModal" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
    <div class="reveal-header">
      <h2><i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="delivery-table-icon">Новая доставка</span></h2>
    </div>
    <div class="reveal-body">
      <div class="row">
        <div class="small-12 medium-5 columns">
          <label class="middle"><?php echo $lang['SETTING_LOCALE_19']?>:</label>
        </div>
        <div class="small-12 medium-7 columns">
          <input type="text" name="deliveryName" title="<?php echo $lang['T_TIP_USER_EMAIL'];?>">
          <div class="errorField"><?php echo $lang['ERROR_EMPTY'];?></div>
        </div>
      </div>
      <div class="row">
        <div class="small-12 medium-5 columns">
          <label class="middle"><?php echo $lang['SETTING_LOCALE_20']?>:</label>
        </div>
        <div class="small-12 medium-7 columns">
          <div class="input-with-text">
            <input class="small" type="text" name="deliveryCost"><?php echo MG::getSetting('currency')?>
            <div class="errorField"><?php echo $lang['ERROR_NUMERIC'];?></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="small-12 medium-5 columns">
          <label class="middle"><?php echo $lang['SETTING_LOCALE_21']?>:</label>
        </div>
        <div class="small-12 medium-7 columns">
          <input type="text" name="deliveryDescription">
          <div class="errorField"><?php echo $lang['ERROR_EMPTY'];?></div>
        </div>
      </div>
      <div class="row">
        <div class="small-12 medium-5 columns">
          <label class="middle"><?php echo $lang['SETTING_BASE_12'];?>:</label>
        </div>
        <div class="small-12 medium-7 columns">
          <div class="input-with-text">
            <input class="small" type="text" name="free" title="<?php echo $lang['SETTING_BASE_13'];?>"><?php echo MG::getSetting('currency')?>
            <div class="errorField"><?php echo $lang['ERROR_NUMERIC'];?></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="small-10 medium-5 columns">
          <label class="middle"><?php echo $lang['SETTING_LOCALE_22']?>:</label>
        </div>
        <div class="small-2 medium-7 columns">
          <div class="checkbox margin">
            <input id="cc2" type="checkbox" name="deliveryActivity">
            <label for="cc2"></label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="small-10 medium-5 columns">
          <label class="middle"><?php echo $lang['SETTING_LOCALE_YMARKET']?>:</label>
        </div>
        <div class="small-2 medium-7 columns">
          <div class="checkbox margin">
            <input id="cc3" type="checkbox" name="deliveryYmarket">
            <label for="cc3"></label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="small-10 medium-5 columns">
          <label class="middle"><?php echo $lang['SETTING_LOCALE_DATE']?>:</label>
        </div>
        <div class="small-2 medium-7 columns">
          <div class="checkbox margin">
            <input id="cc4" type="checkbox" name="deliveryDate">
            <label for="cc4"></label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="small-12 columns">
          <div class="title">Варианты оплаты:</div>
        </div>
      </div>
      <div id="paymentCheckbox">
        <div class="row" style="margin: 0 -15px;width:100%;">
        <?php $columns = -1; ?>
        <?php foreach($data['paymentMethod-settings']['paymentArray'] as $payment):?>
        <?php 
          $columns++;
          if($columns > 1) {
            $columns = 0;
            echo '<div class="row"></div>';
          }
        ?>
            <div class="payment-<?php echo $payment['id']?> small-12 medium-6 columns">
              <div class="small-10 medium-10 columns">
                <label class="middle"><?php echo $payment['name']?></label>
              </div>
              <div class="small-2 medium-2 columns">
                <div class="checkbox margin">
                  <input id="m1-<?php echo $payment['id']?>" type="checkbox" name="<?php echo $payment['id']?>">
                  <label for="m1-<?php echo $payment['id']?>"></label>
                </div>
              </div>
            </div>
        <?php endforeach;?>
        </div>
      </div>
    </div>
    <div class="reveal-footer text-right">
      <a class="link closeModal" href="javascript:void(0);">Отмена</a>
      <a class="button success save-button" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a>
    </div>
  </div>
</div>

<!--  -->

<div class="row">
  <div class="large-12 columns">
    <h4>Доставка</h4>
    <div class="widget-inner">
      <div class="widget-panel-holder">
        <div class="widget-panel clearfix">
          <div class="buttons-holder fl-left"><a class="button success tip add-new-button" href="javascript:void(0);" title="Новая доставка" data-open="add-delivery-modal"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить доставку</a></div>
        </div>
      </div>
      <div class="table-wrapper">
        <table class="main-table">
          <thead>
            <tr>
              <th class="text-left">id</th>
              <th class="text-left"><?php echo $lang['SETTING_LOCALE_19']?></th>
              <th class="text-left"><?php echo $lang['SETTING_LOCALE_20']?></th>
              <th class="text-left"><?php echo $lang['SETTING_LOCALE_21']?></th>
              <th class="text-left"><?php echo $lang['SETTING_LOCALE_29']?></th>
              <th class="text-right"><?php echo $lang['SETTING_LOCALE_23']?></th>
            </tr>
          </thead>
          <tbody class="text-left">
            <?php if(0 < count($data['deliveryMethod-settings']['deliveryArray'])):
              foreach($data['deliveryMethod-settings']['deliveryArray'] as $delivery):?>
                <tr id="delivery_<?php echo $delivery['id'] ?>" data-id="<?php echo $delivery['id'] ?>">
                  <td class="deliveryId"><?php echo $delivery['id'] ?></td>
                  <td id="deliveryName" ><?php echo $delivery['name'] ?></td>
                  <td id="deliveryCost"><span class="costValue"><?php echo MG::numberFormat($delivery['cost'])?></span> <span class="currency"><span class="currency"><?php echo MG::getSetting('currency')?></span></span> </td>
                  <td id="deliveryDescription"><?php echo $delivery['description'] ?></td>
                  <td class="free"><span class="costFree"><?php echo MG::numberFormat($delivery['free']) ?></span> <span class="currency"><?php echo MG::getSetting('currency')?></span></td>
                  <td class="actions">
                    <ul class="action-list text-right">
                      <li class="edit-row" id="<?php echo $delivery['id'] ?>"><a class="mg-open-modal tool-tip-bottom fa fa-pencil" href="javascript:void(0);" title="<?php echo $lang['EDIT'];?>"></a></li>
                      <li class="activity" data-delivery-date ="<?php echo $delivery['date'] ?>" data-delivery-ymarket ="<?php echo $delivery['ymarket'] ?>" status="<?php echo $delivery['activity'] ?>" id="<?php echo $delivery['id'] ?>"><a href="javascript:void(0)" class="fa fa-lightbulb-o <?php if($delivery['activity']) echo 'active' ?>"></a></li>
                      <li class="delete-row " id="<?php echo $delivery['id'] ?>"><a class="tool-tip-bottom fa fa-trash" href="javascript:void(0);" title="<?php echo $lang['DELETE'];?>"></a></li>
                    </ul>
                  </td>
                  <td id="paymentHideMethod" style="display: none"></td>
                </tr>
              <?php endforeach;
            else:?>
                <tr id="none_delivery"><td class="no-delivery" colspan="6"><?php echo $lang['NONE_DELIVERY'];?></td></tr>
            <?php endif;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>