var postcalcModuleAdmin = (function() {
  
  return { 
    init: function() {      

      $("body").on('click', '.order-payment-sum #postcalcSend', function(){

        if ($("#indexTo").val().length == 6 && $.isNumeric($("#indexTo").val())) {
          var indexTo = $('#indexTo').val();
          $('#indexTo').removeClass("postcalcInputError");
          $('#postcalcIndexError').hide();
        }else{
          $('#postcalcIndexError').show();
          $('#indexTo').addClass("postcalcInputError");
          return false;
        }

        var weight = 0;
        var price = 0;
        var count = 0;
        //забор данных из таблицы
        $('.status-table > tbody  > tr').each(function() {
          $this = $(this);
          price += parseFloat($this.find('.summ').find('span').text().replace( /\s/g, "").replace(/\,/g, '.'));
          count += parseFloat($this.find('.count').find('input').val().replace( /\s/g, "").replace(/\,/g, '.'));
          weight += (parseFloat($this.find('.weight').text().replace( /\s/g, "").replace(/\,/g, '.'))*parseFloat($this.find('.count').find('input').val().replace( /\s/g, "").replace(/\,/g, '.')));
        });

        if (weight == 0) {
          weight = 100;
        }
        else{
          weight = Math.ceil(weight * 1000);
        }

        /*console.log('weight');
        console.log(weight);
        console.log('price');
        console.log(price);
        console.log('count');
        console.log(count);*/
        //получение и вывод модалки с таблицей результатов
        $.ajax({
          type: "POST",
          url: mgBaseDir+"/ajaxrequest",
          data: {
            pluginHandler: 'postcalc', // имя папки в которой лежит данный плагин
            actionerClass: "Pactioner",
            action: "constructTableAdmin", // название действия в классе 
            indexTo: indexTo,
            weight: weight,
            count: count,
            price: price,
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
      });

      //пользователь выбрал вариант в модалке
      $("body").on('click', '.order-payment-sum .postcalcResultId', function(){

        //обновление поля 'Стоимость доставки'
        var tmp = $(".order-payment-sum").find('td#' + $(this).attr('id') + 'price').attr('price');
        $("#deliveryCost").val(tmp);

        //обновление поля 'Комментарий менеджера'
        var comment = $(".cancel-order-reason").val()+'Изменены данные "Индекс получателя = '+$('#indexTo').val()+"; Тип доставки = "+$(this).attr('id')+'"';
        $(".cancel-order-reason").val(comment);

        $('#postcalcShow').hide();
        $('#postcalcScreenBlock').hide();
        $('html, body').css('overflow', 'auto');

      });

      //закрытие модалки
      $("body").on('click', '.order-payment-sum #postcalcClose, #postcalcScreenBlock', function(){
        $('#postcalcShow').hide();
        $('#postcalcScreenBlock').hide();
        $('html, body').css('overflow', 'auto');
      });
    },
  }
})();

$(document).ready(function() {
  postcalcModuleAdmin.init();
});
