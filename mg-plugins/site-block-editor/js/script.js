/**
 /* 
 * Модуль  siteBlockEditorJs, подключается на странице настроек плагина.
 */

var siteBlockEditorJs = (function() {
  // supportCkeditor: null,
  
  return { 
    lang: [], // локаль плагина 
    init: function() {           
      // Выводит модальное окно для добавления
      $('body').on('click', '.add-new-button', function() {    
        siteBlockEditorJs.showModal('add');
        siteBlockEditorJs.changeType('img');
      });
      
      // Выводит модальное окно для редактирования
      $('body').on('click', '.edit-row', function() {       
        var id = $(this).data('id');
        siteBlockEditorJs.showModal('edit', id);
        siteBlockEditorJs.changeType($(this).data('type'));        
      });
      
       // Сохраняет изменения в модальном окне
      $('body').on('click', '.slide-editor .save-button', function() {
        console.log('save');
        var id = $(this).data('id');    
        siteBlockEditorJs.saveField(id);        
      });
      
      // Удаляет запись
      $('body').on('click', '.delete-row', function() {
        var id = $(this).data('id');
        siteBlockEditorJs.deleteEntity(id);
      });      
      
      // Выбор картинки слайдера
      $('body').on('click', '.slide-editor .browseImage', function() {
        admin.openUploader('siteBlockEditorJs.getFile');
      });     

      
      // Смена типа слайда
      $('body').on('change', '.slide-editor select[name=type]', function() {
        siteBlockEditorJs.changeType($(this).val());
      });     
      
    },
    
    // открытие модального окна
    showModal: function(type, id) {
      try {
        if (CKEDITOR.instances['html_content']) {
          CKEDITOR.instances['html_content'].destroy();
        }
      } catch (e) {
      }

      switch (type) {
        case 'add':
          {
            siteBlockEditorJs.clearField();           
            break;
          }
        case 'edit':
          {
            siteBlockEditorJs.clearField();
            siteBlockEditorJs.fillField(id);
            break;
          }
        default:
          {
            break;
          }
      }

      admin.openModal('.slide-editor');      
      $('.slide-editor textarea').ckeditor();  

      $('.slide-editor textarea').ckeditor(function () {
        this.setData(siteBlockEditorJs.supportCkeditor);
      });
    },
                 
   /**
    * функция для приема файла из аплоадера
    */         
    getFile: function(file) {      
      $('.slide-editor  input[name="src"]').val(file.url);
    },      
            
   /**
    * Очистка модального окна
    */         
    clearField: function() {
      $('.slide-editor input').val('');
      $('.slide-editor textarea').text('');
      $('.slide-editor .id-entity').text('');
      $('.slide-editor .save-button').data('id','');
    },
            
    /**
     * Заполнение модального окна данными из БД
     * @param {type} id
     * @returns {undefined}
     */        
    fillField: function(id) {
      $('#block-code').html(id);
      admin.ajaxRequest({
        mguniqueurl: "action/getEntity", // действия для выполнения на сервере
        pluginHandler: 'site-block-editor', // плагин для обработки запроса
        id: id // id записи
      },
      
      function(response) {
        // $('.slide-editor select option[value="'+response.data.type+'"]').prop('selected','selected');
        siteBlockEditorJs.changeType(response.data.type);
        siteBlockEditorJs.supportCkeditor = response.data.content;
        if(response.data.type == 'img') {
          $('.slide-editor input[name="src"]').val(response.data.content);
        } else {
          $('.slide-editor textarea').val(response.data.content);  
        }
        $('.slide-editor input[name="comment"]').val(response.data.comment);
        $('.slide-editor input[name="alt"]').val(response.data.alt);
        $('.slide-editor input[name="title"]').val(response.data.title);
        $('.slide-editor input[name="href"]').val(response.data.href);
        $('.slide-editor input[name="width"]').val(response.data.width);
        $('.slide-editor input[name="height"]').val(response.data.height);
        $('.slide-editor input[name="class"]').val(response.data.class);
         
        $('.slide-editor .save-button').data('id',response.data.id);
      },
              
      $('.slide-editor .widget-table-body') // вывод лоадера в контейнер окна, пока идет загрузка данных
      
      );

    },
    
    /**
     * Сохранение данных из модального окна
     * @param {type} id
     * @returns {undefined}
     */        
    saveField: function(id) {
      var type = $('.slide-editor select[name=type]').val();   
      var comment = $('.slide-editor input[name=comment]').val();     
      var src = $('.slide-editor input[name="src"]').val();
      var alt = $('.slide-editor input[name="alt"]').val();
      var title = $('.slide-editor input[name="title"]').val();
      var href = $('.slide-editor input[name="href"]').val();
      var classV = $('.slide-editor input[name="class"]').val();
      var content = $('.slide-editor textarea').val();

      var width = $('.slide-editor input[name=width]').val();
      var height = $('.slide-editor input[name=height]').val();
            
      if(type=='img'){
        var content = src;
      }  
 
      admin.ajaxRequest({
        mguniqueurl: "action/saveEntity", // действия для выполнения на сервере
        pluginHandler: 'site-block-editor', // плагин для обработки запроса
        id: id,
        content: content,
        type: type,
        comment: comment,
        href: href,
        alt: alt,
        title: title,
        width: width, 
        class: classV,
        height: height,    
      },
      
      function(response) {
        admin.indication(response.status, response.msg);      
        admin.closeModal('.slide-editor');      
        siteBlockEditorJs.getRows();
        siteBlockEditorJs.getPublicCode(id);
      },
              
      $('.slide-editor .widget-table-body') // на месте кнопки
      
      );

    },
       
    /**    
     * Удаляет  строку сущности в главной таблице
     * @param {type} data - данные для вывода в строке таблицы
     */           
    deleteEntity: function(id) {
      if(!confirm(lang.DELETE+'?')){
        return false;
      }
      
      admin.ajaxRequest({
        mguniqueurl: "action/deleteEntity", // действия для выполнения на сервере
        pluginHandler: 'site-block-editor', // плагин для обработки запроса
        id: id               
      },
      function(response) {
        admin.indication(response.status, response.msg);
        siteBlockEditorJs.getRows();
      });
    },
    
    
    /**
    * Смена типа слайда
    */         
    changeType: function(type) {
       switch (type) {
        case 'img':
          {
            $('.type-img').show();
            $('.type-html').hide(); 
            $('.slide-editor select[name=type] option[value=img]').prop('selected','selected');
            break;
          }
        case 'html':
          {
            $('.type-img').hide();
            $('.type-html').show(); 
            $('.slide-editor select[name=type] option[value=html]').prop('selected','selected');
           
            break;
          }
        default:
          {
            break;
          }
      }
    },

    getPublicCode: function(id) {
      admin.ajaxRequest({
        mguniqueurl: "action/getPublicCode", // действия для выполнения на сервере
        pluginHandler: 'site-block-editor', // плагин для обработки запроса
        id: id,
      },
      function(response) {
        $('.site-block-editor[data-item='+id+']').replaceWith(response.data);
      });
    },

    getRows: function() {
      admin.ajaxRequest({
        mguniqueurl: "action/getRows", // действия для выполнения на сервере
        pluginHandler: 'site-block-editor', // плагин для обработки запроса
      },
      function(response) {
        var html = '';
        if(response.data == null) {
          html = '<tr class="no-results">\
                <td colspan="4" align="center">Шорткодов не обнаружено</td>\
            </tr>';
        } else {
          for(i = 0; i < response.data.length; i++) {
            html += '<tr data-id="'+response.data[i].id+'">\
                    <td>[site-block id='+response.data[i].id+']</td>\
                    <td>'+response.data[i].comment+'</td>\
                    <td class="type">';
            
            if(response.data[i].type == "img"){
              html += '<img height="50px" style="max-width:300px;" src="'+response.data[i].content+'">';
            } else {
              html += response.data[i].content;
            }
            html += '</td>\
                    <td class="actions text-right">\
                        <ul class="action-list"><!-- Действия над записями плагина -->\
                          <li class="edit-row" data-id="'+response.data[i].id+'" data-type="'+response.data[i].type+'"><a class="tool-tip-bottom fa fa-pencil" href="javascript:void(0);" title="Редактировать шорткод"></a></li>\
                          <li class="delete-row" data-id="'+response.data[i].id+'"><a class="tool-tip-bottom fa fa-trash" href="javascript:void(0);"  title="Удалить шорткод"></a></li>\
                        </ul>\
                    </td>\
                </tr>';
          }
        }
        $('.entity-table-tbody').html(html);
      });
    },
    
  }
})();

siteBlockEditorJs.init();