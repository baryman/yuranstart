var DDeliveryMap = (function () {
  var
    map = null,
    renderGeoObject,
    mapObject, // объект карты
    params,    
    cluster,
    pointCurrent,
    points,
    companiesList,
    indexed,
    mapBalloonContentLayout, // Макет для контента балуна
    mapBalloonLayout,// Макет для обертки балуна
    mapHintBestLayout, // Макет для хинта карты лучших  предложений
    mapHintLayout, // Макет для хинта карты обычных  предложений    
    /**
     * Инициализация карты
     */
    renderMap = function () {      
      mapObject = $('.mapper');
//      map.destroy();
      var bounds = renderGeoObject.properties.get('boundedBy');
      var centerAndZoom = ymaps.util.bounds.getCenterAndZoom(bounds, [mapObject.parent().parent().width(), mapObject.parent().parent().height()]);
      
      if (map === null) {
        centerAndZoom.zoom = centerAndZoom.zoom || 10;
        map = new ymaps.Map(mapObject[0], {
          center: centerAndZoom.center,
          zoom: centerAndZoom.zoom,          
          behaviors: ['default', 'scrollZoom']
        }, {
          maxZoom: 17
        });
        //alert('sss');
        //alert(mapObject.html());
//        addMapControlls();
        cluster = new ymaps.Clusterer({
          preset: 'islands#invertedVioletClusterIcons',
          /**
           * Ставим true, если хотим кластеризовать только точки с одинаковыми координатами.
           */
          groupByCoordinates: false,
          openBalloonOnClick: false,
          /**
           * Опции кластеров указываем в кластеризаторе с префиксом "cluster".
           * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Cluster.xml
           */
          clusterDisableClickZoom: false,
          maxZoom: 15,
          gridSize: 100,
          synchAdd: false // Добавлять объекты на карту сразу, може тупить на медленных пк
        });                

        $(window).on('points:clear', function () {
          cluster.removeAll();
        });
        
        $(window).trigger("map:loaded");
      } else {
        map.setCenter(centerAndZoom.center, centerAndZoom.zoom, {duration: 400});
      }
      //map.setCenter(centerAndZoom.center, centerAndZoom.zoom, {duration: 400});
    },
    /**
     * Инициализировать метки в контексте кластеров
     */
    renderPoints = function () {     
      var geoObjects = [];
      cluster.removeAll();
      
      for (var pointKey in points) {        
//        points[pointKey].latitude += 0.0001
        points[pointKey].pointKey = pointKey;
        
        if(initPointPlacemark(points[pointKey])){                  
            geoObjects.push(points[pointKey].placemark);            
        }
      }
      cluster.add(geoObjects);
      
      cluster.events.add('click', function (e) {
        var target = e.get('target');
        var point = target.properties.get('point');
        if (typeof (point) != 'undefined') {  //pointCurrent.company='xxx';          
          pointCurrent = point;          
          pointCurrent.placemark = null;
          //renderPointInfo(point);
        } else {
        }
      });
      map.geoObjects.add(cluster);
    },
    initPointPlacemark = function (point) {
      var indexCompany = companiesList[point.company_id] || null;
      if(indexCompany == null)
        return false;
           
      initCompanyInfo(point);            
      
      point.placemark = new ymaps.Placemark([point.latitude, point.longitude], {
        point: point,        
        iconContent: DDeliveryMap.getMonthString(DDeliveryMap.parseDate(indexCompany.delivery_date))+' - '+indexCompany.client_price,
        balloonContent: point.name,
        balloonContentHeader: point.name
      }, {
        preset: 'islands#violetStretchyIcon',       
        balloonShadow: false,
        //balloonLayout: mapBalloonLayout,
        //hintLayout: hintLayout,
        balloonContentLayout: mapBalloonContentLayout,
        balloonPanelMaxMapArea: 0,
        //openHintOnHover: true
          //hasBalloon:false
      });
            
      return true;
    },
    initCompanyInfo = function (point) {
      var indexCompany = companiesList[point.company_id] || {};
      
      if (typeof indexCompany.client_price == 'undefined')
        return;
      point.client_price = indexCompany.client_price;
      point.date = DDeliveryMap.parseDate(indexCompany.delivery_date);
      if (point.is_cash == 1) {
        point.paymentText = ' Наличными ';
      }
      if (point.is_card == 1) {
        point.paymentText = ' Картой ';
      }
      if (point.type == '1') {
        point.typeText = 'Ячейка'
      } else if (point.type == '2') {
        point.typeText = 'Живой пункт'
      }
      point.display = true;
//      point.description_out = "";
//      point.schedule = "";
//      point.metro = "";
//      point.address = "";
      var delivery_date = DDeliveryMap.parseDate(indexCompany.delivery_date);
      point.date_preaty = DDeliveryMap.getMonthYearString(delivery_date);
      delivery_date = DDeliveryMap.addDate(delivery_date, parseInt(indexCompany.date_due));
      point.date_due = DDeliveryMap.getDayDotString(delivery_date);
    },
    mapBalloonContentBody = function(){                  
      
      var deliveryId = 0;
      if(typeof deliveryCalcDDelivery !=="undefined"){
        deliveryId = deliveryCalcDDelivery.deliveryId;
      }else{
        deliveryId = $('#delivery option:selected').attr('name');
      }
      
      $('.map').on('click', 'div.popup .back', function(){        
        map.balloon.close();
      });
      $('.map').on('click', 'div.popup .select-self', function(){          
        pointCurrent.type = '1';      
        
        $.ajax({
          type: "POST",
          url: mgBaseDir + "/ajaxrequest",
          data: {
            pluginHandler: 'mg-delivery-ddelivery', // имя папки в которой лежит данный плагин
            actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
            action: "setPrice", // название действия в пользовательском  классе Comments   
            admin: 1,
            deliveryId: deliveryId,
            result: pointCurrent
          },
          dataType: "json",
          cache: false,
          success: function (result) {            
            map.balloon.close();
            map.destroy();
            $(window).trigger("map:closed");
          }
        });        
        var deliveryInfo = pointCurrent.company+', '+pointCurrent.name+', '+pointCurrent.city+', '+pointCurrent.address;
        $('input[name=address]').val(deliveryInfo);
        $('.delivery-details-list input[value='+deliveryId+']').trigger('click');         
      });
      
      mapBalloonContentLayout = ymaps.templateLayoutFactory.createClass($('.balloonContentLayout').html());       
    },
    renderPointInfo = function(point){
      $.ajax({
        type: "POST",
        url: mgBaseDir + "/ajaxrequest",
        data: {
          pluginHandler: 'mg-delivery-ddelivery', // имя папки в которой лежит данный плагин
          actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
          action: "getPointInfo", // название действия в пользовательском  классе Comments                  
          point: point._id,
        },
        dataType: "json",
        cache: false,
        success: function (result) {
          pointCurrent.placemark = null;
        }
      });          
    };
    
  return {
    init: function () {
      map = null;
      var city = $('#ddelivery_container_place .delivery .city').text();
      ymaps.ready(function () {
//        initLayouts();
        mapBalloonContentBody();
        ymaps.geocode(city, {results: 1}).then(function (res) {
          renderGeoObject = res.geoObjects.get(0);
          renderMap();
          // Инпут поиска
        });
      });

      $('.map__search input[type=submit]').click(function () {        
        return false;
      });

//      events();
    },
    /*
     * Получить список точек по заданным параметрам
     */
    getPoints: function (params) {             
      $('div.map').css('display', 'none');
      $('.map-loader').css('display', 'block');  
      $.ajax({
        type: "POST",
        url: mgBaseDir + "/ajaxrequest",
        data: {
          pluginHandler: 'mg-delivery-ddelivery', // имя папки в которой лежит данный плагин
          actionerClass: 'Pactioner', // класс Comments в comments.php - в папке плагина
          action: "getMapPoints", // название действия в пользовательском  классе Comments  
          cityId: params.city,
          companies: params.companies,   
          orderItems: order.orderItems,
        },
        dataType: "json",
        cache: false,
        success: function (result) {          
          points = result.data.points;
          companiesList = result.data.companies;
          indexed = result.data.index;
          renderPoints();
          $('.map-loader').css('display', 'none');
          $('div.map').css('display', 'block');
        }
      });
    },
    parseDate:function(dateStr){
            if(/\d\d\.\d\d.\d\d\d\d/.test(dateStr)){
                var arrA = dateStr.split('.');
                var month = parseInt(arrA[1]);
                return new Date(arrA[2], (month - 1), arrA[0]);
            }
            return new Date();
        },

        getDayDotString:function(date){
            //date = Map.addDate(delivery_date, 5);
            var day = (date.getDate().toString().length == 1)?"0" +
            date.getDate().toString():date.getDate().toString();

            var month = ((date.getMonth() + 1).toString().length == 1)?"0" +
            (date.getMonth() + 1).toString():(date.getMonth() + 1).toString();
            return (day+"."+month);
        },
        getMonthString:function(date){
            var days = ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
            var month = (date.getMonth()+1).toString();
            var num = date.getDate().toString();
            var day = date.getDay();

            if(month.length == 1){
                month = "0" + month;
            }
            if(num.length == 1){
                num = "0" + num;
            }
            return (num + "." + month + " " + days[day]);
        },

        getMonthYearString:function(date){
            var days = ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
            var monthes = [ "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня","Июля",
                            "Августа", "Сентября", "Октября", "Ноября", "Декабря"
            ];
            var month = date.getMonth();
            var num = date.getDate().toString();
            var day = date.getDay();
            return (num + " " + monthes[month] + " (" + days[day] + ") ");
        },

        getDayDelta:  function (incomingDate, today ){
            var  delta;
            today.setHours(0);
            today.setMinutes(0);
            today.setSeconds(0);
            today.setMilliseconds(0);
            delta = incomingDate - today;
            return Math.round(delta / 1000 / 60 / 60/ 24);
        },
        addDate: function addDate(date, day) {
            var newDate = date;
            newDate.setDate(date.getDate() + day);
            return newDate;
        }
  };
})();