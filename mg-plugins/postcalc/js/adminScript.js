var postcalcModule = (function() {
  
  return { 
    init: function() {      
       // Сохраняет базовые настроки
      $('body').on('click', '.section-postcalc .base-setting-save', function() {
        
        if ($("#indexFrom").val().length == 6 && $.isNumeric($("#indexFrom").val())) {
          var indexFrom = $('#indexFrom').val();
          $('#indexFrom').removeClass("postcalcInputError");
          $('#postcalcIndexError').hide();
        }else{
          $('#postcalcIndexError').show();
          $('#indexFrom').addClass("postcalcInputError");
          return false;
        }

        //удаление пробелов
        var site = $(".base-settings input[name=site]").val();
        site = site.replace( /\s/g, "");
        var mail = $(".base-settings input[name=mail]").val();
        mail = mail.replace( /\s/g, "");

        $.ajax({
          type: "POST",
          url: mgBaseDir+"/ajaxrequest",
          data: {
            pluginHandler: 'postcalc', // имя папки в которой лежит данный плагин
            actionerClass: "Pactioner", 
            action: "saveBaseOption", // название действия в классе 
            data:{
              indexFrom : indexFrom,
              site : site,
              mail : mail,
              ПростоеПисьмо : $(".base-settings input[name=ПростоеПисьмо]").val(),
              ЗаказноеПисьмо : $(".base-settings input[name=ЗаказноеПисьмо]").val(),
              ЦенноеПисьмо : $(".base-settings input[name=ЦенноеПисьмо]").val(),
              ПростоеПисьмо1Класс : $(".base-settings input[name=ПростоеПисьмо1Класс]").val(),
              ЗаказноеПисьмо1Класс : $(".base-settings input[name=ЗаказноеПисьмо1Класс]").val(),
              ЦенноеПисьмо1Класс : $(".base-settings input[name=ЦенноеПисьмо1Класс]").val(),
              ПростойМультиконверт : $(".base-settings input[name=ПростойМультиконверт]").val(),
              ЗаказнойМультиконверт : $(".base-settings input[name=ЗаказнойМультиконверт]").val(),
              ПростаяБандероль : $(".base-settings input[name=ПростаяБандероль]").val(),
              ЗаказнаяБандероль : $(".base-settings input[name=ЗаказнаяБандероль]").val(),
              ЦеннаяБандероль : $(".base-settings input[name=ЦеннаяБандероль]").val(),
              ЦеннаяПосылка : $(".base-settings input[name=ЦеннаяПосылка]").val(),
              ЗаказнаяБандероль1Класс : $(".base-settings input[name=ЗаказнаяБандероль1Класс]").val(),
              ЦеннаяБандероль1Класс : $(".base-settings input[name=ЦеннаяБандероль1Класс]").val(),
              EMS : $(".base-settings input[name=EMS]").val(),
              КурьерОнлайн : $(".base-settings input[name=КурьерОнлайн]").val(),
              ПосылкаОнлайн : $(".base-settings input[name=ПосылкаОнлайн]").val(),
            },
          },
          dataType: "json",
          success: function(response){
            admin.indication(response.status, response.msg);
          }
        });
      });
    },
  }
})();

$(document).ready(function() {
  postcalcModule.init();
});
