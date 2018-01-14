
/**
 * Модуль для  раздела "Товары".
 */
var catalog = (function () {
  return {
    errorVariantField: false,
    memoryVal: null, // HTML редактор для   редактирования страниц
    supportCkeditor: null,
    deleteImage: '', // список картинок помеченных на удаление, при сохранении товара, данный список передается на сервер и картинки удаляются физически
    tmpImage2Del: '',
    /**
     * Инициализирует обработчики для кнопок и элементов раздела.
     */
    init: function() {
      includeJS(admin.SITE+'/mg-core/script/jquery.bxslider.min.js');

      // для показа картинок вариантов
      $(document).on({
          mouseenter: function () {
            $(this).parents('tr').find('.img-this-variant').show();
          },
          mouseleave: function () {
            $(this).parents('tr').find('.img-this-variant').hide();
          }
      }, ".admin-center .fa-picture-o"); //pass the element as an argument to .on

      $('.admin-center').on('click','.section-catalog .showAllVariants', function() {
        $(this).parents('tr').find('.second-block-varians').show();
        $(this).detach();
      });

      // Вызов модального окна при нажатии на кнопку добавления товаров.
      $('.admin-center').on('click', '.section-catalog .add-new-button', function() {
        catalog.openModalWindow('add');
      });

      /*Инициализирует CKEditior*/
      $('body').on('click', '.section-catalog #add-product-wrapper .html-content-edit', function() {
        var link = $(this);
        $('textarea[name=html_content]').ckeditor(function() {});
      });

      // Показывает панель с фильтрами.
      $('.admin-center').on('click', '.section-catalog .show-filters', function() {
        $('.import-container').slideUp();
        $('.filter-container').slideToggle(function() {
          $('.widget-table-action').toggleClass('no-radius');
        });
      });

      // Выделить все страницы
      $('.admin-center').on('click', '.section-catalog .check-all-page', function () {
        $('.product-tbody input[name=product-check]').prop('checked', 'checked');
        $('.product-tbody input[name=product-check]').val('true');
        $('.product-tbody tr').addClass('selected');

        $(this).addClass('uncheck-all-page');
        $(this).removeClass('check-all-page');
      });
      // Снять выделение со всех  страниц.
      $('.admin-center').on('click', '.section-catalog .uncheck-all-page', function () {
        $('.product-tbody input[name=product-check]').prop('checked', false);
        $('.product-tbody input[name=product-check]').val('false');
        $('.product-tbody tr').removeClass('selected');
        
        $(this).addClass('check-all-page');
        $(this).removeClass('uncheck-all-page');
      });

      // Применение выбранных фильтров
      $('.admin-center').on('click', '.section-catalog .filter-now', function() {
        catalog.getProductByFilter();
        return false;
      });

      // показывает все фильтры в заданной характеристике
      $('.admin-center').on('click', '.section-catalog .mg-filter-item .mg-viewfilter', function() {
        $(this).parents('ul').find('li').fadeIn();
        $(this).hide();
      });

       // показывает все группы фильтров
      $('.admin-center').on('click', '.section-catalog .mg-viewfilter-all', function() {
        $(this).hide();
        $('.mg-filter-item').fadeIn();
      });

      // Вызов модального окна при нажатии на кнопку изменения товаров.
      $('.admin-center').on('click', '.section-catalog .clone-row', function() {
        catalog.cloneProd($(this).attr('id'), $(this).parents('.product-row'));

      });

      // Вызов модального окна при нажатии на кнопку изменения товаров.
      $('.admin-center').on('click', '.section-catalog .import-csv', function() {
        $('.filter-container').slideUp();
        $('.import-container').slideToggle(function() {
          $('.widget-table-action').toggleClass('no-radius');
        });

      });

      // Обработчик для загрузки файла импорта из CSV
      $('body').on('change', '.section-catalog input[name="upload"]', function() {
        catalog.uploadCsvToImport();
      });

      // Обработчик для смены категории
      $('body').on('change', '.section-catalog .filter-container select[name="cat_id"]', function() {
        var cat_id= $('.section-catalog .filter-container select[name="cat_id"]').val();
        if(cat_id=="null") {
          cat_id = 0;
        }
        admin.show("catalog.php", cookie("type"), "page=0&cat_id=" + cat_id + '&displayFilter=1', catalog.callbackProduct);
      });
      // Обработчик для  переключения вывода товаров подкатегорий
      $('body').on('change', '.section-catalog .filter-container input[name="insideCat"]', function() {
        var cat_id= $('.section-catalog .filter-container select[name="cat_id"]').val();
        if(cat_id=="null") {
            cat_id = 0;
        }
        var request = $("form[name=filter]").formSerialize();
        var insideCat = $(this).prop('checked');
        admin.show("catalog.php", cookie("type"), request+"&page=0&insideCat="+insideCat+"&cat_id=" +cat_id, catalog.callbackProduct);
      });

      // Обработчик для загрузки файла импорта из CSV
      $('body').on('click', '.section-catalog .repeat-upload-csv', function() {
        $('.import-container input[name="upload"]').val('');
        $('.repeat-upload-file').hide();
        $('.upload-btn').show();
        $('.cancel-importing').hide();
        $('select[name=importScheme]').attr('disabled', 'disabled');
        $('select[name=identifyType]').attr('disabled', 'disabled');
        $('input[name=no-merge]').removeAttr("checked");
        $('input[name=no-merge]').val(false);
        $('input[name=no-merge]').attr('disabled', 'disabled');
        $('.message-importing').text('');
        catalog.STOP_IMPORT=false;
      });

      // Обработчик для загрузки изображения на сервер, сразу после выбора.
      $('body').on('click', '.section-catalog .start-import', function() {
        if(!confirm('Перед началом импорта, категорически, рекомендуем проверить параметры импорта и создать копию базы данных! Копия создана?')) {
          return false;
        }
        $('.repat-upload-file').hide();
        $('.block-upload-сsv').hide();
        $('.cancel-importing').show();
        catalog.startImport($('.block-importer .uploading-percent').text());
      });

      // Останавливает процесс загрузки товаров.
      $('body').on('click', '.section-catalog .cancel-import', function() {
        catalog.canselImport();
      });

       // Открывает список  дополнительных категорий
      $('body').on('click', '#add-product-wrapper .add-category', function() {
        $(this).toggleClass('open');
        if($(this).hasClass('open')) {
          $('.inside-category').show();
        } else {
          $('.inside-category').hide();
        }
      });
      
      $('body').on('click', '.section-catalog .backToCsv', function() {
        $('.block-upload-images').hide();
        $('.import-container h3.title').text(lang.BLOCK_UPLOAD_CSV_TITLE);
        $('.block-upload-сsv').show();
      });

      // снимает выделение со всех дополнительных категорий
      $('body').on('click', '#add-product-wrapper .clear-select-cat', function() {
        $(this).parents('.inside-category').find('select option').prop('selected', false);
      });
        // снимает выделение со всех опций в характеристике 
      $('body').on('click', '#add-product-wrapper .clear-select-property', function() {
        $(this).parents('.price-settings').find('select option').prop('selected', false);
      });

      // разворачивает список всех дополнительных категорий
      $('body').on('click', '#add-product-wrapper .full-size-select-cat.closed-select-cat', function() {
        var name = $(this).parents('.inside-category').find('select').attr('name');
        $('select[name='+name+']').attr('size',$('select[name=inside_cat] option').length);
        $(this).removeClass('closed-select-cat').addClass('opened-select-cat');
        $(this).text(lang.PROD_CLOSE_CAT);
      });

      $('body').on('click', '.yml-title', function() {
        $(this).toggleClass('opened').toggleClass('closed');
        $('.yml-wrapper').slideToggle(300);
        if($(this).hasClass('opened')) {
          $(this).html('Спрятать настройки YML');
        }
        else {
          $(this).html('Показать настройки YML');
        }
      });

      // сворачивает список всех дополнительных категорий
      $('body').on('click', '#add-product-wrapper .full-size-select-cat.opened-select-cat', function() {
        var name = $(this).parents('.inside-category').find('select').attr('name');
        $('select[name='+name+']').attr('size',4);
        $(this).removeClass('opened-select-cat').addClass('closed-select-cat');
        $(this).text(lang.PROD_OPEN_CAT);
      });

      // для рекомендованных категорий
      // разворачивает список всех дополнительных категорий
      $('body').on('click', '#add-product-wrapper .full-size-select-cat.closed-select-cat', function() {
        $('select[name=related_cat]').attr('size',$('select[name=related_cat] option').length);
        $(this).removeClass('closed-select-cat').addClass('opened-select-cat');
        $(this).text(lang.PROD_CLOSE_CAT);
      });
      // сворачивает список всех дополнительных категорий
      $('body').on('click', '#add-product-wrapper .full-size-select-cat.opened-select-cat', function() {
        $('select[name=related_cat]').attr('size',4);
        $(this).removeClass('opened-select-cat').addClass('closed-select-cat');
        $(this).text(lang.PROD_OPEN_CAT);
      });
       // снимает выделение со всех дополнительных категорий
      $('body').on('click', '#add-product-wrapper .clear-select-cat-related', function() {
        $('select[name=related_cat] option').prop('selected', false);
      });

      // Вызов формы для выбора валют.
      $('body').on('click', '#add-product-wrapper .btn-selected-currency', function() {
        var position = $(this).position();
        $('#add-product-wrapper .select-currency-block').show();
      });

      // применение выбраной валюты
      $('body').on('click', '#add-product-wrapper .apply-currency', function() {
        catalog.changeIso();
      });

      // Вызов модального окна при нажатии на кнопку изменения товаров.
      $('.admin-center').on('click', '.section-catalog .edit-row', function() {
        catalog.openModalWindow('edit', $(this).attr('id'));
      });

      // Удаление товара.
      $('.admin-center').on('click', '.section-catalog .delete-order', function() {
        catalog.deleteProduct(
          $(this).attr('id'),
          $('tr[id='+$(this).attr('id')+'] .uploads').attr('src'),
          false,
          $(this)
        );
      });

      // Нажатие на кнопку - рекомендуемый товар
      $('.admin-center').on('click', '.section-catalog .recommend', function() {
        $(this).find('a').toggleClass('active');
        var id = $(this).data('id');

        if($(this).find('a').hasClass('active')) {
          catalog.recomendProduct(id, 1);
          $(this).find('a').attr('title', lang.PRINT_IN_RECOMEND);
        }
        else {
          catalog.recomendProduct(id, 0);
          $(this).find('a').attr('title', lang.PRINT_NOT_IN_RECOMEND);
        }
        $('#tiptip_holder').hide();
        admin.initToolTip();
      });

      // Нажатие на кнопку - активный товар
      $('.admin-center').on('click', '.section-catalog .visible', function() {
        $(this).find('a').toggleClass('active');
        var id = $(this).data('id');

        if($(this).find('a').hasClass('active')) {
          catalog.visibleProduct(id, 1);
          $(this).find('a').attr('title', lang.ACT_V_PROD);
        }
        else {
          catalog.visibleProduct(id, 0);
          $(this).find('a').attr('title', lang.ACT_UNV_PROD);
        }
        $('#tiptip_holder').hide();
        admin.initToolTip();
      });

       // Нажатие на кнопку - новый товар
      $('.admin-center').on('click', '.section-catalog .new', function() {
        $(this).find('a').toggleClass('active');
        var id = $(this).data('id');

        if($(this).find('a').hasClass('active')) {
          catalog.newProduct(id, 1);
          $(this).find('a').attr('title', lang.PRINT_IN_NEW);
        }
        else {
          catalog.newProduct(id, 0);
          $(this).find('a').attr('title', lang.PRINT_NOT_IN_NEW);
        }
        $('#tiptip_holder').hide();
        admin.initToolTip();
      });

      // Выделить все товары.
      $('.admin-center').on('click', '.section-catalog .checkbox-cell input[name=product-check]', function() {

        if($(this).val()!='true') {
          $('.product-tbody input[name=product-check]').prop('checked','checked');
          $('.product-tbody input[name=product-check]').val('true');
        } else {
          $('.product-tbody input[name=product-check]').prop('checked', false);
          $('.product-tbody input[name=product-check]').val('false');
        }
      });

      // Сброс фильтров.
      $('.admin-center').on('click', '.section-catalog .refreshFilter', function() {
        admin.clearGetParam();
        admin.show("catalog.php","adminpage","refreshFilter=1",admin.sliderPrice);
        return false;
      });

     // Обработка выбранной категории (перестраивает пользовательские характеристики).
      $('body').on('change', '#productCategorySelect', function() {
        //достаем id редактируемого продукта из кнопки "Сохранить"
        var product_id=$(this).parents('#add-product-wrapper').find('.save-button').attr('id');
        var category_id=$(this).val();
        catalog.generateUserProreprty(product_id, category_id);

      });

      // Обработчик для загрузки изображения на сервер, сразу после выбора.
      $('body').on('change', '.add-img-block input[name="photoimg"]', function() {
        var currentImg = '';
        var img_container = $(this).parents('.parent');

        if(!img_container.attr('class')) {
          img_container = $(this).parents('.variant-row');
        }

        if(img_container.find('img').length > 0) {
          currentImg = img_container.find('img').attr('alt');
        } else {
          currentImg = img_container.find('img').attr('filename');
        }

        //Пишем в поле deleteImage имена изображений, которые необходимо будет удалить при сохранении
        if(catalog.deleteImage) {
          catalog.deleteImage += '|'+currentImg;
        } else {
          catalog.deleteImage = currentImg;
        }
        if($(this).val()) {
          catalog.addImageToProduct(img_container);
        }
      });

      // Добавляет ссылку на электронный товар
      $('body').on('click', '.add-link-electro', function() {
         admin.openUploader('catalog.getFileElectro');
         $('#overlay:last').css('z-index', '100');
      });

      // Удаляет ссылку на электронный товар
      $('body').on('click', '.del-link-electro', function() {
         $('.section-catalog input[name="link_electro"]').val('');
         $('.del-link-electro').hide();
         $('.add-link-electro').show();
      });


      // Удаление изображения товара, как из БД таи физически с сервера.
      $('body').on('click', '.cancel-img-upload', function() {
        var img_container = $(this).parents('.parent');
        catalog.delImageProduct($(this).attr('id'),img_container);

      });

      // Сохранение продукта при на жатии на кнопку сохранить в модальном окне.
      $('body').on('click', '#add-product-wrapper .save-button', function() {
        catalog.saveProduct($(this).attr('id'));
      });

       // Нажатие ентера при вводе в строку поиска товара
      $('body').on('keypress', '.widget-panel input[name=search]', function(e) {
        if(e.keyCode==13) {
          catalog.getSearch($(this).val());
          $(this).blur();
        }
      });


       // Добавить вариант товара
      $('body').on('click', '.variant-table-wrapper .add-position', function() {
        catalog.addVariant($('.variant-table'));
      });

       // Удалить вариант товара
      $('body').on('click', '#add-product-wrapper .del-variant', function() {
        if(confirm('Удалить?')) {
          if($('.variant-table tr').length==2) {
            $('.variant-table .hide-content').hide();
            $('.variant-table').data('have-variant','0');
          } else {
            $(this).parents('tr').remove();
          }

          var imgFile = $(this).parents('tr').find('.img-this-variant img').attr('src');
          return false;
          admin.ajaxRequest({
            mguniqueurl:"action/deleteImageProduct",
            imgFile: imgFile,
          },

          function(response) {
            admin.indication(response.status, response.msg);
          });
        }

      });

       // при ховере на иконку картинки варианта  показывать  имеющееся изображение
       $('body').on('mouseover mouseout', '.product-table-wrapper .img-variant, .product-table-wrapper .del-img-variant',  function(event) {
        if (event.type == 'mouseover') {
          $(this).parents('td').find('.img-this-variant').show();
        } else {
          $(this).parents('td').find('.img-this-variant').hide();
        }
      });

      // При получении фокуса в поля для изменения значений, запоминаем каким было  исходное значение
      $('.admin-center').on('focus', '.section-catalog .fastsave', function() {
        catalog.memoryVal = $(this).val();
      });

      // сохранение параметров товара прямо из общей таблицы товаров при потере фокуса
      $('.admin-center').on('blur', '.section-catalog .fastsave', function() {
        //если введенное отличается от  исходного, то сохраняем.
        if(catalog.memoryVal!=$(this).val()) {
          catalog.fastSave($(this).data('packet'), $(this).val(),$(this));
        }
        catalog.memoryVal = null;
      });

      // сохранение параметров товара прямо из общей таблицы товаров при нажатии ентера
      $('.admin-center').on('keypress', '.section-catalog .fastsave', function(e) {
        if(e.keyCode==13) {
          $(this).blur();
        }
      });

      // показывает сроку поиска для связанных товаров
      $('body').on('click', '#add-product-wrapper .add-related-product', function() {
        $('.select-product-block').show();
      });

      // Удаляет связанный товар из списка связанных
      $('body').on('click', '#add-product-wrapper .add-related-product-block .remove-added-product', function() {
        $(this).parents('.product-unit').remove();
        catalog.widthRelatedUpdate();
        catalog.msgRelated();
      });
      // Удаляет связанную категорию товар из списка связанных
      $('body').on('click', '#add-product-wrapper .add-related-product-block .remove-added-category', function() {
        $(this).parents('.category-unit').remove();
        catalog.widthRelatedUpdate();
        catalog.msgRelated();
      });

      // Закрывает выпадающий блок выбора связанных товаров
      $('body').on('click', '#add-product-wrapper .add-related-product-block .cancel-add-related', function() {
        $('.select-product-block').hide();
      });

      // Поиск товара при создании связанного товара.
      // Обработка ввода поисковой фразы в поле поиска.
      $('body').on('keyup', '#add-product-wrapper .search-block input[name=searchcat]', function() {
        admin.searchProduct($(this).val(),'#add-product-wrapper .search-block .fastResult');
      });

      // подбор случайного товара
      $('body').on('click', '#add-product-wrapper .random-add-related', function() {
        admin.ajaxRequest({
          mguniqueurl:"action/getRandomProd"
        },
        function(response) {
          admin.indication(response.status, response.msg);
          if(response.status!='error') {
            catalog.addrelatedProduct(0, response.data.product);
          }
        },
        false,
        false,
        true
       );
      });

      // Подстановка товара из примера в строку поиска связанного товара.
      $('body').on('click', '#add-product-wrapper .search-block  .example-find', function() {
        $('.section-catalog .search-block input[name=searchcat]').val($(this).text());
        admin.searchProduct($(this).text(),'#add-product-wrapper .search-block .fastResult');
      });

     // Клик по найденым товарам поиска в форме добавления связанного товара.
      $('body').on('click', '#add-product-wrapper .fast-result-list a', function() {
        catalog.addrelatedProduct($(this).data('element-index'));
      });

      // Выполнение выбранной операции с товарами
      $('.admin-center').on('click', '.section-catalog .run-operation', function() {
        catalog.runOperation($('.product-operation').val());
      });

      $('.admin-center').on('change', '.section-catalog select[name="operation"]', function() {
        if($(this).val() == 'move_to_category') {
          $('select#moveToCategorySelect').show(1);
        } else {
          $('select#moveToCategorySelect').hide(1);
        }
      });

      // Изменение типа каталога для импорта из CSV
      $('.admin-center').on('change', ".block-upload-сsv select[name=importType]", function() {
        $('.block-upload-сsv .example-csv').hide();
        $('input[name=upload]').val('');

        if ($(this).val() != 0) {
          $('input[name=upload]').removeAttr('disabled');
          $('.block-upload-сsv .view-'+$(this).val()).show();          
          $('select[name=importScheme]').attr('disabled', 'disabled');
          $('select[name=identifyType]').attr('disabled', 'disabled');
          $('.upload-csv-form').removeClass('disabled');
          $('input[name=no-merge]').attr('disabled', 'disabled');
          $('input[name=no-merge]').removeAttr("checked");
          $('input[name=no-merge]').val(false);
          $('.upload-btn').show();
          $('.repeat-upload-file').hide();
          $('.message-importing').text('');
        } else {
          $('input[name=upload]').attr('disabled', 'disabled');
          $('.upload-csv-form').addClass('disabled');
        }

        if($(this).val() === 'MogutaCMSUpdate') {
          $('.identifyType').hide();
          $(".delete-all-products-btn").hide();
        } else {
          $('.identifyType').show();
          $(".delete-all-products-btn").show();
        }
      });

     // Обработчик для загрузки изображения на сервер, сразу после выбора.
      $('body').on('change', 'tr input[name="photoimg"]', function() {
        // отправка картинки на сервер
        var imgContainer = $(this).parents('td');
        var mguniqueurl = "action/addImage";
        var oldimage = null;
        var nowatermark = $(this).hasClass('img-variant')?1:0;
        if(nowatermark) {
          oldimage = $(this).parents('td').find('img').attr('filename');
          if(!oldimage) {
             oldimage = $(this).parents('td').find('img').data('filename');
          }
          mguniqueurl = "action/addImageNoWaterMark";
        }

        $(this).parents('form').ajaxForm({
          type:"POST",
          url: "ajax?oldimage="+oldimage,
          data: {
            mguniqueurl: mguniqueurl,
            oldimage: oldimage,
          },
          cache: false,
          dataType: 'json',
          success: function(response) {

            admin.indication(response.status, response.msg);
            if(response.status != 'error') {
              var src=admin.SITE+'/uploads/'+response.data.img;
              imgContainer.find('img').attr('src',src).attr('filename', response.data.img);
              imgContainer.find('.del-img-variant').show();
              // imgContainer.find('.img-button').hide();
            } else {
              var src=admin.SITE+'/mg-admin/design/images/no-img.png';
              imgContainer.find('img').attr('src',src).attr('filename', 'no-img.png');
            }
          }
        }).submit();
      });

      // Устанавливает количество выводимых записей в этом разделе.
      $('.admin-center').on('change', '.section-catalog .countPrintRowsProduct', function() {
        var count = $(this).val();
        admin.ajaxRequest({
          mguniqueurl: "action/setCountPrintRowsProduct",
          count: count
        },
        function(response) {
          admin.refreshPanel();
        }
        );

      });


      // Подобрать продукты по поиску
      $('.admin-center').on('click', '.section-catalog .searchProd', function() {
        var keyword =  $('input[name="search"]').val();
        catalog.getSearch(keyword);
      });


       //Добавить изображение для продукта
       $('body').on('click', '#add-product-wrapper .add-image', function() {
         var src=admin.SITE+'/mg-admin/design/images/no-img.png';
         var row = catalog.drawControlImage(src, true,'','','');
         $('.sub-images').append(row);
         admin.initToolTip();
       });

       // для главной картинки меняем классы сохраняем в буфер и удаляем

       //Сделать основной картинку продукта
       $('body').on('click', '.set-main-image', function() {
          var obj = $(this).parents('.parent');
          catalog.upMainImg(obj);
       });

       //Показать окно с настройками title и alt для картинки
       $('body').on('click', '#add-product-wrapper .seo-image', function(e) {
        var seoBlock = $(this).parents('.parent').find('.custom-popup');
        var main = false;
        if(seoBlock.is(':visible')==true) {
          seoBlock.hide();
        } else {
          seoBlock.show();
        }
        if(!main) {
          var obj = $(this).parents('.image-item'),
            objIndex = obj.index() + 1;

          if (objIndex % 2 == 0) {   //Если остаток деления на 2 равен 0, то четное
            seoBlock.css('margin-left','-100%');
          }
        }
       });

      //Спрятать  окно с настройками title и alt для картинки
      $('body').on('click', '#add-product-wrapper .apply-seo-image', function() {
        $(this).parents('.custom-popup').hide();
      });

      //Спрятать окно с настро title и alt для картинок, если параментры не были указаны
      $('body').on('click', '#add-product-wrapper .seo-image-block-close', function() {
        $(this).parents('.custom-popup').hide();
      });

       //Клик по кнопке Яндекс.Маркет
       $('body').on('click', '.get-yml-market', function() {
          admin.ajaxRequest({
             mguniqueurl:"action/existXmlwriter"
           },
           function(response) {
            admin.indication(response.status, response.msg);
            if(response.status!='error') {
              window.location=admin.SITE+'/mg-admin?yml=1';
            }
            admin.ajaxRequest({
              mguniqueurl:"action/createYmlLink",
            },
            function(response) {          
              admin.indication(response.status, response.msg);   
              if (response.status == 'success') {       
                admin.openModal($('.section-catalog .yml-link-was-formed'));            
                $('.section-catalog .yml-link-was-formed .yml-link').text(response.data);
                $('.section-catalog .yml-link-was-formed .yml-link').attr('href', response.data);
                $('.section-catalog .yml-link-was-formed .save-namelinkyml').addClass('save-button');
                $('.section-catalog .yml-link-was-formed').show();
              }
            })
           }, 
           $('.userField')
          );

       });
       $('body').on('click', '.section-catalog .yml-link-was-formed .edit-link', function() {
         $(this).parents('.product-table-wrapper').find('.link-name').show();
         $(this).parents('.product-table-wrapper').find('.link').hide();
       });
      // выводит путь родительских категорий при наведении мышкой
      $('.admin-center').on('mouseover', '.section-catalog tbody tr.product-row .cat_id', function() {
        if (!$(this).find('.parentCat').hasClass('categoryPath') && $(this).attr('id')!=0) {
          $(this).find('.parentCat').addClass('categoryPath');
          var cat_id = $(this).attr('id');
          var path = '';
          var parent = $('.section-catalog #add-product-wrapper select[name=cat_id] option[value='+cat_id+']').data('parent');
          if (parent) {
            while (parent != 0) {
              path = $('.section-catalog #add-product-wrapper select[name=cat_id] option[value='+parent+']').text()+ '/' + path ;
              parent = $('.section-catalog #add-product-wrapper select[name=cat_id] option[value='+parent+']').data('parent');
            }
            path = path.replace(/-/g,'');
            $(this).find('.parentCat').attr('title', '/'+path);
            $('#tiptip_holder').hide();
            admin.initToolTip();
          }
        }
      });
       // открытие текстового редактора для ввода значения текстовой характеристики, замена вхождения <br> на перенос строки /n
      $('body').on('click', '.property.custom-textarea', function() {
        var id = $(this).data('name');
        var html = $('.userField .custom-textarea[data-name='+id+']').parent().find('.value').text();
        html = html.replace(/&lt;br\s*\/*&gt;/g, '\n');
        $('#textarea-property-value textarea[name=html_content-textarea]').val(admin.htmlspecialchars_decode(html));
        var offset = (window.pageYOffset);
        admin.openModal("#textarea-property-value");
        $('#textarea-property-value textarea[name=html_content-textarea]').ckeditor();
        $('#textarea-property-value .save-button-value').data('id', id);
      });
      // если поле изменено и не сохранено - перед закрытием выводит сообщение
      $('body').on('click', '#textarea-property-value .proper-modal_close', function() {
        // if ($(this).hasClass('edited')) {
        //   if (!confirm('Изменения не сохранены. Закрыть окно характеристики?')) {
        //     return false;
        //   }
        // }
        $(this).removeClass('edited');
        admin.closeModal("#textarea-property-value");
        $('#textarea-property-value textarea').val('');
        $('#textarea-property-value .save-button-value').data('id', '');
      });
       // добавление класса на кнопку закрытия при изменении
      $('body').on('click', '#textarea-property-value .custom-textarea-value', function() {
        $('#textarea-property-value .proper-modal_close').addClass('edited');
      })
       // сохранение значения текстовой характеристики
      $('body').on('click', '#textarea-property-value .save-button-value', function() {
        var id = $(this).data('id');
        var value = $('#textarea-property-value textarea').val();
        $('#add-product-wrapper .userField .custom-textarea[data-name='+id+']').parent().find('.value').text(admin.htmlspecialchars(value));
        admin.indication('success', 'Значение характеристики сохранено');
        admin.closeModal("#textarea-property-value");
        $('#textarea-property-value textarea').val('');
        $('#textarea-property-value .save-button-value').data('id', '');
        $('#textarea-property-value .proper-modal_close').removeClass('edited');
      })
      // добавление "своего" артикула
      $('.admin-center').on('keyup', '.variant-table .default-code', function() {
        $(this).removeClass('default-code');
      });
      // формирование meta title по введенному названию
      $('body').on('blur', '.product-text-inputs input[name=title]', function() {
        var title = $(this).val().replace(/"/g,'');
        
        if (!$('#add-product-wrapper input[name=meta_title]').val()) {
          $('#add-product-wrapper input[name=meta_title]').val(title);
        }
        
        catalog.generateKeywords(title);
      });
      // при заполнении поля описание товара - первые 160 символов копируются в блок SEO - description
      CKEDITOR.on('instanceCreated', function(e) {
        if (e.editor.name === 'html_content') {
          e.editor.on('blur', function (event) {
          var description = $('#add-product-wrapper textarea[name=meta_desc]').val();
          if (!$.trim(description)) {
            description = $('textarea[name=html_content]').val();
            var short_desc = catalog.generateMetaDesc(description);
            $('#add-product-wrapper textarea[name=meta_desc]').val($.trim(short_desc));
            $('#add-product-wrapper textarea[name=meta_desc]').trigger('blur');
          }
          });
        }
      });

      /*Инициализирует CKEditior и раскрывает поле для заполнения описания товара*/
      /*$('body').on('click', '.product-desc-wrapper .html-content-edit', function() {
        //var link = $(this);
      });*/

      /*Разворачивает все варианты товара на странице*/
      $('.admin-center').on('click', '.section-catalog .show-all-variants', function() {
        var prodId = $(this).attr('data-id');
        var blockVariants = $('.product-table tr#'+prodId+' .second-block-varians');

        if(blockVariants.is(':visible')) {
          blockVariants.hide();
          $(this).text(lang.ACT_SHOW_ALL_VARIANTS);
        } else {
          blockVariants.show();
          $(this).text(lang.ACT_HIDE_VARIANTS);
        }

        return false;
      });

      /**
       * Дополнительный обработчик закрытия модального окна,
       * для удаления загруженных изображений.
       */
      $('body').on('click', '.b-modal_close', function () {
        var imagesList = '';
        if($(this).attr('item-id')) {
          imagesList = catalog.tmpImage2Del;
          catalog.tmpImage2Del = '';
        } else {
          imagesList = catalog.createFieldImgUrl();

          $('.variant-table .variant-row').each(function() {
            var filename = $(this).find('img[filename]').attr('filename');

            if(!filename) {
              filename = $(this).find('img').data('filename');
            }

            if(filename) {
              imagesList += '|'+filename;
            }
          });

          imagesList += '|'+catalog.deleteImage;
          catalog.deleteImage = '';
        }

        admin.ajaxRequest({
          mguniqueurl:"action/deleteTmpImages",
          images: imagesList
        });
        // удаляем добавленные характеристики, если товар не был сохранен
        catalog.closeAddedProperty('close');
      });
      /* Добавляет новую характеристику для товара */
      $('body').on('click', '#add-product-wrapper .add-property', function() {
        $('#add-product-wrapper .new-added-properties').show();
      });
      /* Добавляет новую характеристику для товара */
      $('body').on('click', '#add-product-wrapper .apply-new-prop', function () {
        var name = $(this).parents('.custom-popup').find('input[name=name]').val();
        var value = $(this).parents('.custom-popup').find('input[name=value]').val();
        if (name == '') {
          $(this).parents('.custom-popup').find('input[name=name]').addClass('error-input');
          $('#add-product-wrapper .new-added-properties .errorField').show();
          return false;
        } else {
          catalog.addNewProperty(admin.htmlspecialchars(name), admin.htmlspecialchars(value));
        }
      });

      /* Отменяет создание новой характеристики */
      $('body').on('click', '#add-product-wrapper .cancel-new-prop', function() {
        catalog.closeAddedProperty();
      });
      /* Удаляет вновь созданную характеристику */
      $('body').on('click', '#add-product-wrapper .remove-added-property', function() {
        var id = $(this).parents('.new-added-prop').data('id');
        $(this).parents('.new-added-prop').remove();
        admin.ajaxRequest({
          mguniqueurl: "action/deleteUserProperty",
          id: id
        })
      });

      // Удалить фотографию варианта товара
      $('body').on('click', '#add-product-wrapper .del-img-variant', function() {
        if (confirm(lang.DELETE_IMAGE+'?')) {
          var src = admin.SITE+'/mg-admin/design/images/no-img.png';
          var currentImg = $(this).parents('tr').find('.img-this-variant img').attr('filename');
          $(this).parents('tr').find('.img-this-variant img').attr('src',src).attr('filename', 'no-img.png');
          $(this).hide();
          $(this).parents('tr').find('.img-button').show();
            //Пишем в поле deleteImage имена изображений, которые необходимо будет удалить при сохранении
          if(catalog.deleteImage) {
            catalog.deleteImage += '|'+currentImg;
          } else {
            catalog.deleteImage = currentImg;
          }
        }
        return false;
      });

      // переключение табов в попапе для добаления рекомендованного товарар или категории
      $('body').on('click', '#add-related-product-tabs .tabs-title', function() {
        $('#add-related-product-tabs .tabs-title').removeClass('is-active');
        $(this).addClass('is-active');

        $('#add-related-product-tabs-content .tabs-panel').removeClass('is-active');
        $('#add-related-product-tabs-content #'+$(this).data('target')).addClass('is-active');
      });

      $('body').on('click', 'a.get-csv', function() {
        catalog.exportToCsv();

        return false;
      });

      $('.admin-center').on('change', '.section-catalog select[name=importScheme]', function() {
        switch($(this).val()) {
          case 'last':
            catalog.showSchemeSettings('last');
            break;
          case 'new':
            catalog.showSchemeSettings('auto');
            break;
          default:
            return false;
        }
      });

      // Сохраняет изменения в модальном окне
      $('.admin-center').on('click', '.section-catalog .columnComplianceModal .save-button', function() {
        var data = {};
        var obj = '{';
        $('.section-catalog .columnComplianceModal select').each(function() {
          obj += '"' + $(this).attr('name') + '":"' + admin.htmlspecialchars($(this).val()) + '",';
        });
        obj += '}';
        //преобразуем полученные данные в JS объект для передачи на сервер
        data.compliance =  eval("(" + obj + ")");

        obj = '{';
        $('.section-catalog .columnComplianceModal input[type="checkbox"]').each(function() {
          if($(this).prop('checked')) {
            obj += '"' + $(this).attr('name') + '":"1",';
          }
        });
        obj += '}';

        //преобразуем полученные данные в JS объект для передачи на сервер
        data.not_update =  eval("(" + obj + ")");

        admin.ajaxRequest({
          mguniqueurl: "action/setCsvCompliance", // действия для выполнения на сервере
          data: data,
          importType: $('.columnComplianceModal button.save-button').attr('importType')
        },
        function(response) {
          admin.indication(response.status, response.msg);
          admin.closeModal($('.columnComplianceModal'));
        });
      });

      $('.admin-center').on('click', '.section-catalog .columnComplianceModal .b-modal_close', function() {
        admin.closeModal($('.columnComplianceModal'));
      });

      //Пропустить шаг импорта товаров и перейти к загрузке изображений
      $('.admin-center').on('click', '.csv_skip_step', function() {
        $('.block-upload-сsv').hide();
        $('.import-container h3.title').text(lang.BLOCK_UPLOAD_IMAGES_TITLE);
        $('.block-upload-images').show();
      });

      // Выбор ZIP архива на сервере
      $('.admin-center').on('click', '.section-catalog .block-upload-images .browseImage', function() {
        admin.openUploader('catalog.getFile');
         //catalog.printLog('Файлы архива распаковываются в tempimg/ !');
      });

      // Обработчик для загрузки архива с изображениями
      $('.admin-center').on('change', '.section-catalog .block-upload-images input[name="uploadImages"]', function() {
        catalog.uploadImagesArchive();
      });

      $('.admin-center').on('click', '.section-catalog .block-upload-images .startGenerationProcess', function() {
        $(this).hide();
        $('.message-importing').html('Идет процесс генерации миниатюр. Обработано:' + 0
                + '%<div class="progress-bar"><div class="progress-bar-inner" style="width:' + 0
                + '%;"></div></div>');
        $('.message-importing').show();
        catalog.startGenerationImage();
      });

      $('.admin-center').on('click', 'a.gotoImageUpload' , function() {
        $('.message-importing').hide();
        $('.import-container h3.title').text(lang.BLOCK_UPLOAD_IMAGES_TITLE);
        $('.block-upload-images').show();
        return false;
      });
      $('body').on('click', '#overlay', function() {
        if ($('.section-catalog .yml-link-was-formed').is(":visible")) {
          $('.section-catalog .yml-link-was-formed .save-namelinkyml').removeClass('save-button');
          admin.closeModal($('.section-catalog .yml-link-was-formed'));
        }
      });
      // сохранение ссылки для yml - название файла надо переименовать после изменений.
      $('body').on('click', ".section-catalog .yml-link-was-formed .save-namelinkyml", function() {
        var name = $(this).parents('.yml-link-was-formed').find('input[name=getyml]').val();
        admin.ajaxRequest({
          mguniqueurl:"action/renameYmlLink",
          name: name
        },
        function(response) {          
          admin.indication(response.status, response.msg);   
          if (response.status == 'success') {       
            $('.section-catalog .yml-link-was-formed .yml-link').attr('href', admin.SITE+'/'+name);
            $('.section-catalog .yml-link-was-formed .yml-link').text(admin.SITE+'/'+name);
            $('.section-catalog .yml-link-was-formed .link').show();
            $('.section-catalog .yml-link-was-formed .link-name').hide();
          }
        })
      });
      // выбор рекомендуемых товаров или категорий
      $('body').on('click', '#add-product-wrapper .related-type li', function() {
        if ($(this).hasClass('ui-state-active') ) {
          return false;
        } 
        var type = $(this).data('type');
        $(this).parent().find('.ui-state-active').removeClass('ui-state-active');
        $(this).addClass('ui-state-active');
        $('#add-product-wrapper .search-block').hide();
        $('#add-product-wrapper .search-block.'+type).show();
      });
      // добавление выбранных категорий в список рекомендуемых товаров save-add-related
      $('body').on('click', '#add-product-wrapper .save-add-related', function() {
        var related = $('#add-product-wrapper select[name=related_cat]').val();
        if(related != null) {
          if (related.length > 0) {
          admin.ajaxRequest({
              mguniqueurl:"action/getRelatedCategory",
              cat:related
            },
            function(response) {
              if(response.status!='error') {
                catalog.addrelatedCategory(response.data);
              }
            })
          }
        }
      });
      // раскрывает список опций в админке в карточке товара 
      $('body').on('click', '#add-product-wrapper .toggle-properties', function() {
        if ($(this).hasClass('open')) {
          var size = 4;
          $(this).text(lang.PROD_OPEN_CAT);
        } else {
          var size = $(this).parents('.price-settings').find('select.property option').length;
          $(this).text(lang.PROD_CLOSE_CAT);
        }
        $(this).toggleClass('open');
        $(this).parents('.price-settings').find('select.property').attr("size", size);
      });
      
      $('body').on('click', '#add-product-wrapper .seo-gen-tmpl', function() {
        catalog.generateSeoFromTmpl('userClick');
      });

     },
     /**
      * Генерируем мета описание
      */
     generateMetaDesc: function(description) {
        if(description == undefined) description = '';
        var short_desc = description.replace(/<\/?[^>]+>/g, '');
        short_desc = admin.htmlspecialchars_decode(short_desc.replace(/\n/g, ' ').replace(/&nbsp;/g, '').replace(/\s\s*/g, ' ').replace(/"/g, ''));
        
        if (short_desc.length > 150) {
          var point = short_desc.indexOf('.', 150);
          short_desc = short_desc.substr(0, (point > 0 ? point : short_desc.indexOf(' ',150)));
        }
        
        return short_desc;
     },
     /**
      * Генерируем ключевые слова для товара
      * @param string title
      */
     generateKeywords: function(title) {
        if (!$('#add-product-wrapper input[name=meta_keywords]').val()) {
          var code = $('input[name=code]').val();
          if(code) {
            code = ', '+code;
          }
          var keywords = title +' '+ lang.META_BUY + code;
          var keyarr = title.split(' ');
          for ( var i=0; i < keyarr.length; i++) {
            var word = keyarr[i].replace('"','');
            if (word.length > 3) {
              keywords += ', ' + word;
            } else {
                if(i!==keyarr.length-1) {
                   keywords += ', '+ word + ' ' + keyarr[i+1].replace(/"/g,'');
                   i++;
                } else {
                    keywords += ', '+ word;
                }
            }
          }
          $('#add-product-wrapper input[name=meta_keywords]').val(keywords);
        }
     },
     /**
      * Запускаем генерацию метатегов по шаблонам из настроек
      */
     generateSeoFromTmpl: function(who) {
        if (!$('#add-product-wrapper input[name=meta_keywords]').val()) {
          catalog.generateKeywords($('.product-text-inputs input[name=title]').val());
        }
        
        if (!$('#add-product-wrapper input[name=meta_title]').val()) {
          $('#add-product-wrapper input[name=meta_title]').val($('.product-text-inputs input[name=title]').val());
        }
        
         if (!$('#add-product-wrapper textarea[name=meta_desc]').val()) {
          var short_desc = catalog.generateMetaDesc($('textarea[name=html_content]').val());
          $('#add-product-wrapper textarea[name=meta_desc]').val($.trim(short_desc));
        }
       
        var data = {
          title: $('.product-text-inputs input[name=title]').val(),
          category_name: $('.product-text-inputs select#productCategorySelect option:selected').text(),
          code: $('.product-text-inputs input[name=code]').val(),
          description: $('textarea[name=html_content]').val(),
          meta_title: $('#add-product-wrapper input[name=meta_title]').val(),
          meta_keywords: $('#add-product-wrapper input[name=meta_keywords]').val(),
          meta_desc: $('#add-product-wrapper textarea[name=meta_desc]').val(),
          userProperty: userProperty.getUserFields(),
        };
        
        admin.ajaxRequest({
          mguniqueurl:"action/generateSeoFromTmpl",
          type: 'product',
          data: data
        }, function(response) {
          $.each(response.data, function(key, value) {
            if (value) {
              if (key == 'meta_desc') {
                $('#add-product-wrapper textarea[name='+key+']').val(value);
              } else {
                $('#add-product-wrapper input[name='+key+']').val(value);
              }
            }
          });
          
          $('#add-product-wrapper textarea[name=meta_desc]').trigger('blur');
          if(who != 'userClick') {
            admin.indication(response.status, response.msg);
          }
        });
     },
    startGenerationImage: function(nextItem, total_count, imgCount) {
      admin.ajaxRequest({
        mguniqueurl:"action/startGenerationImagePreview",
        nextItem: nextItem,
        total_count: total_count,
        imgCount: imgCount
      },
      function(response) {
        admin.indication(response.status, response.msg);

        if(response.data.percent<100) {
          $('.message-importing').html('Идет процесс генерации миниатюр. Обработано:'
                  + response.data.percent
                  + '%<div class="progress-bar"><div class="progress-bar-inner" style="width:'
                  + response.data.percent + '%;"></div></div>');
          catalog.startGenerationImage(response.data.nextItem, response.data.total_count, response.data.imgCount);
        } else {
          $('.message-importing').html('Идет процесс генерации миниатюр. Обработано:'
                  + response.data.percent
                  + '%<div class="progress-bar"><div class="progress-bar-inner" style="width:'
                  + '100%;"></div></div>');
//          admin.refreshPanel();
        }
        $('.log').text($('.log').text()+response.data.log);
        $('.log').text($('.log').text()+response.msg);
      });
    },
    /**
     * Загружает Архив с изображениями на сервер для последующего импорта
     */
    uploadImagesArchive: function() {
      $('.section-comerceml input[name="upload"]').hide();
      // $('.mailLoader').before('<div class="view-action" style="margin-top:-2px;">' + lang.LOADING + '</div>');
      // отправка архива с изображениями на сервер
      // comerceMlModule.printLog('Идет передача файла на сервер. Подождите, пожалуйста...');    
      $('.upload-goods-image-form').ajaxForm({
        type: "PUT",
        url: "ajax",
        cache: false,
        dataType: 'json',
        data: {
          mguniqueurl: "action/uploadImagesArchive",
        },
        error: function(q,w,r) {
         // comerceMlModule.printLog("Ошибка: Загружаемый вами файл превысил максимальный объем и не может быть передан на сервер из-за ограничения в настройках файла php.ini");
          admin.indication('error', "Ошибка: Загружаемый вами файл превысил максимальный объем и не может быть передан на сервер из-за ограничения в настройках файла php.ini");
          $('.section-comerceml input[name="upload"]').show();
          $('.view-action').remove();
        },
        success: function(response) {
          admin.indication(response.status, response.msg);
          if (response.status == 'success') {
            $('.upload-images').hide();
            $('.start-generate').show();
          } else {
            $('.import-container input[name="upload"]').val('');
          }
          $('.view-action').remove();
        },
      }).submit();
    },
    /**
    * функция для приема файла из аплоадера
    */
    getFile: function(file) {
      $('.section-comerceml .b-modal input[name="src"]').val(file.url);
      $.ajax({
        type: "POST",
        url: "ajax",
        data: {
          mguniqueurl: "action/selectImagesArchive",
          data: {
          filename: file.url,
          }
        },
        dataType: 'json',
        success: function(response) {
          admin.indication(response.status, response.msg);
          if (response.status == 'success') {
            $('.upload-images').hide();
            $('.start-generate').show();
          }
        }
      });
    },
    /*
     * Открывает модальное окно для установки соответствия полей импорта
     * @param string scheme
     * @returns void
     */
    showSchemeSettings: function(scheme) {
      $('.columnComplianceModal .widget-table-body ul').empty();
      var importType = $('.section-catalog select[name="importType"]').val();
      admin.ajaxRequest({
        mguniqueurl: "action/getCsvCompliance", // действия для выполнения на сервере
        scheme: scheme,
        importType: importType
      },
      catalog.fillCsvCopliance(importType));
      $('.columnComplianceModal button.save-button').attr('importType', importType);
      admin.openModal($('.columnComplianceModal'));
    },
    /*
     * Заполнение модального окна выбора соответствия полей данными
     * @returns {Function}
     */
    fillCsvCopliance: function(importType) {
      return function(response) {
        var titleList = '';
        var compList = '';
        var fieldContinue = -1;

        if(importType === 'MogutaCMS') {
          fieldContinue = 2;

          if($(".block-upload-сsv select[name=identifyType]").val() == 'article') {
            fieldContinue = 8;
          }
        }

        $('.columnComplianceModal .widget-table-body .complianceHeaders tbody').html('');

        response.data.titleList.forEach(function(item, i, arr) {
          titleList += '<option value="'+i+'">'+item+'</option>';
        });

        response.data.maskArray.forEach(function(item, i, arr) {
          var notUpdate = '';
          var disabled = '';

          if(i == fieldContinue) {
            disabled = 'disabled="disabled"';
          } else if(response.data.notUpdate[i] == 1) {
            notUpdate = 'checked="checked"';
          }

          compList = '\
            <tr><td>'+item+'</td>\
              <td><select name="colIndex'+i+'" style="margin:0;">\
                '+titleList+'\
              </select></td>\
              <td>\
                <div class="checkbox">\
                  <input type="checkbox" '+notUpdate+' id="notUpdate-'+i+'" class="notUpdate" name="notUpdate'+i+'" value="">\
                  <label for="notUpdate-'+i+'"></label>\
                </div>\
            </tr>';
          $('.columnComplianceModal .widget-table-body .complianceHeaders tbody').append(compList);
          $('.columnComplianceModal .widget-table-body .complianceHeaders tbody select[name=colIndex'+i+'] option[value='+response.data.compliance[i]+']').attr('selected', 'selected');
        });
      }
    },

    exportToCsv: function(page, rowCount) {
      if(!page) {
        page = 1;
      }
      if(!rowCount) {
        rowCount = 0;
      }
      loader = $('.mailLoader');
      $.ajax({
        type: "POST",
        url: mgBaseDir + "/mgadmin",
        data: {
          csv: 1,
          page: page,
          rowCount: rowCount
        },
        dataType: "json",
        cache: false,
        beforeSend: function() {
          // флаг, говорит о том что начался процесс загрузки с сервера
          admin.WAIT_PROCESS = true;
          loader.hide();
          loader.before('<div class="view-action" style="display:none; margin-top:-2px;">' + lang.LOADING + '</div>');
          // через 300 msec отобразится лоадер.
          // Задержка нужна для того чтобы не мерцать лоадером на быстрых серверах.

          setTimeout(function () {
            if (admin.WAIT_PROCESS) {
              admin.waiting(true);
            }
          }, admin.WAIT_DELAY);
        },
        success: function(response) {
          admin.WAIT_PROCESS = false;
          admin.waiting(false);
          loader.show();
          $('.view-action').remove();

          if(!response.success) {
            admin.indication('success', lang.INDICATION_INFO_EXPORTED+' '+response.percent+'%');
            catalog.exportToCsv(response.nextPage, response.rowCount);
          } else {
            admin.indication('success', lang.INDICATION_INFO_EXPORTED+' 100%');
            setTimeout(function() {
              if (confirm('Файл с выгрузкой создан в корне сайта под именем: '+response.file+' Желаете скачать сейчас?')) {
                location.href = mgBaseDir+'/'+response.file;
              }
            }, 1000);
//            $('body').append('<iframe src="'+mgBaseDir+'/'+response.file+'" style="display: none;"></iframe>');
          }
        }
      });
    },

    /**
     * Открывает модальное окно.
     * type - тип окна, либо для создания нового товара, либо для редактирования старого.
     */
    openModalWindow: function(type, id) {

      try{
        if(CKEDITOR.instances['html_content']) {
          CKEDITOR.instances['html_content'].destroy();
        }
        if(CKEDITOR.instances['html_content-textarea']) {
          CKEDITOR.instances['html_content-textarea'].destroy();
        }
      } catch(e) { }

      switch (type) {
        case 'edit':{

          catalog.clearFields();
          $('.html-content-edit').show();
          $('.product-desc-wrapper #html-content-wrapper').hide();
          $('.add-product-table-icon').text('Редактирование товара');
          catalog.editProduct(id);

          break;
        }
        case 'add':{
          $('.add-product-table-icon').text('Добавление нового товара');
          catalog.clearFields();
          $('textarea[name=html_content]').ckeditor();

          $('.related-block').html('');
          $('.related-block').hide();

          // получаем с сервера все доступные пользовательские параметры
          admin.ajaxRequest({
              mguniqueurl:"action/getUserProperty"
            },
            function(response) {
            // выводим поля для редактирования пользовательских характеристик
              userProperty.createUserFields(null,response.data.allProperty);
            },
            $('.error-input').removeClass('error-input')
          );

          catalog.msgRelated();
          var src=admin.SITE+'/mg-admin/design/images/no-img.png';
          var row = catalog.drawControlImage(src, false,'','','');
          $('.main-image').html(row);
          // $('.main-img-prod .main-image').hide();

          var catId = $('.filter-container select[name=cat_id]').val();
          if(catId == 'null') {
            catId = 0;
          }
          // получаем набор общих характеристик и выводим их
          catalog.generateUserProreprty(0, catId);

          break;
        }
        default:{
          catalog.clearFields();
          break;
        }
      }

      // Вызов модального окна.
      admin.openModal('.product-desc-wrapper');

    },

    
    /**
     *  Изменяет список пользовательских свойств для выбранной категории в редактировании товара
     */
     generateUserProreprty: function(produtcId,categoryId) {
       admin.ajaxRequest({
          mguniqueurl:"action/getProdDataWithCat",
          produtcId: produtcId,
          categoryId: categoryId
        },
        function(response) {
          userProperty.createUserFields($('.userField'), response.data.thisUserFields, response.data.allProperty);
          admin.initToolTip();
        },
        $('.userField')
       );
     },


    /**
     *  Проверка заполненности полей, для каждого поля прописывается свое правило.
     */
    checkRulesForm: function() {
      $('.errorField').css('display','none');
      $('.product-text-inputs input').removeClass('error-input');
      var error = false;

      // наименование не должно иметь специальных символов.
      if(!$('.product-text-inputs input[name=title]').val()) {
        $('.product-text-inputs input[name=title]').parent("label").find('.errorField').css('display','block');
        $('.product-text-inputs input[name=title]').addClass('error-input');
        error = true;
        $(".b-modal").animate({ scrollTop: 0 }, 300);
      }

      // наименование не должно иметь специальных символов.
      if(!admin.regTest(2, $('.product-text-inputs input[name=url]').val()) || !$('.product-text-inputs input[name=url]').val()) {
        $('.product-text-inputs input[name=url]').parent("label").find('.errorField').css('display','block');
        $('.product-text-inputs input[name=url]').addClass('error-input');
        error = true;
      }

      // артикул обязательно надо заполнить.
      if(!$('.product-text-inputs input[name=code]').val()) {
        $('.product-text-inputs input[name=code]').parent("label").find('.errorField').css('display','block');
        $('.product-text-inputs input[name=code]').addClass('error-input');
        error = true;
      }

      // Проверка поля для стоимости, является ли текст в него введенный числом.
      if(isNaN(parseFloat($('.product-text-inputs input[name=price]').val()))) {
        $('.product-text-inputs input[name=price]').parent("label").find('.errorField').css('display','block');
        $('.product-text-inputs input[name=price]').addClass('error-input');
        error = true;
      }
      
      var url = $('.product-text-inputs input[name=url]').val();
      var reg = new RegExp('([^/-a-z\.\d])','i');
      
      if (reg.test(url)) {
        $('.product-text-inputs input[name=url]').parent("label").find('.errorField').css('display','block');
        $('.product-text-inputs input[name=url]').addClass('error-input');
        $('.product-text-inputs input[name=url]').val('');
        error = true;
      }

      // Проверка поля для старой стоимости, является ли текст в него введенный числом.
      $('.product-text-inputs input[name=old_price]').each(function() {
        var val = $(this).val();
        if(isNaN(parseFloat(val))&&val!="") {
          $(this).parent("label").find('.errorField').css('display','block');
          $(this).addClass('error-input');
          error = true;
        }
      });

      // Проверка поля количество, является ли текст в него введенный числом.
      $('.product-text-inputs input[name=count]').each(function() {
        var val = $(this).val();
        if(val=='\u221E'||val==''||parseFloat(val)<0) {val = "-1"; $(this).val('∞'); }
        if(isNaN(parseFloat(val))) {
          $(this).parent("label").find('.errorField').css('display','block');
          $(this).addClass('error-input');
          error = true;
        }
      });
      if(error == true) {
        return false;
      }

      return true;
    },


    /**
     * Сохранение изменений в модальном окне продукта.
     * Используется и для сохранения редактированных данных и для сохранения нового продукта.
     * id - идентификатор продукта, может отсутствовать если производится добавление нового товара.
     */
    saveProduct: function(id) {
      // Если поля неверно заполнены, то не отправляем запрос на сервер.
      if(!catalog.checkRulesForm()) {
        return false;
      }

      var recommend = $('.save-button').data('recommend');
      var activity =  $('.save-button').data('activity');
      var newprod =  $('.save-button').data('new');
      //определяем имеются ли варианты товара
      var variants=catalog.getVariant();

      if(catalog.errorVariantField) {
        admin.indication('error', lang.ERROR_VARIANT);
        return false;
      }

      if($('textarea[name=html_content]').val()=='') {
        if(!confirm(lang.ACCEPT_EMPTY_DESC+'?')) {
          return false;
        }
      }
      if ($('.addedProperty .new-added-prop').length > 0) {
        catalog.saveAddedProperties();
      }
      
      if(!variants) {

        // Пакет характеристик товара.
        var packedProperty = {
          mguniqueurl:"action/saveProduct",
          id: id,
          title: $('#add-product-wrapper .product-text-inputs input[name=title]').val(),
          link_electro: $('#add-product-wrapper .product-text-inputs input[name=link_electro]').val(),
          url: $('#add-product-wrapper .product-text-inputs input[name=url]').val(),
          code: $('#add-product-wrapper .product-text-inputs input[name=code]').val(),
          price: $('#add-product-wrapper .product-text-inputs input[name=price]').val(),
          old_price: $('#add-product-wrapper .product-text-inputs input[name=old_price]').val(),
          image_url: catalog.createFieldImgUrl(),
          image_title: catalog.createFieldImgTitle(),
          image_alt: catalog.createFieldImgAlt(),
          delete_image: catalog.deleteImage,
          count: $('#add-product-wrapper .product-text-inputs input[name=count]').val(),
          weight: $('#add-product-wrapper .product-text-inputs input[name=weight]').val(),
          cat_id: $('#add-product-wrapper .product-text-inputs select[name=cat_id]').val(),
          inside_cat: catalog.createInsideCat(),
          description: $('textarea[name=html_content]').val(),
          meta_title: $('#add-product-wrapper input[name=meta_title]').val(),
          meta_keywords: $('#add-product-wrapper input[name=meta_keywords]').val(),
          meta_desc: $('#add-product-wrapper textarea[name=meta_desc]').val(),
          currency_iso: $('#add-product-wrapper select[name=currency_iso]').val(),
          recommend: recommend,
          activity: activity,
          new:newprod,
          userProperty: userProperty.getUserFields(),
          variants:null,
          related: catalog.getRelatedProducts(),
          yml_sales_notes: $('.yml-wrapper input[name=yml_sales_notes]').val(),
          related_cat: catalog.getRelatedCategory(),
        }
      } else {

        var packedProperty = {
          mguniqueurl:"action/saveProduct",
          id: id,
          title: $('#add-product-wrapper .product-text-inputs input[name=title]').val(),
          link_electro: $('#add-product-wrapper .product-text-inputs input[name=link_electro]').val(),
          code: $('#add-product-wrapper .variant-table tr').eq(1).find('input[name=code]').val(),
          price: $('#add-product-wrapper .variant-table tr').eq(1).find('input[name=price]').val(),
          old_price: $('#add-product-wrapper .variant-table tr').eq(1).find('input[name=old_price]').val(),
          count: $('#add-product-wrapper .variant-table tr').eq(1).find('input[name=count]').val(),
          weight: $('#add-product-wrapper .variant-table tr').eq(1).find('input[name=weight]').val(),
          url: $('#add-product-wrapper .product-text-inputs input[name=url]').val(),
          image_url: catalog.createFieldImgUrl(),
          image_title: catalog.createFieldImgTitle(),
          image_alt: catalog.createFieldImgAlt(),
          delete_image: catalog.deleteImage,
          cat_id: $('#add-product-wrapper .product-text-inputs select[name=cat_id]').val(),
          inside_cat: catalog.createInsideCat(),
          description: $('#add-product-wrapper textarea[name=html_content]').val(),
          meta_title: $('#add-product-wrapper input[name=meta_title]').val(),
          meta_keywords: $('#add-product-wrapper input[name=meta_keywords]').val(),
          meta_desc: $('#add-product-wrapper textarea[name=meta_desc]').val(),
          currency_iso: $('#add-product-wrapper select[name=currency_iso]').val(),
          recommend: recommend,
          activity: activity,
          new:newprod,
          userProperty: userProperty.getUserFields(),
          variants:variants,
          related: catalog.getRelatedProducts(),
          yml_sales_notes: $('.yml-wrapper input[name=yml_sales_notes]').val(),
          related_cat: catalog.getRelatedCategory(),
        }

      }

      catalog.deleteImage = '';

      // отправка данных на сервер для сохранения
      admin.ajaxRequest(packedProperty,
        function(response) {
          admin.clearGetParam();
          admin.indication(response.status, response.msg);

          var row = catalog.drawRowProduct(response.data);

          // Вычисляем, по наличию характеристики 'id',
          // какая операция производится с продуктом, добавление или изменение.
          // Если id есть значит надо обновить запись в таблице.
          if(packedProperty.id) {
            $('.product-tbody tr[id='+packedProperty.id+']').replaceWith(row);
          } else {
            // Если id небыло значит добавляем новую строку в начало таблицы.
            if($('.product-tbody tr:first').length>0) {
              $('.product-tbody tr:first').before(row);
            } else{
              $('.product-tbody ').append(row);
            }

            var newCount = $('.widget-table-title .produc-count strong').text()-0+1;
            if(response.status=='success') {
              $('.widget-table-title .produc-count strong').text(newCount);
            }

            $('.product-count strong').html(+$('.product-count strong').html() + 1);
          }

           $('.no-results').remove();

          // Закрываем окно
          admin.closeModal('#add-product-wrapper');
          admin.initToolTip();
        }
      );
    },

    cloneProd: function(id, prod) {
     // получаем с сервера все доступные пользовательские параметры
      admin.ajaxRequest({
         mguniqueurl:"action/cloneProduct",
         id:id
       },
       function(response) {
        admin.indication(response.status, response.msg);
        var row = catalog.drawRowProduct(response.data);

        // Если id небыло значит добавляем новую строку в начало таблицы.
        if($('.product-tbody tr:first').length>0){
          $('.product-tbody tr:first').before(row);
        } else{
          $('.product-tbody ').append(row);
        }

        for(i = 0; i < prod.find('.view-price').length; i++) {
          $('tr#'+response.data.id+' .view-price:eq('+i+')').html(prod.find('.view-price:eq('+i+')').html());
        }

        var newCount = $('.widget-table-title .produc-count strong').text()-0+1;
        if(response.status=='success') {
          $('.widget-table-title .produc-count strong').text(newCount);
        }

        $('.product-count strong').html(+$('.product-count strong').html() + 1);
      });
    },

    /**
     * изменяет строки в таблице товаров при редактировании изменении.
     */
    drawRowProduct: function(element) {
        if(!element.real_price) {
          element.real_price = element.price;
        }
      // получаем название категории из списка в форме, чтобы внести в строку в таблице
          var cat_name = $('.product-text-inputs select[name=cat_id] option[value='+element.cat_id+']').text();
          if (cat_name.indexOf(' -- ') != -1) {
            cat_name = cat_name.replace(/ -- /g, '');
            cat_name = '<a class="parentCat " title="" style="cursor:pointer;">../</a>' + cat_name;
          }
          // получаем URL имеющейся картинки товара, если она была
          var src=$('tr[id='+element.id+'] .image_url .uploads').attr('src');

          if(element.image_url) {
            // если идет процесс обновления и картинка новая то обновляем путь к ней
            src=element.image_url;
          }else {
            src=admin.SITE+'/mg-admin/design/images/no-img.png'
          }

          if(element.image_url=='no-img.png') {
            src=admin.SITE+'/mg-admin/design/images/no-img.png'
          }

          // переменная для хранения класса для подсветки активности товара
          var classForTagActivity='activity-product-true';

          var recommend = element.recommend==='1'?'active':'';
          var titleRecommend = element.recommend?lang.PRINT_IN_RECOMEND:lang.PRINT_NOT_IN_RECOMEND;

          var $new = element.new==='1'?'active':'';
          var titleNew = element.new?lang.PRINT_IN_NEW:lang.PRINT_NOT_IN_NEW;

          var activity = element.activity==='1'?'active':'';
          var titleActivity = element.activity?lang.ACT_V_CAT:lang.ACT_UNV_CAT;

          var printPrice = false;

          // построение  ячейки с ценами
          var tdPrice ='<td  class="price">';
          tdPrice += '<div class="row"><table class="variant-row-table">';
          if(element.price_course && !element.variants) {
           if(element.price_course!=element.real_price) {
           printPrice = true;
            tdPrice +='<tr><td colspan="3" class="text-right" style="font-weight:bold;">';
            tdPrice +='<span class="view-price " style="color: '+((parseFloat(element.price_course)>parseFloat(element.real_price))?"#1C9221":"#B42020")+'" title="с учетом скидки/наценки">'+admin.numberFormat(element.price_course)+' '+admin.CURRENCY+'</span><div class="clear"></div>';
            tdPrice += '</td>';
            tdPrice += '</tr>';
           }
          }
          if(element.variants) {
            element.variants.forEach(function (variant, index, array) {
              if(variant.price_course) {
                if(variant.price_course!=variant.real_price) {
                  if (variant.price != variant.price_course) {
                    printPrice = true;
                    tdPrice += '<tr><td colspan="3" class="text-right" style="font-weight:bold;">';
                    tdPrice += '<span class="view-price " style="color: '+((parseFloat(variant.price_course)>parseFloat(variant.price))?"#1C9221":"#B42020")+'" title="с учетом скидки/наценки">'+admin.numberFormat(variant.price_course)+' '+admin.CURRENCY+'</span><div class="clear"></div>';
                    tdPrice += '</td>';
                    tdPrice += '</tr>';
                  } else {}
                }
              }
              if(index > 2) {
                hide = 'second-block-varians';
                hideCss = 'display:none;';
              } else {
                hide = '';
                hideCss = '';
              }
			        tdPrice +='<tr class="variant-price-row '+hide+'" style="'+hideCss+'"><td>';
              tdPrice +='<span class="price-help">'+(element.codeshow  ? '['+variant.code+'] ': '')+variant.title_variant+'</span></td><td><input class="variant-price fastsave small" type="text" value="'+variant.price+'" data-packet="{variant:1,id:'+variant.id+',field:\'price\'}"/></td><td>'+ catalog.getShortIso(element.currency_iso) +'<div class="clear"></div></td></tr>';
            });
            showBtn = true;
          } else {
            showBtn = false;
		        tdPrice +='<tr class="variant-price-row"><td>';
            tdPrice += '</td><td><input type="text" value="'+element.real_price+'" class="fastsave small variant-price" data-packet="{variant:0,id:'+element.id+',field:\'price\'}"/></td><td> '+catalog.getShortIso(element.currency_iso)+'<div class="clear"></div></td></tr>';
          }

          tdPrice += '</table></div>';
          if(showBtn) tdPrice += '<div class="text-right"><a href="javascript:void(0)" class="link showAllVariants">Показать все</a></div>';
          tdPrice += '</td>';


         // построение  ячейки с остатками вариантов товара
          var tdCount ='<td class="count" style="padding-top:3px;">';
          var margin = '';
          if(printPrice) {
            margin = 23;
          } else {
            margin = 2;
          }
           if(element.variants) {
             element.variants.forEach(function (variant, index, array) {
                if(index > 2) {
                  hide = 'second-block-varians';
                  hideCss = 'display:none;';
                } else {
                  hide = '';
                  hideCss = '';
                }
               if(variant.count<0) {variant.count='∞'}
              tdCount +='<div style="margin: '+margin+'px 0 4px 0;'+hideCss+'" class="count '+hide+'"><input class="variant-count fastsave tiny" type="text" value="'+variant.count+'" data-packet="{variant:1,id:'+variant.id+',field:\'count\'}"/> '+lang.UNIT+'</div>';
             }
           );
          } else {
            if(element.count<0) {element.count='∞'}
            tdCount += '<div style="margin: '+margin+'px 0 2px 0;" class="count"><input type="text" value="'+element.count+'" class="fastsave tiny" data-packet="{variant:0,id:'+element.id+',field:\'count\'}"/> '+lang.UNIT+'</div>';
          }
          tdCount += '</td>'
          var tdSort = '';
          if (element.sortshow) {
            tdSort = '<td class="sort"><input type="text" value="'+element.sort+'" class="fastsave tiny"  data-packet="{variant:0,id:'+element.id+',field:\'sort\'}"/></td>';
          }
          var link = element.link ? element.link : mgBaseDir+'/'+(element.category_url ? element.category_url : "catalog")+'/'+element.product_url;
          // html верстка для  записи в таблице раздела
          var row='\
            <tr id="'+element.id+'" data-id="'+element.id+'" class="product-row">\
              <td class="check-align">\
                <div class="checkbox">\
                  <input type="checkbox" id="prod-'+element.id+'" name="product-check">\
                  <label for="prod-'+element.id+'"></label>\
                </div>\
              </td>\
              <td class="id">'+element.id+'</td>\
              <td class="mover"><i class="fa fa-arrows"></i></td>\
              <td id="'+element.cat_id+'" class="cat_id">'+cat_name+'</td>\
              <td class="product-picture image_url">\
                <img class="uploads" src="'+src+'"/>\
              </td>\
              <td class="name"><span class="product-name"><a class="name-link tip edit-row" id="'+element.id+'" href="javascript:void(0);">'+(element.codeshow &&!element.variants  ? '['+element.code+'] ' : '')+element.title+'</a><a class="fa fa-external-link tip" title="'+lang.PRODUCT_VIEW_SITE+'" href="'+link+'"  target="_blank"></a></span></td>\
              '+tdPrice+'\
              '+tdCount+'\
              '+tdSort+'\
              <td class="actions">\
                <ul class="action-list fl-right">\
                  <li class="edit-row" id="'+element.id+'"><a href="javascript:void(0);" class="fa fa-pencil" title="'+lang.EDIT+'"></a></li>\
                  <li class=" new" data-id="'+element.id+'" title="'+titleNew+'" ><a href="javascript:void(0);" class="fa fa-tag '+$new+'"></a></li>\
                  <li class=" recommend" data-id="'+element.id+'" title="'+titleRecommend+'" ><a href="javascript:void(0);" class="fa fa-star '+recommend+'"></a></li>\
                  <li class="clone-row" id="'+element.id+'"><a href="javascript:void(0);" class="fa fa-files-o" title="'+lang.CLONE+'"></a></li>\
                  <li class="visible " data-id="'+element.id+'" title="'+titleActivity+'"><a href="javascript:void(0);" class="fa fa-lightbulb-o '+activity+'"></a></li>\
                  <li class="delete-order" id="'+element.id+'"><a href="javascript:void(0);" class="fa fa-trash" title="'+lang.DELETE+'"></a></li>\
                </ul>\
              </td>\
           </tr>';

        return row;
    },

    /**
     * Получает данные о продукте с сервера и заполняет ими поля в окне.
     */
    editProduct: function(id) {
      admin.ajaxRequest({
        mguniqueurl:"action/getProductData",
        id: id
      },
      catalog.fillFields(),
      $('#add-product-wrapper .add-img-form')
      );
    },

    /**
     * Удаляет продукт из БД сайта и таблицы в текущем разделе
     */
    deleteProduct: function(id,imgFile,massDel,obj) {
      var confirmed = false;
      if(!massDel) {
        if(confirm(lang.DELETE+'?')) {
          confirmed = true;
        }
      } else {
        confirmed = true;
      }
      if(confirmed) {
        admin.ajaxRequest({
          mguniqueurl:"action/deleteProduct",
          id: id,
          imgFile: imgFile,
          msgImg: true
        },
        function(response) {
          if(!massDel) {admin.indication(response.status, response.msg);}
          $(obj).parents('tr').detach();
          $('.product-count strong').html($('.product-count strong').html() - 1);
          }
        );
      }

    },


    /**
     * Выполняет выбранную операцию со всеми отмеченными товарами
     * operation - тип операции.
     */
    runOperation: function(operation) {

      var products_id = [];
      $('.product-tbody tr').each(function() {
        if($(this).find('input[name=product-check]').prop('checked')) {
          products_id.push($(this).attr('id'));
        }
      });

      //Объект для передачи дополнительных данных, необходимых при выполнения действия
      var data = {};

      if($('select#moveToCategorySelect').is(':visible')) {
        data.category_id = $('select#moveToCategorySelect').val();
      }

      var notice = (operation.indexOf('changecur') != -1) ? lang.RUN_NOTICE : '';


      if (confirm(lang.RUN_CONFIRM + notice)) {
        admin.ajaxRequest({
          mguniqueurl: "action/operationProduct",
          operation: operation,
          products_id: products_id,
          data: data
        },
        function(response) {
          if(response.data.clearfilter) {
            admin.show("catalog.php","adminpage","refreshFilter=1",admin.sliderPrice);
          } else {
           if(response.data.filecsv) {
            admin.indication(response.status, response.msg);
            setTimeout(function() {
              if (confirm('Файл с выгрузкой создан в корне сайта под именем: '+response.data.filecsv+' Желаете скачать сейчас?')) {
              location.href = mgBaseDir+'/'+response.data.filecsv;
            }}, 2000);
           }
           if(response.data.fileyml) {
            admin.indication(response.status, response.msg);
            setTimeout(function() {
              if (confirm('Файл с выгрузкой создан в корне сайта под именем: '+response.data.fileyml+' Желаете скачать сейчас?')) {
              location.href = mgBaseDir+'/mg-admin?yml=1&filename='+response.data.fileyml;
            }}, 2000);
           }
           admin.refreshPanel();
         }
        }
        );
      }


    },

    /**
    * Формирует HTML для добавления и удаления картинки
    */
    drawControlImage:function(url,main,filename,title,alt) {
      var mainclass="main-img-prod";
      if(main==true) {
        mainclass='small-img';
      }

      if(!main) {
        return '<div class="img-holder" data-filename="'+filename+'">\
                  <a class="icon tip seo-image" href="javascript:void(0);" title="SEO настройка"><i class="fa fa-cogs" aria-hidden="true"></i></a>\
                    <div class="popup-holder" >\
                      <div class="custom-popup right" style="display:none;">\
                        <div class="row">\
                          <div class="large-12 columns">\
                            <label>title:</label>\
                          </div>\
                        </div>\
                        <div class="row">\
                          <div class="large-12 columns">\
                            <input type="text" name="image_title" value="'+title+'">\
                          </div>\
                        </div>\
                        <div class="row">\
                          <div class="large-12 columns">\
                            <label>alt:</label>\
                          </div>\
                        </div>\
                        <div class="row">\
                          <div class="large-12 columns">\
                            <input type="text" name="image_alt" value="'+alt+'">\
                          </div>\
                        </div>\
                        <div class="row">\
                          <div class="large-12 columns">\
                            <a class="button fl-left seo-image-block-close" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> Отменить</a>\
                            <a class="button success fl-right apply-seo-image" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Применить</a>\
                          </div>\
                        </div>\
                      </div>\
                    </div>\
                    <img src="'+url+'" alt="'+filename+'">\
                  </div>\
                  <div class="img-actions clearfix">\
                    <div class="upload-form fl-left">\
                      <form class="imageform" method="post" noengine="true" enctype="multipart/form-data">\
                        <label class="button tip" title="Загрузить изображение">\
                          <i class="fa fa-picture-o" aria-hidden="true"></i> Загрузить\
                          <input type="file" name="photoimg" title="'+lang['UPLOAD_IMG']+'">\
                        </label>\
                      </form>\
                    </div>\
                    <a class="button alert tip fl-right cancel-img-upload" href="javascript:void(0);" title="Удалить изображение">\
                    <i class="fa fa-trash" aria-hidden="true"></i> Удалить</a>\
                  </div>';
      } else {
        return '<div class="image-item parent" data-filename="'+filename+'">\
                  <div class="custom-popup" style="display:none;">\
                    <div class="row">\
                      <div class="large-12 columns">\
                        <label>title:</label>\
                      </div>\
                    </div>\
                    <div class="row">\
                      <div class="large-12 columns">\
                        <input type="text" name="image_title" value="'+title+'">\
                      </div>\
                    </div>\
                    <div class="row">\
                      <div class="large-12 columns">\
                        <label>alt:</label>\
                      </div>\
                    </div>\
                    <div class="row">\
                      <div class="large-12 columns">\
                        <input type="text" name="image_alt" value="'+alt+'">\
                      </div>\
                    </div>\
                    <div class="row">\
                      <div class="large-12 columns">\
                        <a class="button fl-left seo-image-block-close" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> Отменить</a>\
                        <a class="button success fl-right apply-seo-image" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> Применить</a>\
                      </div>\
                    </div>\
                  </div>\
                  <div class="img-holder">\
                    <img src="'+url+'" alt="'+filename+'">\
                  </div>\
                  <div class="img-actions clearfix">\
                    <div class="upload-form fl-left">\
                    </div>\
                  </div>\
                  <div class="img-action-hover">\
                    <div class="elem">\
                      <a class="fa fa-check top set-main-image btn" href="javascript:void(0);" title="По умолчанию" aria-hidden="true"></a>\
                    </div>\
                    <div class="elem">\
                      <a class="fa fa-cogs top seo-image btn" aria-hidden="true btn" href="javascript:void(0);" title="SEO настройка"></a>\
                    </div>\
                    <div class="elem">\
                      <form class="imageform" method="post" noengine="true" enctype="multipart/form-data">\
                        <label class="btn tip icon" title="Загрузить изображение">\
                          <i class="fa fa-picture-o" aria-hidden="true"></i>\
                          <input type="file" name="photoimg" style="display:none;" title="'+lang['UPLOAD_IMG']+'">\
                        </label>\
                      </form>\
                    </div>\
                    <div class="elem">\
                      <a class="btn tip icon fl-right cancel-img-upload" href="javascript:void(0);" title="Удалить изображение">\
                        <i class="fa fa-trash" aria-hidden="true"></i>\
                      </a>\
                    </div>\
                  </div>\
                </div>\
                ';
      }
    },

   /**
    * Заполняет поля модального окна данными
    */
    fillFields:function() {

      return function(response) {
        var imageDir = Math.floor(response.data.id/100)+'00/'+response.data.id+'/';

        catalog.supportCkeditor = response.data.description;
        $('.product-desc-wrapper textarea[name=html_content]').text(response.data.description);
        $('.product-text-inputs input').removeClass('error-input');
        $('.product-text-inputs input[name=title]').val(response.data.title);
        $('.product-text-inputs input[name=link_electro]').val(response.data.link_electro);
        
        if(response.data.link_electro) {
          $('.section-catalog .del-link-electro').text(response.data.link_electro.substr(0,50));
        }
        
        $('.section-catalog .del-link-electro').attr('title',response.data.link_electro);
        if(response.data.link_electro) {
          $('.section-catalog .del-link-electro').show();
          $('.section-catalog .add-link-electro').hide();
        }
        $('.product-text-inputs select[name=cat_id]').val(response.data.cat_id);
        $('.product-text-inputs input[name=url]').val(response.data.url);

        catalog.selectCategoryInside(response.data.inside_cat);
        catalog.cteateTableVariant(response.data.variants, imageDir);

        if(!response.data.variants) {
          $('.product-text-inputs input[name=code]').val(response.data.code);
          $('.product-text-inputs input[name=price]').val(response.data.price);
          $('.product-text-inputs input[name=old_price]').val(response.data.old_price);
          $('.product-text-inputs input[name=weight]').val(response.data.weight);
          //превращаем минусовое значение в знак бесконечности
          var val = response.data.count;
          if((val=='\u221E'||val==''||parseFloat(val)<0)) {val = '∞';}
          $('.product-text-inputs input[name=count]').val(val);
        }

        var rowMain = '';
        var rows = '';

        response.data.images_product.forEach(
          function (element, index, array) {
            var title=response.data.images_title[index]?response.data.images_title[index]:'';
            var alt=response.data.images_alt[index]?response.data.images_alt[index]:'';

            var src=admin.SITE+'/mg-admin/design/images/no-img.png';
            if(element) {
              var src=element;
            }

            if(index!=0) {
              rows += catalog.drawControlImage(src, true, element, title, alt);
            } else {
              rowMain = catalog.drawControlImage(src, false, element, title, alt);
            }

          }
        );

        $('.main-image').html(rowMain);
        $('.sub-images').html(rows);
        $('.main-img-prod .main-image').hide();
        $('textarea[name=html_content]').val(response.data.description);
        $('#add-product-wrapper input[name=meta_title]').val(response.data.meta_title);
        $('#add-product-wrapper input[name=meta_keywords]').val(response.data.meta_keywords);
        $('#add-product-wrapper textarea[name=meta_desc]').val(response.data.meta_desc);
        $('.yml-wrapper input[name=yml_sales_notes]').val(response.data.yml_sales_notes);
        catalog.drawRelatedProduct(response.data.relatedArr);
        catalog.addrelatedCategory(response.data.relatedCat);
        $('.save-button').attr('id',response.data.id);
        $('.save-button').data('recommend',response.data.recommend);
        $('.save-button').data('activity',response.data.activity);
        $('.save-button').data('new',response.data.new);
        $('.b-modal_close').attr('item-id', response.data.id);
        $('.cancel-img-upload').attr('id',response.data.id);
        $('.userField').html('');

        try{
          $('.symbol-count').text($('#add-product-wrapper textarea[name=meta_desc]').val().length);
        }catch(e) {
          $('.symbol-count').text('0');
        }

        userProperty.createUserFields($('.userField'), response.data.prodData.thisUserFields, response.data.prodData.allProperty);
        $('.userField tr td .value').each(function() {
          var value = $(this).text();
          if (value) {
            $(this).text(admin.htmlspecialchars(value));
          }
        });

        var iso = response.data.currency_iso?response.data.currency_iso:admin.CURRENCY_ISO;
        $('#add-product-wrapper .btn-selected-currency').text(catalog.getShortIso(iso));

        $('#add-product-wrapper select[name=currency_iso] option[value='+JSON.stringify(iso)+']').prop('selected','selected');
      
        // Проверка на наличии поля в возвращаемом результате, для вывода предупреждения,
        // если этот товар является комплектом товаров, созданным в плагине "Комплект товаров"
        if (response.data.plugin_message) {
          $('#add-product-wrapper .add-product-table-icon').append(response.data.plugin_message);
        }
        //$('textarea[name=html_content]').ckeditor(function() {});
      }
    },


   /**
    * Чистит все поля модального окна
    */
    clearFields:function() {

      $('.product-text-inputs input[name=title]').val('');
      $('.product-text-inputs input[name=link_electro]').val(''),
      $('.product-text-inputs input[name=url]').val('');
      $('.product-text-inputs input[name=code]').val('');
      $('.product-text-inputs input[name=price]').val('');
      $('.product-text-inputs input[name=old_price]').val('');
      $('.product-text-inputs input[name=count]').val('');

      catalog.selectCategoryInside('');

      var catId = $('.filter-container select[name=cat_id]').val();
      if(catId == 'null') {
        catId = 0;
      }

      $('select[name=inside_cat]').attr('size',4);
      $('.full-size-select-cat').removeClass('opened-select-cat').addClass('closed-select-cat');
      $('.full-size-select-cat').text(lang.PROD_OPEN_CAT);


      $('.product-text-inputs select[name=cat_id]').val(catId);

      // $('.prod-gallery').html('<div class="small-img-wrapper"></div>');
      $('textarea[name=html_content]').val('');
      $('#add-product-wrapper input[name=meta_title]').val('');
      $('#add-product-wrapper input[name=meta_keywords]').val('');
      $('#add-product-wrapper textarea[name=meta_desc]').val('');
      $('.yml-wrapper input[name=yml_sales_notes]').val(''),
      $('.product-text-inputs .variant-table').html('');
      $('.added-related-product-block').html('');
      $('.added-related-product-block').css('width',"800px");
      $('.userField').html('');
      $('.symbol-count').text('0');
      $('.save-button').attr('id','');
      $('.save-button').data('recommend','0');
      $('.save-button').data('activity','1');
      $('.save-button').data('new','0');
      $('.select-product-block').hide();
      catalog.cteateTableVariant(null);
      catalog.deleteImage ='';

      $('.del-link-electro').hide();
      $('.add-link-electro').show();
      // Стираем все ошибки предыдущего окна если они были.
      $('.errorField').css('display','none');

      $('#add-product-wrapper .select-currency-block').hide();

      var short = catalog.getShortIso(admin.CURRENCY_ISO);
      $('#add-product-wrapper .btn-selected-currency').text(short);
      $('#add-product-wrapper select[name=currency_iso] option[value='+admin.CURRENCY_ISO+']').prop('selected','selected');
      $('.error-input').removeClass('error-input');

      catalog.supportCkeditor = '';
      $('.addedProperty').html('');

      $('#add-product-wrapper .custom-popup').css('display','none');
      $('#add-product-wrapper .product-desc-field').css('display','none');
      $('#add-product-wrapper .add-category').removeClass('open');

      $('.sub-images').html('');
    },


   /**
    * Добавляет изображение продукта
    */
    addImageToProduct:function(img_container) {
      var currentImg = '';
      img_container.find('.img-loader').show();

      if(img_container.find('.prev-img img').length > 0) {
        currentImg = img_container.find('.prev-img img').attr('alt');
      } else {
        currentImg = img_container.find('img').attr('data-filename');
      }

      //Пишем в поле deleteImage имена изображений, которые необходимо будет удалить при сохранении
      if(catalog.deleteImage) {
        catalog.deleteImage += '|'+currentImg;
      } else {
        catalog.deleteImage = currentImg;
      }

      // отправка картинки на сервер
      img_container.find('.imageform').ajaxForm({
        type:"POST",
        url: "ajax",
        data: {
          mguniqueurl:"action/addImage"
        },
        cache: false,
        dataType: 'json',
        success: function(response) {
          admin.indication(response.status, response.msg);
          if(response.status != 'error') {
            var src=admin.SITE+'/uploads/'+response.data.img;
            catalog.tmpImage2Del += '|'+response.data.img;
            img_container.find('img').attr('src',src);
            img_container.find('img').attr('alt',response.data.img);
          } else {
            var src=admin.SITE+'/mg-admin/design/images/no-img.png';
            img_container.find('img').attr('src',src);
            img_container.find('img').attr('alt',response.data.img);
          }
         img_container.find('.img-loader').hide();
        }
      }).submit();
    },

    /**
     *  собирает названия файлов всех картинок чтобы сохранить их в БД в поле image_url
     */
    createFieldImgUrl: function() {
      var image_url = "";
      $('.images-block img').each(function() {
        if($(this).attr('alt') && $(this).attr('alt')!='undefined') {
          image_url += $(this).attr('alt')+'|';
        }
      });

      if(image_url) {
        image_url = image_url.slice(0,-1);
      }

      return image_url;
    },

    /**
     *  собирает все заголовки для картинок, чтобы сохранить их в БД в поле image_title
     */
    createFieldImgTitle: function() {
       var image_title = "";
       $('.images-block img').each(function() {
         if($(this).attr('alt') && $(this).attr('alt')!='undefined') {
           var title = $(this).parents('.parent').find('input[name=image_title]').val();
           title = title.replace('|','');
           image_title+=title+'|';
         }
       });

       if(image_title) {
         image_title = image_title.slice(0,-1);
       }

       return image_title;
    },

     /**
     *  собирает все описания для картинок, чтобы сохранить их в БД в поле image_alt
     */
    createFieldImgAlt: function() {
       var image_alt = "";
       $('.images-block img').each(function() {
         if($(this).attr('alt') && $(this).attr('alt')!='undefined') {
           var title = $(this).parents('.parent').find('input[name=image_alt]').val();
           title = title.replace('|','');
           image_alt+=title+'|';
         }
       });

       if(image_alt) {
         image_alt = image_alt.slice(0,-1);
       }

       return image_alt;
    },

   /**
     * Помещает  выбранную основной картинку в начало ленты
     * removemain = true - была удалена главная и требуется поднять из лены первую на место главной
     */
    upMainImg: function(obj, removemain) {
      if(obj.find('img').attr('src') == SITE+'/mg-admin/design/images/no-img.png') {
        return false;
      }
      var newMain = {
        src: obj.find('img').attr('src'),
        alt: obj.find('img').attr('alt'),
        imgTitle: obj.find('[name=image_title]').val(),
        imgAlt: obj.find('[name=image_alt]').val()
      };


      var main = $('.main-image');
      var sub = obj;

      sub.find('img').attr('src',main.find('img').attr('src')); 
      sub.find('img').attr('alt',main.find('img').attr('alt')); 
      sub.find('[name=image_title]').val(main.find('[name=image_title]').val()); 
      sub.find('[name=image_alt]').val(main.find('[name=image_alt]').val()); 

      main.find('img').attr('src',newMain.src); 
      main.find('img').attr('alt',newMain.alt); 
      main.find('[name=image_title]').val(newMain.imgTitle); 
      main.find('[name=image_alt]').val(newMain.imgAlt); 

      if(removemain) {
        obj.detach();
      }
    },

   /**
    * Удаляет изображение продукта
    */
    delImageProduct: function(id,img_container) {
      var imgFile = img_container.find('img').attr('src');

      if(confirm(lang.DELETE_IMAGE+'?')) {
        catalog.deleteImage += "|"+imgFile;
        // удаляем текущий блок управления картинкой
        if($('.images-block img').length>1) {
          if(img_container.hasClass('.main-image')) {
            catalog.upMainImg($('.sub-images').eq(0), true);
          } else {
            img_container.remove();
          }
        } else{
          // если блок единственный, то просто заменяем в нем картнку на заглушку
          var src = admin.SITE+'/mg-admin/design/images/no-img.png';
          img_container.find('img').attr('src',src).attr('alt','');
          img_container.data('filename','');
        }
      $('#tiptip_holder').hide();
      admin.ajaxRequest({
        mguniqueurl:"action/deleteImageProduct",
        imgFile: imgFile,
        id: id,
      },
      function(response) {
        admin.indication(response.status, response.msg);
      });
     }
    },

   /**
    * Поиск товаров
    */
    getSearch: function(keyword) {
      keyword = $.trim(keyword);
      if(keyword == lang.FIND+"...") {
        keyword = '';
      }
      if(!keyword) {
        admin.refreshPanel();
        admin.indication('error', 'Введите поисковую фразу');
        return false
      };

      admin.ajaxRequest({
          mguniqueurl:"action/searchProduct",
          keyword:keyword,
          mode: 'groupBy',
      },
      function(response) {
        admin.indication(response.status, response.msg);
        $('.product-tbody tr').remove();
        response.data.forEach(
          function (element, index, array) {
             var row = catalog.drawRowProduct(element);
             $('.product-tbody').append(row);
          });
          // Если в результате поиска ничего не найдено
          if(response.data.length==0) {
            var row = "<tr><td class='no-results' colspan='"+$('.product-table th').length+"'>"+lang.SEARCH_PROD_NONE+"</td></tr>"
            $('.product-tbody').append(row);
          }
          $('.mg-pager').hide();
        }
      );
    },


    //  Получает данные из формы фильтров и перезагружает страницу
    getProductByFilter: function() {
       var request = $("form[name=filter]").formSerialize();
       var insideCat = $('input[name="insideCat"]').prop('checked');
       admin.show("catalog.php","adminpage",request+'&insideCat='+insideCat+'&applyFilter=1&displayFilter=1',catalog.callbackProduct);
       return false;
    },

    // Устанавливает статус продукта - рекомендуемый
     recomendProduct:function(id, val) {
      admin.ajaxRequest({
        mguniqueurl:"action/recomendProduct",
        id: id,
        recommend: val,
      },
      function(response) {
        admin.indication(response.status, response.msg);
      }
      );
    },

    // Устанавливает статус - видимый
     visibleProduct:function(id, val) {
      admin.ajaxRequest({
        mguniqueurl:"action/visibleProduct",
        id: id,
        activity: val,
      },
      function(response) {
        admin.indication(response.status, response.msg);
      }
      );
    },

    // вывод в новинках
    newProduct:function(id, val) {
      admin.ajaxRequest({
        mguniqueurl:"action/newProduct",
        id: id,
        new: val,
      },
      function(response) {
        admin.indication(response.status, response.msg);
      }
      );
    },

     // Добавляет строку в таблицу вариантов
    cteateTableVariant:function(variants, imageDir) {

      admin.ajaxRequest({
        mguniqueurl:"action/nextIdProduct",
      },
      function(response) {
        if (!$('.product-text-inputs .variant-table .default-code').val()) {
          var id = response.data.id;
          var prefix = response.data.prefix_code ? response.data.prefix_code : 'CN';
          $('.product-text-inputs .variant-table .default-code').val(prefix + id);
        }
      }
      );
      // строим первую строку заголовков
      $('.product-text-inputs .variant-table').html('');
      if(variants) {
        var position ='\
        <tr class="text-left">\
          <td></td>\
          <th class="hide-content" style="width:150px;">'+lang.NAME_VARIANT+'</th>\
          <th style="width:80px;">'+lang.CODE_PRODUCT+'</th>\
          <th>'+lang.PRICE_PRODUCT+'/<a href="javascript:void(0);" class="btn-selected-currency"></a></th>\
          <th>'+lang.OLD_PRICE_PRODUCT+'</th>\
          <th>'+lang.WEIGHT+'</th>\
          <th>'+lang.UNIT +'</th>\
          <th class="hide-content"></th>\
        </tr>\ ';
        $('.variant-table').append(position);
        // заполняем вариантами продукта
        variants.forEach(function(variant, index, array) {
          var src = admin.SITE+"/mg-admin/design/images/no-img.png";
          if(variant.image) {
            src = variant.image;
          }

          if(variant.count<0) {variant.count='∞'};
          var position ='\
          <tr data-id="'+variant.id+'"  class="variant-row">\
            <td><i class="fa fa-arrows"></i></td>\
            <td class="hide-content">\
              <label for="title_variant"><input type="text" name="title_variant" value="'+variant.title_variant+'" class="product-name-input " title="'+lang.NAME_PRODUCT+'" ><div class="errorField" style="display:none;">'+lang.NAME_PRODUCT+'</div></label>\
            </td>\
            <td>\
              <label for="code"><input type="text" name="code" value="'+variant.code+'" class="product-name-input " title="'+lang.T_TIP_CODE_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_EMPTY+'</div></label>\
            </td>\
            <td>\
              <label for="price"><input type="text" name="price" value="'+variant.price+'" class="product-name-input  " title="'+lang.T_TIP_PRICE_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
            </td>\
            <td>\
              <label for="old_price"><input type="text" name="old_price" value="'+variant.old_price+'" class="product-name-input  " title="'+lang.T_TIP_OLD_PRICE+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
            </td>\
            <td>\
              <label for="weight"><input type="text" name="weight" value="'+variant.weight+'" class="product-name-input  " title="'+lang.T_TIP_WEIGHT_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
            </td>\
            <td>\
              <label for="count"><input type="text" name="count" value="'+variant.count+'" class="product-name-input  " title="'+lang.T_TIP_COUNT_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
            </td>\
            <td class="hide-content actions">\
            <div class="variant-dnd"></div>\
            <ul class="action-list">\
              <div class="img-this-variant" style="display:none; position: relative;">\
                <img src="'+src+'" style="width:50px; min-height:100%; position: absolute; bottom: 0;" data-filename="'+variant.image+'">\
              </div>\
              <li>\
                <form method="post" noengine="true" enctype="multipart/form-data" class="img-button" style="display:inline-block">\
                  <span class="add-img-clone"></span>\
                  <label>\
                    <a class="fa fa-picture-o"></a>\
                    <input type="file" style="display:none;" name="photoimg" class="add-img-var img-variant " title="Загрузить фотографию варианта">\
                  </label>\
                </form>\
                <a href="javascript:void(0);" class="del-img-variant " title="Удалить фотографию варианта" style="display:'+(variant.image.indexOf('no-img')==-1 ? 'inline-block': 'none')+'"> </a>\
              </li>\
              <li>\
                <a href="javascript:void(0);" class="del-variant fa fa-trash"></a>\
              </li>\
            </ul>\
            </td>\
          </tr>\ ';
          $('.variant-table').append(position);
        });
        $('.variant-table').data('have-variant','1');
      } else {

        var position ='\
        <tr class="text-left">\
          <th style="display:none" class="hide-content"></th>\
          <th style="display:none" class="hide-content">'+lang.NAME_VARIANT+'</th>\
          <th>'+lang.CODE_PRODUCT+'</th>\
          <th>'+lang.PRICE_PRODUCT+'/<a href="javascript:void(0);" class="btn-selected-currency"></a></th>\
          <th>'+lang.OLD_PRICE_PRODUCT+'</th>\
          <th>'+lang.WEIGHT+'</th>\
          <th>'+lang.UNIT+'</th>\
          <th style="display:none" class="hide-content"></th>\
        </tr>\ ';
        $('.variant-table').append(position);
        var position ='\
          <tr class="variant-row">\
            <td class="hide-content"><i class="fa fa-arrows"></i></td>\
            <td class="hide-content">\
              <label for="title_variant"><input type="text" name="title_variant" value="" class="product-name-input " title="'+lang.NAME_PRODUCT+'" ><div class="errorField" style="display:none;">'+lang.NAME_PRODUCT+'</div></label>\
            </td>\
            <td>\
              <label for="code"><input type="text" name="code" value="" class="product-name-input default-code" title="'+lang.T_TIP_CODE_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_EMPTY+'</div></label>\
            </td>\
            <td>\
              <label for="price"><input type="text" name="price" value="" class="product-name-input  " title="'+lang.T_TIP_PRICE_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
            </td>\
            <td>\
              <label for="old_price"><input type="text" name="old_price" value="" class="product-name-input  " title="'+lang.T_TIP_OLD_PRICE+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
            </td>\
            <td>\
              <label for="weight"><input type="text" name="weight" value="" class="product-name-input  " title="'+lang.T_TIP_WEIGHT_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
            </td>\
            <td>\
              <label for="count"><input type="text" name="count" value="" class="product-name-input  " title="'+lang.T_TIP_COUNT_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
            </td>\
            <td class="hide-content actions">\
            <div class="variant-dnd"></div>\
            <ul class="action-list" style="display:none;">\
              <div class="img-this-variant" style="display:none; position: relative;">\
                <img src="'+admin.SITE+'/mg-admin/design/images/no-img.png" style="width:50px; min-height:100%; position: absolute; bottom: 0;" data-filename="">\
              </div>\
              <li>\
                <form method="post" noengine="true" enctype="multipart/form-data" class="img-button" style="display:inline-block">\
                  <span class="add-img-clone"></span>\
                  <label>\
                    <a class="fa fa-picture-o"></a>\
                    <input type="file" style="display:none;" name="photoimg" class="add-img-var img-variant " title="Загрузить фотографию варианта">\
                  </label>\
                </form>\
                <a href="javascript:void(0);" class="del-img-variant " title="Удалить фотографию варианта" style="display:inline-block"> </a>\
              </li>\
              <li>\
                <a href="javascript:void(0);" class="del-variant fa fa-trash"></a>\
              </li>\
            </ul>\
            </td>\
          </tr>';
          $('.variant-table').append(position);
          $('.variant-table').data('have-variant','0');
          $('.variant-table').sortable({
            opacity: 0.6,
            axis: 'y',
            handle: '.fa-arrows',
            items: "tr+tr"
            }
          );
        }

        $('.btn-selected-currency').replaceWith('\
          <div class="popup-holder"><a class="btn-selected-currency" href="javascript:void(0);"></a>\
            '+$('#for-curency').html()+'\
          </div>\
          ');

        if($('#add-product-wrapper .variant-row').length > 1) {
          $('.hide-content').css('display','');
        } else {
          $('.hide-content').css('display','none');
        }

      admin.initToolTip();
    },


    // Добавляет строку в таблицу вариантов
    addVariant:function(table) {
      if($('.variant-table').data('have-variant')=="0") {
        $('.variant-table .hide-content').show();
        $('.variant-table').data('have-variant','1');
      }
      var code = $('.variant-table input[name="code"]:first').val();

      var position ='\
        <tr class="variant-row">\
          <td><i class="fa fa-arrows"></i></td>\
          <td class="hide-content">\
            <label for="title_variant"><input type="text" name="title_variant" value="" class="product-name-input " title="'+lang.NAME_PRODUCT+'" ><div class="errorField" style="display:none;">'+lang.NAME_PRODUCT+'</div></label>\
          </td>\
          <td>\
            <label for="code"><input type="text" name="code" value="" class="product-name-input default-code" title="'+lang.T_TIP_CODE_PROD+'"><div class="errorField" style="display:none;">'+lang.ERROR_EMPTY+'</div></label>\
          </td>\
          <td>\
            <label for="price"><input type="text" name="price" value="" class="product-name-input  " title="'+lang.T_TIP_PRICE_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
          </td>\
          <td>\
            <label for="old_price"><input type="text" name="old_price" value="" class="product-name-input  " title="'+lang.T_TIP_OLD_PRICE+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
          </td>\
          <td>\
            <label for="weight"><input type="text" name="weight" value="" class="product-name-input  " title="'+lang.T_TIP_WEIGHT_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
          </td>\
          <td>\
            <label for="count"><input type="text" name="count" value="" class="product-name-input  " title="'+lang.T_TIP_COUNT_PROD+'" ><div class="errorField" style="display:none;">'+lang.ERROR_NUMERIC+'</div></label>\
          </td>\
          <td class="hide-content actions">\
          <div class="variant-dnd"></div>\
          <ul class="action-list">\
            <div class="img-this-variant" style="display:none; position: relative;">\
              <img src="'+admin.SITE+'/mg-admin/design/images/no-img.png" style="width:50px; min-height:100%; position: absolute; bottom: 0;" data-filename="">\
            </div>\
            <li>\
              <form method="post" noengine="true" enctype="multipart/form-data" class="img-button" style="display: inline-block;">\
                <span class="add-img-clone"></span>\
                <label>\
                  <a class="fa fa-picture-o"></a>\
                  <input type="file" style="display:none;" name="photoimg" class="add-img-var img-variant " title="Загрузить фотографию варианта">\
                </label>\
              </form>\
              <a href="javascript:void(0);" class="del-img-variant " title="Удалить фотографию варианта" style="display:inline-block"> </a>\
            </li>\
            <li>\
              <a href="javascript:void(0);" class="del-variant fa fa-trash"></a>\
            </li>\
          </ul>\
          </td>\
        </tr>';
      table.append(position);

      $('.variant-table input[name="code"]:last').val(code+'-'+$('.variant-table input[name="code"]').length);

      $('.variant-row:eq(0) .action-list').css('display','');

      admin.initToolTip();
    },


    // возвращает пакет  вариантов собранный из таблицы вариантов
    getVariant: function() {
      catalog.errorVariantField = false;
      $('.errorField').hide();

      if($('.variant-table').data('have-variant')=="1") {
        var result = [];
        $('.variant-table .variant-row').each(function() {

          //собираем  все значения полей варианта для сохранения в БД

          var id =$(this).data('id');
          var currency_iso = $('#add-product-wrapper select[name=currency_iso] option:selected').val();
          var obj = '{';
          $(this).find('input').removeClass('error-input');
          $(this).find('input').each(function() {

            if($(this).attr('name')!='photoimg') {
              var val = $(this).val();
              if((val=='\u221E'||val==''||parseFloat(val)<0)&&$(this).attr('name')=="count") {val = "-1";}
              if(val==""&&$(this).attr('name')=='weight') {val = "0";}
              if(val==""&&$(this).attr('name')!='old_price') {
                $(this).addClass('error-input');
                catalog.errorVariantField = true;
                $(this).parents('td').find('.errorField').show();
              }
              obj += '"' + admin.htmlspecialchars($(this).attr('name')) + '":"' + admin.htmlspecialchars(val) + '",';
            }
          });
          obj += '"activity":"1",';
          obj += '"id":"'+id+'",';
          obj += '"currency_iso":"'+currency_iso+'",';

          var filename = $(this).find('img[filename]').attr('filename');
          if(!filename) {filename = $(this).find('img').data('filename')}
          obj += '"image":"'+filename+'",';

          obj += '}';
          //преобразуем полученные данные в JS объект для передачи на сервер
          result.push(eval("(" + obj + ")"));
        });

        return result;
      }
      return null;
    },

    // возвращает список id связанных товаров с редактируемым
    getRelatedProducts: function() {
      var result = '';
      $('.add-related-product-block .product-unit').each(function() {
        result += $(this).data('code') + ',';
      });
      result = result.slice(0, -1);


      return result;
    },
    // возвращает список id связанных категорий с редактируемым
    getRelatedCategory: function() {
      var result = '';
      $('.add-related-product-block .category-unit').each(function() {
        result += $(this).data('id') + ',';
      });
      result = result.slice(0, -1);
      return result;
    },

    // сохраняет параметры товара прямо со страницы каталога в админке
    fastSave:function(data, val, input) {
      var obj = eval("(" + data + ")");
      // Проверка поля для стоимости, является ли текст в него введенный числом.

      // знак бесконечности
      if((val=='\u221E'||val==''||parseFloat(val)<0)&&obj.field=="count") {val = "-1"; input.val('∞'); }


      if(isNaN(parseFloat(val))) {
        admin.indication('error', lang.ENTER_NUM);
        input.addClass('error-input');
        return false;
      } else {
        input.removeClass('error-input');
      }
      var id = input.parents('.product-row').attr('id');
      // получаем с сервера все доступные пользовательские параметры
      admin.ajaxRequest({
        mguniqueurl:"action/fastSaveProduct",
        variant:obj.variant,
        id:obj.id,
        field:obj.field,
        value:val,
        product_id: id
      },
      function(response) {
        if (response.data) {
          $(".product-tbody tr#"+id+" .price").find(".view-price[data-productId="+obj.id+"]").text(response.data+' '+admin.CURRENCY);
        }
        admin.clearGetParam();
        admin.indication(response.status, response.msg);
      });

    },


    importFromCsv:function() {
      admin.ajaxRequest({
        mguniqueurl:"action/importFromCsv",
      },
      function(response) {
        admin.indication(response.status, response.msg);
      });
    },

    /**
     * Загружает CSV файл на сервер для последующего импорта
     */
    uploadCsvToImport:function() {
      // отправка файла CSV на сервер
      $('.repeat-upload-file .message').text('Идет передача файла на сервер. Подождите, пожалуйста...');
      $('.upload-csv-form').ajaxForm({
        type:"POST",
        url: "ajax",
        data: {
          mguniqueurl:"action/uploadCsvToImport"
        },
        cache: false,
        dataType: 'json',
        error: function() {alert("Загружаемый вами файл превысил максимальный объем и не может быть передан на сервер из-за ограничения в настройках файла php.ini\n\n{Внимание! Поддерживается загрузка zip архива с CSV файлом}");},
        success: function(response) {
          admin.indication(response.status, response.msg);
          if(response.status=='success') {
            $('.section-catalog select[name=importScheme]').removeAttr('disabled');
            $('.section-catalog select[name=identifyType]').removeAttr('disabled');
            $('input[name=no-merge]').removeAttr('disabled');
            $('.repeat-upload-file').show();
            $('.block-upload-сsv .upload-btn').hide();
            catalog.setCsvCompliance();
            $('.repeat-upload-file .message').text('Файл готов к импорту товаров в каталог');
          } else {
            $('.message-importing').text('');
            $('.import-container input[name="upload"]').val('');
          }
        },

      }).submit();
    },

    /**
     * Устанавливает первоначальное соответствие полей для CSV по их заголовкам
     */
    setCsvCompliance: function() {
      var importType = $('.section-catalog select[name="importType"]').val();

      admin.ajaxRequest({
        mguniqueurl:"action/setCsvCompliance",
        importType: importType,
      },function(response) {
        admin.indication(response.status, response.msg);
      });
    },

      /**
     * Контролирует процесс импорта, выводит индикатор в процентах обработки каталога.
     */
    startImport:function(rowId, percent,downloadLink) {
     var typeCatalog = $(".block-upload-сsv select[name=importType]").val();
     var identifyType = $(".block-upload-сsv select[name=identifyType]").val();
     var schemeType = $('.section-catalog select[name=importScheme]').val();

     var delCatalog=null;
      if(!rowId){
        if(!$('.loading-line').length) {
          $('.process').append('<div class="loading-line"></div>');
        }
        rowId = 0;
        delCatalog = $('input[name=no-merge]').val();
      }
      if(!percent){
        percent = 0;
      }

       if(!downloadLink){
        downloadLink = false;
       }


      if(!catalog.STOP_IMPORT){
        $('.message-importing').html('Идет процесс импорта товаров. Загружено:'+percent+'%<div class="progress-bar"><div class="progress-bar-inner" style="width:'+percent+'%;"></div></div>');
      }else{
        $('.loading-line').remove();
      }

      // отправка файла CSV на сервер
      admin.ajaxRequest({
        mguniqueurl:"action/startImport",
        rowId:rowId,
        delCatalog:delCatalog,
        typeCatalog: typeCatalog,
        identifyType: identifyType,
        schemeType: schemeType,
        downloadLink: downloadLink,
      },
      function(response){



        if(response.status=='error'){
          admin.indication(response.status, response.msg);
        }

        if(response.data.percent<100){
          if(response.data.status=='canseled'){
            $('.message-importing').html('Процесс импорта остановлен пользователем! Загружено: '+response.data.rowId+' товаров  [<a href="javascript:void(0);" class="repeat-upload-csv">Загрузить другой файл</a>]' );
//            $('.block-upload-сsv').hide();
            //  console.log(response);
            $('.loading-line').remove();
          }else{
            catalog.startImport(response.data.rowId,response.data.percent,response.data.downloadLink);
          }
        } else{
           $('.cancel-importing').hide();
           $('.message-importing').html('Импорт товаров успешно завершен! \
              <a class="refresh-page custom-btn" href="'+mgBaseDir+'/mg-admin/">\n\
                <span>Обновите страницу</span>\n\
              </a> или <a href="jaascript:void(0);" class="gotoImageUpload custom-btn"><span>перейдите к загрузке изображений для товаров</span></a>');
           $('.block-upload-сsv').hide();

    
           if(response.data.startGenerationImage==true){    
            $('.message-importing').hide();
            $('.import-container h3.title').text('Создание миниатюр для изображений товаров');	
            $('.block-upload-images').show();    
            $('.block-upload-images .upload-images').hide();           
            catalog.startGenerationImage(); 
           }

           //startImport


           $('.loading-line').remove();
        }

      });
    },

     /**
     * Клик по найденным товарам поиске в форме добавления связанного товара.
     */
    addrelatedProduct: function(elementIndex, product) {
      $('.search-block .errorField').css('display', 'none');
      $('.search-block input.search-field').removeClass('error-input');
      if(!product) {
        var product = admin.searcharray[elementIndex];
      }

      if (product.category_url.charAt(product.category_url.length-1) == '/') {
        product.category_url = product.category_url.slice(0,-1);
      }
      
      var html = catalog.rowRelatedProduct(product);
      $('.added-related-product-block .product-unit[data-id='+product.id+']').remove();
      $('.related-wrapper .added-related-product-block').prepend(html);
      catalog.widthRelatedUpdate();
      catalog.msgRelated();
      $('input[name=searchcat]').val('');
      $('.select-product-block').hide();
      $('.fastResult').hide();
    },
      /**
     * Клик по выбранным связанным категориям 
     */
    addrelatedCategory: function(category) {
      var html = '';
      category.forEach(function(item, i, arr) {
        if(item.image_url == null) {
          image_url = '/uploads/no-img.jpg';
        } else {
          image_url = item.image_url;
        }
        html += '\
      <div class="category-unit" data-id='+ item.id +'>\
          <div class="product-img">\
              <a href="javascript:void(0);"><img src="' + mgBaseDir + image_url  + '"></a>\
          </div>\
          <a href="' + mgBaseDir + '/'+ item.parent_url + item.url + 
              '" data-url="' + item.url +'" class="product-name" target="_blank" title="' +
              item.title + '">' +
              item.title + '</a>\
          <a class="remove-added-category custom-btn fa fa-trash" href="javascript:void(0);"><span></span></a>\
      </div>\
      ';

        $('.added-related-category-block .category-unit[data-id='+item.id+']').remove();
      }) 

      $('.related-wrapper .added-related-category-block').prepend(html);
      catalog.widthRelatedUpdate();
      catalog.msgRelated();
      $('.search-block.category select[name=related_cat] option').prop('selected', false);
      $('.select-product-block').hide();
    },

     /**
     * формирует верстку связанного продукта.
     */
    rowRelatedProduct: function(product) {
      var price = (product.real_price) ? product.real_price : product.price;

      var html = '\
      <div class="product-unit" data-id='+ product.id +' data-code="'+ product.code +'">\
        <div class="product-img" style="text-align:center;height:50px;">\
          <a href="javascript:void(0);"><img src="' + product.image_url + '" style="height:50px;"></a>\
          <a class="remove-img fa fa-trash tip remove-added-product" href="javascript:void(0);" aria-hidden="true" data-hasqtip="88" oldtitle="Удалить" title="" aria-describedby="qtip-88"></a>\
        </div>\
        <a href="' + mgBaseDir + '/' + product.category_url + "/" + product.product_url +
          '" data-url="' + product.category_url +
          "/" + product.product_url + '" class="product-name" target="_blank" title="' +
          product.title + '">' +
          product.title + '</a>\
        <span>' + admin.numberFormat(price) +' '+ admin.CURRENCY+'</span>\
      </div>\
      ';
      return html;
    },

    //выводит связанные товары
    //relatedProducts - массив с товарами
    drawRelatedProduct: function(relatedArr) {
      relatedArr.forEach(function (product, index, array) {
        var html = catalog.rowRelatedProduct(product);
        $('.related-wrapper .added-related-product-block').append(html);
        catalog.widthRelatedUpdate();
      });
      catalog.msgRelated();
    },

    //выводит ссылку в пустом блоке для добавления связанного товара
    msgRelated: function() {
      if($('.added-related-product-block .product-unit').length==0&&$('.added-related-category-block .category-unit').length==0) {
        if ($('a.add-related-product.in-block-message').length==0) {
        $('.related-wrapper .added-related-product-block').append('\
         <a class="add-related-product in-block-message" href="javascript:void(0);"><span>'+lang.RELATED_PROD+'</span></a>\
       ');
        }
        $('.added-related-product-block').width('800px');
      }else {
        $('.added-related-product-block .add-related-product').remove();
      };
      if ($('.added-related-category-block .category-unit').length==0) {
        $('.add-related-product-block .add-related-category.in-block-message').hide();
      } else {
        $('.add-related-product-block .add-related-category.in-block-message').show();
      }
    },

    //пересчитывает ширину блока с связанными товарами, для работы скрола.
    widthRelatedUpdate: function() {
      var prodWidth = $('.product-unit').length * ($('.product-unit').width() + 30);
      var catWidth = $('.category-unit').length * ($('.category-unit').width() + 30);
      if(prodWidth > catWidth) {
        $('.related-block').width(prodWidth);
      } else {
        $('.related-block').width(catWidth);
      }
      if($('.product-unit').length == 0) {
        $('.added-related-product-block').css('display','none');
      } else {
        $('.added-related-product-block').css('display','');
      }
      if($('.category-unit').length == 0) {
        $('.added-related-category-block').css('display','none');
      } else {
        $('.added-related-category-block').css('display','');
      }
    },

    /**
     * Останавливает процесс импорта в каталог товаров
     */
    canselImport:function() {
      $('.message-importing').text('Происходит остановка импорта!');
      catalog.STOP_IMPORT=true;
      admin.ajaxRequest({
        mguniqueurl:"action/canselImport"
      },
      function(response) {
        admin.indication(response.status, response.msg);
      });
    },

    /**
     *Пакет выполняемых действий после загрузки раздела товаров
     */
    callbackProduct:function() {
      admin.sliderPrice();
      if (!$('.section-catalog table tbody').data('refresh')) {
        admin.AJAXCALLBACK = [
          {callback:'admin.sortable', param:['.product-table > tbody','product']},
        ];
      }  
    },
    

    /**
     * Выделяет все категории в списке, в которых будет отображаться товар
     */
    selectCategoryInside:function(selectedCatIds) {
      if(!selectedCatIds) {
        $('.add-category').removeClass('opened-list');
        $('.inside-category').hide();
      } else {
        $('.add-category').addClass('opened-list');
        $('.inside-category').show();
      }
      if(selectedCatIds) {
      var htmlOptionsSelected = selectedCatIds.split(',');
      $('select[name=inside_cat] option').prop('selected', false);
      function buildOption(element, index, array) {
        $('.inside-category select[name="inside_cat"] [value="' + element + '"]').prop('selected', 'selected');
      }
      ;
      htmlOptionsSelected.forEach(buildOption);
      }
    },

    /**
     * Возвращает список выбранных категорий для товара
     */
    createInsideCat: function() {
      var category = '';
      $('select[name=inside_cat] option').each(function() {
        if ($(this).prop('selected')) {
          category += $(this).val() + ',';
        }
      });

      category = category.slice(0, -1);

      return category;
    },

    /**
     * Возвращает список выбранных категорий для товара
     */
    getFileElectro: function(file) {
      var dir = file.url;
      dir= dir.replace(mgBaseDir, '');
      $('.section-catalog input[name="link_electro"]').val(dir);
      $('.section-catalog .del-link-electro').text(dir.substr(0,50));
      $('.section-catalog .del-link-electro').attr('title',dir);
      $('.section-catalog .del-link-electro').show();
      $('.section-catalog .add-link-electro').hide();
    },

    /**
     * Смена валюты
     */
    changeIso: function() {
      var short = $('#add-product-wrapper select[name=currency_iso] option:selected').text();
      var rate = $('#add-product-wrapper select[name=currency_iso] option:selected').data('rate');
      $('#add-product-wrapper .btn-selected-currency').text(short);
      $('#add-product-wrapper .select-currency-block').hide();
    },

    /**
     * Возвращает сокращение, из списка допустимых валют
     * @param {type} iso
     * @returns {undefined}
     */
    getShortIso: function(iso) {
      iso = JSON.stringify(iso);
      var short = $('#for-curency select[name=currency_iso] option[value='+iso+']').text();
      return short;
    },

    closeAddedProperty: function(type) {
      if (type == 'close') {
        $('.addedProperty .new-added-prop').each(function() {
          var id = $(this).data('id');
          admin.ajaxRequest({
            mguniqueurl: "action/deleteUserProperty",
            id: id
          })
        });
      }
      $('#add-product-wrapper .new-added-properties').hide();
      $('#add-product-wrapper .new-added-properties input').val('');
      $('#add-product-wrapper .new-added-properties input').removeClass('error-input');
      $('.new-added-properties .errorField').hide();
    },

    // добавляет новую характеристику
    addNewProperty: function (name, value) {
      admin.ajaxRequest({
        mguniqueurl: "action/addUserProperty",
      },
        function (response) {
          var id = response.data.allProperty.id;
          var html = '<div class="new-added-prop" data-id="' + id + '">\
                        <div class="row">\
                          <div class="medium-4 small-12 columns">\
                            <label>' + name + ':</label>\
                          </div>\
                          <div class="medium-8 small-11 columns to-input-btn">\
                            <input class="property custom-input" type="text" value="' + value + '">\
                            <a href="javascript:void(0);" class="remove-added-property fa fa-trash btn red"></a>\
                          </div>\
                        </div>\
                      </div>';
          $('#add-product-wrapper .addedProperty').prepend(html);
          admin.ajaxRequest({
            mguniqueurl: "action/saveUserProperty",
            id: id,
            name: name,
          })
          var category = $('.product-text-inputs select[name=cat_id]').val();
          admin.ajaxRequest({
            mguniqueurl: "action/saveUserPropWithCat",
            id: id,
            category: category
          })
          })
      catalog.closeAddedProperty();
    },

     //Добавляет новую характеристику
    saveAddedProperties: function () {
      $('.addedProperty .new-added-prop ').each(function () {
        var id = $(this).data('id');
        var category = $('.product-text-inputs select[name=cat_id]').val();
        admin.ajaxRequest({
          mguniqueurl: "action/saveUserPropWithCat",
          id: id,
          category: category
        })
      })
    },

  }
})();

// инициализация модуля при подключении
catalog.init();
