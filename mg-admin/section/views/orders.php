<div class="section-order">
<!-- modal -->
<div class="reveal-overlay" style="display:none;">
  <div class="reveal large orders-table-wrapper" id="add-order-wrapper" style="display:block;">
    <div class="row">
      <div class="">
        <div class="widget add-order" style="margin:0;">
          <button class="close-button closeModal" type="button" title="<?php echo $lang['T_TIP_CLOSE_MODAL']; ?>"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
          <div class="reveal-header">
            <h2><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="add-order-table-icon"></span></h2>
          </div>
          <div class="widget-body" id="order-data">
            <div class="work-area">
              
              <div class="bottom-block clearfix">
                <div class="">
                  <div class="side-header" style="padding:10px 15px 0 15px;min-height: 0;border:0;">
                    <div class="category-filter">
                      <button class="editor-order tool-tip-bottom order-edit-visible fl-right button primary" title="<?php echo $lang['MOD_ORDER_1']; ?>" data-id="" style="margin-left:10px;">
                        <i class="fa fa-pencil" aria-hidden="true"></i> <span><?php echo $lang['MOD_ORDER_1']; ?></span>
                      </button>
                      <button class="print-button tool-tip-bottom order-edit-visible fl-right button primary" title="<?php echo $lang['T_TIP_PRINT_ORDER']; ?>" data-id="" style="margin-left:10px;">
                        <i class="fa fa-print" aria-hidden="true"></i> <span><?php echo $lang['PRINT_ORDER']; ?></span>
                      </button>
                      <button class="csv-button tool-tip-bottom order-edit-visible fl-right button primary" title="<?php echo $lang['OREDER_LOCALE_1']?>" data-id="" style="margin-left:10px;">
                        <i class="fa fa-download" aria-hidden="true"></i> <span><?php echo $lang['MOD_ORDER_2']; ?></span>
                      </button>
                      <button class="get-pdf-button tool-tip-bottom order-edit-visible fl-right button primary" title="<?php echo $lang['T_TIP_PRINT_ORDER_PDF']; ?>"  data-id="" style="margin-left:10px;">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <span><?php echo $lang['PRINT_ORDER_PDF']; ?></span>
                      </button>

                      <div class="user-inform" style="display: inline-block;">
                        <label class="middle input-label" style="padding: 0;">
                          <input id="cc1" type="checkbox" name="inform-user" value = "false" style="display: none;">
                          <label for="cc1" style="margin:0;">
                            <i class="fa fa-bullhorn tool-tip-bottom" aria-hidden="true" title="Информировать покупателя о смене статуса." style="font-size:16px;padding:7px;"></i>
                          </label>
                        </label>
                      </div>
                      <span class="custom-text"><?php echo $lang['MOD_ORDER_3']; ?>:</span>
                      <select id="orderStatus" class="last-items-dropdown custom-dropdown tool-tip-right" title="<?php echo $lang['SELECT_ORDER_STATUS']; ?>"  name="status_id" style="width:200px;">
                          <?php foreach ($assocStatus as $k => $v): ?>
                            <option value="<?php echo $k ?>"> <?php echo $assocStatus[$k] ?> </option>
                          <?php endforeach; ?>
                      </select>
                      <button class="button primary order-edit-display addProductToOrder" style="margin: 0 0 0 10px;"><?php echo $lang['ORDER_BS_1']; ?></button>
                      
                    </div>
                  </div>

                  <div>
                      
              <div class="top-block clearfix" style="display: none;">
                <div class="side-header search-block order-edit-display" style="border-top: 1px solid #e6e6e6;border-width:1px 0 0 0;">
                  <div class="search-block">
                    <div class="search-block order-edit-display">
                      <div class="fl-left"></div> 
                      <div class="add-product-field fl-left" style="line-height:1.7;position:relative;">            
                        <input type="search" autocomplete="off" name="searchcat" class="search-field fl-left" placeholder="<?php echo $lang['RELATED_7']; ?>"><i class="fa fa-search" aria-hidden="true"></i>

                        <div class="errorField" style="display: none;"><?php echo $lang['ORDER_BS_2']; ?></div>

                      </div>
                      <a href="javascript:void(0)" class="custom-btn clear-product link fl-left" style="position:absolute;top:6px;left:330px;"><span>Очистить</span></a>
                      <div class="example-line fl-left" style="width:100%;"><?php echo $lang['ORDER_BS_3']; ?>: <a href="javascript:void(0)" class="example-find" ><?php echo $exampleName ?></a></div>
                      <div class="fastResult"></div>               
                    </div>
                    <!--  -->
                  </div>
                </div>
                <div class="product-block">
                    <!-- Здесь будет отображена карточка товара -->
                </div>
              </div>
                      

                      <form name="orderContent">   
                          <div class="order-history">   
                          </div>
                      </form>

                  </div>

                </div> 

              </div>
            </div>
          </div>
          <div class="widget-footer text-right">
            <a class="button success save-button" href="javascript:void(0);" title="<?php echo $lang['APPLY']; ?>"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo $lang['APPLY']; ?> заказ</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--  -->
