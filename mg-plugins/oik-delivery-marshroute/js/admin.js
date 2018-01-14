var deliveryCalcMarshroute = (function(){
  return {
    deliveryId: 0,
    pluginName: 'oik-delivery-marshroute',
    init: function(){ 
      deliveryCalcMarshroute.deliveryId = $('.delivery-addition-info input[name=marshroute_delivery_id]').val();            
      
      if($('.delivery-addition-info.delivery'+deliveryCalcMarshroute.deliveryId+' .deliveryInfo').attr("show")=="1"){ 
        deliveryCalcMarshroute.showPlugin(deliveryCalcMarshroute.deliveryId);
        $('.delivery-details-list input[value='+deliveryCalcMarshroute.deliveryId+']').trigger('click');
      }
      
      // Сохраняет базовые настроки плагина
      $('.admin-center').on('click', '.section-'+deliveryCalcMarshroute.pluginName+' .base-setting-save', function() {
        var value = '';
        var obj = '{';
        $('.section-'+deliveryCalcMarshroute.pluginName+' .list-option input, .section-'+deliveryCalcMarshroute.pluginName+' .list-option select').each(function() { 
          if($(this).attr('type') == 'checkbox'){
            if($(this).is(':checked')){
              value = 1;
            }else{
              value = 0;
            }          
          }else{
            value = $(this).val();
          }
          
          obj += '"' + $(this).attr('name') + '":"' + value + '",';
        });
        obj += '}';    

        //преобразуем полученные данные в JS объект для передачи на сервер
        var data =  eval("(" + obj + ")");

        admin.ajaxRequest({
          mguniqueurl: "action/saveBaseOption", // действия для выполнения на сервере
          pluginHandler: deliveryCalcMarshroute.pluginName, // плагин для обработки запроса
          data: data 
        },
        function(response) {
          admin.indication(response.status, response.msg);      
        });
        
      });
      
      $('body').on('click', '.oik-marshroute-apply-city', function(){
        $('select#oik-marshroute-delivery-correct').hide();
        $('select#oik-marshroute-delivery-variant').hide();
        var deliveryCity = $('#oik-marshroute-delivery-city').val();
        deliveryCalcMarshroute.getDeliveryVariant(deliveryCity, '');
      });
      
      $('body').on('change', 'select#oik-marshroute-delivery-correct', function(){
        var kladr = $(this).val();
        
        if(kladr != 0){
          $('.'+deliveryCalcMarshroute.pluginName+' input[name=arrivalPoint]').val(kladr);
          deliveryCalcMarshroute.getDeliveryVariant('', kladr);
        }
      });
      
      $('body').on('change', 'select#oik-marshroute-delivery-variant', function(){
        var kladr = $('.'+deliveryCalcMarshroute.pluginName+' input[name=arrivalPoint]').val();
        var typeId = $(this).val();
        //!!!
        //Добавлять не только адрес но и сам город
        var address = $('select#oik-marshroute-delivery-correct option:selected').text() + ' '
                + $(this).find('option:selected').text();
        $('input[name=address]').val(address);
        
        if(kladr != 0 && typeId != 0){
          $('.'+deliveryCalcMarshroute.pluginName+' input[name=deliveryType]').val(typeId);
          deliveryCalcMarshroute.onSelectDerivalPoint(kladr, typeId);
        }
      });
    },
    //Получение списка подходящих городов или точек доставки в городе
    getDeliveryVariant: function(deliveryCity, kladr){
      $.ajax({
        type: "POST",
        url: mgBaseDir+"/ajaxrequest",
        dataType: "json",
        cache: false,
        data: {
          pluginHandler: 'oik-delivery-marshroute', // имя папки в которой лежит данный плагин
          actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
          action: "getCityVariant", // название действия в пользовательском  классе Comments
          deliveryId: deliveryCalcMarshroute.deliveryId,
          deliveryCity: deliveryCity,
          deliveryKladr: kladr
        },        
        success: function(response){
          $('.'+deliveryCalcMarshroute.pluginName+' span.error')
                  .empty();
            
          if(response.data.error.length > 0){
            $('.'+deliveryCalcMarshroute.pluginName+' span.error')
                    .text(response.data.error);
            return;
          }
            
          if(response.data.correct.length > 1){
            var correct = $('select#oik-marshroute-delivery-correct');
            correct.empty();
            correct.append('<option value="0">Уточните населенный пункт</option>');
            response.data.correct.forEach(function(element, index, array){
              correct.append('<option value="'+element.kladr+'">'+element.name+'</option>');
            });
            correct.show();
          }else{
            var variant = $('select#oik-marshroute-delivery-variant');
            variant.empty();
            variant.append('<option value="0">Выберите вариант доставки</option>');
            response.data.variant.forEach(function(element, index, array){
              variant.append('<option value="'+element.delivery_code+'">'+element.name+'</option>');
            });
            variant.show();
            $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=arrivalPoint]')
                    .val(response.data.kladr);
          }
          
          $('.delivery-details-list input[value='+deliveryCalcMarshroute.deliveryId+']')
                  .trigger('click'); 
        }
      });
    },
    //Функция вызывается после выбора города доставки.    
    onSelectDerivalPoint: function(kladr, typeId){           
      $.ajax({
        type: "POST",
        url: mgBaseDir+"/ajaxrequest",
        dataType: "json",
        cache: false,
        data: {
          admin: 1,
          pluginHandler: deliveryCalcMarshroute.pluginName, // имя папки в которой лежит данный плагин
          actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
          action: "getPrice", // название действия в пользовательском  классе Comments
          deliveryId: $('#delivery option:selected').attr('name'),
          arrivalPoint: kladr,
          deliveryType: typeId,
          orderItems: order.orderItems
        },        
        success: function(response){   
          $("#deliveryCost").val(response.data.deliverySum);
          order.calculateOrder();
        }
      });
    }
  }
})();
deliveryCalcMarshroute.init();