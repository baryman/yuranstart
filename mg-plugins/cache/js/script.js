 /* 
 * Модуль  cacheModule, подключается на странице настроек плагина.
 */

var cacheModule = (function() {
  
  return { 
    lang: [], // локаль плагина 
    init: function() {      
      
      // установка локали плагина 
      admin.ajaxRequest({
          mguniqueurl: "action/seLocalesToPlug",
          pluginName: 'cache'
        },
        function(response) {
          cacheModule.lang = response.data;        
        }
      );        
       
    
    // Сохраняет базовые настроки запись
      $('.admin-center').on('click', '.section-cache .base-setting-save', function() {
       
        var obj = '{';
        $('.section-cache .list-option input, .section-cache .list-option select').each(function() {     
          obj += '"' + $(this).attr('name') + '":"' + $(this).val() + '",';
        });
        obj += '}';    

        //преобразуем полученные данные в JS объект для передачи на сервер
        var data =  eval("(" + obj + ")");
        
        data.no_cache = $('.section-cache .list-option textarea').val();

        admin.ajaxRequest({
          mguniqueurl: "action/saveBaseOption", // действия для выполнения на сервере
          pluginHandler: 'cache', // плагин для обработки запроса
          data: data // id записи
        },

        function(response) {
          admin.indication(response.status, response.msg);          
        }

        );
        
      }); 
      
      // Показывает панель с настройками.
      $('.admin-center').on('click', '.section-cache .show-property-order', function() {
        $('.property-order-container').slideToggle(function() {     
          $('.widget-table-action').toggleClass('no-radius');
        });
      });
      
      // удаляет кэш из папки
      $('.admin-center').on('click', '.section-cache .clear-cache-btn', function() {
        admin.ajaxRequest({
          mguniqueurl: "action/resetCache", 
          pluginHandler: 'cache',
        },
        function(response) {
          admin.indication(response.status, response.msg);          
        }
        );
      });
      

    },
    /*
     * Переключатель активности
     */
    visibleEntity:function(id, val) {
      admin.ajaxRequest({
        mguniqueurl:"action/visibleEntity",
        pluginHandler: 'cache', // плагин для обработки запроса
        id: id,
        invisible: val,
      },
      function(response) {
        admin.indication(response.status, response.msg);
      } 
      );
    },  
    
  }
})();

cacheModule.init();
