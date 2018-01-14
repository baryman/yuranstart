$('body').on('click', '#ddelivery_select_params', function(){
  showDdeliverySelectForm();
}); 

$('body').on('click', '#close_popup_ddelivery', function(){
  $('.ddelivery-popup-select').hide();
  //$('#overlay-ddelivery-popup-select').remove();
});

$('body').on('click', '#ddCityChange', function(){
  $('.citySearchBlock').show();
});

$('body').on('click', 'a#openMap', function(){
  var companies = $(this).attr('data-companies');
  var cityId = $(this).attr('data-city');

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
    $('div.map').remove();
    
    if(typeof order != "undefined"){
      order.calculateOrder();
    }
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
  var deliveryId = deliveryId = $('#delivery option:selected').attr('name');
  result.type = 2;               
  result.city_id = $('div.select-city-trigger input.cityId').val();
  result.company_id = $('select#courier_company option:selected').val();   
  
  if($('.add-delivery-info .ddelivery-popup-select input.radio-way').val() == 2){
    if(!checkRequireField()){
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
      admin: 1,
      deliveryId: deliveryId,
      result: result
    },
    dataType: "json",
    cache: false,
    success: function (result) { 
      var form = $('.add-delivery-info .ddelivery-popup-select form#courier_form');
      var deliveryCompany = form.find('select#courier_company option:selected');
      var deliveryInfo = 'Доставка: '+deliveryCompany.attr('data-name')+', '+deliveryCompany.attr('data-date');
      var address = 'Адрес: '+$('#ddelivery_container_place span.city').text()+', ';
      address += form.find('input[name=street]').val()+' ';  
      address += form.find('input[name=house]').val()+', ';  
      address += 'кв. '+form.find('input[name=flat]').val()+'';  
      $('input[name=address]').val(deliveryInfo+'. \n'+address);
      $('.ddelivery-popup-select').hide();      
    }
  });
  order.calculateOrder();
  
//  $('.delivery-details-list input[value='+deliveryCalcDDelivery.deliveryId+']').trigger('click'); 
//        $('textarea[name=address]').text(pointCurrent.name+', '+pointCurrent.city+', '+' '+pointCurrent.address);
});

function checkRequireField(){
  var fieldNotEmpty = true;

  $('.add-delivery-info .ddelivery-popup-select form#courier_form input[type=text]')
          .each(function(){

    if(!$(this).val()){          
      $(this).css("border-color", "#f11");
      fieldNotEmpty = false;
    }
  });

  return fieldNotEmpty;
}

function showDdeliverySelectForm(){
  $('.map-loader').css('display', 'block');
  $.ajax({
    type: "POST",
    url: mgBaseDir+"/ddelivery-form",
    data: {
      orderItems: order.orderItems,
    },
    dataType: "html",
    cache: false,
    success: function(result){   
      $('div#ddelivery_container_place').html(result);   
      $('.map-loader').css('display', 'none');
    }
  });                 

//  $('body').append('<div id="overlay-ddelivery-popup-select"></div>');
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
//    $('#overlay-ddelivery-popup-select').remove();
  });
}

function selectPath(result){ 
  $('input[name=address]').val(result.info);
  $.ajax({
    type: "POST",
    url: mgBaseDir+"/ajaxrequest",
    data: {
      pluginHandler: 'mg-delivery-ddelivery', // имя папки в которой лежит данный плагин
      actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
      action: "setPrice", // название действия в пользовательском  классе Comments
      deliveryId: $('#delivery option:selected').attr('name'),
      result: result,
    },
    dataType: "json",
    cache: false,
    success: function(response){ 
      $("#deliveryCost").val(response.data.deliverySum);
        order.calculateOrder();          
    }
  });
  $('textarea[name=address]').text(result.info);
  return false;
}
