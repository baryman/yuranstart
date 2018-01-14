var deliveryCalcDelLin = (function(){
  return {
    pluginName: 'oik-delivery-dellin',
    init: function(){          
      // Сохраняет базовые настроки плагина
      $('.admin-center').on('click', '.section-'+deliveryCalcDelLin.pluginName+' .base-setting-save', function() {
        var data = deliveryCalcDelLin.getData('.section-'+deliveryCalcDelLin.pluginName+' .list-option input, .section-'+deliveryCalcDelLin.pluginName+' .list-option select');

        data.nameEntity = $(".section-blog .base-settings input[name=nameEntity]").val();

        admin.ajaxRequest({
          mguniqueurl: "action/saveBaseOption", // действия для выполнения на сервере
          pluginHandler: deliveryCalcDelLin.pluginName, // плагин для обработки запроса
          data: data 
        },
        function(response) {
          admin.indication(response.status, response.msg);      
        });
        
      });
    },

    getData: function(fields) {
      var data = {};
      $(fields).each(function(){
        switch($(this).attr('type')) {
           // обработка чекбоксов
           case 'checkbox':
            data[$(this).attr('name')] = $(this).prop('checked');
            break;
           // обработка текстовых полей
           default:
            data[$(this).attr('name')] = $(this).val();
            break;
          }
        });
      return data;
    }
  }
})();
deliveryCalcDelLin.init();