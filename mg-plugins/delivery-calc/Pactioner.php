<?php
/**
 * Created by PhpStorm.
 * User: vitalijlogvinenko
 * Date: 07.05.14
 * Time: 21:20
 */

class Pactioner extends Actioner{

  /**
   * Функция расчета стоимости доставки
   */
  public function Calculate(){
    unset($_POST['pluginHandler']);
    unset($_POST['actionerClass']);
    unset($_POST['action']);    
    $_SESSION['delivery'][$_POST['deliveryId']] = $_POST;
    
    if(URL::isSection('mg-admin') && (empty($_POST['orderItems']) || empty($_POST['ptNumber']))){
      $this->data['deliverySum'] = 0;
      unset($_SESSION['delivery'][$_POST['deliveryId']]);
    }
    
    $res = deliveryCalc::Calculate($_POST['to']);
       
    if(is_object($res['data'])){
      $this->data['deliverySum'] = $res['data']->rsp->price;
      $this->data['stat'] = $res['data']->rsp->stat;
      $this->data['term'] = $res['data']->rsp->term;
    }else{
      $this->data['deliverySum'] = -1;
      $this->data['error'] = $res['data'];
    }        
    
    return $res['status'];
  }

  /**
   * Сохраняем настройки компонента
   */
  public function savePlugin(){
    if(!empty($_POST['from'])){
      MG::setOption(array('option' => 'deliveryRegionFrom', 'value' => $_POST['from']));
      return true;
    }
    else
      return false;
  }
  
  /*Обязательная функция плагина, для получения стоимости доставки из админки, по параметрам заказа*/
  public function getPriceForParams(){       
    $_POST['to'] = $_SESSION['delivery'][$_POST['deliveryId']]['to'];
    $this->Calculate();     
    return true;
  }
  
  /**
   * Обязательная функция плагина, для возможности пересчета стоимости, или изменения пункта достаки из админки
   * Возвращает дополнительную верстку для выборпа парметров доставки
   */
  public function getAdminDeliveryForm(){
    $this->data['form'] = '';
    $to = $_SESSION['delivery'][$_POST['deliveryId']]['to'];
    $cityList = deliveryCalc::getCityList();
    $cityListHtml = '';
    for ($i=0; $i<count($cityList); $i++){
      if($to == $cityList[$i]->{'value'}){
        $sel = 'selected';
      }
      else{
        $sel = '';
      }
      $cityListHtml = $cityListHtml.'<option '.$sel.' value='.$cityList[$i]->{'value'}.'>'.$cityList[$i]->{'name'}.'</option>';
    };
    
    $this->data['form'] = '
      <div class="delivery-calc-plugin" id='.$_POST['deliveryId'].'>
          <div class="delivery-plugin-title" style="display:none">
              <div id="delivery-plugin-title-from">Выберите город для доставки: &nbsp;</div>
              <div class="clear"></div>
              <div id="delivery-plugin-title-weight" style="display:none">Вес: &nbsp;</div>
          </div>
          <div class="delivery-plugin-form">
              <div class="delivery-to">
                  <select name="delivery-calc-plugin">
                      <option>Выберите свой регион</option>
                      '.$cityListHtml.'
                  </select>
              </div>
              <div class="weight" style="display:none">
                  <input id="delivery-calc-plugin-weight" type="number" minvalue=0 maxvalue=100>
              </div>
              <div class="delivery-plugin-calc" style="display:none">
                  <button id="start">РАССЧИТАТЬ</button>
              </div>
          </div>
          <div class="delivery-calc-response">
          </div>
      </div>
      <script>
      $(".delivery-calc-plugin .delivery-to select").on("change",function(){
        city = $(this).val();
        loader = $(\'.mailLoader\');
        $.ajax({
          type: "POST",
          url: mgBaseDir+"/ajaxrequest",
          data: {
            pluginHandler: \'delivery-calc\', // имя папки в которой лежит данный плагин
            actionerClass: \'Pactioner\',
            action: "Calculate",
            deliveryId: '.$_POST['deliveryId'].',
            to: city,            
            orderItems: order.orderItems,
          },
          dataType: "json",
          cache: false,
          beforeSend: function () {
            // флаг, говорит о том что начался процесс загрузки с сервера
            admin.WAIT_PROCESS = true;
            loader.hide();
            loader.before(\'<div class="view-action" style="display:none; margin-top:-2px;">\' + lang.LOADING + \'</div>\');
            // через 300 msec отобразится лоадер.
            // Задержка нужна для того чтобы не мерцать лоадером на быстрых серверах.
            setTimeout(function () {
              if (admin.WAIT_PROCESS) {
                admin.waiting(true);
              }
            }, admin.WAIT_DELAY);
          },
          success: function(response){                
            $("#deliveryCost").val(response.data.deliverySum);
            order.calculateOrder();
            // завершился процесс
            admin.WAIT_PROCESS = false;
            //прячим лоадер если он успел появиться
            admin.waiting(false);
            loader.show();
            $(\'.view-action\').remove();
          },
          error: function(a,b,c){
            console.info(a);
            console.info(b);
            console.info(c);
          }
        });
      });
      </script>
';
    
    return true;
  }

}