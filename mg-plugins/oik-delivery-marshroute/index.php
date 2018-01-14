<?php
/*
  Plugin Name: Marschroute.ru. Расчет стоимости доставки.
  Description: Для установки плагина необходимо поставить шорткод [oik-delivery-marshroute] в любое место в верстке страницы оформления заказа: "views/order.php".<br /><b>В дефолтном шаблоне шорткод вставлять не нужно.</b>
  Author: Иван Осипов
  Version: 1.0.1
 */

new OikDeliveryMarshroute;

class OikDeliveryMarshroute{
  private static $pluginName = ''; // название плагина (соответствует названию папки)
  private static $lang = array(); // массив с переводом плагина 
  private static $path = '';
  private static $options = '';  
  private static $apiKey = '';
  private static $packageParams = array();  
  
  public function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
    mgDeactivateThisPlugin(__FILE__, array(__CLASS__, 'deactivate'));
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin')); 
    //Шорткод для ввода/вывода дополнительной информации по способу доставки. 
    //Обязательно должен совпадать с именем папки плагина
    mgAddShortcode('oik-delivery-marshroute', array(__CLASS__, 'addDeliveryParam'));
    //Обработчик для повторного расчета стоимости доставки, при оправке данных заказа
    mgAddAction('models_order_isvaliddata', array(__CLASS__, 'getDeliveryPrice'),1);
    //Расчет стоимости доставки в момент получения списка способов оплаты(5.9+)
    mgAddAction('controllers_order_getpaymentbydeliveryid', array(__CLASS__, 'getDeliveryPrice'),1);
    //Добавление дополнительной информации о способе доставки в базу(5.9+)
    mgAddAction('Models_Order_addOrder', array(__CLASS__, 'addOrderDeliveryInfo'), 1);
    mgAddAction('Models_Order_updateOrder', array(__CLASS__, 'addOrderDeliveryInfo'), 1);

    self::$pluginName = PM::getFolderPlugin(__FILE__); // имя плагина
    self::$lang = PM::plugLocales(self::$pluginName); // получение строк локали плагина        
    self::$path = PLUGIN_DIR.self::$pluginName;
    self::$options = unserialize(stripcslashes(MG::getSetting(self::$pluginName.'-option')));
    self::$apiKey = self::$options['api_key'];
    
