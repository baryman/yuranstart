<div class="section-page">
  <div class="wrapper">

    <!-- Верстка модального окна -->
    <!-- modals-->
    <div class="reveal-overlay" style="display:none;">
      <div class="reveal xssmall" id="add-page-modal" style="display:block;">
        <button class="close-button closeModal" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
        <div class="reveal-header">
          <h2><i class="fa fa-plus-circle" aria-hidden="true"></i> <span id="modalTitle"></span></h2>
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
                </div>
              </div>
              <div class="row">
                <div class="small-12 medium-5 columns">
                  <label class="middle">URL адрес:</label>
                </div>
                <div class="small-12 medium-7 columns">
                  <input type="text" name="url">
                </div>
              </div>
              <div class="row">
                <div class="small-12 medium-5 columns">
                  <label class="middle">Родительская страницы:</label>
                </div>
                <div class="small-12 medium-7 columns">
                  <select class="with-search" name="parent">
                    <option selected value='0'>-</option>
                    <?php echo $selectPages ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="small-12 medium-5 columns">
                  <label class="middle">Не показывать в меню:</label>
                </div>
                <div class="small-12 medium-7 columns">
                  <div class="checkbox margin">
                    <input type="checkbox" id="cc1" name="invisible">
                    <label for="cc1"></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <ul class="accordion" data-accordion="" data-multi-expand="true" data-allow-all-closed="true">
            <li class="accordion-item html-content-edit" data-accordion-item=""><a class="accordion-title" href="javascript:void(0);">Содержание страницы:</a>
              <div class="accordion-content" data-tab-content="" style="padding:0px;">
                <textarea name="html_content"></textarea>
              </div>
            </li>
            <li class="accordion-item seo-wrapper" data-accordion-item=""><a class="accordion-title" href="javascript:void(0);">Блок для SEO</a>
              <div class="accordion-content" data-tab-content="">
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
                      <div class="symbol-text">Кол-во символов: <strong class="symbol-left symbol-count">0</strong></div>
                    </div>
                  </div>
                  <div class="small-12 medium-9 columns">
                    <textarea name="meta_desc"></textarea>
                  </div>
                </div>
                <div class="row text-right">
                  <div class="large-12 columns"><a class="button secondary tip seo-gen-tmpl" href="javascript:void(0);" title="Мета теги будут сгенерированы по шаблонам, заданным на вкладке SEO, в разделе настроек."><i class="fa fa-refresh" aria-hidden="true"></i> Генерировать мета-теги по шаблону</a></div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="reveal-footer clearfix">
          <form action="<?php echo SITE ?>/previewer" id="previewer" method="post" target="_blank" style="display:none">
            <input id="previewContent" type="hidden" name="content" value=""/>
          </form>
          <a class="button fl-left previewPage" href="javascript:void(0);"><i class="fa fa-eye" aria-hidden="true"></i> Предпросмотр</a>
          <a class="button success fl-right save-button" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a></div>
      </div>
    </div>
    

    <!-- Верстка модального окна -->

      <div class="row">
        <div class="large-12 columns">
          <div class="widget table">
            <div class="widget-header clearfix"><i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo $lang['TITLE_PAGES']; ?>
              <div class="product-count fl-right">Всего страниц <strong><?php echo $countPages?></strong> шт.</div>
            </div>
            <div class="widget-body">
              <div class="widget-panel-holder">
                <div class="widget-panel">
                  <div class="buttons-holder clearfix">
                    <a class="button success tip add-new-button" href="javascript:void(0);" title="Новый товар"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $lang['ADD_PAGE']; ?></a>
                  </div>
                </div>
              </div>
              <div class="table-wrapper">
                <table class="main-table">
                  <thead>
                    <tr>
                      <th class="checkbox">
                        <div class="checkbox tip" title="Отметить все">
                          <input type="checkbox" id="c-all">
                          <label for="c-all" class="check-all-page"></label>
                        </div>
                      </th>
                      <th class="checkbox"></th>
                      <th class="number">№</th>
                      <th>Название</th>
                      <th>URL</th>
                      <th class="text-right">Действия</th>
                    </tr>
                  </thead>
                  <tbody class="page-tree">
                  <?php echo $getPages; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="widget-footer">
              <div class="table-pagination clearfix">
                <div class="label-select fl-left"><span class="select-label">Действия:</span>
                  <select class="no-search page-operation large" style="margin-right:10px;">
                    <option value="invisible_0">Выводить в меню</option>
                    <option value="invisible_1">Не выводить в меню</option>
                    <option value="delete">Удалить выбранные страницы</option>            
                  </select><a class="button secondary run-operation" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Выполнить</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="h-height"></div>
    </div>
<!--  -->
    
  </div>
</div>

<script type="text/javascript">


</script>