<div class="section-user">

  <div class="row">
    <div class="large-12 columns">
      <div class="widget table">
        <div class="widget-header clearfix"><i class="fa fa-users" aria-hidden="true"></i> <?php echo $lang['TITLE_USERS'];?>
          <div class="product-count fl-right"><?php echo $lang['ALL_COUNT_USERS'];?>: <strong><?php echo $usersCount ?></strong> <?php echo $lang['UNIT'];?></div>
        </div>
        <div class="widget-body">
          <div class="widget-panel-holder">
            <div class="widget-panel">
              <div class="buttons-holder clearfix">
                <a class="button success tip add-new-button" href="javascript:void(0);" data-open="add-user-modal" title="<?php echo $lang['T_TIP_USER_ADD'];?>">
                  <i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $lang['USER_ADD'];?>
                </a>
                <a class="button tip mg-panel-toggle show-filters" href="javascript:void(0);" title="Панель фильтров">
                  <i class="fa fa-filter" aria-hidden="true"></i> Фильтры
                </a>
                <a href="<?php echo SITE ?>/mg-admin?csvuser=1" class="get-csv button secondary tip" title="Выгрузить в">
                  <i class="fa fa-download" aria-hidden="true"></i> <?php echo $lang['IN_CSV'];?>
                </a>

              </div>
            </div>
            <div class="widget-panel-content filter-container" <?php if (!$displayFilter) {  echo "style='display:none'"; } ?>>
              <?php echo $filter ?> 
            </div>
          <div class="table-wrapper">
            <table class="main-table">
              <thead>
                <tr>
                  <th class="checkbox">
                    <div class="checkbox">
                      <input type="checkbox" id="check-all">
                      <label class="check-all-page" for="check-all"></label>
                    </div>
                  </th>
                  <th>
                    <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0]=="email") ? $sorterData[3]:'asc' ?>" data-sort="<?php echo ($sorterData[0]=="email") ? $sorterData[1]*(-1) : 1 ?>" data-field="email">
                      <?php echo $lang['USER_EMAIL'];?>
                    </a>
                  </th>
                  <th class="text-center">
                    <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0]=="activity") ? $sorterData[3]:'asc' ?>" data-sort="<?php echo ($sorterData[0]=="activity") ? $sorterData[1]*(-1) : 1 ?>" data-field="activity">
                      <?php echo $lang['USER_STATUS'];?>
                    </a>
                  </th>
                  <th class="text-center">
                    <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0]=="role") ? $sorterData[3]:'asc' ?>" data-sort="<?php echo ($sorterData[0]=="role") ? $sorterData[1]*(-1) : 1 ?>" data-field="role">
                      <?php echo $lang['USER_GROUP'];?>
                    </a>
                  </th>
                  <th class="text-center">
                    <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0]=="date_add") ? $sorterData[3]:'asc' ?>" data-sort="<?php echo ($sorterData[0]=="date_add") ? $sorterData[1]*(-1) : 1 ?>" data-field="date_add">
                      <?php echo $lang['USER_DATE_ADD'];?>
                    </a>
                  </th>
                  <th class="text-center">
                    <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0]=="blocked") ? $sorterData[3]:'asc' ?>" data-sort="<?php echo ($sorterData[0]=="blocked") ? $sorterData[1]*(-1) : 1 ?>" data-field="blocked">
                        <?php echo $lang['ACCESS_PERSONAL'];?>
                      </a>
                  </th>
                  <th class="row-config"><?php echo $lang['ACTIONS'];?></th>
                </tr>
              </thead>
              <tbody>
                                        <?php if ($users) { ?> 
                                        <?php foreach($users as $data) { ?>

                                        <tr id="<?php echo $data['id'] ?>">
                                            <td class="check-align">
                                              <div class="checkbox">
                                                <input type="checkbox" id="c<?php echo $data['id']; ?>">
                                                <label class="select-row" for="c<?php echo $data['id']; ?>"></label>
                                              </div>
                                            </td>
                                            <td class="email"><?php echo $data['email']?></td>
                                            <td class="activity text-center">
                                                    <?php if($data['activity']) { ?>
                                                <span class="badge success"><?php echo $lang['USER_ACTYVITY_TRUE'];?></span>
                                                        <?php } else { ?>
                                                <span class="badge alert"><?php echo $lang['USER_ACTYVITY_FALSE'];?></span>
                                                        <?php }?>
                                            </td>
                                            <td class="role text-center"><?php                           
                                                    echo $groupName[$data['role']];
                                                    ?></td>
                                            <td class="date_add text-center"><?php echo date('d.m.Y H:i', strtotime($data['date_add'])) ?></td>
                                            <td class="blocked text-center">
                                                    <?php if($data['blocked']) { ?>
                                                <span class="badge alert"><?php echo $accessStatus[1] ?></span>
                                                        <?php } else { ?>
                                                <span class="badge success"><?php echo $accessStatus[0] ?></span>
                                                        <?php }?>
                                            </td>
                                            <td class="actions">
                              
                                                <ul class="action-list text-right">                  
                                   <?php                                                                        
                                   // для модератора не выводить  элементы управления записью администратора
                                   if(USER::AccessOnly('4') && $data['role'] != "1"):?>
                                                    <li class="edit-row" id="<?php echo $data['id'] ?>">
                                                      <a class="fa fa-pencil tip" href="javascript:void(0);" aria-hidden="true" title="<?php echo $lang['EDIT'];?>"></a>
                                                    </li>
                                                    <li class="delete-order " id="<?php echo $data['id'] ?>">
                                                      <a class="fa fa-trash tip" href="javascript:void(0);" aria-hidden="true" title="<?php echo $lang['DELETE'];?>"></a>
                                                    </li>
                                                   <?php endif; ?>
                                   
                                  <?php
                                   // для модератора  выводить  элементы управления всех пользователей
                                  if(USER::AccessOnly('1')):?>
                                                    <li><a class="mg-open-modal tool-tip-bottom" href="javascript:void(0);"></a></li>
                                                    <li><a class="tool-tip-bottom" href="javascript:void(0);"></a></li>

                                                    <li class="edit-row" id="<?php echo $data['id'] ?>">
                                                      <a class="fa fa-pencil tip" href="javascript:void(0);" aria-hidden="true" title="<?php echo $lang['EDIT'];?>"></a>
                                                    </li>
                                                    <li class="delete-order " id="<?php echo $data['id'] ?>">
                                                      <a class="fa fa-trash tip" href="javascript:void(0);" aria-hidden="true" title="<?php echo $lang['DELETE'];?>"></a>
                                                    </li>
                                                  <?php endif; ?>
                                  
                                </ul>             
                                 
                                             
                            
                                            </td>
                                        </tr>
                                            <?php }
                                            
                }else {
                  ?>
                <tr><td colspan="7" class="noneOrders"><?php echo $lang['USER_NONE'] ?></td></tr>

                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="widget-footer">
          <div class="table-pagination clearfix">
            <div class="label-select fl-left"><span class="select-label">Действия:</span>
              <select class="no-search user-operation" style="width: auto;">
                <option value="delete"><?php echo $lang['DELL_SELECTED_USER'] ?></option> 
                <option value="getcsvuser"><?php echo $lang['IN_CSV'] ?></option> 
              </select>
              <a class="button secondary run-operation" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Выполнить</a>
            </div>

            <?php echo $pagination ?>

            <div class="label-select small fl-right" style="margin: 0 10px;"><span class="select-label">Показать на странице:</span>
              <select class="no-search countPrintRowsUser small">
                <?php
                foreach(array(5, 10, 15, 20, 25, 30) as $value) {
                    $selected = '';
                    if($value == $countPrintRowsUser) {
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

        <!-- modals-->
<div class="reveal-overlay" style="display:none;">
  <div class="reveal lsmall" id="add-user-modal" style="display:block;">
    <button class="close-button closeModal" type="button" title="<?php echo $lang['T_TIP_CLOSE_WITHOUT_SAVE'];?>"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
    <div class="reveal-header">
      <h2><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $lang['TITLE_USER_NEW'];?></h2>
    </div>
    <div class="reveal-body">
      <div class="row">
        <div class="large-12 columns">
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle"><?php echo $lang['USER_EMAIL'];?>:</label>
            </div>
            <div class="small-12 medium-8 columns">
              <input type="email" name="email">
              <div class="errorField"><?php echo $lang['ERROR_EMAIL'];?></div>
            </div>
          </div>
          <div class="controlEditorPas">
            <div class="row">
              <div class="small-12 medium-4 columns">
                <label class="middle"><?php echo $lang['USER_PASS'];?>:</label>
              </div>
              <div class="small-12 medium-8 columns">
                <label class="middle "><a class="link editPass" href="javascript:void(0);">Не менять</a></label>
              </div>
            </div>
          </div>
          <div class="editorPas" style="display:none">
            <div class="row">
              <div class="small-12 medium-4 columns">
                <label class="middle"><?php echo $lang['USER_PASS'];?>:</label>
              </div>
              <div class="small-12 medium-8 columns">
                <input type="password" name="pass">
                <div class="errorField"><?php echo $lang['ERROR_PASS'];?></div>
              </div>
            </div>
            <div class="row">
              <div class="small-12 medium-4 columns">
                <label class="middle"><?php echo $lang['USER_PASS_CONFIRM'];?>:</label>
              </div>
              <div class="small-12 medium-8 columns">
                <input type="password" name="passconfirm">
                <div class="errorField"><?php echo $lang['ERROR_CONFIRM_PASS'];?></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle"><?php echo $lang['USER_NAME'];?>:</label>
            </div>
            <div class="small-12 medium-8 columns">
              <input type="text" name="name">
            </div>
          </div>
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle"><?php echo $lang['USER_SNAME'];?>:</label>
            </div>
            <div class="small-12 medium-8 columns">
              <input type="text" name="sname">
            </div>
          </div>
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle"><?php echo $lang['USER_BIRTHDAY'];?>:</label>
            </div>
            <div class="small-12 medium-8 columns">
              <div class="date-input">
                <input class="datepicker birthday" name="birthday" type="text"><i class="fa fa-calendar" aria-hidden="true"></i>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle"><?php echo $lang['USER_PHONE'];?>:</label>
            </div>
            <div class="small-12 medium-8 columns">
              <input type="tel" name="phone">
            </div>
          </div>
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle"><?php echo $lang['USER_ADDRESS'];?>:</label>
            </div>
            <div class="small-12 medium-8 columns">
              <textarea name="address"></textarea>
            </div>
          </div>
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle">Группа:<i class="fa fa-question-circle tip" aria-hidden="true" title="Менеджер - может работать только с заказами и плагинами, Модератор - не имеет доступа к настройкам сайта. Администратор -  все права."></i></label>
            </div>
            <div class="small-12 medium-8 columns">
              <select class="no-search role" name="role">
                <option value="2"><?php echo $lang['USER_GROUP_NAME2'];?></option>
                <option value="3"><?php echo $lang['USER_GROUP_NAME3'];?></option>
                <option value="4"><?php echo $lang['USER_GROUP_NAME4'];?></option>  
                   <?php if(USER::AccessOnly('1')):?>
                    <option value="1"><?php echo $lang['USER_GROUP_NAME1'];?></option>
                  <?php endif;?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle"><?php echo $lang['USER_STATUS'];?>:</label>
            </div>
            <div class="small-12 medium-8 columns">
              <select class="no-search activity" name="activity">
                <option value="0"><?php echo $lang['USER_ACTYVITY_FALSE'];?></option>
                <option value="1"><?php echo $lang['USER_ACTYVITY_TRUE'];?></option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="small-12 medium-4 columns">
              <label class="middle"><?php echo $lang['ACCESS_PERSONAL'];?>:</label>
            </div>
            <div class="small-12 medium-8 columns">
              <select class="no-search" title="<?php echo $lang['T_TIP_USER_BLOCKED'];?>" name="blocked">
                <option value="1"><?php echo $lang['ACCESS_PERSONAL_TRUE'];?></option>
                <option value="0"><?php echo $lang['ACCESS_PERSONAL_FALSE'];?></option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="reveal-footer text-right"><a class="button success save-button" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a></div>
  </div>
</div>
</div>


       
<script>
  $('.section-user .to-date').datepicker({dateFormat: "dd.mm.yy"});
  $('.section-user .from-date').datepicker({dateFormat: "dd.mm.yy"});
  $('.section-user .birthday').datepicker({dateFormat: "dd.mm.yy", changeMonth:true, changeYear:true, yearRange:'-90:+0'});
  $(".ui-autocomplete").css('z-index', '1000');
  $.datepicker.regional['ru'] = {
    closeText: 'Закрыть',
    prevText: '&#x3c;Пред',
    nextText: 'След&#x3e;',
    currentText: 'Сегодня',
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
    'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
    'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
    dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
    dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    dateFormat: 'dd.mm.yy',
    firstDay: 1,
    isRTL: false
  };
  $.datepicker.setDefaults($.datepicker.regional['ru']);
</script>