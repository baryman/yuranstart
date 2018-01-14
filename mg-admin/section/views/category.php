<div class="section-category">
  <div class="widget-table-wrapper">

    <!-- Верстка модального окна -->

  <div class="reveal-overlay" style="display:none;">
    <div class="reveal xssmall" id="add-category-modal" style="display:block;">
      <button class="close-button closeModal" type="button" title="<?php echo $lang['T_TIP_CLOSE_MODAL']; ?>"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
      <div class="reveal-header">
        <h2><i class="fa fa-plus-circle" aria-hidden="true"></i> <span id="modalTitle"><?php echo $lang['NEW_CATEGORY']; ?></span></h2>
      </div>
      <div class="reveal-body">
        <div class="row collapse">
          <div class="large-8 columns">
            <div class="row">
              <div class="small-12 medium-5 columns">
                <label class="middle">Название:</label>
              </div>
              <div class="small-12 medium-7 columns">
                <input type="text" name="title">
                <div class="errorField"><?php echo $lang['ERROR_SPEC_SYMBOL']; ?></div>
              </div>
            </div>
            <div class="row">
              <div class="small-12 medium-5 columns">
                <label class="middle">URL адрес:</label>
              </div>
              <div class="small-12 medium-7 columns">
                <input type="text" name="url">
                <div class="errorField"><?php echo $lang['ERROR_EMPTY']; ?></div>
              </div>
            </div>
            <div class="row">
              <div class="small-12 medium-5 columns">
                <label class="middle">Родительская категория:</label>
              </div>
              <div class="small-12 medium-7 columns">
                <select class="with-search" name="parent">
                  <option selected value='0'><?php echo $lang['ALL']; ?></option>
                  <?php echo $select_categories ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="small-12 medium-5 columns">
                <label class="middle"><?php echo $lang['CAT_IMAGE_URL']; ?>:</label>
              </div>
              <div class="small-12 medium-7 columns">
                <div class="upload-form">
                  <label class="button tip add-image-to-category" for="upload-img" title="Изображение категории"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить</label>
                  <!-- <input type="file" id="upload-img" name="image_url"> -->
                </div>
                <div class="cat-img category-img-block">
                  <input type="hidden" name="image_url" class="tool-tip-bottom" value="http://localhost/repo1/uploads/cat_balestra2.png">
                  <a class="fa fa-trash tip remove-img del-image-to-category" href="javascript:void(0);" aria-hidden="true" title="Удалить изображение"></a>
                  <img src="http://placehold.it/100x100" class="category-image">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="small-12 medium-5 columns">
                <label class="middle"><?php echo $lang['CAT_INVISIBLE']; ?>:</label>
              </div>
              <div class="small-12 medium-7 columns">
                <div class="checkbox margin">
                  <input type="checkbox" id="cc1" name="invisible">
                  <label for="cc1"></label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="large-12 columns" style="margin-bottom:10px;"><a class="discount-setup-rate link" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i> Установить скидку/наценку для товаров категории</a>
                <div class="discount-rate-control">
                  <div class="popup-holder">
                    <a class="change-rate rate-dir-name link" href="javascript:void(0);">Наценка</a>
                    <div class="rate-value"><span class="rate-dir">+</span> <input type="text" name="rate" class="small"> % <a class="fa fa-trash tip remove-discount cancel-rate" href="javascript:void(0);" title="Удалить"></a></div>
                    <div class="custom-popup select-rate-block" style="display:none;">
                      <div class="row">
                        <div class="large-12 columns">
                          <label>Применять к товарам категории:</label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="large-12 columns">
                          <select class="no-search" name="change_rate_dir">
                            <option value="up">Наценку</option>
                            <option value="down">Скидку</option>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="large-12 columns">
                          <a class="button fl-left cancel-rate-dir" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> Отменить</a>
                          <a class="button success fl-right apply-rate-dir" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Применить</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <ul class="accordion" data-accordion="" data-multi-expand="true" data-allow-all-closed="true">
          <li class="accordion-item html-content-edit" data-accordion-item=""><a class="accordion-title" href="javascript:void(0);">Описание категории:</a>
            <div class="accordion-content" data-tab-content="" style="padding: 0px;">
              <textarea name="html_content"></textarea>
            </div>
          </li>
          
          <li class="accordion-item" data-accordion-item=""><a class="accordion-title" href="javascript:void(0);">Блок для SEO</a>
            <div class="accordion-content seo-wrapper" data-tab-content="">
              <div class="row">
                <div class="small-12 medium-3 columns">
                  <label class="middle">Meta Title:</label>
                </div>
                <div class="small-12 medium-9 columns">
                  <input type="text" name="meta_title">
                </div>
              </div>
              <div class="row">
                <div class="small-12 medium-3 columns">
                  <label class="middle">Meta Keywords:</label>
                </div>
                <div class="small-12 medium-9 columns">
                  <input type="text" name="meta_keywords">
                </div>
              </div>
              <div class="row">
                <div class="small-12 medium-3 columns">
                  <div>
                    <label class="middle">Meta Description:</label>
                    <div class="symbol-text">Кол-во символов: <strong class="symbol-count">0</strong></div>
                  </div>
                </div>
                <div class="small-12 medium-9 columns">
                  <textarea name="meta_desc"></textarea>
                </div>
              </div>
              <div class="row text-right">
                <div class="large-12 columns"><a class="button secondary tip generate-tags-btn" href="javascript:void(0);" title="Мета теги будут сгенерированы по шаблонам, заданным на вкладке SEO, в разделе настроек."><i class="fa fa-refresh" aria-hidden="true"></i> Генерировать мета-теги по шаблону</a></div>
              </div>
            </div>
          </li>
          <li class="accordion-item" data-accordion-item="">
            <a class="accordion-title" href="javascript:void(0);">Дополнительное описание для SEO <i class="fa fa-question-circle tip" aria-hidden='true' title="Описание можно делить на полезное - для клиентов (выводится над товарами) и SEO - для поисковиков (выводится внизу страницы)."></i></a>
            <div class="accordion-content" data-tab-content="" style="padding: 0px;">
              <textarea name="html_content-seo"></textarea>
            </div>
          </li>
        </ul>
      </div>
      <div class="reveal-footer text-right">
        <a class="link closeModal" href="javascript:void(0);">Отмена</a>
        <a class="button success save-button" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a>
      </div>
    </div>
  </div>

    <!-- Верстка модального окна -->

    <div class="row">
      <div class="large-12 columns">
        <div class="widget table">
          <div class="widget-header clearfix"><i class="fa fa-list-ol" aria-hidden="true"></i> <?php echo $lang['TITLE_CATEGORIES']; ?>
            <div class="product-count fl-right">Всего категорий <strong><?php echo $countCategory?></strong> шт.</div>
          </div>
          <div class="widget-body">
            <div class="widget-panel-holder">
              <div class="widget-panel">
                <div class="buttons-holder clearfix">
                  <a class="button success tip add-new-button" href="javascript:void(0);" title="<?php echo $lang['T_TIP_ADD_CATEGORY']; ?>"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $lang['ADD_CATEGORY']; ?></a>
                  <a class="button secondary tip get-csv" href="javascript:void(0);" title="<?php echo $lang['T_TIP_CATEGORY_CSV'];?>"><i class="fa fa-download" aria-hidden="true"></i> <?php echo $lang['IN_CSV'];?></a>
                  <a class="button secondary tip import-csv" href="javascript:void(0);" title="<?php echo $lang['T_TIP_CATEGORY_FROM_CSV'];?>"><i class="fa fa-upload" aria-hidden="true"></i> <?php echo $lang['PROD_FROM_CSV'];?></a>
                  <a class="button secondary tip sort-all-cat" href="javascript:void(0);" title="от А до Я"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i> Сортировать</a>
                </div>
              </div>
            </div>
            
            <div class="widget-panel-content import-container" style="display: none;">
              <div class="message-importing"></div>
              <div class="process"></div>
              
              <div class="block-upload-csv">
                  <a href="<?php echo SITE ?>/mg-admin?examplecategorycsv=1" class="link tip get-example-csv-update view-MogutaCMSUpdate example-csv" style="margin:0 0 10px 0;"><i class="fa fa-download" aria-hidden="true"></i> Скачать пример файла</a><br>
                  <form method="post" noengine="true" enctype="multipart/form-data" class="upload-csv-form button secondary tip imageform" style="margin:0;">
                      <a class="" href="javascript:void(0);" title="<?php echo $lang['T_TIP_CATEGORY_FROM_CSV'];?>">
                        <label for="check-file"><i class="fa fa-upload" aria-hidden="true"></i> Выбрать файл</label>
                        <input type="file" name="upload" class="hidden" id="check-file" title="Загрузить CSV файл">
                      </a>
                  </form>
                </div>
              
              <div class="block-importer row" style="display:none;padding: 0 10px;">
                <div class="repeat-upload-file"><a href="javascript:void(0);" class="repeat-upload-csv link" title="Отменить">Отменить</a></div>
                
                <div style="padding:6px 0;" class="delete-all-products-btn checkbox">
                  <!-- <input type="checkbox" name="no-merge" class="" title="<?php echo $lang['CLEAR_CATEGORY_BEFORE_CSV'];?>"  value="false"><?php echo $lang['DEL_ALL_CATEGORY'];?> -->
                  <input type="checkbox" id="csv-no-merge" name="no-merge" title="<?php echo $lang['CLEAR_CATEGORY_BEFORE_CSV'];?>" value="false">
                  <label class="" for="csv-no-merge" style="float:left; margin-right:10px;"></label>
                  <?php echo $lang['DEL_ALL_CATEGORY'];?>
                </div>

                <div>
                  <a href="javascript:void(0);" class="start-import button success tip"><span><?php echo $lang['BEGIN_UPLOAD_CATEGORY_CSV'];?></span></a>
                </div>

                <div class="repeat-upload-csv" style="display:none;">
                  <a href="javascript:void(0);" class="cancel-import link tip"><span><?php echo $lang['BREAK_UPLOAD_CSV'];?></span></a>
                </div>    
              </div>

            </div>

            <div class="widget-body">
            <div class="table-wrapper">
              <table class="main-table">
                <thead>
                  <tr>
                    <th class="checkbox">
                      <div class="checkbox tip" title="Отметить все">
                        <input type="checkbox" id="c-all">
                        <label for="c-all" class="check-all-cat"></label>
                      </div>
                    </th>
                    <th></th>
                    <th class="number">№</th>
                    <th class="name">Название</th>
                    <th>Скидка/Наценка</th>
                    <th>URL</th>
                    <th class="text-right">Действия</th>
                  </tr>
                </thead>
                <tbody class="category-tree">
                  <?php echo $getCategories; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="widget-footer">
            <div class="table-pagination clearfix">
              <div class="label-select fl-left"><span class="select-label">Действия:</span>
                <select class="no-search category-operation large" style="margin-right:10px;">
                  <option value="invisible_1">Не выводить в меню категорий</option>
                  <option value="invisible_0">Выводить в меню категорий</option>
                  <option value="activity_0">Сделать не активными</option>
                  <option value="activity_1">Сделать активными</option>
                  <option value="delete">Удалить выбранные категории</option>
                </select><a class="button secondary run-operation" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Выполнить</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>