var deliveryCalcDDelivery = (function(){
  return {
    deliveryId: 0,
    pluginName: 'mg-delivery-ddelivery',
    init: function(){ 
      deliveryCalcDDelivery.deliveryId = $('.delivery-addition-info input[name=dd_delivery_id]').val();               
      
      $('.delivery-details-list input[name="delivery"]').on('click',function(e){            
        var deliveryId = e.target.defaultValue;           
        deliveryCalcDDelivery.showPlugin(deliveryId);          
      });            
      
      if($('.delivery-addition-info.delivery'+deliveryCalcDDelivery.deliveryId+' .deliveryInfo').attr("show")=="1"){        
        deliveryCalcDDelivery.showPlugin(deliveryCalcDDelivery.deliveryId);         
        $('.delivery-details-list input[value='+deliveryCalcDDelivery.deliveryId+']').trigger('click');
      }            
      
      // Сохраняет базовые настроки пдагина
      $('.admin-center').on('click', '.section-'+deliveryCalcDDelivery.pluginName+' .base-setting-save', function() {
        var value = '';
        var obj = '{';
        $('.section-'+deliveryCalcDDelivery.pluginName+' .list-option input, .section-'+deliveryCalcDDelivery.pluginName+' .list-option select').each(function() { 
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
          pluginHandler: deliveryCalcDDelivery.pluginName, // плагин для обработки запроса
          data: data 
        },
        function(response) {
          admin.indication(response.status, response.msg);      
        });
        
      });
      
      $('body').on('click', 'a#openMap', function(){
        var companies = $(this).attr('data-companies');
        var cityId = $(this).attr('data-city');
//        $('.map-loader').css('display', 'block');
        
        $('body').append('\
          <div class="map" style="position: absolute; display: block; width: 800px;height:500px;top:50px;left: 50%;margin-left: -400px;z-index:500;">\
            <div class="head"><div class="close-map"><img alt="" src="'+mgBaseDir+'/mg-plugins/mg-delivery-ddelivery/images/close.png"></div></div>\
            <div class="body" style="height: 100%;position: relative;width: 100%;float: left;">\
              <div id="mapOverlay" class="mapper" style="width: 100%;height:100%;float: left;"></div>\
            </div>\
          </div>\
        ');
        
        $('body').on('click', '.close-map', function(){
          $('div.map').remove();
        });
        
        $(window).on("map:closed", function(){   
          $('.ddelivery-popup-select').hide();
          $('#overlay-ddelivery-popup-select').remove();
          $('div.map').remove();
        });
        
        $(window).on("map:loaded", function(){          
          DDeliveryMap.getPoints({city:cityId, companies: companies});
        });
        DDeliveryMap.init();        
        //deliveryCalcDDelivery.showMap(cityId, companies);
        return false;
      });
      
      $('body').on('click', 'a#send_order_ddelivery', function(){
        var result = {};        
        result.type = 2;               
        result.city_id = $('div.select-city-trigger input.cityId').val();
        result.company_id = $('select#courier_company option:selected').val();        
        
        if($('.delivery-details-list .ddelivery-popup-select input.radio-way').val() == 2){
          if(!deliveryCalcDDelivery.checkRequireField()){
            return false;
          }
        }
        
        $.ajax({
          type: "POST",
          url: mgBaseDir + "/ajaxrequest",
          data: {
            pluginHandler: 'mg-delivery-ddelivery', // имя папки в которой лежит данный плагин
            actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
            action: "setPrice", // название действия в пользовательском  классе Comments   
            deliveryId: deliveryCalcDDelivery.deliveryId,
            result: result
          },
          dataType: "json",
          cache: false,
          success: function (result) {
            var form = $('.delivery-details-list .ddelivery-popup-select form#courier_form');
            var deliveryCompany = form.find('select#courier_company option:selected');
            var deliveryInfo = 'Доставка: '+deliveryCompany.attr('data-name')+', '+deliveryCompany.attr('data-date');
            var address = 'Адрес: '+$('#ddelivery_container_place span.city').text()+', ';
            address += form.find('input[name=street]').val()+' ';  
            address += form.find('input[name=house]').val()+', ';  
            address += 'кв. '+form.find('input[name=flat]').val()+'';  
            $('textarea[name=address]').text(deliveryInfo+'\n'+address);
            $('.ddelivery-popup-select').hide();
            $('#overlay-ddelivery-popup-select').remove();
          }
        });
        $('.delivery-details-list input[value='+deliveryCalcDDelivery.deliveryId+']').trigger('click'); 
      });
      
      $('body').on('click', '#ddCityChange', function(){
        $('.citySearchBlock').show();
      });                  
            
    },
    checkRequireField: function(){
      var fieldNotEmpty = true;
      
      $('.delivery-details-list .ddelivery-popup-select form#courier_form input[type=text]')
              .each(function(){
                
        if(!$(this).val()){          
          $(this).css("border-color", "#f11");
          fieldNotEmpty = false;
        }
      });
      
      return fieldNotEmpty;
    },
    showMap: function(cityId, companies){      
      var myMap;
      ymaps.ready(init);
            
      function init () {
          // Создание экземпляра карты и его привязка к контейнеру с
          // заданным id ("map").
          myMap = new ymaps.Map('mapOverlay', {
              // При инициализации карты обязательно нужно указать
              // её центр и коэффициент масштабирования.
              center: [55.76, 37.64], // Москва
              zoom: 10
          }, {
              searchControlProvider: 'yandex#search'
          }),objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32
        }); 
        
        objectManager.objects.options.set('preset', 'islands#greenDotIcon');
        objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
        myMap.geoObjects.add(objectManager);
        
//        $.ajax({
//            url: "data.json"
//        }).done(function(data) {
//            objectManager.add(data);
//        });
        
        $.ajax({
          type: "POST",
          url: mgBaseDir+"/ajaxrequest",
          data: {
            pluginHandler: 'mg-delivery-ddelivery', // имя папки в которой лежит данный плагин
            actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
            action: "getMapPoints", // название действия в пользовательском  классе Comments  
            cityId: cityId,
            companies: companies,            
          },
          dataType: "json",
          cache: false,
          success: function(result){   
//            console.info(result);            
          }
        });
      }
    },
    showPlugin: function(id){            
      if(id == deliveryCalcDDelivery.deliveryId){        
        plugin = $('.delivery-details-list li .delivery-addition-info.delivery'+deliveryCalcDDelivery.deliveryId);
        
        if(plugin.length<=0){               
          plugin_html = $('.delivery-addition-info.delivery'+deliveryCalcDDelivery.deliveryId);          
          $('.delivery-details-list :checked').parents("label").append(plugin_html);          
          $('.delivery-addition-info.delivery'+deliveryCalcDDelivery.deliveryId).show();          
        }else{          
          plugin.show();
        }                
        
        $('body').on('click', '#ddelivery_select_params', function(){          
          deliveryCalcDDelivery.showDdeliveryBlock();
        });                
      }
      else{
        $('.delivery-addition-info.delivery'+deliveryCalcDDelivery.deliveryId).hide();                              
      }
      return true;
    },
    showDdeliveryBlock: function(){      
      $('#overlay-ddelivery-popup-select').remove();
      $('.map-loader').css('display', 'block');
            
      $.ajax({
        type: "POST",
        url: mgBaseDir+"/ddelivery-form",         
        dataType: "html",
        cache: false,
        success: function(result){   
          $('div#ddelivery_container_place').html(result);  
          $('.map-loader').css('display', 'none');
        }
      });                 
      
      $('body').append('<div id="overlay-ddelivery-popup-select"></div>');
      $('.ddelivery-popup-select').show();
      
      $('.delivery-details-list .ddelivery-popup-select input.radio-way').on('click', function(){        
        return true;
      });
      
//      send_order.onclick = function(){
//        deliveryCalcDDelivery.selectPath(deliveryData);
//        $('.ddelivery-popup-select').hide();            
//        $('#overlay-ddelivery-popup-select').remove();
//        
//        return false;
//      };
      
      $('body').on('click', '#close_popup_ddelivery', function(){
        $('.ddelivery-popup-select').hide();            
        $('#overlay-ddelivery-popup-select').remove();
      });
    },
  }
})();

$(document).ready(function(){
  deliveryCalcDDelivery.init();
  
});