    mgAddMeta('<script type="text/javascript" src='.SITE.'/'.self::$path
            .'/js/script.js></script>', 'order');
    mgAddMeta('<link rel="stylesheet" href="'.SITE.'/'.self::$path
            .'/css/style.css" type="text/css" /> ', 'order');
  }
  
  /**
   * Действия при активации плагина
   */
  public static function activate(){
    USER::AccessOnly('1,4','exit()');
    self::setDefultPluginOption();    
  }
  
  /**
   * Действия при деактивации плагина
   */
  public static function deactivate(){
    USER::AccessOnly('1,4','exit()');
    $sql = '
      UPDATE `'.PREFIX.'delivery` 
      SET `activity` = 0 
      WHERE `plugin` = '.DB::quote(self::$pluginName);
    DB::query($sql);
  }
  
  /**
   * Вывод страницы плагина в админке
   */
  public static function pageSettingsPlugin() {
    USER::AccessOnly('1,4','exit()');    
    
    echo '
      <link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/style.css" type="text/css" />
      <script type="text/javascript">
        includeJS("'.SITE.'/'.self::$path.'/js/admin.js");          
      </script> ';

    $lang = self::$lang;
    $pluginName = self::$pluginName;
    $options = self::$options;        
    
    $data['propList'] = self::getPropList();
    
//    self::uploadCitiesTable();
    // подключаем view для страницы плагина
    include 'pageplugin.php';
  }
  
  private static function getPropList() {
    $arResult = array();
    $sql = '
      SELECT `id`, `name` 
      FROM `'.PREFIX.'property` 
      WHERE `activity` = 1 AND `type` = \'string\'';
    
    if($dbRes = DB::query($sql)){
      while($result = DB::fetchAssoc($dbRes)){
        $arResult[$result['id']] = $result['name'];
      }
    }
    
    return $arResult;
  }
  
  /**
   * Устанавливаем дефолтные настройки плагина, при первой активации
   */
  private static function setDefultPluginOption(){
    USER::AccessOnly('1,4','exit()');        
    
    $deliveryId = self::getDeliveryForPlugin();
    
    if(MG::getSetting(self::$pluginName.'-option') == null || empty($deliveryId)){            
      
      if(empty($deliveryId)){
        $deliveryId = self::setDeliveryForPlugin();        
      }
      
      $arPluginParams = array(        
        'delivery_id' => $deliveryId,
        'api_key' => '',        
        'lengthPropId' => '',
        'widthPropId' => '',        
        'depthPropId' => '',
        'defaultLength' => 0.1,
        'defaultWidth' => 0.1,
        'defaultDepth' => 0.1,
      );      
      
      MG::setOption(array('option' => self::$pluginName.'-option', 
        'value' => addslashes(serialize($arPluginParams))));            
    }                
  }
  
  /**
   * Возвращает идентификатор записи доставки из БД для плагина, по полю 'name'
   */
  private static function getDeliveryForPlugin(){
    USER::AccessOnly('1,4','exit()');
    
    $result = array();
    $dbRes = DB::query('
      SELECT id
      FROM `'.PREFIX.'delivery`
      WHERE `plugin` = '.DB::quote(self::$pluginName));
    
    if($result = DB::fetchAssoc($dbRes)){
      $sql = '
        UPDATE `'.PREFIX.'delivery` 
        SET `activity` = 1 
        WHERE `plugin` = '.DB::quote(self::$pluginName);
      DB::query($sql);
      
      return $result['id'];
    }    
  }

  /**
   * Добавляет в бд запись с типом доставки для плагина и возвращает её идентификатор
   */
  private static function setDeliveryForPlugin(){
    USER::AccessOnly('1,4','exit()');
    
    $sql = '
      INSERT INTO '.PREFIX.'delivery (`name`,`cost`,`description`,`activity`,`free`, `plugin`) VALUES
      (\''.self::$lang['DELIVERY_NAME'].'('.self::$lang['FROM_PLUGIN'].')\', 0, \''.
            self::$lang['DELIVERY_NAME'].'\', 1, 0, '.DB::quote(self::$pluginName).')';
    
    if(DB::query($sql)){
      return DB::insertId();
    }
  }
  
  /**
   * Функция вызывается по хуку добавления нового заказа, и записывает в этот заказ 
   * дополнительные параметры доставки
   * @param type $args
   * @return type
   */
  public static function addOrderDeliveryInfo($args){         
    $deliveryIdPlugin = self::getDeliveryForPlugin();     
    $deliveryId = 0;
    $admin = URL::isSection('mg-admin');
    
    if($admin){            
      $orderId = $args['args'][0]['id'];
      
      if(empty($orderId)){
        $orderId = $args['result']['id'];
      }
    }else{
      $orderId = $args['result']['id'];
    }
    
    if($dbRes = DB::query('
      SELECT `delivery_id` 
      FROM `'.PREFIX.'order` 
      WHERE `id` = '.DB::quote($orderId, true))){
      
      if($res = DB::fetchAssoc($dbRes)){
        $deliveryId = $res['delivery_id'];
      }
    } 
    
    if(!empty($deliveryId) && $deliveryIdPlugin == $deliveryId){
      if($admin){          
        $options = $_SESSION['deliveryAdmin'][$deliveryId];
      }else{
        $options = $_SESSION['delivery'][$deliveryId];
      }         

      $sql = '
        UPDATE `'.PREFIX.'order` 
        SET `delivery_options` = '.DB::quote(addslashes(serialize($options))).' 
        WHERE `id` = '.DB::quote($orderId, true);

      DB::query($sql);
      
      if($admin){
        unset($_SESSION['deliveryAdmin']);
      }else{
        unset($_SESSION['delivery']);
      }
    }          
    
    return $args['result'];
  }
  
  /**
   * Вывод дополнительной информации о способе доставки по шорткоду
   * @return string
   */
  public static function addDeliveryParam(){ 
//    unset($_SESSION['delivery']);
    
    $deliveryId = self::$options['delivery_id'];        
    $selectedDeliveryId = empty($_POST['delivery']) ? 0 : $_POST['delivery'];    
    $deliveryInfo = '';
    $show = 0;
    
    if($selectedDeliveryId == $deliveryId){
      $show = 1;            
    }    
    
    $res = '
      <span class="delivery-addition-info '.self::$pluginName.' delivery'.$deliveryId.'" style="display:none;">      
      <div class="deliveryInfo" show="'.$show.'">
        <input type="hidden" name="marshroute_delivery_id" value="'.$deliveryId.'"/>        
        <input type="text" placeholder="Укажите город доставки" value="" name="arrivalCity" 
                id="oik-marshroute-delivery-city" style="width:100%;margin-top:5px" /><br />
        <a href="javascript:void(0);" class="oik-marshroute-apply-city">Показать варианты доставки</a>
        <a href="javascript:void(0);" class="oik-marshroute-clear-field">Очистить</a><br />
        <select id="oik-marshroute-delivery-correct" style="display:none;width:100%;margin-top:5px">
          <option value="0">Уточните населенный пункт</option>
        </select>
        <select id="oik-marshroute-delivery-variant" style="display:none;width:100%;margin-top:5px">
          <option value="0">Уточните населенный пункт</option>
        </select>
        <div class="address4courier" style="display: none;">
          Улица: <input type="text" name="street" style="width:395px;margin-top:5px" /><br />
          Дом: <input type="text" name="house" style="width:90px;margin-top:5px;margin-right:11px" />
          Квартира: <input type="text" name="flat" style="width:90px;margin-top:5px;margin-right:11px" />
          Индекс: <input type="text" name="index" style="width:90px;margin-top:5px" />
          <div style="display:none;color: #ff0000;" class="second-line-empty-error"></div>
        </div>
        <input type="hidden" name="deliveryCourier" value=""/>
        <input type="hidden" name="arrivalPoint" value="" />
        <input type="hidden" name="deliveryType" value="" />
        <span class="error" style="margin-top:5px"></span>
      </div>
      </span>
    ';
    
    return $res;
  }
  
  /**
   * Обработчик хука оформления заказа, или запроса данных о способах оплаты и стоимости достаки
   * @param type $args
   * @return string
   */
  public static function getDeliveryPrice($args){
    $selectedDeliveryId = 0;
    $deliveryArgs = array();    
    MG::loger(print_r($args, true));
    //Проверяем, не пришло ли в поле 'result' соотбщение об ошибке
    $bError = (empty($args['result']) || is_array($args['result'])) ? false : true;
    
    if(!$bError){
      //Достаем из настроек плагина id способа доставки
      $deliveryId = self::$options['delivery_id'];
      //При сработывании функции на хуке models_order_isvaliddata, при оформлении заказа
      //в $args['args'][0] будет содержаться массив данных
      if(is_array($args['args'][0])){
        $deliveryArgs = $args['args'][0];
        $selectedDeliveryId = $deliveryArgs['delivery'];
      }else{ //при запросе стоимости доставки, в $args['args'][0] только id способа доставки
        $selectedDeliveryId = $args['args'][0];    
      }                   
      //Проверка id способа доставки, совпадает ли он с тем что нам нужно обработать
      if($selectedDeliveryId == $deliveryId){
        //Обработка хука models_order_isvaliddata
        if(!empty($deliveryArgs)){ 
          if(empty($args['args'][0]['arrivalPoint'])){
            $args['result'] = "Не указан город доставки!";
          }else{
            $params = $args['args'][0];
            
            if(!empty($params['deliveryCourier']) && (empty($params['street']) || empty($params['flat'])
                || empty($params['house']) || empty($params['index']))){
              $args['result'] = 'Не заполнены обязательные параметры курьерской доставки!';
            }
            
            $arDelivery = self::getPrice($args['args'][0]['arrivalPoint'], $args['args'][0]['deliveryType']);
            
            if($arDelivery['error'] != 1){
              $deliveryCost = $arDelivery['delivery_sum'];
              $args['args']['this']->delivery_cost = $deliveryCost;          

              if(is_null($args['args']['this']->delivery_cost)){
                $args['result'] = "Не удалось расчитать стоимость!";
              }
            }else{
              $args['result'] = $arDelivery['error_message'];
            }         
          }                   
        }else{ //Обработка хука controllers_order_getpaymentbydeliveryid
          //Проверяем наличие в сессии, необходимых для расчета стоимости, данных.
          if(empty($_SESSION['delivery'][$deliveryId]['arrivalPoint'])){
            $args['result']['error'] = "Не указан город доставки!";
          }
          
          $deliveryParams = $_SESSION['delivery'][$deliveryId];
          //Если все данные на месте, вызываем функцию расчета стоимости доставки
          $arDelivery = self::getPrice($deliveryParams['arrivalPoint'], $deliveryParams['deliveryType']);   
          //Проверяем наличие ошибок в результатах расчета функцией getPrice
          if($arDelivery['error'] != 1){
            //Если в расчете ошибок не произошло, и возвращенная стоимость больше нуля, то
            //заменяем стоимость в поле summDelivery, в результирующем массиве данных
            if($arDelivery['delivery_sum'] > 0){
              $settings = MG::get('settings');
              $args['result']['summDelivery'] = $arDelivery['delivery_sum'].' '.$settings['currency'];  
            }               
          }else{
            //Если произошла ошибка при расчетае, то помещаем её в поле error
            //и возвращаем значение стоимости -1
            $args['result']['summDelivery'] = -1;
            $args['result']['error'] = self::$lang['DELIVERY_COST_CALCULATE_ERROR'].': '.$arDelivery['error_message'];
          }                            
        }         
      }
    }    
    //Возвращаем массив данных, с внесенными изменениями, либо без них
    return $args['result'];
  }
  
  /**
   * 
   * @param type $arrivalPoint
   */
  public static function getPrice($arrivalPoint, $deliveryType){
    //Объявляем необходимые переменные
    $options = self::$options;
//     /<api_key>/delivery_city?name|index|kladr=<val>[&weight=<weight>][&payment_type=<1,2>][&parcel_size=[В,Ш,Г]][&company=1]	 	 
    $apiUrl = "https://api.marschroute.ru/".self::$apiKey."/delivery_city?kladr=".$arrivalPoint;
    $deliverySum = 0;    
    $orderItems = array();
    $packageParams = true;
    $arReturn = array(
      'error' => 0,
      'error_message' => '',
      'delivery_sum' => 0
    );
    
    //Если по какой-то причине не передан код КЛАДР пункта назначения, возвращаем ошибку
    if(empty($arrivalPoint)){
      $arReturn['error'] = 1;
      $arReturn['error_message'] = 'Не выбран город получения заказа!';
    }
    
    //Запрвашиваем из настроек способа доставки сумму, от которой заказ доставляется бесплатно
    $dbRes = DB::query('SELECT `free` FROM `'.PREFIX.'delivery` WHERE `id` = '
            .DB::quote($options['delivery_id'], true));

    if($delivery = DB::fetchAssoc($dbRes)){
      //Получаем данные корзины
      $cartData = SmalCart::getCartData();

      //Если бесплатная доставка задана, и сумма товаров в корзине больше неё, 
      //то обнуляем доставку
      if($delivery['free'] > 0 && $cartData['cart_price'] >= $delivery['free']){
        $arReturn['delivery_sum'] = 0;
        return $arReturn;
      }
    }
    
    //Если мы работаем из админки, то в поле orderItems массива $_POST, будет содержаться состав заказа
    if(!empty($_POST['orderItems'])){
      $orderItems = $_POST['orderItems'];
    }
    
    //Вызываем функцию расчета суммарных веса и габаритов заказа
    $packageParams = self::getPackageParams($orderItems);    
    
    //Если при расчете характеристик посылки возникли проблемы
    if(!$packageParams || $packageParams['error']){
      $arReturn['error'] = 1;
      $arReturn['error_message'] = (isset($packageParams['error_message'])) 
              ? $packageParams['error_message'] 
              : 'Не у всех товаров заданы обязательные параметры: вес и габариты. '
                . 'Или не заданы значения по умолчанию в настройках плагина.';      
    }
    
    //Если до этого момента никаких ошибок не было, то можно запрашивать стомость доставки через API
    //Этот кусок кода будет зависеть от реализации API службы доставки
    if($arReturn['error'] != 1){
      $apiUrl .= '&weight='.($packageParams['weight']*1000).'&parcel_size=['.
          ($packageParams['length']*1000).','.($packageParams['width']*1000).','.
          ($packageParams['depth']*1000).']';
      //Формируем и выполняем CURL запрос
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $apiUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0); 
      $result = curl_exec($ch);      
      curl_close($ch);
      //Разбираем полученные JSON данные
      $arResult = json_decode($result); 
      
      //Обрабатываем ошибки
      if(!empty($arResult->info) || empty($arResult)){
        $arReturn['error'] = 1;
        //Чтобы не вывалило ошибку, если вдруг просто с интернетом какая-то беда
        $arReturn['error_message'] = 'Не возможно получить ответ от сервера расчета стоимости';
        
        if(!empty($arResult->info->message)){
          $arReturn['error_message'] = '<br />Невозможно рассчитать стоимость: '.
              $arResult->info->message;
        }
      }else{
        $deliveryInfo = $arResult->data[0]->delivery_types->$deliveryType;
        $price = $deliveryInfo->cost;
        
        switch ($deliveryInfo->delivery_id) {
          case 2:
            if(!empty($options['office_cost'])){
              $price = $options['office_cost'];
            }
            break;
          case 3:
            if(!empty($options['post_cost'])){
              $price = $options['post_cost'];
            }
            break;
          case 1:
          case 4:
          default:
            if(!empty($options['courier_cost'])){
              $price = $options['courier_cost'];
            }
            break;
        }
        
        $arReturn['delivery_sum'] = $price;
      }
    }
    
    return $arReturn;
  }
  
  /**
   * Возвращает суммарный вес и габариты товара в заказе
   */
  public static function getPackageParams($items = array()){    
    $options = self::$options;
    $resParams = array(
      'weight' => 0,
      'volume' => 0,
      'width' => 0,
      'length' => 0,
      'depth' => 0,
      'price' => 0,
    );
    $prodIds = array();
    $prodCount = array();
    $weight = 0;
    $price = 0;
    $strParamIds = 0;
    
    if(!empty($_POST['orderItems'])){
      $items = $_POST['orderItems'];
    }
    
    if(empty($items)){
      $cart = new Models_Cart();
      $itemsCart = $cart->getItemsCart();       
      
      foreach($itemsCart['items'] as $item){
        if($item['weight'] <= 0){
          if(empty($options['defaultWeight'])){
            return false;
          }else{
            $item['weight'] = $options['defaultWeight'];
          }          
        }
        
        $price += $item['price']*$item['countInCart'];
        $weight += $item['weight']*$item['countInCart'];
        $prodIds[] = $item['id']; 
        $prodCount[$item['id']] = $item['countInCart'];
      }          
      $resParams['weight'] = $weight;
      $resParams['price'] = $price;
    }else{
      foreach($_POST['orderItems'] as $item){
        if(empty($items[$item['id']])){
          $items[$item['id']] = $item;
        }
        $items[$item['id']]['quantity'] = $item['count'];
      }
      
      foreach($items as $item){
        if($item['weight'] <= 0){
          if(empty($options['defaultWeight'])){
            return false;
          }else{
            $item['weight'] = $options['defaultWeight'];
          }          
        }
        
        $price += $item['price']*$item['quantity'];
        $weight += $item['weight']*$item['quantity'];
        $prodIds[] = $item['id']; 
        $prodCount[$item['id']] = $item['quantity'];
      }          
      $resParams['weight'] = $weight;
      $resParams['price'] = $price;
    }         
    
    if(empty($prodIds)){
      return $resParams;
    }
           
    if(empty($options['lengthPropId']) || empty($options['widthPropId']) || empty($options['depthPropId'])){
      return array(
        'error' => 1,
        'error_message' => 'Не верные настройки плагина, не заданы соответсвия полей габаритов товара.',
      );      
    }
    
    $strParamIds = $options['lengthPropId'].','.$options['widthPropId'].','.$options['depthPropId'];       
    
    $sql = '
      SELECT * 
      FROM `'.PREFIX.'product_user_property` 
      WHERE `product_id` IN ('.implode(',', $prodIds).') AND 
            `property_id` IN ('.$strParamIds.')';
    
    $productProps = array();
    
    if($dbRes = DB::query($sql)){      
      while($arRes = DB::fetchAssoc($dbRes)){  
        $count = intval($prodCount[$arRes['product_id']]);
        $value = floatval($arRes['value']);                
        
        switch($arRes['property_id']){
          case $options['widthPropId']:            
            if($value <= 0){
              if(empty($options['defaultWidth'])){
                return false;
              }else{
                $value = $options['defaultWidth'];
              }
            }     
            
            $productProps[$arRes['product_id']]['width'] = $value*$count;
            $arParams['val']['width'][] = $value;
            break;
          case $options['lengthPropId']:
            if($value <= 0){
              if(empty($options['defaultLength'])){
                return false;
              }else{
                $value = $options['defaultLength'];
              }
            }
            
            $productProps[$arRes['product_id']]['length'] = $value*$count;
            $arParams['val']['length'][] = $value;
            break;
          case $options['depthPropId']:
            if($value <= 0){
              if(empty($options['defaultDepth'])){
                return false;
              }else{
                $value = $options['defaultDepth'];
              }
            }
            
            $productProps[$arRes['product_id']]['depth'] = $value*$count;
            $arParams['val']['depth'][] = $value;
            break;
        }        
      }                                                                     
    }else{
      return array(
        'error' => 1,
        'error_message' => 'Ошибка расчета габаритов товара.',
      );
    }
    
    foreach($prodIds as $itemId){
      $count = intval($prodCount[$itemId]);        
      if(empty($productProps[$itemId]['width'])){
        $productProps[$itemId]['width'] += $options['defaultWidth']*$count;
        $arParams['val']['width'][] = $options['defaultWidth'];
      }  
      if(empty($productProps[$itemId]['length'])){
        $productProps[$itemId]['length'] += $options['defaultLength']*$count;
        $arParams['val']['length'][] = $options['defaultLength'];
      }  
      if(empty($productProps[$itemId]['depth'])){
        $productProps[$itemId]['depth'] += $options['defaultDepth']*$count;
        $arParams['val']['depth'][] = $options['defaultDepth'];
      }                  
    }

    foreach($productProps as $product){
      $arParams['sum']['width'] += $product['width'];
      $arParams['sum']['length'] += $product['length'];
      $arParams['sum']['depth'] += $product['depth'];
    }
    
    $minParam = min($arParams['sum']);    
    $minParamName = array_search($minParam, $arParams['sum']);     
    $resParams[$minParamName] = $arParams['sum'][$minParamName];
    
    $resParams['volume'] = $minParam;
    
    foreach($arParams['val'] as $param=>$values){            
      $resParams['volume'] *= max($values);      
      $resParams[$param] = max($values);
    }                  
    
    return $resParams;
  }
}
