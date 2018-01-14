var deliveryCalcDelLin = (function(){
  return {
    deliveryId: 0,
    pluginName: 'oik-delivery-dellin',
    init: function(){ 
      deliveryCalcDelLin.deliveryId = $('.delivery-addition-info input[name=dellin_delivery_id]').val();            
      
      if($('.delivery-addition-info.delivery'+deliveryCalcDelLin.deliveryId+' .deliveryInfo').attr("show")=="1"){ 
        deliveryCalcDelLin.showPlugin(deliveryCalcDelLin.deliveryId);
        $('.delivery-details-list input[value='+deliveryCalcDelLin.deliveryId+']').trigger('click');
      }
      
      $('body').on('click', '.delivery-details-list input[name="delivery"]', function(e){         
        var deliveryId = e.target.defaultValue;        
        deliveryCalcDelLin.showPlugin(deliveryId);
      });
      
      $('body').on('click', '.delivery-details-list a.oik-dellin-clear-field', function(){ 
        $('#oik-dellin-delivery-city').val('');
        $('.delivery'+deliveryCalcDelLin.deliveryId+' input[name=arrivalPoint]').val('');
        return false;
      });
      
      $( "#oik-dellin-delivery-city" ).autocomplete({
        appendTo: '.delivery'+deliveryCalcDelLin.deliveryId+' .popupList',
        minLength: 2,
        source: function(request, response){
          $.ajax({
            type: "POST",
            url: mgBaseDir+"/ajaxrequest",            
            cache: false,
            dataType: 'json',
            data: {
              pluginHandler: deliveryCalcDelLin.pluginName, // имя папки в которой лежит данный плагин
              actionerClass: 'Pactioner', // класс Pactioner в Pactioner.php - в папке плагина
              action: "getCitiesList", // название действия в пользовательском  классе  
              term: request.term
            },            
            success: function(resp){          
              if(resp.data == 'null'){
                $('button.add-custom-city').remove();
                $('#mg-geolocation-find-city').after('<button class="add-custom-city">Выбрать</button>');
              }else{
                var data =  eval("(" + resp.data + ")");
                response(data);
              }
            }
          });
        },
        focus:function(event,ui){
          event.preventDefault();          
          $('#oik-dellin-delivery-city').val(ui.item.label);
          $('.delivery'+deliveryCalcDelLin.deliveryId+' input[name=arrivalPoint]').val(ui.item.id);
        },
        select: function(event,ui){
          event.preventDefault();
          $('.delivery'+deliveryCalcDelLin.deliveryId+' input[name=arrivalPoint]').val(ui.item.id);
          deliveryCalcDelLin.onSelectDerivalPoint();
        }
      });
      
      // Сохраняет базовые настроки пдагина
      $('.admin-center').on('click', '.section-'+deliveryCalcDelLin.pluginName+' .base-setting-save', function() {
        var value = '';
        var obj = '{';
        $('.section-'+deliveryCalcDelLin.pluginName+' .list-option input, .section-'+deliveryCalcDelLin.pluginName+' .list-option select').each(function() { 
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

        data.nameEntity = $(".section-blog .base-settings input[name=nameEntity]").val();

        admin.ajaxRequest({
          mguniqueurl: "action/saveBaseOption", // действия для выполнения на сервере
          pluginHandler: deliveryCalcDelLin.pluginName, // плагин для обработки запроса
          data: data 
        },

        function(response) {
          admin.indication(response.status, response.msg);      
        }

        );
        
      });
    },
    showPlugin: function(id){            
      if(id == deliveryCalcDelLin.deliveryId){
        plugin = $('.delivery-details-list li .delivery-addition-info.delivery' 
                + deliveryCalcDelLin.deliveryId);
        
        if(plugin.length<=0){          
          plugin_html = $('.delivery-addition-info.delivery'+deliveryCalcDelLin.deliveryId);          
          $('.delivery-details-list :checked').parents("label").append(plugin_html);          
          $('.delivery-addition-info.delivery'+deliveryCalcDelLin.deliveryId).show();
        }
        else{
          plugin.show();
        }        
      }
      else{
        $('.delivery-addition-info.delivery'+deliveryCalcDelLin.deliveryId).hide();                              
      }
    },
    
    //Функция вызывается после выбора города доставки.    
    onSelectDerivalPoint: function(){           
      var arrivalPoint = $('.delivery-details-list .delivery-addition-info.delivery' 
              + deliveryCalcDelLin.deliveryId + ' input[name=arrivalPoint]').val();     
      var arrivalCity = $('.delivery-details-list .delivery-addition-info.delivery' 
              + deliveryCalcDelLin.deliveryId + ' #oik-dellin-delivery-city').val();     
      
      $.ajax({
        type: "POST",
        url: mgBaseDir+"/ajaxrequest",
        dataType: "json",
        cache: false,
        data: {
          pluginHandler: 'oik-delivery-dellin', // имя папки в которой лежит данный плагин
          actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
          action: "getPrice", // название действия в пользовательском  классе Comments
          deliveryId: deliveryCalcDelLin.deliveryId,
          arrivalPoint: arrivalPoint,
          arrivalCity: arrivalCity
        },        
        success: function(response){ 
          $('.delivery-details-list input[value='+deliveryCalcDelLin.deliveryId+']').trigger('click'); 
        }
      });
    }
  }
})();

$(document).ready(function(){
  deliveryCalcDelLin.init();
});