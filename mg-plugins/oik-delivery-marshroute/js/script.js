var deliveryCalcMarshroute = (function(){
  return {
    deliveryId: 0,
    pluginName: 'oik-delivery-marshroute',
    courier: 0,
    init: function(){ 
      deliveryCalcMarshroute.deliveryId = $('.delivery-addition-info input[name=marshroute_delivery_id]').val();            
      
      if($('.delivery-addition-info.delivery'+deliveryCalcMarshroute.deliveryId+' .deliveryInfo').attr("show")=="1"){ 
        deliveryCalcMarshroute.showPlugin(deliveryCalcMarshroute.deliveryId);
        $('.delivery-details-list input[value='+deliveryCalcMarshroute.deliveryId+']').trigger('click');
      }
      
      $('body').on('click', '.delivery-details-list input[name="delivery"]', function(e){         
        var deliveryId = e.target.defaultValue;        
        deliveryCalcMarshroute.showPlugin(deliveryId);        
      });
      
      $('body').on('click', '.delivery-details-list a.oik-marshroute-clear-field', function(){ 
        $('#oik-marshroute-delivery-city').val('');
        $('select#oik-marshroute-delivery-correct').hide();
        $('select#oik-marshroute-delivery-variant').hide();
        $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=arrivalPoint]').val('');
        $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=deliveryType]').val('');
        return false;
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
          $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=arrivalPoint]').val(kladr);
          deliveryCalcMarshroute.getDeliveryVariant('', kladr);
        }
      });
      
      $('body').on('change', 'select#oik-marshroute-delivery-variant', function(){
        var kladr = $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=arrivalPoint]').val();
        var typeId = $(this).val();
        var city = '';
        
        if($('select#oik-marshroute-delivery-correct').val() == 0){
          city = $('#oik-marshroute-delivery-city').val();
        }else{
          city = $('select#oik-marshroute-delivery-correct option:selected').text();
        }
        
        var address = city + ' ' + $(this).find('option:selected').text();
        
        if($(this).find('option:selected').attr('courier') == 1){
          $('.address4courier').show();
          $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=deliveryCourier]').val(1);
          deliveryCalcMarshroute.courier = 1;
        }else{
          $('.address4courier').hide();
          $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=deliveryCourier]').val(0);
          deliveryCalcMarshroute.courier = 0;
        }
        
        $('textarea[name=address]').text(address);
        
        if(kladr != 0 && typeId != 0){
          $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=deliveryType]').val(typeId);
          deliveryCalcMarshroute.onSelectDerivalPoint(kladr, typeId);
        }
      });
      
      $('body').on('submit', '.payment-option form', function(){
        $('.second-line-empty-error').empty();
        $('.second-line-empty-error').hide();
        var submit = true;
        
        if(deliveryCalcMarshroute.courier == 0){
          return true;
        }
        
        if(!$('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=street]').val()){
          $('.second-line-empty-error').append("Не заполнено поле город<br />");
          $('.second-line-empty-error').show();
          submit = false;
        }
        
        if(!$('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=house]').val()){
          $('.second-line-empty-error').append("Не заполнено поле дом<br />");
          $('.second-line-empty-error').show();
          submit = false;
        }
        
        if(!$('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=flat]').val()){
          $('.second-line-empty-error').append("Не заполнено поле квартира<br />");
          $('.second-line-empty-error').show();
          submit = false;
        }
        
        if(!$('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=index]').val()){
          $('.second-line-empty-error').append("Не заполнено поле индекс");
          $('.second-line-empty-error').show();
          submit = false;
        }
        
        if(!submit){
          return false;
        }
        
        var address = $('textarea[name=address]').text() + '. Для курьера: '
                +' Улица: '+ $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=street]').val()
                +' Дом: '+ $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=house]').val()
                +' Квартира: '+ $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=flat]').val()
                +' Индекс: '+ $('.delivery'+deliveryCalcMarshroute.deliveryId+' input[name=index]').val();
        
        $('textarea[name=address]').text(address);
      });
    },
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
          $('.delivery'+deliveryCalcMarshroute.deliveryId+' .deliveryInfo .error')
                  .empty();
            
          if(response.data.error.length > 0){
            $('.delivery'+deliveryCalcMarshroute.deliveryId+' .deliveryInfo .error')
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
              variant.append('<option value="'+element.delivery_code+'" \
                      courier="'+element.courier+'">'+element.name+'</option>');
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
    
    showPlugin: function(id){            
      if(id == deliveryCalcMarshroute.deliveryId){
        plugin = $('.delivery-details-list li .delivery-addition-info.delivery' 
                + deliveryCalcMarshroute.deliveryId);
        
        if(plugin.length<=0){          
          plugin_html = $('.delivery-addition-info.delivery'+deliveryCalcMarshroute.deliveryId);          
          $('.delivery-details-list :checked').parents("label").append(plugin_html);          
          $('.delivery-addition-info.delivery'+deliveryCalcMarshroute.deliveryId).show();
        }
        else{
          plugin.show();
        }        
      }
      else{
        $('.delivery-addition-info.delivery'+deliveryCalcMarshroute.deliveryId).hide();                              
      }
    },
    
    //Функция вызывается после выбора города доставки.    
    onSelectDerivalPoint: function(kladr, typeId){
      $.ajax({
        type: "POST",
        url: mgBaseDir+"/ajaxrequest",
        dataType: "json",
        cache: false,
        data: {
          pluginHandler: 'oik-delivery-marshroute', // имя папки в которой лежит данный плагин
          actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
          action: "getPrice", // название действия в пользовательском  классе Comments
          deliveryId: deliveryCalcMarshroute.deliveryId,
          arrivalPoint: kladr,
          deliveryType: typeId
        },        
        success: function(response){
          $('.delivery-details-list input[value='+deliveryCalcMarshroute.deliveryId+']')
                  .trigger('click'); 
        }
      });
    }
  }
})();

$(document).ready(function(){
  deliveryCalcMarshroute.init();
});