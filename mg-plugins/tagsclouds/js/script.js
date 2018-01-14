
/* 
 * Модуль  tagscloudsModule, подключается на странице настроек плагина.
 */

var tagscloudsModule = (function () {

  return {
    init: function () {

      // Сохраняет базовые настройки и передает значения в бд 
      $('.admin-center').on('click', '.section-tagsclouds .base-setting-save', function () {
        
        var data = {};
        $('.section-tagsclouds .list-option input').each(function(){
          if($(this).attr('type') == 'checkbox') {
            data[$(this).attr('name')] = $(this).prop('checked');
          } else {
            data[$(this).attr('name')] = $(this).val();
          }
        });

        admin.ajaxRequest({
          mguniqueurl: "action/saveBaseOption", // действия для выполнения на сервере
          pluginHandler: 'tagsclouds', // плагин для обработки запроса
          data: data,
        },
          function (response) {
            admin.indication(response.status, response.msg);
          }
        );
      });
    }
  };
})();

tagscloudsModule.init();



