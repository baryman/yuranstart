/**
 * Модуль для  раздела "Страницы".
 */


var page = (function () {
  return {
    wysiwyg: null, // HTML редактор для  редактирования страниц
    supportCkeditor: null,
    openedPageAdmin: [], //массив открытых страниц
    modalPage: null, // модальное окно для редактирования страниц
    /**
     * Инициализирует обработчики для кнопок и элементов раздела.
     */
    init: function () {
      // восстанавливаем массив открытых страниц из куков
      page.openedPageAdmin = eval(cookie("openedPageAdmin"));
      if (!page.openedPageAdmin) {
        page.openedPageAdmin = [];
      }

      /*Инициализирует CKEditior*/
      $('body').on('click', '.section-page #add-page-modal .html-content-edit', function() {
        var link = $(this);
        $('textarea[name=html_content]').ckeditor(function() {});
      });

      // Вызов модального окна при нажатии на кнопку добавления.      
      $('.admin-center').on('click', '.section-page .add-new-button', function () {
        page.openModalWindow('add');
      });

      // Обработка нажатия на кнопку  сделать видимыми все.      
      $('.admin-center').on('click', '.section-page .refresh-visible-cat', function () {
        page.refreshVisible();
      });

      // Вызов модального окна при нажатии на пункт изменения
      $('.admin-center').on('click', '.section-page .edit-sub-cat', function () {
        page.openModalWindow('edit', $(this).parents('tr').data('id'));
      });

      // Вызов модального окна при нажатии на пункт добавления
      $('.admin-center').on('click', '.section-page .add-sub-cat', function () {
        page.openModalWindow('addSubCategory', $(this).parents('tr').data('id'));
      });

      // Удаление страницы.
      $('.admin-center').on('click', '.section-page .delete-sub-cat', function () {
        page.deletePage($(this).parents('tr').data('id'));
      });

      // Сохранение в модальном окне.
      $('body').on('click', '#add-page-modal .save-button', function () {
        page.savePage($(this).attr('id'));
      });

      // Сохранение продукта при нажатии на кнопку сохранить в модальном окне.
      $('body').on('click', '.section-page .link-to-site', function () {
        var url = $(this).data('href');
        if (url == (mgBaseDir + '/index') || url == (mgBaseDir + '/index.html')) {
          url = mgBaseDir;
        }
        window.open(url);
      });

      // Сохранение продукта при на жатии на кнопку сохранить в модальном окне.
      $('body').on('click', ' .previewPage', function () {
        $('#previewContent').val($('textarea[name=html_content]').val());
        $('#previewer').submit();
      });

      // применение класса selected для строки, которой ставят галочку выделения
      $('body').on('click' ,'.select-row', function() {
        var id = $(this).parents('tr').data('id');
        if($('#c'+id).prop('checked')) {
          $(this).parents('tr').removeClass('selected');
        } else {
          $(this).parents('tr').addClass('selected');
        }
      });

      // Разворачивание подпунктов по клику
      $('.admin-center').on('click', '.section-page .show_sub_menu', function () {
        // берем id текущей строки
        var id = $(this).parents('tr').data('id');
        // достаем уровень вложенности данной строки
        var level = $(this).parents('tr').data('level');
        level++;

        // берем порядковый номер текущей строки
        thisSortNumber = $(this).parents('tr').data('sort');

        if ($(this).hasClass('opened')) {
          // удаляем id текщей строки из куков для отображения
          page.delCategoryToOpenArr(id);

          // достаем текущую группу строк ( вложенные страницы одного уровня и с одним родителем, принадлежат одной группе )
          group = $(this).parents('tr').data('group');

          // проходим циклом по строкам проверяя их соответсвие требованиям для дальнейших действий
          var trCount = $('.section-page .main-table tbody tr').length;
          for(i = thisSortNumber; i < trCount; i++) {
            // если строка принадлежит той же группе, что и строка, на которой был клик, либо если ее уровень меньше, то выходим из цикла
            if(($('.section-page .main-table tbody tr:eq('+i+')').hasClass(group)) || (($('.section-page .main-table tbody tr:eq('+i+')').data('level') < level))) {
              break;
            } else {
              // если уровень вложености соответствует вложенным страницам
              if(($('.section-page .main-table tbody tr:eq('+i+')').data('level') >= level)) {
                // скрываем вложенные страницы
                $('.section-page .main-table tbody tr:eq('+i+')').css('display','none');
                // убираем класс открытой вложенности и внутренних страниц, если есть
                $('.section-page .main-table tbody tr:eq('+i+')').find('.show_sub_menu').removeClass('opened');
                // удаляем id из куков, чтобы не открывалось то, что не нужно при перезапуске
                page.delCategoryToOpenArr($('.section-page .main-table tbody tr:eq('+i+')').data('id'));
              }
            }
          }

          $(this).removeClass('opened');
        }
        else {
          // добавляем id в куки
          page.addCategoryToOpenArr(id);
          
          group = $(this).parents('tr').data('group');

          var trCount = $('.section-page .main-table tbody tr').length;
          for(i = thisSortNumber; i < trCount; i++) {
            // если строка принадлежит той же группе, что и строка, на которой был клик, либо если ее уровень меньше, то выходим из цикла
            if(($('.section-page .main-table tbody tr:eq('+i+')').hasClass(group)) || (($('.section-page .main-table tbody tr:eq('+i+')').data('level') < level))) {
              break;
            } else {
              // если строки соответсвуют уровню вложенности на 1 глубже, сроки по которой был клик, показываем строки
              if(($('.section-page .main-table tbody tr:eq('+i+')').data('level') == level)) {
                $('.section-page .main-table tbody tr:eq('+i+')').css('display','');
              }
            }
          }

          $(this).addClass('opened');
        }
      });

      // клик на переключатель, делает невидимой страницу в меню      
      $('.admin-center').on('click', '.section-page .visible', function () {
        var id = $(this).parents('tr').data('id');

        if (!$(this).hasClass('active')) {
          page.invisiblePage(id, 0);
          $(this).addClass('active');
          $(this).attr('title', lang.ACT_V_CAT);
        }
        else {
          page.invisiblePage(id, 1);
          $(this).removeClass('active');
          $(this).attr('title', lang.ACT_UNV_CAT);
        }
        admin.initToolTip();
      });
      // Выполнение выбранной операции с отмеченными страницами
      $('.admin-center').on('click', '.section-page .run-operation', function () {
        page.runOperation($('.page-operation').val());
      });
      
      // Выделить все страницы
      $('.admin-center').on('click', '.section-page .check-all-page', function () {
        $('.page-tree input[name=page-check]').prop('checked', 'checked');
        $('.page-tree input[name=page-check]').val('true');
        $('.page-tree tr').addClass('selected');

        $(this).addClass('uncheck-all-page');
        $(this).removeClass('check-all-page');
      });
      // Снять выделение со всех  страниц.
      $('.admin-center').on('click', '.section-page .uncheck-all-page', function () {
        $('.page-tree input[name=page-check]').prop('checked', false);
        $('.page-tree input[name=page-check]').val('false');
        $('.page-tree tr').removeClass('selected');
        
        $(this).addClass('check-all-page');
        $(this).removeClass('uncheck-all-page');
      });
      // автозаполнение seo для страниц
      $('.admin-center').on('blur', '.add-category-form  input[name=title]', function(){
        var title = $(this).val().replace(/"/g,'');
        
        if (!$('.add-product-form-wrapper .seo-wrapper input[name=meta_title]').val()){
          $('.add-product-form-wrapper .seo-wrapper input[name=meta_title]').val(title);
        }
        
        page.generateKeywords(title);
      });
       // при заполнении поля описание - первые 160 символов копируются в блок SEO - description
      CKEDITOR.on('instanceCreated', function(e) {
        if (e.editor.name === 'html_content') {
          e.editor.on('blur', function (event) {
          var description = $('#add-page-modal .seo-wrapper textarea[name=meta_desc]').val();          
          if (!$.trim(description)) {
            description = $('textarea[name=html_content]').val();
            var short_desc = page.generateMetaDesc(description);
            $('.add-product-form-wrapper .seo-wrapper textarea[name=meta_desc]').val($.trim(short_desc));
            $('.add-product-form-wrapper .seo-wrapper textarea[name=meta_desc]').trigger('blur');
          }
          });
        }
      }); 
      // нажатие на кнопку генерации мета тегов
      $('body').on('click', '#add-page-modal .seo-gen-tmpl', function () {
        page.generateSeoFromTmpl();
      });

    },
    sortableInit: function() {
      $(".section-page .main-table tbody tr").hover( 
        function() {
          group = $(this).data('group');

          var trCount = $('.section-page .main-table tbody tr').length;
          for(i = 0; i < trCount; i++) {
            if($('.section-page .main-table tbody tr:eq('+i+')').hasClass(group)) {
              $('.section-page .main-table tbody tr:eq('+i+')').removeClass('disableSort');
            } else {
              $('.section-page .main-table tbody tr:eq('+i+')').addClass('disableSort');
            }
          }
        }
      );

      $(".section-page .main-table tbody").sortable({
        handle: '.mover',
        start: function(event, ui) {
          group = $(ui.item).data('group');

          var trCount = $('.section-page .main-table tbody tr').length;
          for(i = 0; i < trCount; i++) {
            if(!$('.section-page .main-table tbody tr:eq('+i+')').hasClass(group)) {
              $('.section-page .main-table tbody tr:eq('+i+')').addClass('disabled');
            } else {
              $('.section-page .main-table tbody tr:eq('+i+')').removeClass('disabled');
            }
          }
        },
        items: 'tr:not(.disableSort)',
        helper: fixHelperPage,
        stop: function() {
          page.saveSort();
        }
      }).disableSelection();
    },
    /**
    * Генерируем ключевые слова для категории
    * @param string title
    */
    generateKeywords: function(title) {
      if (!$('.add-product-form-wrapper .seo-wrapper input[name=meta_keywords]').val()) {	 
        var keywords = title;
        var keyarr = title.split(' ');
        
        if(keyarr.length == 1) {
          $('.add-product-form-wrapper .seo-wrapper input[name=meta_keywords]').val(keywords);
          return;
        }
        
        for ( var i=0; i < keyarr.length; i++) {
          var word = keyarr[i].replace('"','');
          
          if (word.length > 3) {
            keywords += ', ' + word;
          } else {
            if(i!==keyarr.length-1){
               keywords += ', '+ word + ' ' + keyarr[i+1].replace(/"/g,'');
               i++; 
            }else{
                keywords += ', '+ word
            } 
          }         
        }
        
        $('.add-product-form-wrapper .seo-wrapper input[name=meta_keywords]').val(keywords);
      }
    },
    /**
    * Генерируем мета описание
    */
    generateMetaDesc: function(description) {
      var short_desc = description.replace(/<\/?[^>]+>/g, '');
      short_desc = admin.htmlspecialchars_decode(short_desc.replace(/\n/g, ' ').replace(/&nbsp;/g, '').replace(/\s\s*/g, ' ').replace(/"/g, ''));

      if (short_desc.length > 150) {
        var point = short_desc.indexOf('.', 150);
        short_desc = short_desc.substr(0, (point > 0 ? point : short_desc.indexOf(' ',150)));
      }

      return short_desc;
    },
    /**
    * Запускаем генерацию метатегов по шаблонам из настроек
    */
    generateSeoFromTmpl: function() {
      if (!$('.seo-wrapper input[name=meta_title]').val()) {
        $('.seo-wrapper input[name=meta_title]').val($('#add-page-modal input[name=title]').val());
      }
      
      if (!$('.seo-wrapper textarea[name=meta_desc]').val()) {
        var short_desc = page.generateMetaDesc($('textarea[name=html_content]').val());
        $('#add-page-modal .seo-wrapper textarea[name=meta_desc]').val($.trim(short_desc));
      }
      
      var data = {
        title: $('input[name=title]').val(),
        html_content: $('textarea[name=html_content]').val(),
        meta_title: $('input[name=meta_title]').val(),
        meta_keywords: $('input[name=meta_keywords]').val(),
        meta_desc: $('textarea[name=meta_desc]').val(),
      };

      admin.ajaxRequest({
        mguniqueurl:"action/generateSeoFromTmpl",
        type: 'page',
        data: data
      }, function(response) {
        $.each(response.data, function(key, value) {
          if (value) {
            if (key == 'meta_desc') {
              $('.seo-wrapper textarea[name='+key+']').val(value);
            } else {
              $('.seo-wrapper input[name='+key+']').val(value);
            }
          }
        });
        
        $('#add-page-modal .seo-wrapper textarea[name=meta_desc]').trigger('blur');
        admin.indication(response.status, response.msg);
      });
    },
    /** 
     * Делает страницу видимой/невидимой в меню
     * oneId - идентификатор первой 
     * twoId - идентификатор второй 
     */
    invisiblePage: function (id, invisible) {
      admin.ajaxRequest({
        mguniqueurl: "action/invisiblePage",
        id: id,
        invisible: invisible
      },
      function (response) {
        admin.indication(response.status, response.msg)
      });
    },
    // добавляет ID открытой категории в массив, записывает в куки для сохранения статуса дерева
    addCategoryToOpenArr: function (id) {

      var addId = true;
      page.openedPageAdmin.forEach(function (item) {
        if (item == id) {
          addId = false;
        }
      });

      if (addId) {
        page.openedPageAdmin.push(id);
      }

      cookie("openedPageAdmin", JSON.stringify(page.openedPageAdmin));
    },
    // удаляет ID закрытой категории из массива, записывает в куки для сохранения статуса дерева
    delCategoryToOpenArr: function (id) {

      var dell = false;
      var i = 0;
      var spliceIndex = 0;
      page.openedPageAdmin.forEach(function (item) {
        if (item == id) {
          dell = true;
          spliceIndex = i;
        }
        i++;
      });

      if (dell) {
        page.openedPageAdmin.splice(spliceIndex, 1);
      }

      cookie("openedPageAdmin", JSON.stringify(page.openedPageAdmin));
    },
    /**
     * Открывает модальное окно.
     * type - тип окна, либо для создания нового товара, либо для редактирования старого.
     * id - редактируемая категория, если это не создание новой
     */
    openModalWindow: function (type, id) {
      try {
        if (CKEDITOR.instances['html_content']) {
          CKEDITOR.instances['html_content'].destroy();
        }
      } catch (e) {
      }

      switch (type) {
        case 'edit':
        {
          page.clearFileds();
          $('#modalTitle').text(lang.PAGE_EDIT);
          page.editCategory(id);
          break;
        }
        case 'add':
        {
          $('#modalTitle').text(lang.PAGE_MODAL_TITLE);
          page.clearFileds();
          break;
        }
        case 'addSubCategory':
        {
          $('#modalTitle').text(lang.ADD_SUBPAGE);
          page.clearFileds();
          $('select[name=parent] option[value="' + id + '"]').prop("selected", "selected");
          break;
        }
        default:
        {
          page.clearFileds();
          break;
        }
      }

      /*$('textarea[name=html_content]').ckeditor(function () {

        this.setData(page.supportCkeditor);
      });*/

      // Вызов модального окна.
      admin.openModal('#add-page-modal');
    },
    /**
     *  Проверка заполненности полей, для каждого поля прописывается свое правило.
     */
    checkRulesForm: function () {
      $('.errorField').css('display', 'none');
      $('input').removeClass('error-input');

      var error = false;
      // наименование не должно иметь специальных символов.
      if (!$('input[name=title]').val()) {
        $('input[name=title]').parent("label").find('.errorField').css('display', 'block');
        $('input[name=title]').addClass('error-input');
        error = true;
      }

      // артикул обязательно надо заполнить.
      if (!$('input[name=url]').val()) {
        $('input[name=url]').parent("label").find('.errorField').css('display', 'block');
        $('input[name=url]').addClass('error-input');
        error = true;
      }
      
      var url = $('input[name=url]').val();
      var reg = new RegExp('([^/-a-z#\.\d])','i');
      
      if (reg.test(url)) {
        $('input[name=url]').parent("label").find('.errorField').css('display','block');
        $('input[name=url]').addClass('error-input');
        $('input[name=url]').val('');
        error = true;
      }

      if (error == true) {
        return false;
      }

      return true;
    },
    /**
     * Сохранение изменений в модальном окне страницы.
     * Используется и для сохранения редактированных данных и для сохранения новой страницы.
     * id - идентификатор страницы, может отсутствовать если производится добавление  новой страницы.
     */
    savePage: function (id) {

      // Если поля неверно заполнены, то не отправляем запрос на сервер.
      if (!page.checkRulesForm()) {
        return false;
      }

      if ($('textarea[name=html_content]').val() == '') {
        if (!confirm(lang.ACCEPT_EMPTY_DESC + '?')) {
          return false;
        }
      }
      // Пакет характеристик категории.
      var packedProperty = {
        mguniqueurl: "action/savePage",
        id: id,
        title: $('input[name=title]').val(),
        url: $('input[name=url]').val(),
        parent: $('select[name=parent]').val(),
        html_content: $('textarea[name=html_content]').val(),
        meta_title: $('input[name=meta_title]').val(),
        meta_keywords: $('input[name=meta_keywords]').val(),
        meta_desc: $('textarea[name=meta_desc]').val(),
        invisible: $('input[name=invisible]').val() == 'true' ? 1 : 0
      }

      // Отправка данных на сервер для сохранения.
      admin.ajaxRequest(packedProperty,
        function (response) {
          admin.indication(response.status, response.msg);

          // Закрываем окно.
          // $('#add-page-modal').foundation('close');
          admin.closeModal('#add-page-modal');
          admin.refreshPanel();
        }
      );
    },
    /**
     * Получает данные о категории с сервера и заполняет ими поля в окне.
     */
    editCategory: function (id) {
      admin.ajaxRequest({
        mguniqueurl: "action/getPageData",
        id: id
      },
      page.fillFields(),
        $('.add-product-form-wrapper .add-category-form')
        );
    },
    /**
     * Удаляет страницу из БД сайта
     */
    deletePage: function (id) {
      if (confirm(lang.SUB_CATEGORY_DELETE + '?')) {
        admin.ajaxRequest({
          mguniqueurl: "action/deletePage",
          id: id
        },
        function (response) {
          admin.indication(response.status, response.msg);
          admin.refreshPanel();
        }
        );
      }

    },
    /**
     * Заполняет поля модального окна данными.
     */
    fillFields: function () {
      return (function (response) {
        page.supportCkeditor = response.data.html_content;

        $('input').removeClass('error-input');
        $('input[name=title]').val(response.data.title);
        $('input[name=url]').val(response.data.url);
        $('select[name=parent]').val(response.data.parent);
        $('select[name=parent]').val(response.data.parent);
        $('input[name=invisible]').prop('checked', false);
        $('input[name=invisible]').val('false');
        if (response.data.invisible == 1) {
          $('input[name=invisible]').prop('checked', true);
          $('input[name=invisible]').val('true');
        }
        $('input[name=meta_title]').val(response.data.meta_title);
        $('textarea[name=html_content]').val(response.data.html_content);

        $('input[name=meta_keywords]').val(response.data.meta_keywords);
        $('textarea[name=meta_desc]').val(response.data.meta_desc);
        $('.symbol-count').text($('textarea[name=meta_desc]').val().length);
        $('.save-button').attr('id', response.data.id);
        //$('textarea[name=html_content]').ckeditor(function() {});
      })
    },
    /**
     * Чистит все поля модального окна.
     */
    clearFileds: function () {

      $('input[name=title]').val('');
      $('input[name=url]').val('');
      $('select[name=parent]').val('0');
      $('input[name=invisible]').prop('checked', false);
      $('input[name=invisible]').val('false');
      $('textarea[name=html_content]').val('');
      $('input[name=meta_title]').val('');
      $('input[name=meta_keywords]').val('');
      $('textarea[name=meta_desc]').val('');
      $('.symbol-count').text('0');
      $('.save-button').attr('id', '');

      // Стираем все ошибки предыдущего окна если они были.
      $('.errorField').css('display', 'none');
      $('.error-input').removeClass('error-input');
      page.supportCkeditor = "";
    },
    /**
     * Обновляет статус видимости всех страниц  в меню
     */
    refreshVisible: function () {
      admin.ajaxRequest({
        mguniqueurl: "action/refreshVisiblePage"
      },
      function (response) {
        admin.indication(response.status, response.msg);
        admin.refreshPanel();
      });
    },
    /**
     * Выполняет выбранную операцию со всеми отмеченными страницами
     * operation - тип операции.
     */
    runOperation: function (operation) {

      var page_id = [];
      $('.page-tree input[name=page-check]').each(function () {
        if ($(this).prop('checked')) {
          page_id.push($(this).parents('tr').data('id'));
        }
      });
      if (confirm(lang.RUN_CONFIRM)) {
        admin.ajaxRequest({
          mguniqueurl: "action/operationPage",
          operation: operation,
          page_id: page_id,
        },
          function (response) {
            admin.refreshPanel();
          }
        );
      }
    },
    // сохранение порядка сортировки
    saveSort: function() {
      data = [];   

      // составление массива строк с индефикаторами, для отправки на сервер, для сохранения позиций
      $.each( $('.section-page .main-table tbody tr'), function() {
        data.push($(this).data('id'));
      });         

      admin.ajaxRequest({
        mguniqueurl: "action/saveSortableTable",
        data: data,
        type: 'page'
      },
      function (response) {
        admin.indication(response.status, response.msg);
        admin.refreshPanel();
      });
    },
    // скрывает спрятанные пункты
    hidePageRows: function() {
      for(var i=0; i<page.openedPageAdmin.length; i++){
        $('#toHide-'+page.openedPageAdmin[i]).click();
      }
    },
    // для повторной инициализации всех необходимых скриптов
    repeatInit: function() {
      page.sortableInit();
      page.hidePageRows();
    }
  }

})();

// инициализация модуля при подключении
page.init();


var fixHelperPage = function(e, ui) {
  // ui.children().each(function() {
    // $(this).width($(this).width()/2);
    // $('.main-table').html();
  // });

  trStyle = "color:#1585cf!important;background-color:#fff!important;";

  // берем id текущей строки
  var id = $(ui).data('id');
  // достаем уровень вложенности данной строки
  var level = $(ui).data('level');
  level++;

  // берем порядковый номер текущей строки
  thisSortNumber = $(ui).data('sort');

  uiq = '<div><table><tr style="'+trStyle+'">'+$(ui).html()+'</tr>';

  group = $(ui).data('group');

  var trCount = $('.section-page .main-table tbody tr').length;
  for(i = thisSortNumber; i < trCount; i++) {
    if(($('.section-page .main-table tbody tr:eq('+i+')').hasClass(group)) || (($('.section-page .main-table tbody tr:eq('+i+')').data('level') < level))) {
      break;
    } else {
      if(($('.section-page .main-table tbody tr:eq('+i+')').data('level') >= level)) {
        uiq += '<tr style="'+trStyle+'display:'+$('.section-page .main-table tbody tr:eq('+i+')').css('display')+'">'+$('.section-page .main-table tbody tr:eq('+i+')').html()+'</tr>';
        $('.section-page .main-table tbody tr:eq('+i+')').css('display','none');
      }
    }
  }

  uiq += '</table></div>';

  return uiq;
};