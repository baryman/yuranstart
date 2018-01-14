<div class="reveal-overlay" style="display:none">
  <div class="reveal lsmall" id="add-paymentMethod-wrapper" style="display:block">
    <button class="close-button closeModal" title="<?php echo $lang['T_TIP_CLOSE_WITHOUT_SAVE'];?>" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
    <div class="reveal-header">
      <h2><i class="fa fa-pencil" aria-hidden="true"></i> <span class="payment-table-icon">Редактировать способ оплаты</span></h2>
    </div>
    <div class="reveal-body">
      <div class="row">
        <div class="small-5 columns">
          <label class="middle"><?php echo $lang['SETTING_LOCALE_19']?>:</label>
        </div>
        <div class="small-7 columns">
          <!-- <input type="text" id="paymentName" name="name"> -->
          <span id="paymentName"><?php echo $lang['SETTING_LOCALE_28']?></span>
        </div>
      </div>
      <div class="row">
        <div class="small-12 columns">
          <div class="title">Параметры</div>
        </div>
      </div>
      <div id="paymentParam"></div>
      <div class="row">
        <div class="small-5 columns">
          <label class="middle">Активность:</label>
        </div>
        <div class="small-7 columns">
          <div class="checkbox margin">
            <input id="cr2" type="checkbox" class="payment-active" name="paymentActivity">
            <label for="cr2"></label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="large-12 columns" style="margin: 0 0 10px 0;"><a class="discount-setup-rate link" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i> Установить скидку/наценку для способа оплаты</a>
          <div class="discount-rate-control">
            <div class="popup-holder">
              <a class="discount-change-rate rate-dir-name link" href="javascript:void(0);" title="Нажмите для выбора скидки или наценки">Наценка</a>
              <div class="rate-value">
                <span class="rate-dir">+</span> <input type="text" class="small" name="rate" value="0"> % 
                <a class="fa fa-trash tip remove-discount cancel-rate" href="javascript:void(0);" title="Удалить"></a>
              </div>
              <div class="discount-error errorField">Введите число</div>
              <div class="custom-popup select-rate-block" style="display:none;">
                <div class="row">
                  <div class="large-12 columns">
                    <label>Применять к товарам категории:</label>
                  </div>
                </div>
                <div class="row">
                  <div class="large-12 columns">
                    <select name="change_rate_dir" class="no-search">
                      <option value="up">Наценку</option>
                      <option value="down">Скидку</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="large-12 columns">
                    <a class="button success fl-right apply-rate-dir" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Применить</a>
                    <a class="button fl-left cancel-rate-dir" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> Отменить</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="small-12 columns">
          <div class="title"><?php echo $lang['SETTING_LOCALE_26']?></div>
        </div>
      </div>
      <div id="deliveryCheckbox">
        <div id="deliveryArray">
          <?php foreach($data['deliveryMethod-settings']['deliveryArray'] as $delivery):?>
            <div class="row">
              <div class="small-5 columns">
                <label class="middle"><?php echo $delivery['name']?></label>
              </div>
              <div class="small-7 columns">
                <div class="checkbox margin">
                  <input id="cr3" type="checkbox" name="<?php echo $delivery['id']?>" class="deliveryMethod">
                  <label for="cr3"></label>
                </div>
              </div>
            </div>
          <?php endforeach;?>
        </div>
      </div>
      <div id="urlParam"></div>
    </div>
    <div class="reveal-footer text-right">
      <a class="link closeModal" href="javascript:void(0);">Отмена</a>
      <a class="button success save-button" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a>
    </div>
  </div>
</div>

<div class="row">
  <div class="large-12 columns">
    <h4>Способы оплаты <a href="http://wiki.moguta.ru/kak-nastroit-sposobi-oplati-v-moguta-cms" target="_blank"> (Видеоинструкция)</a></h4>
    <div class="widget-inner">
      <div class="table-wrapper">
        <table class="main-table">
          <thead>
            <tr>
              <th style="width:30px; text-align: center;"></th>
              <th class="id-way" style="display:none">id способа</th>
              <th><?php echo $lang['SETTING_LOCALE_19']?></th>
              <th class="text-right"><?php echo $lang['SETTING_LOCALE_23']?></th>
            </tr>
          </thead>
          <tbody class="paymentMethod-tbody">
            <?php foreach($data['paymentMethod-settings']['paymentArray'] as $payment):?>
              <?php 
              
              ?>
              <tr id="payment_<?php echo $payment['id'] ?>" data-id="<?php echo $payment['id'] ?>">
                <td class="mover"><i class="fa fa-arrows ui-sortable-handle" aria-hidden="true"></i></td>
                <td class="paymentId" style="display:none"><?php echo $payment['id'] ?></td>
                <td id="paymentName"><span class="icon-style pay-<?php echo $payment['id'] ?>"></span><?php echo $payment['name'] ?></td>
                <td class="actions">
                  <ul class="action-list text-right">
                    <li class="edit-row" id="<?php echo $payment['id'] ?>"><a class="mg-open-modal tool-tip-bottom fa fa-pencil" href="javascript:void(0);" title="<?php echo $lang['EDIT'];?>"></a></li>
                    <li class="activity" id="<?php echo $payment['id'] ?>" status="<?php echo $payment['activity'] ?>"><a href="javascript:void(0)" class="fa fa-lightbulb-o <?php if($payment['activity']) echo 'active' ?>"></a></li>
                  </ul>
                </td>
                <td id="paramHideArray" style="display: none"><?php echo $payment['paramArray'] ? htmlspecialchars($payment['paramArray']): '{"0":0}' ?></td>
                <td id="deliveryHideMethod" style="display: none"><?php echo $payment['deliveryMethod'] ? $payment['deliveryMethod']: '{"0":0}' ?></td>
                <td id="urlArray" style="display: none"><?php echo $payment['urlArray'] ?></td>
                <td id="paymentRate" style="display: none"><?php echo $payment['rate']?></td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>