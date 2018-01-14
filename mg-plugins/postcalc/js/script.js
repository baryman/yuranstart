var postcalcModule = (function() {
  
  return { 
    init: function() {      

      $("body").on('click', '#postcalcSend', function(){

        if ($("#indexTo").val().length == 6 && $.isNumeric($("#indexTo").val())) {
          $('#indexTo').removeClass("postcalcInputError");
          $('#postcalcIndexError').hide();
          postcalcModule.getTable();
        }
        else{
          $('#postcalcIndexError').show();
          $('#indexTo').addClass("postcalcInputError");
          return false;
        }
      });

      //пользователь выбрал вариант в модалке
      $("#postcalcResult").on('click', '.postcalcResultId', function(){

        var delivId = $(this).attr('id');

        $.ajax({
          type: "POST",
          url: mgBaseDir+"/ajaxrequest",
          data: {
            pluginHandler: 'postcalc', // имя папки в которой лежит данный плагин
            actionerClass: "Pactioner", 
            action: "getPrice", // название действия в классе 
            delivId: delivId,
          },
          dataType: "json",
          success: function(response){
            $('.delivery-summ').html(response);
            $('#postcalcShow').hide();
            $('#postcalcScreenBlock').hide();
            $('html, body').css('overflow', 'auto');
            $('.payment-details-list').show();

            //изменение цены доставки рядом с названием плагина

            var zero = postcalcName.lastIndexOf("0");
            var rub = postcalcName.lastIndexOf("руб.");

            if (rub > zero) {
              var input = $("#postcalcInput").parent().find('label').find('input');
              var str = postcalcName.slice(0,zero);
              str += $(".order-delivery-summ").text();
              $("#postcalcInput").parent().find('label').html(input);
              $("#postcalcInput").parent().find('label').append(str);
            }
          }
        });
      });



      //закрытие модалки
      $("body").on('click', '#postcalcClose, #postcalcScreenBlock', function(){
        $('#postcalcShow').hide();
        $('#postcalcScreenBlock').hide();
        $('html, body').css('overflow', 'auto');

      });

      //изменение видимости поля ввода
      $(".delivery-details-list").on('change', 'input', function(){
        var selected = $("#postcalcInput").parent().find('input').is(':checked');

        if (selected) {
          $('#postcalcInput').show();
          $('.payment-details-list').hide();
        }
        else{
          $('#postcalcInput').hide();
          $('.payment-details-list').show();
        }
        
      });

      $("body").on('keyup', '#indexTo', function(){

        if ($("#indexTo").val().length == 6 && $.isNumeric($("#indexTo").val())) {
          $('#indexTo').removeClass("postcalcInputError");
          $('#postcalcIndexError').hide();
          postcalcModule.getTable();
        }
      });
    },

    //изменение видимости поля ввода при старте
    check: function() {
      var selected = $("#postcalcInput").parent().find('input').is(':checked');
        if (selected) {
          $('#postcalcInput').show();
          $('.payment-details-list').hide();
        }
        else{
          $('#postcalcInput').hide();
        }
    },

    getTable: function() {
      var indexTo = $('#indexTo').val();

      //получение и вывод модалки с таблицей результатов
      $.ajax({
        type: "POST",
        url: mgBaseDir+"/ajaxrequest",
        data: {
          pluginHandler: 'postcalc', // имя папки в которой лежит данный плагин
          actionerClass: "Pactioner",
          action: "constructTable", // название действия в классе 
          indexTo: indexTo,
        },
        dataType: "json",
        success: function(response){
          //console.log(response);
          $('#postcalcResult').html(response);
          $('#postcalcShow').show();
          $('#postcalcScreenBlock').show();
          $('html, body').css('overflow', 'hidden');
        }
      });


    }

  }
})();

$(document).ready(function() {
  postcalcModule.init();
  postcalcModule.check();
  postcalcName = $("#postcalcInput").parent().find('label').text();
});