<div class="row">
  <div class="large-12 columns">
    <div class="widget table">
      <div class="widget-header clearfix"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <?php echo $lang['TITLE_ORDERS']; ?>
        <div class="product-count fl-right"><?php echo $lang['ALL_COUNT_ORDER']; ?> <strong><?php echo $orderCount ?></strong> <?php echo $lang['UNIT']; ?></div>
      </div>
      <div class="widget-body">
        <div class="widget-panel-holder">
          <div class="widget-panel">
            <div class="buttons-holder clearfix table-pagination">
              <a class="button success tip add-new-button" href="javascript:void(0);" title="<?php echo $lang['T_TIP_ADD_NEW_ORDER']; ?>"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $lang['ADD_NEW_ORDER']; ?></a>
              <a class="button tip mg-panel-toggle show-filters" href="javascript:void(0);" title="<?php echo $lang['T_TIP_SHOW_FILTER']; ?>"><i class="fa fa-filter" aria-hidden="true"></i> <?php echo $lang['FILTER']; ?></a>
              <a class="button tip mg-panel-toggle show-property-order" href="javascript:void(0);" title="<?php echo $lang['T_TIP_SHOW_PROPERTY_ORDER']; ?>"><i class="fa fa-cogs" aria-hidden="true"></i> <?php echo $lang['SHOW_PROPERTY_ORDER']; ?></a>
              <a class="button secondary tip get-csv" href="<?php echo SITE ?>/mg-admin?csvorder=1" title="<?php echo $lang['T_TIP_CSV_ORDERS']; ?>"><i class="fa fa-download" aria-hidden="true"></i> <?php echo $lang['CSV_ORDERS']; ?></a>
              <a class="button secondary tip get-csv" href="<?php echo SITE ?>/mg-admin?csvorderfull=1" title="<?php echo $lang['T_TIP_CSV_ORDERS_FULL']; ?>"><i class="fa fa-download" aria-hidden="true"></i> <?php echo $lang['CSV_ORDERS_FULL']; ?></a>

              <?php echo $pager; ?>

            </div>
          </div>
          <div class="widget-panel-content filter-container" <?php if ($displayFilter) { echo "style='display:block'"; } else { echo "style='display:none'"; } ?>>
            <?php echo $filter ?>
            <div class="alert-block success text-center" style="margin: 10px 0 0 0;">
                <span>Найдено заказов: <strong><?php echo  $itemsCount; ?> шт.</strong></span>
                <span>Общая сумма заказов: <strong><?php echo  MG::priceCourse($totalSumm).' '.MG::getSetting('currency'); ?></strong></span>
            </div>
        </div>
        <!--  -->
        <div class="widget-panel-content property-order-container" style="display:none;">    
            <h2><?php echo $lang['OREDER_LOCALE_24'] ?>:</h2>
            <form name="requisites" method="POST">
              <div class="row">
                <div class="large-3 small-6 columns">
                  <div class="requisites-list">
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_9'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="nameyur" value="<?php echo htmlspecialchars($propertyOrder["nameyur"]) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_15'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="adress" value="<?php echo htmlspecialchars($propertyOrder["adress"]) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_16'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="inn" value="<?php echo htmlspecialchars($propertyOrder["inn"]) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_17'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="kpp" value="<?php echo htmlspecialchars($propertyOrder["kpp"]) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_32'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="ogrn" value="<?php echo htmlspecialchars($propertyOrder["ogrn"]) ?>">
                      </div>
                    </div>               
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_18'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="bank" value="<?php echo htmlspecialchars($propertyOrder["bank"]) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_19'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="bik" value="<?php echo htmlspecialchars($propertyOrder["bik"]) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_20'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="ks" value="<?php echo htmlspecialchars($propertyOrder["ks"]) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_21'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="rs" value="<?php echo htmlspecialchars($propertyOrder["rs"]) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="large-4 columns">
                        <span><?php echo $lang['OREDER_LOCALE_25'] ?>:</span>
                      </div>
                      <div class="large-8 columns">
                        <input type="text" name="general" value="<?php echo htmlspecialchars($propertyOrder["general"]) ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="large-3 small-6 columns order-form-img-list">
                  <div>
                    <span><?php echo $lang['OREDER_LOCALE_26'] ?>: </span><input type="hidden" name="sing" value="<?php echo $propertyOrder["sing"] ?>"><br>
                    <img class="singPreview" src="<?php echo file_exists($propertyOrder["sing"]) ? SITE.'/'.$propertyOrder["sing"] : SITE.'/uploads/sing.jpg'; ?>"><br>    
                    <a href="javascript:void(0);" class="button primary upload-sign custom-btn"><span><?php echo $lang["UPLOAD"] ?></span></a>
                  </div>
                  <div>
                    <span><?php echo $lang['OREDER_LOCALE_27'] ?>:</span><input type="hidden" name="stamp" value="<?php echo $propertyOrder["stamp"] ?>"><br>
                    <img class="stampPreview" src="<?php echo file_exists($propertyOrder["stamp"]) ? SITE.'/'.$propertyOrder["stamp"] : SITE.'/uploads/stamp.jpg'; ?>"><br>
                    <a href="javascript:void(0);" class="button primary upload-stamp custom-btn"><span><?php echo $lang["UPLOAD"] ?></span></a>
                  </div>
                </div>
                <div class="large-6 small-12 columns nds-list">
                  <div class="row">
                    <div class="large-12 columns">
                      <?php echo $lang['OREDER_LOCALE_28'] ?>: <input  type="text" class="tiny inline" name="nds" size="2" value="<?php echo $propertyOrder["nds"] ?>"> %
                    </div>
                  </div>
                  <div class="row">
                    <div class="large-12 columns">
                      <?php echo $lang['OREDER_LOCALE_29'] ?>: 
                      <div class="checkbox inline" style="margin-bottom:-4px;">
                        <input type="checkbox" id="usedsing-199" class="" name="usedsing" value="<?php echo $propertyOrder["usedsing"] ?>" <?php echo $propertyOrder["usedsing"] ? 'checked=cheked' : '' ?>>
                        <label for="usedsing-199"></label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="large-12 columns">
                      <?php echo $lang['OREDER_LOCALE_31'] ?>: <input  type="text" class="inline medium" name="currency" placeholder="рубль,рубля,рублей" value="<?php echo $propertyOrder["currency"] ?>">
                    </div>
                  </div>  
                  <div class="row">
                    <div class="large-12 columns">
                      <?php echo $lang['DEFAULT_ORDER_STATUS'] ?>:
                      <select name="order_status" class="inline medium">
                        <?php foreach($statusList as $id=>$status){?>
                        <option value="<?php echo $id?>" <?php echo($propertyOrder['order_status']==$id)?'selected=selected':''?>><?php echo $status?></option>
                        <?php }?>
                      </select>
                    </div>
                  </div> 
                  <div class="row">
                    <div class="large-12 columns">
                      <?php echo $lang['DEFAULT_DATE_FILTER'] ?>:
                      <select name="default_date_filter" class="inline medium">
                        <?php foreach($dateFilterValues as $value=>$label){?>
                        <option value="<?php echo $value?>" <?php echo($propertyOrder['default_date_filter']==$value)?'selected=selected':''?>><?php echo $label?></option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="clear"></div>
              </div>
            </form>
            <div class="clear"></div>
            <a href="javascript:void(0);" class="save-property-order button success"><span><?php echo $lang['SAVE']; ?></span></a>
            <div class="clear"></div>
        </div>
        <!--  -->
        <div class="to-overflow table-wrapper" style="overflow: visible;">
          
            <table class="main-table">
              <thead>
                <th class="checkbox">
                  <div class="checkbox tip" title="Отметить все">
                    <input type="checkbox" id="o-all">
                    <label for="o-all" class="check-all-order"></label>
                  </div>
                </th>
                <th class="number">№</th>
                <th>Номер заказа</th>
                <th class="text-center">
                  <a class="order field-sorter <?php echo ($sorterData[0] == "add_date") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "add_date") ? $sorterData[1] * (-1) : 1 ?>" data-field="add_date" href="javascript:void(0);">Дата и время добавления</a>
                </th>
                <th class="text-center">
                  <a class="order field-sorter <?php echo ($sorterData[0] == "name_buyer") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "name_buyer") ? $sorterData[1] * (-1) : 1 ?>" data-field="name_buyer" href="javascript:void(0);">Ф.И.О. покупателя</a>
                </th>
                <th class="text-center">
                  <a class="order field-sorter <?php echo ($sorterData[0] == "user_email") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "user_email") ? $sorterData[1] * (-1) : 1 ?>" data-field="user_email" style="cursor:pointer;">Электронный адрес</a>
                </th>
                <th class="text-center">
                  <a class="order field-sorter <?php echo ($sorterData[0] == "delivery_id") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "delivery_id") ? $sorterData[1] * (-1) : 1 ?>" data-field="delivery_id" href="javascript:void(0);">Способ доставки</a>
                </th>
                <th class="text-center">
                  <a class="order field-sorter <?php echo ($sorterData[0] == "payment_id") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "payment_id") ? $sorterData[1] * (-1) : 1 ?>" data-field="payment_id" href="javascript:void(0);">Способ оплаты</a>
                </th>
                <th class="text-center">
                  <a class="order field-sorter <?php echo ($sorterData[0] == "summ") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "summ") ? $sorterData[1] * (-1) : 1 ?>" data-field="summ" href="javascript:void(0);">Стоимость</a>
                </th>
                <th class="text-center">
                  <a class="order field-sorter <?php echo ($sorterData[0] == "status_id") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "status_id") ? $sorterData[1] * (-1) : 1 ?>" data-field="status_id" href="javascript:void(0);">Статус</a>
                </th>
                <th class="row-config">Действия</th>
              </thead>
              <tbody class="order-tbody">
                <?php if ($orders) { ?>            
                  <?php foreach ($orders as $order) { ?>

                    <tr class="" order_id="<?php echo $order['id'] ?>" >
                        <td class="check-align">
                          <div class="checkbox">
                            <input type="checkbox" id="order-<?php echo $order['id']; ?>" name="order-check">
                            <label for="order-<?php echo $order['id']; ?>"></label>
                          </div>
                        </td>
                        <td> <?php echo $order['id'] ?></td>
                        <td> <?php echo $order['number']!='' ? $order['number'] : $order['id']; ?></td>
                        <td class="add_date"> <?php echo MG::dateConvert(date('d.m.Y H:i', strtotime($order['add_date']))).' г. в '.date('H:i', strtotime($order['add_date'])); ?></td>
                        <td> <?php echo $order['name_buyer'] ?></td>
                        <td> <?php echo $order['user_email'] ?></td>
                        <td> <?php echo $assocDelivery[$order['delivery_id']] ?></td>
                        <td><span class="icon-payment-<?php echo $order['payment_id'] ?>"></span> <?php echo $assocPay[$order['payment_id']] ?></td>
                        <td><b><?php echo MG::numberFormat(($order['summ']*1 + $order['delivery_cost']*1)) ?> <?php echo MG::getSetting('currency'); ?></b></td>
                        <td class="statusId id_<?php echo $order['status_id'] ?>">
                          <span class="badge <?php echo (empty($assocStatusClass[$order['status_id']]) ? 'get-paid' : $assocStatusClass[$order['status_id']]); ?>">
                            <?php echo $assocStatus[$order['status_id']] ?>
                          </span>
                        </td>

                        <td class="actions">
                          <ul class="action-list">
                            <li class="see-order" id="<?php echo $order['id'] ?>" data-number="<?php echo $order['number'] != '' ? $order['number'] : $order['id']; ?>">
                              <a class="mg-open-modal tool-tip-bottom fa fa-pencil" href="javascript:void(0);" title="<?php echo $lang['SEE']; ?>"></a>
                            </li>
                            <li class="order-to-csv">
                              <a  data-id="<?php echo $order['id'] ?> " class="tool-tip-bottom fa fa-download" href="javascript:void(0);" title="<?php echo $lang['order_LOCALE_1']?>"></a>
                            </li>
                            <?php
                              $textBtnFde = "Квитанция";            
                              $textBtnFdf = "Счет";
                            ?>
                            <li class="order-to-pdf has-menu">
                              <a class="tool-tip-top keep-alive fa fa-file-pdf-o" href="javascript:void(0);" title="Сохранить в PDF"></a>
                              <ul class="pdf-docs-list sub-list">
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="qittance"><?php echo $textBtnFde;?></a></li>
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="order"><?php echo $textBtnFdf;?></a></li>
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="sales_receipt">Товарный чек</a></li>
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="order_act">Акт по счёту</a></li>
                              </ul>
                            </li>
                            <li class="order-to-print has-menu">
                              <a class="tool-tip-top fa fa-print" href="javascript:void(0);" title="Печать"></a>
                              <ul class="print-docs-list sub-list">
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="qittance"><?php echo $textBtnFde;?></a></li>
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="order"><?php echo $textBtnFdf;?></a></li>
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="sales_receipt">Товарный чек</a></li>
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="packing-list">ТОРГ-12</a></li>
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="invoice">Счет-фактура</a></li>
                                <li><a href='javascript:void(0);' data-id="<?php echo $order['id'] ?>" data-template="order_act">Акт по счёту</a></li>
                              </ul>
                            </li>
                            <li class="clone-row" id="<?php echo $order['id'] ?>">
                              <a title="Клонировать заказ"  class="tool-tip-bottom fa fa-files-o" href="javascript:void(0);"></a>
                            </li>
                            <li class="delete-order " id="<?php echo $order['id'] ?>" >
                              <a class="tool-tip-bottom fa fa-trash" href="javascript:void(0);"  title="<?php echo $lang['DELETE']; ?>"></a>
                            </li>
                          </ul>
                        </td>
                    </tr>

                    <?php
                  }
                }else {
                  ?>

                  <tr><td colspan="11" class="noneOrders"><?php echo $lang['ORDER_NONE'] ?></td></tr>

                <?php } ?>
              </tbody>
            </table>
        </div>
      </div>
      <div class="widget-footer">
        <div class="table-pagination clearfix">
          <div class="label-select fl-left"><span class="select-label">Действия:</span>
            <select class="no-search order-operation"  name="operation" style="width:300px; margin-right: 10px;">
              <?php foreach ($assocStatus as $k => $v) :?>
              <option value="status_id_<?php echo $k ?>"><?php echo $lang['ACTION_ORDER'].' "'.$v.'"' ?></option> 
              <?php endforeach;?>
              <option value="delete"><?php echo $lang['ACTION_ORDER_7'] ?></option> 
              <option value="getcsvorder"><?php echo $lang['CSV_ORDERS'] ?></option> 
              <option value="csvorderfull"><?php echo $lang['CSV_ORDERS_FULL'] ?></option> 
            </select>
            <a class="button secondary run-operation" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> <?php echo $lang['ACTION_RUN'] ?></a>
          </div>

          <?php echo $pager ?>

          <div class="label-select small fl-right" style="margin: 0 10px;"><span class="select-label">Показать на странице:</span>
            <select class="no-search countPrintRowsOrder small">
              <?php
                foreach (array(10, 20, 30, 50, 100) as $value) {
                  $selected = '';
                  if ($value == $countPrintRowsOrder) {
                    $selected = 'selected="selected"';
                  }
                  echo '<option value="'.$value.'" '.$selected.' >'.$value.'</option>';
                }
              ?>
            </select>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
</div>