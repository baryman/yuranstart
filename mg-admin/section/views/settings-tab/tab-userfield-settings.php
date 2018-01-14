<!-- Тут начинается Верстка модального окна -->
<div class="reveal-overlay" style="display:none;">
  <div class="reveal xssmall" id="edit-category" style="display:block;">
    <button class="close-button close-category-edit" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
    <div class="reveal-header">
      <h2><span id="modalTitle"><?php echo $lang['STNG_LIST_CAT'];?></span></h2>
    </div>
    <div class="reveal-body">
      <div class="row">
        <div class="small-6 columns">
          <div id="select-category-form-wrapper" class="user-fields-wrapper">
            <select class ="tool-tip-right category-select" style="height:200px;" title="<?php echo $lang['T_TIP_SELECTED_U_CAT'];?>" name="listCat" multiple>
            </select>
          </div>
        </div>
        <div class="small-6 columns">
          <div class="user-fields-desc-wrapper">
            <span><?php echo $lang['STNG_LISC_SELECT_CAT'];?></span> : "<span class="propertyName"></span>"
            <p class="clear-text"><?php echo $lang['STNG_LISC_TIP'];?></p>
            <a href="javascript:void(0);" class="cancelSelect link"><?php echo $lang['STNG_LISC_CANCEL_SELECT'];?></a>
          </div>
        </div>
      </div>
    </div>
    <div class="reveal-footer clearfix">
      <button class="save-button tool-tip-bottom button success fl-right" title="<?php echo $lang['T_TIP_SAVE_U_CAT'];?>"><?php echo $lang['SAVE'];?></button>
    </div>
  </div>
</div>
<!-- Тут заканчивается Верстка модального окна -->

<div class="row">
  <div class="large-12 columns">
    <h4><?php echo $lang['STNG_USER_FIELD'];?> <a href="http://wiki.moguta.ru/tovary/harakteristiki-tovarov" target="_blank"> (Документация)</a></h4>
    <div class="widget-inner">
      <div class="widget-panel-holder">
        <div class="widget-panel clearfix">
          <div class="buttons-holder fl-left">
            <a class="button success tip addProperty" href="javascript:void(0);" title="Новая характеристика" data-open="add-characteristic-modal"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить характеристику</a>
            <a class="button mg-panel-toggle tip" href="javascript:void(0);" title="Панель фильтров"><i class="fa fa-filter" aria-hidden="true"></i> Фильтры</a>
            <label class="property-options" style="display:inline-block;vertical-align:bottom;">
              Показать характеристики привязанные к категории:
              <select name="cat_id" style="width:auto;">
                <?php foreach ($listCategories as $key => $value):?>
                  <option value="<?php echo $key?>"><?php echo $value?></option>
                <?php endforeach;?>
              </select>
            </label>
          </div>
        </div>
        <div class="widget-panel-content filters" style="display:none;margin: 0 -15px 15px;border-top: 1px solid #e6e6e6;padding:20px;">
          <form name="filter" class="filter-form" action="/repo3/mg-admin/ajax" data-print-res="on">
          <div class="row">
            <div class="large-6 columns">
              <div class="row">
                <div class="small-4 medium-5 columns">
                  <label class="middle dashed" style="padding: 0 0 2px 0; margin:0;">Название:</label>
                </div>
                <!-- <input type="hidden" name="name[]" value="like"> -->
                <div class="small-8 medium-7 columns">
                  <input type="text" name="name[]" value="" class="price-input">
                </div>
              </div>
            </div>
            <div class="large-6 columns end">
              <div class="row">
                <div class="small-4 medium-5 columns">
                  <label class="middle dashed" style="padding: 0 0 2px 0; margin:0;">Способ редактирования:</label>
                </div>
                <div class="small-8 medium-7 columns">
                  <select name="type" class="no-search">
                    <option value="null" selected="selected">Не выбрано</option>
                    <option value="string">Строка</option>
                    <option value="select">Список</option>
                    <option value="assortment">Набор для выбора</option>
                    <option value="assortmentCheckBox">Чекбоксы</option>
                    <option value="textarea">Текстовое поле</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="actions-panel">
            <div class="actions text-right">
              <a class="button filter-now" href="javascript:void(0);"><i class="fa fa-filter" aria-hidden="true"></i> Фильтровать</a>
              <a class="button secondary refreshFilter" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> Сбросить</a>
            </div>
          </div>
          </form>

        </div>
      </div>
      <div class="alert-block warning text-center">
        Обратите внимание, что в одной категории нельзя использовать характеристики с одинаковыми названиями.
        В карточке товара выводятся только уникальные названия свойств, повторяющиеся отображаться не будут.
      </div>
      <div class="table-wrapper">
        <table class="userField-settings-list main-settings-list main-table"></table>
      </div>
    </div>
  </div>
</div>
<div class="widget-footer">
  <div class="table-pagination clearfix">
    <div class="label-select fl-left"><span class="select-label">Действия:</span>
      <select name="operation" class="no-search property-operation large">
        <option value="activity_0"><?php echo $lang['SETTING_BASE_7']?></option>
        <option value="activity_1"><?php echo $lang['SETTING_BASE_8']?></option>
        <option value="filter_1">Использовать в фильтрах</option>
        <option value="filter_0">Не использовать в фильтрах</option>
        <option value="delete"><?php echo $lang['SETTING_BASE_9']?></option>
      </select>
      <a class="button secondary run-operation" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Выполнить</a>
    </div>

    <div class="to-paginator fl-right"></div>

    <div class="fl-right">
      <div style="margin: 0 10px;">
        <span class="last-items"><?php echo $lang['SHOW_PROPERTY_COUNT'];?></span>
        <select class="last-items-dropdown countPrintRowsProperty small">
        <?php
          foreach(array(10, 20, 30, 50, 100) as $value) {
            $selected = '';
            if($value == $countPrintRowsProperty) {
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

