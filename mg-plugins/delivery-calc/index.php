<?php

/*
  Plugin Name: Стоимость доставки EMS
  Description: Плагин для расчета стоимости доставки по EMS. Для установки плагина необходимо поставить шорткод [delivery-calc] в любое место в верстке страницы оформления заказа.
  Author: Виталий Логвиненко, Иван Осипов
  Version: 1.1.4
 */

new deliveryCalc;

class deliveryCalc {

  private static $pluginName = ''; // название плагина (соответствует названию папки)
  private static $path = '';

  public function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate')); //Инициализация  метода выполняющегося при активации
    mgDeactivateThisPlugin(__FILE__, array(__CLASS__, 'deactivate'));
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin')); //Инициализация  метода выполняющегося при нажатии на кнопку настроект плагина
    mgAddShortcode('delivery-calc', array(__CLASS__, 'deliveryCalc'));
    mgAddAction('models_order_isvaliddata', array(__CLASS__, 'hookFunction'),1);
    //Считаываем или записываем настройки для плагина
    if(!MG::getSetting('deliveryRegionFrom')){
      MG::setOption(array('option' => 'deliveryRegionFrom', 'value' => 'city--moskva'));
    }
    self::$pluginName = PM::getFolderPlugin(__FILE__);
    self::$path = PLUGIN_DIR.self::$pluginName;
    $meta =     
      '<link href='.SITE.'/'.self::$path.'/css/style.css rel="stylesheet" type="text/css">';
    mgAddMeta($meta);
	  mgAddMeta('<script type="text/javascript" src='.SITE.'/'.self::$path.'/js/script.js></script>', 'order');
  }

  /**
   * Хук при оформлении заказа
   * @param $args
   * @return string
   */
  static function hookFunction($args){
    $bError = isset($arg['result']) ? true : false;
    if(!$bError){
      $idForPlugin = MG::getSetting('deliveryIdForPlugin');
      $deliveryId = $args['args'][0]['delivery'];
      if($deliveryId == $idForPlugin){
        $deliveryCost = self::Calculate((string)$args['args'][0]['delivery-calc-plugin']);
        $args['args']['this']->delivery_cost = $deliveryCost['data']->{'rsp'}->{'price'};
        if(is_null($args['args']['this']->delivery_cost )){
            $args['result'] = "Выберите регион для доставки по EMS";
        }
      }
    }
    return $args['result'];
  }

  /**
   * Основная функция для отображения при инициализации плагина
   * @return string
   */
  static function deliveryCalc(){
    $cityList = self::getCityList();
    $idForPlugin = MG::getSetting('deliveryIdForPlugin');
    $deliveryId = $_POST['delivery'];
    $to = $_POST['delivery-calc-plugin'];
    $isShow = false;
    if($deliveryId == $idForPlugin){
      $isShow = true;
    }
    $lenCityList = count($cityList);
    $cityListHtml = '';
    for ($i=0; $i<$lenCityList; $i++){
      if($to == $cityList[$i]->{'value'}){
          $sel = 'selected';
      }
      else{
          $sel = '';
      }
      $cityListHtml = $cityListHtml.'<option '.$sel.' value='.$cityList[$i]->{'value'}.'>'.$cityList[$i]->{'name'}.'</option>';
    };
    $res = '
    <div class="delivery-calc-ems-plugin" isShow='.$isShow.'>
      <div class="delivery-calc-plugin" id='.$idForPlugin.'>
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
    </div>
    ';
    return $res;
  }

  static function activate(){
    $getDeliv = self::getDeliveryForPlugin();
    if($getDeliv <= 0){
      $setDeliv = self::setDeliveryForPlugin();
      MG::setOption(array('option' => 'deliveryIdForPlugin', 'value' => $setDeliv));
    }
    else{
      MG::setOption(array('option' => 'deliveryIdForPlugin', 'value' => $getDeliv[0]['id']));
    }
  }
  
  static function deactivate(){
    USER::AccessOnly('1,4','exit()');
    $sql = '
      UPDATE `'.PREFIX.'delivery` 
      SET `activity` = 0 
      WHERE `plugin` = \'delivery-calc\'';
    DB::query($sql);
  }

  /**
   * Возвращает запись с доставкой из БД для плагина
   */
  static function getDeliveryForPlugin(){    
    $dbRes = DB::query("
      SELECT `id`
      FROM ".PREFIX."delivery
      WHERE `plugin` = 'delivery-calc'
    ");
    
    if($result = DB::fetchAssoc($dbRes)){
      $sql = '
        UPDATE `'.PREFIX.'delivery` 
        SET `activity` = 1 
        WHERE `plugin` = \'delivery-calc\'';
      DB::query($sql);
      
      return $result['id'];
    }
    
    return 0;
  }

  /**
   * Добавляет в бд запись с типом доставки для плагина
   */
  static function setDeliveryForPlugin(){
    DB::query("
      INSERT INTO ".PREFIX."delivery (`name`,`cost`,`description`,`activity`,`free`, `plugin`) VALUES
      ('EMS (плагин)', 0, 'EMS', 1, 0, 'delivery-calc')
    ");
    $deliveryId = DB::insertId();
    $sql = "
      INSERT INTO `".PREFIX."delivery_payment_compare`
        (`compare`,`payment_id`, `delivery_id`)
      VALUES (
        '1', '1', '".$deliveryId."'
      );
    ";
    $result = DB::query($sql);
    return $deliveryId;
  }

  /**
   * EMS API
   */
  static function execEMS($query){
    $url = "http://emspost.ru/api/rest/?";
    //$query = array('method'=>'ems.calculate','from'=>$from,'to'=>$_POST['to'],'weight'=>$_POST['weight']);
    $query = http_build_query($query);
    $fullUrl = $url.$query;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    $res = json_decode($res);
    curl_close($ch);
    return $res;
  }


  /**
   * Получаем список городов из api EMS
   */
  static function getCityList(){
    $query = array('method'=>'ems.get.locations','type'=>'russia','plain'=>'true');
    $res = self::execEMS($query);
    return $res->{'rsp'}->{'locations'};
  }

  /**
   * Функция расчета стоимости доставки
   */
  static function Calculate($delivTo){
    //$d = new deliveryCalc();
    $data = array("res"=>null,"status"=>null);
    $weight = self::getCartItemsWeight();
    
    if(!empty($delivTo) && $weight>0){
      $query = array('method'=>'ems.test.echo');
      $testConnect = deliveryCalc::execEMS($query); //Проверяем доступность сервиса
      if( $testConnect->{'rsp'}->{'stat'} == 'ok'){
        $query = array('method'=>'ems.get.max.weight');
        $maxWeight = self::execEMS($query); //Проверяем максимальный вес поссылки
        $maxWeight = $maxWeight->{'rsp'}->{'max_weight'};
        if($maxWeight >= $weight){
          $from = MG::getSetting('deliveryRegionFrom');
          $query = array('method'=>'ems.calculate','from'=>$from,'to'=>$delivTo,'weight'=>$weight);
          $res = self::execEMS($query);
          if($res->{'rsp'}->{'stat'} == 'fail'){
              $res = "Выберите регион доставки";
              $status = false;
          }
          else
              $status = true;
          //deliveryCalc::$price=$res->{'rsp'}->{'price'};
          $result["data"] = $res;
          $result["status"] = $status;
          return $result;
        }
        else{
          $result["data"] = "Максимальный вес поссылки = ".$maxWeight;
          $result["status"] = false;
          return $result;
        }
      }
      else{
        $result["data"] = "Сервис не доступен";
        $result["status"] = false;
        return $result;
      }
    }
    else{
      if(empty($delivTo))
          $data = "Выберите регион доставки";
      //viewData($weight);
      if($weight<=0)
          $data = "Вес заказанного товара должен быть больше 0";
      $result["data"] = $data;
      $result['status'] = false;
      return $result;
    }
  }

  /**
   * Вывод страницы плагина в админке
   */
  static function pageSettingsPlugin() {
    $pluginName = self::$pluginName;

    $lang = PM::plugLocales('delivery-calc');
    $from = MG::getSetting('deliveryRegionFrom');
    $path = self::$path;
    $cityList = self::getCityList();

    $lenCityList = count($cityList);
    $cityListHtml = '';
    for ($i=0;$i<$lenCityList;$i++){
      if($from == $cityList[$i]->{'value'}){
        $sel = 'selected';
      }
      else{
        $sel = '';
      }
      $cityListHtml = $cityListHtml.'<option '.$sel.' val='.$cityList[$i]->{'value'}.'>'.$cityList[$i]->{'name'}.'</option>';
    };
    // подключаем view для страницы плагина
    include 'pagePlugin.php';
  }

  /**
   * Возвращает суммарный вес заказанного товара
   */
  static function getCartItemsWeight(){ 
if(!empty($_POST['orderItems'])){
$itemsCart['items'] = $_POST['orderItems'];
}else{
$cart = new Models_Cart();
$itemsCart = $cart->getItemsCart();
} 
$sumWeight = 0;
foreach ($itemsCart['items'] as $item) {
if(empty($item['countInCart'])) {
$sumWeight += $item['weight'] * $item['count'];
} else {
$sumWeight += $item['weight'] * $item['countInCart'];
}
}
return $sumWeight;
}

}
