<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script>
$(function(){
  $( "#ddCitySearchInput").autocomplete({
    appendTo: ".citySearchBlock",
    minLength: 2,
    source: function(request, response){
      $.ajax({
        type: "POST",
        url: mgBaseDir+"/ajaxrequest",
        data: {
          pluginHandler: 'mg-delivery-ddelivery', // имя папки в которой лежит данный плагин
          actionerClass: 'Pactioner', // класс Pactioner в Pactioner.php - в папке плагина
          action: 'getCityAutocomplete', // название действия в пользовательском  классе  
          term: request.term
        },
        cache: false,
        dataType: 'json',
        success: function(resp){          
          if(resp.data == 'null'){
            $('button.add-custom-city').remove();
            $('#mg-geolocation-find-city').after('<button class="add-custom-city">Выбрать</button>');
          }else{
            var data = eval("(" + resp.data + ")");
            response(data);
            $('ui-helper-hidden-accessible').hide();
          }
        }
      });
    },        
    select: function(event,ui){      
      event.preventDefault();  
      $('.map-loader').css('display', 'block');
      $('div.select-city-trigger input.cityId').val(ui.item.id);
      $('div.select-city-trigger span.city').text(ui.item.label);
      $('.citySearchBlock').hide();
      
      var cityInfo = {
        city: ui.item.label,
        city_id: ui.item.id
      };
      
      $.ajax({
        type: "POST",
        url: mgBaseDir+"/ddelivery-form",         
        dataType: "html",
        cache: false,
        data: {
          cityInfo: cityInfo
        },
        success: function(result){   
          $('.map-loader').css('display', 'none');
          $('div#ddelivery_container_place').html(result);  
        }
      });
  //          mgGeoIp.setCity(ui.item.label);
    }
  });
});
</script>
<div class="delivery-page map-page select-city-page container">
  <div class="overlay global-loader" style="display: none; background: rgb(255, 255, 255) none repeat scroll 0% 0%;">
    <img class="loader" src="/assets/v2/img/loader-2.gif">
  </div>
  <div class="delivery">   
    <div class="item select-city-trigger">
      Ваш город - <span class="city"><?php echo $cityInfo->city;?></span>
      <div class="citySearchBlock" style="display: none;">
        <input type="text" name="citySearch" id="ddCitySearchInput" />        
      </div>      
      <input class="cityId" type="hidden" value="<?php echo $cityInfo->city_id;?>">
      <span class="link">        
        <a id="ddCityChange" href="javascript:void(0);"> Изменить </a>
      </span>
    </div>
    <div class="item">
      <label class="col-lg-1 col-sm-2">
        <input class="radio-way" type="radio" value="2" name="optionsRadios" checked="checked">
        <span>Курьером</span>    
      </label>
      <form id="courier_form" class="cform-inp">
        <div class="wrapper">
          <select id="courier_company" class="form-control price-select-courier" name="courier_company">
          <?php 
          $count = 0;
          foreach($dCompany2List as $company):?>
            <option <?php echo ($count==0) ? 'selected="selected"' : '';?> 
              data-name="<?php echo $company->delivery_company_name;?>" 
              data-date="<?php echo $company->delivery_date;?>" 
              value="<?php echo $company->delivery_company;?>">
              Доставка - <?php echo $company->delivery_date.', '.$company->client_price.' руб.';?>
            </option>
          <?php 
          $count++;
          endforeach;?>
          </select>
        </div>
        <div class="wrapper">
          <input class="form-control required" type="text" value="" placeholder="Улица" name="street">
        </div>
        <div class="wrapper">
          <input class="form-control required" type="text" value="" placeholder="Дом" name="house">
        </div>
        <div class="wrapper">
          <input class="form-control required" type="text" value="" placeholder="Квартира" name="flat">
        </div>
      </form>
      <span class="price-orange">
        <span class="price-val-courier"></span>
        <i class="fa fa-rub"></i>
      </span>
    </div>
    <div class="item">
      <label>
        <input class="radio-way" type="radio" value="1" name="optionsRadios">
        <span>В пункт выдачи</span>
      </label>
      <span class="link">
        <img width="25" height="22" alt="" src="<?php echo SITE.'/'.self::$path?>/images/icon3.png">
        <a href="javascript:void(0);" id="openMap" data-city="<?php echo $cityInfo->city_id;?>" data-companies="<?php echo $delivery1List?>">Выбрать пункт выдачи</a>
      </span>
      <span class="price-orange">
        <span class="price-val-courier">от <?php echo $minCompany1Price;?> руб.</span>
        <i class="fa fa-rub"></i>
      </span>
    </div>
  </div>
</div>

<div class="balloonContentLayout" style="display: none;">
  <div class="ddelivery-popup popup show" style="width: 350px;">
    <div class="balloon-head">$[properties.point.name]</div>
    <div class="item">
      <div class="name"> Компания: </div>
      <div class="value"> $[properties.point.company] </div>
    </div>
    <div class="item">
      <div class="name"> Дата доставки: </div>
      <div class="value">
        <div class="wrapper"> $[properties.point.date_preaty] </div>    
      </div>
    </div>
    <div class="item">
      <div class="name"> Тип пункта: </div>
      <div class="value"> $[properties.point.typeText] </div>
    </div>
    <div class="item">
      <div class="name"> Вариант оплаты: </div>
      <div class="value payment-info"> $[properties.point.paymentText] </div>
    </div>
    <div class="item more">
      <div class="name"> Адрес: </div>
      <div class="value point-address"> $[properties.point.address] </div>
    </div>
    <div class="item more">
      <div class="name"> Метро: </div>
      <div class="value point-metro"> $[properties.point.metro] </div>
    </div>
    <div class="item more">
      <div class="name"> Расписание работы: </div>
      <div class="value">
        <ul class="timetable">
          <li class="point-schedule"> $[properties.point.schedule] </li>
        </ul>
      </div>
    </div>
	<span class="price">
          $[properties.point.client_price]          
        </span>
    <div class="item btn-group">     
      <div class="value">
        <button class="select-self">          
          Заберу здесь
        </button>
        <button class="back">          
          Назад к карте
        </button>
      </div>
    </div>
  </div>
</div>