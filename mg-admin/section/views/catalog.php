<div class="row section-catalog">
  <!-- modals-->
      <div class="reveal xssmall" id="row-config-modal" data-reveal>
        <button class="close-button" data-close="" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
        <div class="reveal-header">
          <h2><i class="fa fa-cogs" aria-hidden="true"></i> Настройка колонок таблицы</h2>
        </div>
        <div class="reveal-body">
          <div class="sortable-block clearfix">
            <div class="left-side">
              <h3>Активные поля</h3>
              <ul class="sortable-list init-scroll">
                <li><a class="drag-handle" href="javascript:void(0);"></a>Номер</li>
                <li><a class="drag-handle" href="javascript:void(0);"></a>Категория</li>
                <li><a class="drag-handle" href="javascript:void(0);"></a>Фото</li>
                <li><a class="drag-handle" href="javascript:void(0);"></a>Название</li>
                <li><a class="drag-handle" href="javascript:void(0);"></a>Цена</li>
                <li><a class="drag-handle" href="javascript:void(0);"></a>Остаток</li>
              </ul>
            </div>
            <div class="right-side">
              <h3>Доступные поля</h3>
              <ul class="sortable-list init-scroll">
                <li><a class="drag-handle" href="javascript:void(0);"></a>Артикул</li>
                <li><a class="drag-handle" href="javascript:void(0);"></a>Дата добавления</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="reveal-footer "><a class="button success" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a><a class="button" href="javascript:void(0);" data-close><i class="fa fa-times" aria-hidden="true"></i> Закрыть</a></div>
      </div>

      <!--  -->
    <div class="reveal-overlay" style="display:none;">
      <div class="reveal large product-desc-wrapper" id="add-product-wrapper" style="display: block;">
        <button class="close-button closeModal" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
        <div class="reveal-header">
          <h2><i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="add-product-table-icon"></span></h2>
        </div>
        <div class="reveal-body product-text-inputs">
          <div class="row collapse">
            <div class="large-8 columns">
              <div class="row">
                <div class="small-12 medium-4 columns">
                  <label class=" middle"><?php echo $lang['NAME_PRODUCT']; ?>:</label>
                </div>
                <div class="small-12 medium-8 columns">
                  <input type="text" title="<?php echo $lang['T_TIP_NAME_PROD']; ?>" name="title">
                  <div class="errorField alert badge" style="margin-bottom: 15px;"><?php echo $lang['ERROR_SPEC_SYMBOL']; ?></div>
                  <input type="hidden" name="link_electro" class="product-name-input">
                  <a class="link add-link-electro" href="javascript:void(0);">Добавить ссылку на электронный товар</a>
                  <a href="javascript:void(0);" class="link del-link-electro">Удалить</a>
                </div>
              </div>
              <div class="row">
                <div class="small-12 medium-4 columns">
                  <label class=" middle"><?php echo $lang['URL_PRODUCT']; ?>:</label>
                </div>
                <div class="small-12 medium-8 columns">
                  <input type="text" name="url" title="<?php echo $lang['T_TIP_URL_PRODUCT']; ?>">
                  <div class="errorField alert badge"><?php echo $lang['ERROR_SPEC_SYMBOL']; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="small-12 medium-4 columns">
                  <label class=" middle"><?php echo $lang['CAT_PRODUCT']; ?>:<a class="fa fa-plus-circle add-category" href="javascript:void(0);" aria-hidden="true"></a></label>
                </div>
                <div class="small-12 medium-8 columns">
                  <select class="no-search" name="cat_id" title="<?php echo $lang['T_TIP_CAT_PROD']; ?>" id="productCategorySelect">
                    <option selected="selected" value="0"><?php echo $lang['ALL']; ?></option>
                    <?php echo $categoriesOptions ?>
                  </select>
                </div>
              </div>
              <div class="row inside-category" style="display:none">
                <div class="small-12 medium-4 columns">
                  <label class=" middle">Показать в категориях:</label>
                </div>
                <div class="small-12 medium-8 columns">
                  <select class="tip" name="inside_cat" title="<?php echo $lang['T_TIP_SELECTED_U_CAT']; ?>" multiple size="8">
                    <?php echo $categoriesOptions ?>
                  </select>
                  <div class="select-links clearfix clear">
                    <a class="link full-size-select-cat closed-select-cat" href="javascript:void(0);"><?php echo $lang['PROD_OPEN_CAT']; ?></a>
                    <a class="link fl-right clear-select-cat" href="javascript:void(0);"><?php echo $lang['PROD_CLEAR_CAT']; ?></a></div>
                </div>
              </div>
      
              <select class="curency-var" style="display:none">
                <?php
                $currencyShort = MG::getSetting('currencyShort');
                $currencyRate = MG::getSetting('currencyRate');
                foreach ($currencyShort as $iso => $short): ?>
                  <option value="<?php echo $iso; ?>" data-rate="<?php echo $currencyRate[$iso]; ?>"><?php echo $short; ?></option>
                <?php endforeach; ?>                       
              </select>  

              <div class="row collapse">
                <div class="large-12 columns">
                  <div class="variant-table-wrapper">
                    <div class="table-wrapper">
                      <table class="variant-table"></table>
                    </div>
                    <a class="add-position link" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить вариант</a>
                  </div>
                  <div class="add-property-field">
                    <a class="add-property link" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить свойство</a>
                    <div class="custom-popup new-added-properties" style="display:none">
                      <div class="row">
                        <div class="large-12 columns">
                          <label>Название свойства:</label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="large-12 columns">
                          <input type="text" placeholder="Например, длина" name="name">
                        </div>
                      </div>
                      <div class="row">
                        <div class="large-12 columns">
                          <label>Значение:</label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="large-12 columns">
                          <input type="text" placeholder="Например, 10см" name="value">
                        </div>
                      </div>
                      <div class="row">
                        <div class="large-12 columns">
                          <a class="button fl-left cancel-new-prop" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> Отменить</a>
                          <a class="button success fl-right apply-new-prop" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Применить</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->
                  <div class="addedProperty"></div>
                  <div class="userField"></div>  
                  <!--  -->
                </div>
              </div>
            </div>
            <div class="large-4 columns">
              <div class="add-img-block">
                <div class="images-block">
                <div class="prod-gallery">
                  <div class="row">
                    <div class="main-image parent"></div>
                  </div>
                  <div class="row">
                    <div class="sub-images"></div>
                  </div>
                </div>                  
                <div class="controller-gallery">
                  <a href="javascript:void(0);" class="add-image link"><i class="fa fa-plus-circle" aria-hidden="true"></i> <span><?php echo $lang['ADD_IMG']; ?></span></a>
                </div>
                <div class="clear"></div>
                </div>
              </div>
            </div>
          </div>
          <br>
          
          <ul class="accordion" data-accordion="" data-multi-expand="true" data-allow-all-closed="true">
            <li class="accordion-item" data-accordion-item="">
              <a class="accordion-title html-content-edit" href="javascript:void(0);">Описание товара</a>
              <div class="accordion-content" data-tab-content="" style="padding:0;">
                 <textarea class="product-desc-field" name="html_content" style="width: 821px; visibility: hidden; display: none;" placeholder="Редактор текста"></textarea>
              </div>
            </li>
            <li class="accordion-item" data-accordion-item="">
              <a class="accordion-title" href="javascript:void(0);">С этим товаром покупают</a>
              <div class="accordion-content" data-tab-content="">
                 <div class="add-related-product-block">
                   <div class="add-related-button-wrapper">
                      <a class="add-related-product link add-related-product" href="javascript:void(0);">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Прикрепить товар
                      </a>
                     <div class="custom-popup select-product-block" style="display:none;">
                       <ul class="tabs" id="add-related-product-tabs" data-tabs="">
                         <li class="tabs-title is-active" data-target="add-product"><a href="javascript:void(0);">Добавить товар</a></li>
                         <li class="tabs-title" data-target="add-category"><a href="javascript:void(0);">Добавить категорию</a></li>
                       </ul>
                       <div class="tabs-content" id="add-related-product-tabs-content">
                         <div class="tabs-panel is-active" id="add-product">
                           <div class="search-block wide">
                             <form action="#">
                               <input type="search" autocomplete="off" name="searchcat" class="search-field" placeholder="Наименование или артикул товара.">
                               <div class="errorField" style="display: none;"><?php echo $lang['RELATED_1']; ?></div>
                               <i class="fa fa-search" aria-hidden="true"></i>
                               <div class="example-line"><?php echo $lang['RELATED_2']; ?>: <a href="javascript:void(0)" class="example-find link"><?php echo $exampleName ?></a></div>
                               <div class="fastResult"></div>  
                             </form>
                           </div>
                           <div class="buttons clearfix">
                             <a class="button secondary fl-right random-add-related" href="javascript:void(0);"><i class="fa fa-random" aria-hidden="true"></i> Случайный товар</a>
                             <a class="button fl-left cancel-add-related" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> <?php echo $lang['RELATED_3']; ?></a>
                           </div>
                         </div>
                         <div class="tabs-panel" id="add-category">
                           <select class="tip " title="<?php echo $lang['T_TIP_SELECTED_U_CAT']; ?>" name="related_cat" multiple size="4">
                             <?php echo $categoriesOptions ?>
                           </select>
                           <div class="select-links clearfix">
                             <a class="link full-size-select-cat closed-select-cat" href="javascript:void(0);"><?php echo $lang['PROD_OPEN_CAT']; ?></a>
                             <a class="link fl-right clear-select-cat-related" href="javascript:void(0);"><?php echo $lang['PROD_CLEAR_CAT']; ?></a>
                           </div>
                           <div class="buttons clearfix">
                             <a class="button success fl-right save-add-related" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a>
                             <a class="button fl-left cancel-add-related" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> <?php echo $lang['RELATED_3']; ?></a>
                           </div>
                         </div>
                       </div>
                     </div>
                   </div>
                   <div class="related-wrapper" style="overflow:auto;">
                     <div class="added-related-product-block related-block clearfix" style="display:none;"></div>
                     <div class="added-related-category-block related-block clearfix" style="display:none;"></div>
                   </div>
                 </div>
              </div>
            </li>
            <li class="accordion-item" data-accordion-item="">
              <a class="accordion-title" href="javascript:void(0);">Настройки YML</a>
              <div class="accordion-content" data-tab-content="">
                <div class="row">
                  <div class="small-12 medium-3 columns">
                    <label class="text-right">Содержание поля sales_notes для экспорта в Яндекс.Маркет:<i class="fa fa-question-circle tip" aria-hidden="true" title="Используется для указания важной информации: необходимости предоплаты, условий комплектации, доставки для данного товара, минимальной суммы заказа, а также для описания акций, скидок, распродаж и пр."></i></label>
                  </div>
                  <div class="small-12 medium-9 columns">
                    <input type="text" name="yml_sales_notes" title="Будет подставлено в sales_notes. Допустимая длина - 50 символов." class="product-name-input meta-data ">
                  </div>
                </div>
              </div>
            </li>
            <li class="accordion-item" data-accordion-item="">
              <a class="accordion-title auto-meta" href="javascript:void(0);"><?php echo $lang['SEO_BLOCK'] ?></a>
              <div class="accordion-content" data-tab-content="">
                <div class="row">
                  <div class="small-12 medium-3 columns">
                    <label class=" middle"><?php echo $lang['META_TITLE']; ?>:</label>
                  </div>
                  <div class="small-12 medium-9 columns">
                    <input type="text" name="meta_title" title="<?php echo $lang['T_TIP_META_TITLE']; ?>" class="product-name-input meta-data ">
                  </div>
                </div>
                <div class="row">
                  <div class="small-12 medium-3 columns">
                    <label class=" middle"><?php echo $lang['META_KEYWORDS']; ?>:</label>
                  </div>
                  <div class="small-12 medium-9 columns">
                    <input type="text" name="meta_keywords" class="product-name-input meta-data " title="<?php echo $lang['T_TIP_META_KEYWORDS']; ?>">
                  </div>
                </div>
                <div class="row">
                  <div class="small-12 medium-3 columns">
                    <div class="">
                      <label class="middle"><?php echo $lang['META_DESC']; ?>:</label>
                      <div class="symbol-text"><?php echo $lang['LENGTH_META_DESC']; ?>: <strong class="symbol-count">0</strong></div>
                    </div>
                  </div>
                  <div class="small-12 medium-9 columns">
                    <textarea class="product-meta-field " name="meta_desc" title="<?php echo $lang['T_TIP_META_DESC']; ?>"></textarea>
                  </div>
                </div>
                <div class="row text-right">
                  <div class="large-12 columns">
                    <a class="button secondary tip seo-gen-tmpl" href="javascript:void(0);" title="<?php echo $lang['T_TIP_SEO_GEN_TMPL'];?>"><i class="fa fa-refresh" aria-hidden="true"></i> <?php echo $lang['SEO_GEN_TMPL'];?></a>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="reveal-footer text-right">
          <a class="link closeModal" href="javascript:void(0);" data-close style="margin-right:10px;">Отмена</a>
          <a class="button success save-button" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a>
        </div>
      </div>

      
      <!-- для валют -->
      <div id="for-curency" style="display: none;">
        <div class="custom-popup select-currency-block" style="display:none">
          <div class="row">
            <div class="large-12 columns">
              <label>Выберите валюту:</label>
            </div>
          </div>
          <div class="row">
            <div class="large-12 columns">
              <select class="no-search product-name-input" name="currency_iso">
                <?php
                  $currencyShort = MG::getSetting('currencyShort');
                  $currencyRate = MG::getSetting('currencyRate');
                  foreach ($currencyShort as $iso => $short): ?>
                    <option value="<?php echo $iso; ?>" data-rate="<?php echo $currencyRate[$iso]; ?>"><?php echo $short; ?></option>
                <?php endforeach; ?>        
              </select>
            </div>
          </div>
          <div class="row">
            <div class="large-12 columns text-right">
              <a class="button success apply-currency" href="javascript:void(0);">
                <i class="fa fa-check" aria-hidden="true"></i> Применить
              </a>
            </div>
          </div>
        </div>
      </div>


    </div>

      <!-- Тут начинается Верстка модального окна для текстовой характеристики  -->
    <div class="reveal-overlay" style="display:none;" id="property-value-overlay">
      <div class="reveal" id="textarea-property-value" style="display: block;">
        <div class="product-table-wrapper">
          <button class="close-button closeModal" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
          <div class="reveal-header">
            <h2>Редактирование характеристики</h2>
          </div>
          <div class="widget-table-body">
            <div class="property-value custom-textarea-value">
              <textarea name="html_content-textarea" ></textarea>
            </div>
          </div>
          <div class="reveal-footer text-right">
            <div class="save">
              <button class="save-button-value  button success" title="Сохранить значение характеристики"><span><?php echo $lang['SAVE']; ?></span></button>
            </div>
          </div>
        </div>
      </div>
    </div>
        <!-- Тут начинается Верстка модального окна для установки соответствия полей при импорте из CSV -->
      <div class="reveal-overlay" style="display:none;">
        <div class="columnComplianceModal hidden-form reveal">
          <div class="product-table-wrapper add-news-form">
            <button class="close-button closeModal" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
            <div class="reveal-header">
              <h2 class="pages-table-icon" id="modalTitle"><?php echo $lang['MODAL_TITLE']; ?>Соответствие полей импорта</h2>
            </div>
            <div class="widget-table-body">
              <div class="add-product-form-wrapper">
                <table class="main-table complianceHeaders">
                  <thead>
                    <th width="200px"><?php echo $lang['CSV_CMP_SYSTEM_FIELD'] ?></th>
                    <th width="320px"><?php echo $lang['CSV_CMP_FILE_FIELD'] ?></th>
                    <th><?php echo $lang['CSV_NOT_UPDATE_FIELD'] ?></th>
                  </thead>
                  <tbody></tbody>
                </table>
                <div class="complianceInfo alert-block warning">
                  <b><?php echo $lang['CSV_CMP_WARNING'] ?></b>
                  <br>
    <?php echo $lang['CSV_CMP_WARNING_INFO'] ?>
                </div>
              </div>
            </div>
            <div class="reveal-footer text-right">
              <div class="save">
                <button class="save-button  button success" title="<?php echo $lang['T_TIP_SAVE']; ?>"><span><?php echo $lang['SAVE']; ?></span></button>
              </div>
            </div>
          </div>
        </div>
      </div>

  <!-- юмл модалка с текстом -->
    <div class="reveal-overlay" style="display:none;">
      <div class="reveal yml-link-was-formed" style="display:block; width:500px;">
        <div class="product-table-wrapper">
          <button class="close-button closeModal" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
          <div class="reveal-header">
            <div class="alert-block warning" style="margin-left:40px;"><?php echo $lang['MESSAGE_ABOUT_YML'] ?></div>
            <!--  -->
            <div class="link text-center"><a href="javascript:void(0);" target="_blank" class="yml-link">link</a>
              <span class="edit-link"><a class=" fa fa-pencil" title="<?php echo $lang['NAME_OF_LINKYML'] ?>" href="javascript:void(0);"></a></span>
            </div>
            <div class="row">
              <div class="link-name" style="display:none;margin-left:40px;">
                <button class="save-namelinkyml button success fl-right"><span><?php echo $lang['SAVE']; ?></span></button>
                <span style="margin-top:6px;" class="fl-left"><?php echo SITE . '/' ?></span>                
                <input type="text" name="getyml" value="<?php echo MG::getSetting('nameOfLinkyml') ?>" style="width:150px;float:right;margin-right:10px;">
              </div>
            </div>
            <!--  -->
          </div>
        </div>
      </div>
    </div>
    

  
      <!-- основнеая часть -->
  <div class="large-12 columns">
    <div class="widget table">
      <div class="widget-header clearfix"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <?php echo $lang['TITLE_PRODUCTS']; ?>
        <div class="product-count fl-right"><?php echo $lang['ALL_COUNT_PRODUCT']; ?> <strong><?php echo $productsCount ?></strong> <?php echo $lang['UNIT']; ?></div>
      </div>
      <div class="widget-body">
        <div class="widget-panel-holder">
          <div class="widget-panel clearfix">
            <div class="buttons-holder fl-left">
              <a class="button success tip add-new-button" href="javascript:void(0);" title="Новый товар" data-open="add-product-modal">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить товар
              </a>
              <a class="button mg-panel-toggle tip show-filters" href="javascript:void(0);" title="<?php echo $lang['T_TIP_SHOW_FILTER']; ?>">
                <i class="fa fa-filter" aria-hidden="true"></i> <?php echo $lang['FILTER']; ?>
              </a>
              <a class="button secondary tip get-csv" href="javascript:void(0);" title="<?php echo $lang['T_TIP_PRODUCT_CSV']; ?>">
                <i class="fa fa-download" aria-hidden="true"></i> <span><?php echo $lang['IN_CSV']; ?>
              </a>
              <a class="button secondary tip import-csv" href="javascript:void(0);" title="Загрузить из">
                <i class="fa fa-upload" aria-hidden="true"></i> <?php echo $lang['PROD_FROM_CSV']; ?>
              </a>
              <a class="button secondary tip get-yml-market" href="javascript:void(0);" title="<?php echo $lang['T_TIP_UPLOAD_YA']; ?>">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i> <?php echo $lang['PROD_UPLOAD_YA']; ?>
              </a>
            </div>

            <div class="search-block fl-left mg-catalog-search">
              <!-- <form action="#"> -->
                <input type="search" placeholder="Найти товар по артикулу или названию..." class="search-input" name="search"><i class="fa fa-search" aria-hidden="true"></i>
              <!-- </form> -->
            </div>

            <div class="table-pagination clearfix">
              <?php echo $pagination ?>
            </div>
            
            
          </div>
          <!--  -->
          <div class="import-container widget-panel-content" style="display: none;">
                  <h3 class="title">Импорт товаров</h3>
                  <a href="javascript:void(0);" class="csv_skip_step link">Пропустить шаг и перейти к загрузке изображений</a>
                  <br><br>
                  <div class="message-importing"></div>
                  <div class="process"></div>
                  <div class="block-upload-сsv">
                    <div class="form-list">
                      <div class="row">
                        <div class="large-12 small-12 columns">
                          <div class="row">
                            <div class="large-3 small-12 medium-4 columns">
                              <span>Тип импорта товаров:</span>
                            </div>
                            <div class="large-9 small-12 medium-8 columns">
                              <select name="importType" style="width:200px;">
                                <option value="0">Выберите тип импорта</option>
                                <option value="MogutaCMS">Загрузка полного каталога</option>
                                <option value="MogutaCMSUpdate">Обновление цен и остатков</option>
                              </select>
                              <a href="<?php echo SITE ?>/mg-admin?examplecsv=1" class="get-example-csv view-MogutaCMS example-csv link" style="display:none">Скачать пример файла для загрузки</a>
                              <a href="<?php echo SITE ?>/mg-admin?examplecsvupdate=1" class="get-example-csv-update view-MogutaCMSUpdate example-csv link" style="display:none">Скачать пример файла для обновления цен и остатков</a>
                            </div>
                          </div>
                          <div class="row">
                            <div class="large-3 small-12 medium-4 columns">
                              <span>Загрузка:</span>
                            </div>
                            <div class="large-9 small-12 medium-8 columns">
                              <form method="post" noengine="true" enctype="multipart/form-data" class="upload-csv-form imageform button primary disabled">
                                <a href="javascript:void(0);" class="add-img-wrapper upload-btn">
                                  <label for="check-file"><span style="color:#fff;">Выбрать файл</span></label>
                                  <input type="file" style="display:none;" name="upload" class="" disabled="disabled" id="check-file" title="Загрузить CSV файл">
                                </a>
                                <div class="repeat-upload-file" style="display:none;"><span class="message"></span><a href="javascript:void(0);" class="repeat-upload-csv link" title="Отменить"></a></div>
                              </form>
                            </div>
                          </div>
                          <div class="row">
                            <div class="large-3 small-12 medium-4 columns">
                              <span>Идентификация товара:</span>
                            </div>
                            <div class="large-9 small-12 medium-8 columns">
                              <select name="identifyType" disabled="disabled" style="width:200px;">
                                <option value="name">По наименованию</option>
                                <option value="article">По артикулу</option>
                              </select>
                            </div>
                          </div>
                          <div class="identifyType"></div>
                          <div class="row">
                            <div class="large-3 small-12 medium-4 columns">
                              <span>Соответствие колонок:</span>
                            </div>
                            <div class="large-9 small-12 medium-8 columns">
                              <select name="importScheme" disabled="disabled" style="width:200px;">
                              <option value="default">Стандартная схема</option>
                              <option value="last">Последняя схема импорта</option>
                              <option value="new">Новая схема импорта</option>          
                            </select>
                            </div>
                          </div>
                        </div>
                      </div>     

                      <div class="row">
                        <div class="large-12 columns">
                          <!-- <div class="cancel-importing"><a href="javascript:void(0);" class="cancel-import custom-btn link"><span>Прервать процесс загрузки</span></a></div>               -->
                          <div class="columnCompliance">
                            
                          </div>
                          <div class="delete-all-products-btn"><label>
                              <span>Удалить все имеющиеся товары, категории и свойства товаров.</span>
                              <span class="checkbox">
                                <input type="checkbox" id="for-csv-checbox-check" name="no-merge" class="" disabled="disabled" title="Очистить каталог от товаров перед загрузкой" value="false">
                                <label for="for-csv-checbox-check" style="display:inline-block;margin-bottom:-4px;"></label>
                              </span>
                          </div>
                          <a href="javascript:void(0);" class="start-import custom-btn button success" style="margin-top:8px;"><span>Начать загрузку товаров в каталог</span></a>
                        </div>
                        
                      </div>     
                    </div>
                    
                    

                  </div>

                  <div class="block-upload-images" style="display:none;">
          <!--          <h3>Генерация миниатюр изображений товара</h3><br />-->
                    <div class="upload-images">
                      <form method="post" noengine="true" enctype="multipart/form-data" class="upload-goods-image-form button primary">
                        <a href="javascript:void(0);" class="add-img-wrapper upload-btn">
                          <label for="upload-from-server">
                            <span style="color:#fff;">Выбрать файл с компьютера</span>
                          </label>
                        </a>
                        <input type="file" id="upload-from-server" name="uploadImages" class="" title="Загружаемый файл" style="display:none;">
                      </form>
                      <form class="upload-goods-image-form button primary">
                        <a href="javascript:void(0);" class="browseImage add-img-wrapper upload-btn">
                          <label>
                            <span style="color:#fff;">Выбрать файл на сервере</span>
                          </label>                      
                        </a>
                      </form>  
                      <a href="javascript:void(0);" class="backToCsv custom-btn button primary">
                        <label>
                          <span style="color:#fff;">Вернуться к загрузке товаров</span>
                        </label>                      
                      </a>
                    </div>
                    <div class="start-generate" style="display: none;">
                      <a href="javascript:void(0);" class="startGenerationProcess">
                        <span>Начать генерацию миниатюр</span>
                      </a>

                      <div class="process">
                        <div class="loading-line"></div>
                      </div>
                    </div>
                    <div class="loger" style="margin-top: 10px;">
                      <textarea class="log widget-table-action no-radius" style="width:98%; height:200px;"></textarea>
                    </div>
                  </div>
                  <div class="clear"></div>
                </div>
          <!--  -->
          <div class="widget-panel-content filter-container" <?php if ($displayFilter) { echo "style='display:block'"; } else { echo "style='display:none'"; }?>>
          <?php  
            $arReuestUrl = parse_url($_SERVER['REQUEST_URI']);
            
            echo '<form style="padding: 0 15px 0 10px;" name="filter" class="filter-form" action="'.$arReuestUrl['path'].'" data-print-res="'.MG::getSetting('printFilterResult').'">';
          ?>
            <div class="row">
              <div class="large-6 medium-12 columns">
                <div class="row">
                  <div class="small-4 medium-5 large-5 columns to-1250rem" style="padding-left: 0; padding-right: 1.875rem;">
                    <label class="middle dashed">Категория:</label>
                  </div>
                  <div class="small-8 medium-7 large-7 columns" style="padding-left: 3px;">
                    <select class="no-search" name="cat_id" tabindex="-1" aria-hidden="true">
                      <?php
                      foreach ($listCategory as $value => $text) {
                        $selected = ($_REQUEST['cat_id'] . "" === $value . "") ? 'selected="selected"' : '';
                        $html .= '<option value="' . $value . '" ' . $selected . '>' . $text . '</option>';
                      }
                      $html .= '</select>';
                      echo $html;
                      ?>       
                    </select>
                  </div>
                <?php
                  if(MG::get('controller')=='controllers_catalog' || $_REQUEST['mguniqueurl'] == 'catalog.php') {
                    if ($_REQUEST['insideCat'] === "true" || empty($_REQUEST['insideCat'])) {
                      $checked = 'checked=checked';
                    }
                    echo '<div class="large-12 medium-12 columns">
                            <div class="small-12 columns">
                              <div class="checkbox-label fl-right">
                                <label for="1insaCat1 dashed">'.$lang['FILTR_PRICE7'].'</label>
                                <div class="checkbox">
                                  <input type="checkbox" id="1insaCat1" name="insideCat" '.$checked.'>
                                  <label for="1insaCat1"></label>
                                </div>
                              </div>
                            </div>
                          </div>';
                  }
                ?>
              </div></div>  
              <?php echo $filter ?>
              </div>
             
            <div class="actions-panel">
              <div class="alert-block success text-center">Найдено товаров: <strong><?php echo $itemsCount; ?> шт.</strong></div>
            </div>
          </div>
        </div>
      </div>
      <div class="table-wrapper">
        <table class="main-table product-table">
          <thead>
            <tr>
              <th class="checkbox">
                <div class="checkbox tip" title="Отметить все">
                  <input type="checkbox" id="c1">
                  <label for="c1" class="check-all-page"></label>
                </div>
              </th>
              <th class="number">
                <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0] == "id") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "id") ? $sorterData[1] * (-1) : 1 ?>" data-field="id">№</a>
              </th>
              <th class="number"></th>
              <th class="prod-cat">
                <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0] == "cat_id") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "cat_id") ? $sorterData[1] * (-1) : 1 ?>" data-field="cat_id"><?php echo $lang['CAT_PRODUCT']; ?></a>
              </th>
              <th class="text-center"><?php echo $lang['IMAGE']; ?></th>
              <th>
                <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0] == "title") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "title") ? $sorterData[1] * (-1) : 1 ?>" data-field="title"><?php echo $lang['NAME_PRODUCT']; ?></a>
              </th>
              <th class="text-center">
                <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0] == "price_course") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "price_course") ? $sorterData[1] * (-1) : 1 ?>"  data-field="price_course"><?php echo $lang['PRICE_PRODUCT']; ?></a>
              </th>
              <th class="text-center">
                <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0] == "count") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "count") ? $sorterData[1] * (-1) : 1 ?>" data-field="count"><?php echo $lang['REMAIN']; ?></a>
              </th>
              <th class="number" <?php if(MG::getSetting('showSortFieldAdmin') != 'true') echo 'style="display:none;"' ?>>
                <a href="javascript:void(0);" class="order field-sorter <?php echo ($sorterData[0] == "sort") ? $sorterData[3] : 'asc' ?>" data-sort="<?php echo ($sorterData[0] == "sort") ? $sorterData[1] * (-1) : 1 ?>" data-field="sort"><?php echo $lang['SORT']; ?></a>
              </th>
              <th class="row-config">
                <?php echo $lang['ACTIONS']; ?>
              </th>
            </tr>
          </thead>
          <tbody class="product-tbody">
          <?php
          if (!empty($catalog)) {
            // viewData($catalog);
            $currencyShort = MG::getSetting('currencyShort');
            $currencyShopIso = MG::getSetting('currencyShopIso');
            $currency = MG::getSetting('currency');
            foreach ($catalog as $data) {
              $data['currency_iso'] = $data['currency_iso'] ? $data['currency_iso'] : $currencyShopIso;
              ?>
                     <tr id="<?php echo $data['id'] ?>" data-id="<?php echo $data['id'] ?>" class="product-row">

              <td class="check-align">
                <div class="checkbox">
                  <input type="checkbox" id="prod-<?php echo $data['id'] ?>" name="product-check">
                  <label for="prod-<?php echo $data['id'] ?>"></label>
                </div>
              </td>
              <td class="id"><?php echo $data['id'] ?></td>
              <td class="mover">
                <i class="fa fa-arrows"></i>
              </td>
              <td id="<?php echo $data['cat_id'] ?>" class="cat_id">
                <?php $path = (substr_count($data['category_url'], '/') > 1) ? '<a class="parentCat " title="" style="cursor:pointer;">../</a>' : ''; ?>
                <?php echo $listCategories[$data['cat_id']] ? $path . $listCategories[$data['cat_id']] : 'Категория удалена'; ?>    
              <td class="product-picture image_url">
                <?php
                $imagesUrl = explode("|", $data['image_url']);

                if (!empty($imagesUrl[0])) {
                  $src = mgImageProductPath($imagesUrl[0], $data["id"], 'small');
                }
                ?>
                <img class="uploads" src="<?php echo $src ?>"/>
              </td>                
              <?php $showcode = '';
              if (MG::getSetting('showCodeInCatalog')=='true') {
                $showcode = $data['variant_exist'] ? ' ' : '['.$data['code'].'] ';
                }?>
              <td class="name" >
                <span class="product-name">
                  <a class="name-link tip edit-row" id="<?php echo $data['id'] ?>" href="javascript:void(0);" title="Редактировать товар"><?php echo $showcode.$data['title'] ?></a>
                  <a class="fa fa-external-link tip" href="<?php echo $data['link'] ?>" aria-hidden="true" title="<?php echo $lang['PRODUCT_VIEW_SITE']; ?>" target="_blank"></a>
                </span>
              </td>
              <?php $printPrice = false; ?>   
              <td class="price">
                <div class="row">
                <table class="variant-row-table">
                <?php $marginToRightColumn = 3 ?>

                <?php if ($data['price'] != MG::numberFormat($data['real_price']) && empty($data['variants'])): ?>   
                  <?php $printPrice = true; ?>
                  <tr>
                    <td colspan="3" class="text-right" style="font-weight: bold;">
                      <span class="view-price "  data-productId="<?php echo $data['id'] ?>" style="color: <?php echo (MG::numberDeFormat($data['price']) > MG::numberDeFormat($data['real_price'])) ? '#1C9221' : '#B42020'; ?>;" title="с учетом скидки/наценки"><?php echo MG::priceCourse($data['price_course']) . ' ' . $currency ?></span>
                      <div class="clear"></div>  
                    </td>
                  </tr>  
                  <?php $marginToRightColumn += 18; ?>   
                    <?php endif; $showBtn = false; ?>                  
                    <?php if (!empty($data['variants'])) { ?>
                    <tbody>
                      <?php
                      foreach ($data['variants'] as $count => $item) {
                        if ($count > 2) {
                          $showBtn = true;
                          ?>
                        </tbody>
                        <tbody class="second-block-varians" style="display:none;">
                        <?php }
                        ?>
                        <?php if ($item['price'] != $item['price_course']): ?>
                          <tr>
                            <td colspan="3" class="text-right" style="font-weight: bold;">
                              <span class="view-price " data-productId="<?php echo $item['id'] ?>" style="color: <?php echo (MG::numberDeFormat($item['price']) < MG::numberDeFormat($item['price_course'])) ? '#1C9221' : '#B42020'; ?>;" title="с учетом скидки/наценки"><?php echo MG::priceCourse($item['price_course']) . ' ' . $currency ?></span>
                              <div class="clear"></div>
                            </td>
                          </tr>
                                    <?php else: ?>
                                    <?php endif; ?>
                                    <?php $showcode = '';
                                    if (MG::getSetting('showCodeInCatalog')=='true') {
                                      $showcode = '['.$item['code'].'] ';
                                      }?>
                                    <?php
                                    $printPrice = true;
                                    echo '<tr class="variant-price-row"><td><span class="price-help">' .$showcode. $item['title_variant'] . '</span></td><td><input  class="variant-price fastsave small variant-price" type="text" value="' . $item['price'] . '"  data-packet="{variant:1,id:' . $item['id'] . ',field:\'price\'}"/></td><td>' . $currencyShort[$data['currency_iso']] . '</td></tr>';
                                  }
                                  ?>
                                </tbody>
                                <?php
                              }else {
                                echo ' <tr><td></td><td><input type="text" value="' . $data['real_price'] . '" class="fastsave small variant-price"  data-packet="{variant:0,id:' . $data['id'] . ',field:\'price\'}"/></td><td> ' . $currencyShort[$data['currency_iso']] . '</td></tr>';
                              }
                              ?>
                            </table></div> 
                            <?php 
                              if ($showBtn) {
                                echo '<div class="text-right"><a href="javascript:void(0)" class="link showAllVariants">Показать все</a></div>';
                              }
                            ?>
                          </td>
                          <td class="count" style="padding-top:<?php echo $marginToRightColumn; ?>px">
                            <?php 
                            if (!empty($data['variants'])) {
                              if ($item['price'] != $item['price_course']) {
                                $marginTop = 23;
                              } else {
                                $marginTop = 4;
                              }
                              echo '<div>';
                              foreach ($data['variants'] as $count => $item) {
                                if ($count > 2) {
                                  echo '</div><div class="second-block-varians" style="display:none;">';
                                }
                                echo '<div style="margin:'.$marginTop.'px 0 4px 0;" class="count"><input class="variant-count fastsave tiny" type="text" value="' . ($item['count'] < 0 ? '&#8734;' : $item['count']) . '" data-packet="{variant:1,id:' . $item['id'] . ',field:\'count\'}"/> ' . $lang['UNIT'] . '</div>';
                              }
                              echo '</div>';
                            } else {
                              echo '<div style="margin: 2px 0;" class="count"><input type="text" value="' . ($data['count'] < 0 ? '&#8734;' : $data['count']) . '" class="fastsave tiny"  data-packet="{variant:0,id:' . $data['id'] . ',field:\'count\'}"/> ' . $lang['UNIT'] . '</div>';
                            }
                            $margin = '';
                            ?>
                          </td> 
                <td class="sort" <?php if(MG::getSetting('showSortFieldAdmin') != 'true') echo 'style="display:none;"' ?>> 
                  <input class="fastsave tiny" type="text" value="<?php echo($data['sort']) ?>" class="fastsave"  data-packet="{variant:0,id:<?php echo $data['id'] ?>,field:'sort'}"/></td>
                          <td class="actions">
                            <ul class="action-list fl-right">                
                              <li class="edit-row" id="<?php echo $data['id'] ?>"><a class="mg-open-modal  fa fa-pencil" href="javascript:void(0);" title="<?php echo $lang['EDIT']; ?>"></a></li>
                              <li class="new " data-id="<?php echo $data['id'] ?>" title="<?php echo ($data['new']) ? $lang['PRINT_IN_NEW'] : $lang['PRINT_NOT_IN_NEW']; ?>"><a href="javascript:void(0);" class="fa fa-tag <?php echo ($data['new']) ? 'active' : '' ?>"></a></li>
                              <li class="recommend " data-id="<?php echo $data['id'] ?>" title="<?php echo ($data['recommend']) ? $lang['PRINT_IN_RECOMEND'] : $lang['PRINT_NOT_IN_RECOMEND']; ?>"><a href="javascript:void(0);" class="fa fa-star <?php echo ($data['recommend']) ? 'active' : '' ?>"></a></li>
                              <li class="clone-row" id="<?php echo $data['id'] ?>"><a class=" fa fa-files-o" href="javascript:void(0);" title="<?php echo $lang['CLONE']; ?>"></a></li>
                              <li class="visible  " data-id="<?php echo $data['id'] ?>" title="<?php echo ($data['activity']) ? $lang['ACT_V_PROD'] : $lang['ACT_UNV_PROD']; ?>"><a href="javascript:void(0);" class="fa fa-lightbulb-o <?php echo ($data['activity']) ? 'active' : '' ?>"></a></li>
                              <li class="delete-order " id="<?php echo $data['id'] ?>"><a class=" fa fa-trash" href="javascript:void(0);"  title="<?php echo $lang['DELETE']; ?>"></a></li>
                            </ul> 
                          </td>               
                          </tr>
                          <?php
                        }
                      }else {
                        ?>

                        <tr class="no-results"><td colspan="10"><?php echo $lang['PROD_NONE'] ?></td></tr>

            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="widget-footer">
        <div class="table-pagination clearfix">
          <div class="label-select fl-left"><span class="select-label">Действия:</span>
            <select class="no-search product-operation" name="operation" style="width:300px;">
              <option value="activity_0"><?php echo $lang['ACTION_PROD_1'] ?></option> 
              <option value="activity_1"><?php echo $lang['ACTION_PROD_2'] ?></option> 
              <option value="recommend_1"><?php echo $lang['ACTION_PROD_3'] ?></option> 
              <option value="recommend_0"><?php echo $lang['ACTION_PROD_4'] ?></option> 
              <option value="new_1"><?php echo $lang['ACTION_PROD_5'] ?></option> 
              <option value="new_0"><?php echo $lang['ACTION_PROD_6'] ?></option> 
              <option value="move_to_category"><?php echo $lang['ACTION_PROD_10'] ?></option>
              <option value="clone"><?php echo $lang['ACTION_PROD_7'] ?></option> 
              <option value="getcsv"><?php echo $lang['ACTION_PROD_8'] ?></option> 
              <option value="getyml"><?php echo $lang['ACTION_PROD_9'] ?></option> 
              <?php foreach (MG::getSetting('currencyShort') as $iso => $short): ?>
                <option value="changecur_<?php echo $iso; ?>">Пересчитать валюту в <?php echo $iso; ?></option>
              <?php endforeach; ?> 
              <option value="delete"><?php echo $lang['DELL_SELECTED_PROD'] ?></option> 
            </select>
                  <select  style="width:270px;display:none;" title="<?php echo $lang['T_TIP_CAT_PROD']; ?>" id="moveToCategorySelect" name="move_to_cat_id">        
            <?php echo $categoriesOptions ?>
                  </select>
            <a class="button secondary run-operation" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> <?php echo $lang['ACTION_RUN'] ?></a>
          </div>
          <?php echo $pagination ?>
          <div class="label-select small fl-right" style="margin: 0 10px;"><span class="select-label">Показать на странице:</span>
            <select class="no-search countPrintRowsProduct small">
              <?php
              foreach (array(10, 20, 50, 100, 300) as $value) {
                $selected = '';
                if ($value == $countPrintRowsProduct) {
                  $selected = 'selected="selected"';
                }
                echo '<option value="' . $value . '" ' . $selected . ' >' . $value . '</option>';
              }
              ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="large-12 columns">
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="h-height"></div>