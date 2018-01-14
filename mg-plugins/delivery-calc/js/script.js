/**
 * Created by vitalijlogvinenko on 07.05.14.
 */
var deliveryCalcPlugin = (function(){
    return {
        calculate: function(){
        
            var //to = $('.delivery-calc-plugin .delivery-to select option:selected').attr('val'),
                self = $('.delivery-to [name="delivery-calc-plugin"]'),
                to = self.children('option:selected').val(),
                weight = $('#delivery-calc-plugin-weight').val();
            $.ajax({
                type: "POST",
                url: mgBaseDir+"/ajaxrequest",
                data: {
                    pluginHandler: 'delivery-calc', // имя папки в которой лежит данный плагин
                    actionerClass: "Pactioner", // класс Comments в comments.php - в папке плагина
                    action: "Calculate", // название действия в пользовательском  классе Comments
                    to: to,
                    weight: weight
                },
                dataType: "json",
                cache: false,
                success: function(response){
                    if(response.status!="error"){
                        var data = response.data;
                        stat = data.stat;
                        price = data.deliverySum;
                        term = data.term;
                        if(stat=="ok"){
                          var selectorEms = 'input[name=delivery][value='+$('.delivery-calc-plugin').attr('id')+']';
                          $(selectorEms).parents('label').html('<input type="radio" checked=checked name="delivery" value="'+$('.delivery-calc-plugin').attr('id')+'">'+'EMS '+price+'руб.');
                          $('.delivery-details-list '+selectorEms).on('click',function(e){
                              id = e.target.defaultValue;
                              deliveryCalcPlugin.showPlugin(id);
                          });                  
                      
                          html = "Стоимость доставки = "+price+" руб. <br/>Срок доставки от "+term.min+" до "+term.max + " дней.";
                          self.parents('li.active').children('label').children('.delivery-cost').html(price + " руб.");
                        }
                        else{
                            html = data.msg;
                        }
                        $('.delivery-calc-response').html(html);

                        //console.log(response.data.rsp);
                    }
                    else
                        $('.delivery-calc-response').html(response.data.error);
                }});
        },

        showPlugin: function(id){
            plugin_id = $('.delivery-calc-plugin').attr('id');
            if(id == plugin_id){
                plugin = $('.delivery-details-list li.active .delivery-calc-plugin');
                if(plugin.length<=0){
                    $('.delivery-calc-plugin').show();
                    plugin_html = $('.delivery-calc-ems-plugin').html();
                    $('.delivery-calc-ems-plugin').html('');
                    $('.delivery-details-list :checked').parents("li").append(plugin_html);
                    $('.delivery-calc-plugin .delivery-to select').on("change",deliveryCalcPlugin.calculate);
                }
                else{
                    plugin.show();
                }
            }
            else{
                $('.delivery-calc-plugin').hide();
            }
        },

        init: function(){
            //Если находимся на странице order прячем подключенный плагин
         
                $('.delivery-calc-ems-plugin').hide();
                if($('.delivery-calc-ems-plugin').attr("isShow")=="1"){
                    deliveryCalcPlugin.showPlugin($('.delivery-calc-plugin').attr('id'));
                    deliveryCalcPlugin.calculate();
                }

           
            $('.delivery-plugin-calc #start').on("click",deliveryCalcPlugin.calculate);
            $('.delivery-calc-plugin .delivery-to select').on("change",deliveryCalcPlugin.calculate);

            $('.admin-center').on('click', '.section-deliveryсalc .save-button', function() {
                var from = $('.select-delivery-from select option:selected').attr('val');

                admin.ajaxRequest({
                        pluginHandler: 'delivery-calc', // имя папки в которой лежит данный плагин
                        actionerClass: "Pactioner", // класс
                        action: "savePlugin", // название действия в пользовательском  классе
                        from: from
                    },
                    function(response) {
                        admin.indication(response.status, response.msg);
                    }
                );
            });

            
                $('.delivery-details-list input').on('click',function(e){                    
                    //id = $('.delivery-details-list .active [name="delivery"]').val();
                    id = e.target.defaultValue;
                    deliveryCalcPlugin.showPlugin(id);
                });
           
        }
    }
})();

$(document).ready(function(){
    deliveryCalcPlugin.init();
});

