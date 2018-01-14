/**
 * Модуль для  раздела "Пользователи".
 */
var user = (function () {
  return {


    /**
     * Инициализирует обработчики для кнопок и элементов раздела.
     */
    init: function() {
      $('select[name=blocked]').val('')
      // Вызов модального окна при нажатии на кнопку добавления пользователя.
      $('.admin-center').on('click', '.section-user .add-new-button', function(){
        user.openModalWindow('add');
      });

      // Вызов модального окна при нажатии на кнопку изменения пользователя.
      $('.admin-center').on('click', '.section-user .edit-row', function(){
        user.openModalWindow('edit', $(this).attr('id'));
      });


      // Удаление пользователя.
      $('.admin-center').on('click', '.section-user .delete-order', function(){
        user.deleteUser($(this).attr('id'));
      });

      // Сохранение продукта при на жатии на кнопку сохранить в модальном окне.
      $('body').on('click', '#add-user-modal .save-button', function(){
        user.saveUser($(this).attr('id'));
      });

      $('body').on('click', '.editPass', function(){
        user.editPassword();
      });

      // применение класса selected для строки, которой ставят галочку выделения
      $('body').on('click' ,'.select-row', function() {
        var id = $(this).parents('tr').attr('id');
        if($('#c'+id).prop('checked')) {
          $(this).parents('tr').removeClass('selected');
        } else {
          $(this).parents('tr').addClass('selected');
        }
      });

      // Выделить все страницы
      $('.admin-center').on('click', '.section-user .check-all-page', function () {
        $('.section-user .main-table tbody input').prop('checked', 'checked');
        $('.section-user .main-table tbody input').val('true');
        $('.section-user .main-table tbody tr').addClass('selected');

        $(this).addClass('uncheck-all-page');
        $(this).removeClass('check-all-page');
      });
      // Снять выделение со всех  страниц.
      $('.admin-center').on('click', '.section-user .uncheck-all-page', function () {
        $('.section-user .main-table tbody input').prop('checked', false);
        $('.section-user .main-table tbody input').val('false');
        $('.section-user .main-table tbody tr').removeClass('selected');
        
        $(this).addClass('check-all-page');
        $(this).removeClass('uncheck-all-page');
      });

      // Устанавливает количество выводимых записей в этом разделе.
      $('.admin-center').on('change', '.section-user .countPrintRowsUser', function(){
        var count = $(this).val();
        user.printCountRow(count);
      });
      
      // Показывает панель с фильтрами.
      $('.admin-center').on('click', '.section-user .show-filters', function () {
        $('.filter-container').slideToggle(function () {
          $('.widget-table-action').toggleClass('no-radius');
        });
      });
      
       // Применение выбранных фильтров
      $('.admin-center').on('click', '.section-user .filter-now', function () {
        user.getUserByFilter();
        // admin.refreshPanel();
        return false;
      });
      
       // Сброс фильтров.
      $('.admin-center').on('click', '.section-user .refreshFilter', function () {
        admin.clearGetParam();
        // admin.refreshPanel();
        admin.show("users.php", "adminpage", "refreshFilter=1");
        return false;
      });
      
      // Автоматический ввод email
      $('.admin-center').on('click', '.section-user input[name=email]', function () {
        
      });

      // Выполнение выбранной операции с выделенными пользователями
      $('.admin-center').on('click', '.section-user .run-operation', function(){
        user.runOperation($('.user-operation').val());
      });

    },
    printCountRow: function(count) {
      admin.ajaxRequest({
        mguniqueurl: "action/setCountPrintRowsUser",
        count: count
      },
      (function(response) {
        admin.refreshPanel();
      })
      );
    },

    /**
     * Открывает модальное окно.
     * type - тип окна, либо для создания нового пользователя, либо для редактирования старого.
     */
    openModalWindow: function(type, id) {

      switch (type) {
        case 'edit':{
          $('.users-table-wrapper .user-table-icon').text(lang.TITLE_USER_EDIT);
          user.editUser(id);
          $('.editorPas').css('display','none');
          $('.controlEditorPas').css('display','block');
          break;
        }
        case 'add':{
          $('.users-table-wrapper .user-table-icon').text(lang.TITLE_USER_NEW);
          user.clearFileds();
          $('.controlEditorPas').css('display','none');
          $('.editorPas').css('display','block');
          break;
        }
        default:{
          user.clearFileds();
          break;
        }
      }

      // Вызов модального окна.
      admin.openModal('#add-user-modal');

    },


    /**
     *  Проверка заполненности полей, для каждого поля прописывается свое правило.
     */
    checkRulesForm: function() {
      $('.errorField').css('display','none');
      $('input').removeClass('error-input');
      var error = false;
      // проверка email.
      
      if(!/^[-._a-zA-Z0-9]+@(?:[a-zA-Z0-9][-a-zA-Z0-9]{0,61}\.)+[a-zA-Z]{2,6}$/.test($('input[name=email]').val()) || !$('input[name=email]').val()){
        $('input[name=email]').parent(".columns").find('.errorField').css('display','block');
        $('input[name=email]').css('border-color','red');
        error = true;
      } else {
        $('input[name=email]').css('border-color','#ccc');
      }

      // если активен блок смены пароля
      if($('.editorPas').css('display')=='block'){
        // проверка пароля, в нем не должно быть спец символов и он должен быть  не менее 5 символов.
        if(!admin.regTest(1,$('input[name=pass]').val()) || !$('input[name=pass]').val() || $('input[name=pass]').val().length<5){
          $('input[name=pass]').parent(".columns").find('.errorField').css('display','block');
          $('input[name=pass]').css('border-color','red');
          error = true;
        } else {
          $('input[name=pass]').css('border-color','#ccc');
        }

        // повторение пароля.
        if($('input[name=passconfirm]').val()!=$('input[name=pass]').val()){
          $('input[name=passconfirm]').parent(".columns").find('.errorField').css('display','block');
          $('input[name=passconfirm]').css('border-color','red');
          error = true;
        } else {
          $('input[name=passconfirm]').css('border-color','#ccc');
        }
      }

      if(error == true){
        return false;
      }

      return true;
    },
    
    /**
     * Получает данные из формы фильтров и перезагружает страницу
     */
    getUserByFilter: function () {
      var request = $("form[name=filter]").formSerialize();
      admin.show("users.php", "adminpage", request + '&applyFilter=1');
      return false;
    },

    /**
     * Сохранение изменений в модальном окне пользователя.
     * Используется и для сохранения редактированных данных и для сохранения нового продукта.
     * id - идентификатор пользователя, может отсутствовать если производится добавление нового товара.
     */
    saveUser: function(id) {

      // Если поля не верно заполнены, то не отправляем запрос на сервер.
      if(!user.checkRulesForm()){
        return false;
      }

      // Пакет характеристик пользователя.
      var packedProperty = {
        mguniqueurl:"action/saveUser",
        id: id,
        email: $('#add-user-modal input[name=email]').val(),
        pass: $('#add-user-modal input[name=pass]').val(),
        name: $('#add-user-modal input[name=name]').val(),
        birthday: $('#add-user-modal input[name=birthday]').val(),
        sname: $('#add-user-modal input[name=sname]').val(),
        address: $('#add-user-modal textarea[name=address]').val(),
        phone: $('#add-user-modal input[name=phone]').val(),
        blocked: $('#add-user-modal select[name=blocked]').val(),
        activity: $('#add-user-modal select[name=activity]').val(),
        role: $('#add-user-modal select[name=role]').val()
      }

      // отправка данных на сервер для сохранения
      admin.ajaxRequest(packedProperty,
        (function(response) {
          admin.indication(response.status, response.msg);

          if(response.status=='error'){

            return false;
          }

          // Закрываем окно
          admin.closeModal('#add-user-modal');
          admin.refreshPanel();
        })
      );
    },

    /**
     * Получает данные о пользователе с сервера и заполняет ими поля в окне.
     */
    editUser: function(id) {
      admin.ajaxRequest({
        mguniqueurl:"action/getUserData",
        id: id
      },
      user.fillFields(),
      $('.widget-table-body .add-user-form')
      );
    },


    /**
     * Удаляет пользователя из БД сайта и таблицы в текущем разделе
     */
    deleteUser: function(id) {
      if(confirm(lang.DELETE+'?')){
        admin.ajaxRequest({
          mguniqueurl:"action/deleteUser",
          id: id
        },
        function(response) {
          admin.indication(response.status, response.msg);
          if (response.status == 'success') {
            admin.refreshPanel();
          }
        }
        );
      }

    },


   /**
    * Заполняет поля модального окна данными
    */
    fillFields:function() {
      return (function(response) {
        $('#add-user-modal input').removeClass('error-input');
        $('#add-user-modal input[name=email]').val(response.data.email);
        $('#add-user-modal input[name=name]').val(response.data.name);
        $('#add-user-modal input[name=sname]').val(response.data.sname);
        $('#add-user-modal input[name=birthday]').val('');
        if (response.data.birthday && response.data.birthday != '0000-00-00' ) {
          var date = response.data.birthday.split('-');
          date = new Date(date[0], date[1]-1, date[2]);
          var day = date.getDate();
          day = (day < 10) ? '0' + day : day;
          var month = date.getMonth()+1;
          month = (month < 10) ? '0' + month : month;
          var year = date.getFullYear();
          var formattedDate = day + '.' + month + '.' + year;
          $('input[name=birthday]').val(formattedDate);
        }
        $('#add-user-modal input[name=phone]').val(response.data.phone);
        $('#add-user-modal textarea[name=address]').val(response.data.address);
        $('#add-user-modal .activity option[value="'+response.data.activity+'"]').prop("selected", "selected");
        $('#add-user-modal select[name=role] option[value="'+response.data.role+'"]').prop("selected", "selected");
        $('#add-user-modal select[name=blocked] option[value="'+response.data.blocked+'"]').prop("selected", "selected");
        $('#add-user-modal .ip-registration').html('');
        if (response.data.ip != '') {
        $('#add-user-modal .ip-registration').html('<p>ip: '+response.data.ip+'</p>');
        }
        $('#add-user-modal .save-button').attr('id',response.data.id);
        $('#add-user-modal .errorField').css('display','none');
        $('#add-user-modal .editPass').text('Изменить');
      })
    },


   /**
    * Чистит все поля модального окна
    */
    clearFileds:function() {
      $('#add-user-modal input[name=email]').val(''),
      $('#add-user-modal input[name=pass]').val(''),
      $('#add-user-modal input[name=name]').val(''),
      $('#add-user-modal input[name=passconfirm]').val('');
      $('#add-user-modal input[name=sname]').val(''),
      $('#add-user-modal input[name=birthday]').val(''),
      $('#add-user-modal textarea[name=address]').val(''),
      $('#add-user-modal input[name=phone]').val(''),
      $('#add-user-modal select[name=blocked]').val(''),
      $('#add-user-modal select[name=activity]').val(''),
      $('#add-user-modal select[name=role]').val('')
      $('#add-user-modal .ip-registration').html('');
      $('#add-user-modal .save-button').attr('id','');
      $('#add-user-modal .editorPas').css('display', 'none');
      $('#add-user-modal .role option[value="2"]').prop("selected", "selected");  
      $('#add-user-modal select[name=blocked] option[value="0"]').prop("selected", "selected");
      $('#add-user-modal select[name=activity] option[value="1"]').prop("selected", "selected");
      // Стираем все ошибки предыдущего окна если они были.
      $('#add-user-modal .errorField').css('display','none');
      $('#add-user-modal .error-input').removeClass('error-input');
      includeJS(mgBaseDir+'/mg-core/script/jquery.maskedinput.min.js');
    },


   /**
    * открывает блок для смены пароль
    */
    editPassword: function() {
      $('#add-user-modal .editorPas').slideToggle('show', function() {

        $('#add-user-modal .editorPas').css('display')=='block'
          ? $('#add-user-modal .editPass').text(lang.USER_PASS_NO_EDIT)
          : $('#add-user-modal .editPass').text(lang.USER_PASS_EDIT);

        }
      );
    },
    /**
     * Выполняет выбранную операцию со всеми отмеченными пользователями
     * operation - тип операции.
     */
    runOperation: function(operation) { 
      
      var users_id = [];
      $('.main-table tbody tr').each(function(){              
        if($(this).find('input').prop('checked')){  
          users_id.push($(this).attr('id'));
        }
      });  

      if (confirm(lang.RUN_CONFIRM)) {        
        admin.ajaxRequest({
          mguniqueurl: "action/operationUser",
          operation: operation,
          users_id: users_id,
        },
        function(response) {     
          admin.indication(response.status, response.msg);
          if(response.data.filecsv) {
            setTimeout(function() {
              if (confirm('Файл с выгрузкой создан в корне сайта под именем: '+response.data.filecsv+'. Желаете скачать сейчас?')){
              location.href = mgBaseDir+'/'+response.data.filecsv;
            }}, 2000);            
           }
          admin.refreshPanel();  
         
        }
        );
      }
       

    }
  }
})();

// инициализация модуля при подключении
user.init();