<?php

/*
  Plugin Name: Расчет стоимости доставки DDelivery
  Description: Для установки плагина необходимо поставить шорткод [mg-delivery-ddelivery] в любое место в верстке страницы оформления заказа.<br /><b>В дефолтном шаблоне шорткод вставлять не нужно.</b>
  Author: Осипов Иван
  Version: 2.0.1
 */

new MGDeliveryDDelivery;

class MGDeliveryDDelivery{

  private static $pluginName = ''; // название плагина (соответствует названию папки)
  private static $lang = array(); // массив с переводом плагина 
  private static $path = '';
  private static $options = '';
  private static $apiUrl = 'http://cabinet.ddelivery.ru';
  private static $packageParams = array();
  public static $companyList = array();

  public function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate')); //Инициализация  метода выполняющегося при активации
    //mgDeactivateThisPlugin(__FILE__, array(__CLASS__, 'deactivate'));
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin')); //Инициализация  метода выполняющегося при нажатии на кнопку настроект плагина
    mgAddShortcode('mg-delivery-ddelivery', array(__CLASS__, 'addDeliveryParam'));
    mgAddAction('models_order_isvaliddata', array(__CLASS__, 'getDeliveryPrice'),1);       
    mgAddAction('controllers_order_getpaymentbydeliveryid', array(__CLASS__, 'getDeliveryPrice'),1);   
    mgAddAction('Models_Order_addOrder', array(__CLASS__, 'addOrderDeliveryInfo'), 1);
    mgAddAction('Models_Order_updateOrder', array(__CLASS__, 'addOrderDeliveryInfo'), 1);
    mgAddAction('mg_start', array(__CLASS__, 'getDeliveryParamForm'));
    
    self::$pluginName = PM::getFolderPlugin(__FILE__);
    self::$path = PLUGIN_DIR.self::$pluginName;
    self::$lang = PM::plugLocales(self::$pluginName);
    self::$options = unserialize(stripcslashes(MG::getSetting(self::$pluginName.'-option')));
       
    if(!URL::isSection('mg-admin')){
      mgAddMeta('<script type="text/javascript" src='.SITE.'/'.self::$path.'/js/script.js></script>', 'order');
      mgAddMeta('<script type="text/javascript" src='.SITE.'/'.self::$path.'/js/dd.js></script>');
    }	
        
	  mgAddMeta('<link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/style.css" type="text/css" />');
  }
  
  public static function getCityAutocomplete($q){
    $url = self::$apiUrl.'/daemon/?_action=autocomplete&q='.$q;
    $result = self::sendCurl($url);         
    
    return $result->options;
  }

  public static function getDeliveryParamForm(){  
    if(!URL::isSection('ddelivery-form')){
      return;
    }
      
    MG::disableTemplate();        
    
    if(empty($_POST['cityInfo'])){
      $cityInfo = self::getCityByIP();
    }else{
      $cityInfo = (object) $_POST['cityInfo'];
    }    
    
    if($cityId['error']){
      
    }
    
    self::$packageParams = self::getPackageParams();
    
    $dCompany1List = self::getDCompanyList($cityInfo->city_id, 1);  //Компании доставки самовывозом
    $dCompany2List = self::getDCompanyList($cityInfo->city_id, 2);  //Компании доставки курьером        
    
    $minCompany1Price = 0;
    $delivery1List = '';
    
    foreach($dCompany1List as $company){      
      if($minCompany1Price == 0){
        $minCompany1Price = $company->client_price;
        continue;
      }
      
      if($company->client_price < $minCompany1Price){
        $minCompany1Price = $company->client_price;
      }
            
      self::$companyList[$company->delivery_company] = $company;
      $delivery1List .= $company->delivery_company.',';
    }
    
    $delivery1List = substr($delivery1List, 0, -1);      
    
    ob_start();
    include($realDocumentRoot.PLUGIN_DIR.self::$pluginName.'/views/form.php');
    $form = ob_get_contents();
    ob_end_clean();
    
    echo $form;
    exit();
  }
  
  public static function getMapPoints($cityId, $companies){
    $url = self::$apiUrl.'/daemon/?_action=delivery_points&cities='.$cityId.'&companies='.$companies;
    $result = self::sendCurl($url);  
    
    return $result->points;
  }
  
  public static function getPointInfo($point){    
    $packageParams = self::getPackageParams();
    self::$packageParams = $packageParams;
    
    $url = self::$apiUrl.'/api/v1/'.self::$options['api_key'].'/calculator.json?delivery_point='.$point.'&type=1';    
    $url .= '&dimension_side1='.$packageParams['width'].'&dimension_side2='.$packageParams['depth'].'&dimension_side3='.$packageParams['length'].
            '&weight='.$packageParams['weight'].'&declared_price='.$packageParams['price'];
    $result = self::sendCurl($url); 
    $point = $result->response[0];
    
    $point->client_price = $point->delivery_price+$point->sorting_price+$point->declared_price_fee+$point->payment_price_fee+$point->packing_price;
    $point->date_preaty = $point->delivery_date;
//    $point->typeText = (intval($point->type)==2) ? 'Пункт выдачи заказов';
    
    return $point;
  }
  
  public static function getDCompanyList($cityId, $dType){
    $options = self::$options;   
    
    if(!empty(self::$packageParams)){
      $packageParams = self::$packageParams;
    }else{
      $packageParams = self::getPackageParams();
    }
    
    
    $url = self::$apiUrl.'/api/v1/'.self::$options['api_key'].'/calculator.json?city_to='.$cityId.'&type='.$dType;    
    $url .= '&dimension_side1='.$packageParams['width'].'&dimension_side2='.$packageParams['depth'].'&dimension_side3='.$packageParams['length'].
            '&weight='.$packageParams['weight'].'&declared_price='.$packageParams['price'];
    $result = self::sendCurl($url);
    
    if($result->success == 1){
      $dCompanyListNew = array();
      $dCompanyList = $result->response;
      
      foreach($dCompanyList as $cell=>$company){      
        $price = $company->delivery_price+$company->sorting_price+$company->declared_price_fee+$company->payment_price_fee+$company->packing_price;   
        $company->client_price = $price;
        $dCompanyListNew[$company->delivery_company] = $company;        
      }
      
      return $dCompanyListNew;
    }            
    
    return array(
      'error' => 1,
      'message' => 'Ошибка получения данных о компаниях доставки'
    );                
  }
  
  private static function getCityByIP(){
    $ip = $_SERVER['REMOTE_ADDR'];    
    $url = self::$apiUrl.'/daemon/?_action=geoip&ip=85.143.139.186';
    $result = self::sendCurl($url);
    
    if($result->success == 1 && !empty($result->result)){
      return $result->result;
    }
    
    return array(
      'error' => 1,
      'message' => 'Ошибка получения данных о местоположении'
    );     
  }
  
  static function addDeliveryParam(){    
    $deliveryId = self::$options['delivery_id'];    
    $selectedDeliveryId = empty($_POST['delivery']) ? 0 : $_POST['delivery'];
    $deliveryInfo = '';
    $show = 0;    
    
    if($selectedDeliveryId == $deliveryId){
      $show = 1;            
    }
    
    if(is_array($_SESSION['delivery'][$deliveryId]['result'])){      
      $arDeliveryInfo = $_SESSION['delivery'][$deliveryId]['result'];
      $type = ($arDeliveryInfo['type'] == 1) ? 'Самовывоз' : 'Курьерская доставка';
//      $deliveryInfo = $type.': '.$arDeliveryInfo['city_name'].', '.$arDeliveryInfo['company_name'].', '.$arDeliveryInfo['address'];
    }
    
//    $dDeliveryForm = self::getDeliveryParamForm();
    
    $result = '      
      <span class="delivery-addition-info delivery'.$deliveryId.'" style="display:none;">
        <div class="ddelivery-popup-select" style="display:none;">
          <div class="map-loader"><img src="'.SITE.'/'.self::$path.'/images/loader-2.gif" width=200px" height="200px" /></div>
          <a href="javascript:void(0);" id="close_popup_ddelivery">&#10006;</a>
          <div id="ddelivery_container_place" style="background: #fff;"></div>
          <a href="javascript:void(0);" id="send_order_ddelivery" class="custom-btn"><span>Выбрать</span></a>
        </div>        
        <input type="hidden" name="sdk_id" id="sdk_id" value="" />
        <input type="hidden" value="'.$deliveryId.'" name="dd_delivery_id" />              
        <a href="javascript:void(0);" id="ddelivery_select_params">выбрать</a>
        <div class="deliveryInfo" show="'.$show.'">'.$arDeliveryInfo['info'].'</div>
      </span>      
    ';
    
    return $result;
  }
  
  private static function sendCurl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $json = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($json);
  }
  
  static function activate(){
    USER::AccessOnly('1,4','exit()');
    self::setDefultPluginOption();    
  }
  
  /**
   * Вывод страницы плагина в админке
   */
  static function pageSettingsPlugin() {
    USER::AccessOnly('1,4','exit()');
    unset($_SESSION['delivery']);
    echo '
      <link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/style.css" type="text/css" />
      <script type="text/javascript">
        includeJS("'.SITE.'/'.self::$path.'/js/script.js");          
      </script> ';

    $lang = self::$lang;
    $pluginName = self::$pluginName;
    $options = self::$options;        
    
    $data['propList'] = self::getPropList();
    
    // подключаем view для страницы плагина
    include 'pageplugin.php';
  }
  
  private static function getPropList(){
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
  
  private static function setDefultPluginOption(){
    USER::AccessOnly('1,4','exit()');        
    
    $deliveryId = self::getDeliveryForPlugin();
    
    if(MG::getSetting(self::$pluginName.'-option') == null || empty($deliveryId)){            
      
      if(empty($deliveryId)){
        $deliveryId = self::setDeliveryForPlugin();        
      }
      
      $arPluginParams = array(        
        'delivery_id' => $deliveryId,
        'api_key' => '852af44bafef22e96d8277f3227f0998',                
        'lengthPropId' => '',
        'widthPropId' => '',        
        'depthPropId' => '',
        'test_mode' => 1,       
      );      
      
      MG::setOption(array('option' => self::$pluginName.'-option', 'value' => addslashes(serialize($arPluginParams))));
    }
    
    $sql = 'CREATE TABLE IF NOT EXISTS `mg-ddelivery-price-order` (
      `sdk_id` INT(11) NOT NULL,
      `sdk_price` DOUBLE NOT NULL
      )';
    DB::query($sql);
  }
  
  static function addOrderDeliveryInfo($args){
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
    }
    
    if($admin){
      unset($_SESSION['deliveryAdmin']);
    }else{
      unset($_SESSION['delivery']);
    }       
    
    return $args['result'];
  }


  /**
   * Возвращает идентификатор записи доставки из БД для плагина, по полю 'name'
   */
  static function getDeliveryForPlugin(){
    $result = array();
    $dbRes = DB::query('
      SELECT id
      FROM `'.PREFIX.'delivery`
      WHERE `plugin` = \'mg-delivery-ddelivery\'');
    
    if($result = DB::fetchAssoc($dbRes)){
      $sql = '
        UPDATE `'.PREFIX.'delivery` 
        SET `activity` = 1 
        WHERE `plugin` = \'mg-delivery-ddelivery\'';
      DB::query($sql);
      
      return $result['id'];
    }    
  }

  /**
   * Добавляет в бд запись с типом доставки для плагина и возвращает её идентификатор
   */
  static function setDeliveryForPlugin(){
    USER::AccessOnly('1,4','exit()');
    
    $sql = '
      INSERT INTO '.PREFIX.'delivery (`name`,`cost`,`description`,`activity`,`free`, `plugin`) VALUES
      (\''.self::$lang['DELIVERY_NAME'].'('.self::$lang['FROM_PLUGIN'].')\', 0, \''.self::$lang['DELIVERY_NAME'].'\', 1, 0, \'mg-delivery-ddelivery\')';
    
    if(DB::query($sql)){
      return DB::insertId();
    }    
  } 
  
  public static function getDeliveryPrice($args){    
    $selectedDeliveryId = 0; 
    $deliveryArgs = array();
    $bError = (empty($args['result']) || is_array($args['result'])) ? false : true;    

    if(!$bError){
      $deliveryId = self::$options['delivery_id'];            
      
      if(is_array($args['args'][0])){
        $deliveryArgs = $args['args'][0];
        $selectedDeliveryId = $deliveryArgs['delivery'];
      }else{
        $selectedDeliveryId = $args['args'][0];    
      }              
      
      if($selectedDeliveryId == $deliveryId){      
        if(!empty($_SESSION['delivery'][$deliveryId])){
          $deliveryInfo = $_SESSION['delivery'][$deliveryId]['result'];
          
          if(!empty($deliveryArgs)){
            if(empty($deliveryInfo['company_id']) || empty($deliveryInfo['type']) || empty($deliveryInfo['city_id'])){
              $args['result'] = "Не указана точка доставки!";
            }else{
              $price = self::getPrice($deliveryId);
              
              if(empty($price['error'])){
                $args['args']['this']->delivery_cost = $price['delivery_sum'];
              }else{
                $$args['result'] = $price['error'];
              }              
            }            
          }else{
            $price = self::getPrice($deliveryId);
            
            if(empty($price['error'])){
              $settings = MG::get('settings');
              $args['result']['summDelivery'] = $price['delivery_sum'].' '.$settings['currency'];
            }else{
              $args['result']['summDelivery'] = -1;
              $args['result']['error'] = $price['error'];
            }
          }                    
        }else{
          $args['result']['summDelivery'] = -1;
          $args['result']['error'] = self::$lang['DELIVERY_COST_CALCULATE_ERROR'].': выберите способ доставки!';
        }      
      }      
    }    
    
    return $args['result'];
  }        
  
  public static function getApiKey(){
    $options = self::$options;
    return self::$options['api_key'];
  }
  
  public static function getProductCart($items = array()){    
    $options = self::$options;
    $resParams = array(
      'weight' => 0,
      'width' => 0,
      'length' => 0,
      'depth' => 0,
    );
    
    $arProductList = array();
    $prodIds = array();
    $prodCount = array();
    $weight = 0;
    $strParamIds = 0;
    
    if(empty($_SESSION['orderItems'])){      
      $cart = new Models_Cart();
      $itemsCart = $cart->getItemsCart();   
      
      foreach($itemsCart['items'] as $item){
        $arProductList[$item['id']] = array(
          'id'        => $item['id'],
          'name'      => $item['title'],          
          'weight'    => $item['weight'],
          'price'     => $item['price'],
          'quantity'  => $item['countInCart'],
          'sku'       => $item['product_url']
        );
        
        if($item['weight'] <= 0){
          if(intval($options['defaultWeight']) > 0){
            $arProductList[$item['id']]['weight'] = $options['defaultWeight'];
          }else{
            return false;
          }          
        }
                
        $prodIds[] = $item['id'];         
      }                
    }else{      
      $items = $_SESSION['orderItems'];
      foreach($items as $item){
        $arProductList[$item['id']] = array(
          'id'        => $item['id'],
          'name'      => $item['title'],          
          'weight'    => $item['weight'],
          'price'     => $item['price'],
          'quantity'  => $item['count'],
          'sku'       => $item['url']
        );
        
        if($item['weight'] <= 0){
          if(intval($options['defaultWeight']) > 0){
            $arProductList[$item['id']]['weight'] = $options['defaultWeight'];
          }else{
            return false;
          }          
        }
                
        $prodIds[] = $item['id'];
      }
      //unset($_SESSION['orderItems']);
    }            
    
    if(empty($prodIds)){
      return $arProductList;
    }        
    
    $strParamIds = $options['lengthPropId'].','.$options['widthPropId'].','.$options['depthPropId'];       
    
    $sql = '
      SELECT product_id, property_id, value 
      FROM `'.PREFIX.'product_user_property` 
      WHERE `product_id` IN ('.implode(',', $prodIds).') AND 
            `property_id` IN ('.$strParamIds.')';        
    
    if($dbRes = DB::query($sql)){      
      while($arRes = DB::fetchAssoc($dbRes)){        
        $value = intval($arRes['value']);                
        
        switch($arRes['property_id']){
          case $options['widthPropId']:                        
            $arProductList[$arRes['product_id']]['width'] = $value;            
            break;
          case $options['lengthPropId']:            
            $arProductList[$arRes['product_id']]['length'] = $value;
            break;
          case $options['depthPropId']:            
            $arProductList[$arRes['product_id']]['height'] = $value;
            break;
        }                
      }      
      
      foreach($arProductList as $cell=>$item){
        if(empty($item['width'])){
          if(intval($options['defaultWidth']) > 0){
            $arProductList[$cell]['width'] = $options['defaultWidth'];
          }else{
            return false;
          } 
        }
        
        if(empty($item['height'])){
          if(intval($options['defaultDepth']) > 0){
            $arProductList[$cell]['height'] = $options['defaultDepth'];
          }else{
            return false;
          } 
        }
        
        if(empty($item['length'])){
          if(intval($options['defaultLength']) > 0){
            $arProductList[$cell]['length'] = $options['defaultLength'];
          }else{
            return false;
          } 
        }
      }
    }
    
    return $arProductList;
  }    
  
  public static function getPrice($deliveryId){       
    $options = self::$options;
    $url = 'http://cabinet.ddelivery.ru:80/api/v1/'.$options['api_key'].'/calculator.json?';
    $deliverySum = 0;
    $arReturn = array(
      'error' => 0,     
      'delivery_sum' => 0
    );
    $cartItems = array();
    
    if(!empty($_POST['orderItems'])){      
      if(empty($_SESSION['deliveryAdmin'][$deliveryId])){
        $arReturn['delivery_sum'] = -1;
        $arReturn['error'] = 'Не переданы данные о выбранном способе доставки!';
      }else{
        $result = $_SESSION['deliveryAdmin'][$deliveryId]['result'];
      }
    }else{
      if(empty($_SESSION['delivery'][$deliveryId])){
        $arReturn['delivery_sum'] = -1;
        $arReturn['error'] = 'Не переданы данные о выбранном способе доставки!';
      }else{
        $result = $_SESSION['delivery'][$deliveryId]['result'];
      }        
    }
    
    $type = ($result['type'] != 1) ? 2 : 1;
    $city = ($result['city_id']) ? $result['city_id'] : $result['city'];

    $url .= 'type='.$type.'&city_to='.$city;
    
    if(!empty($result['cart']['products'])){
      $cartItems = $result['cart']['products'];
    }
    
    $packageParams = self::getPackageParams($cartItems);      
    
    if(!$packageParams || $packageParams['error']){
      $arReturn['delivery_sum'] = -1;
      $arReturn['error'] = (isset($packageParams['error_message'])) ? $packageParams['error_message'] : 'Не у всех товаров заданы обязательные параметры: вес и габариты';
      
      return $arReturn;
    } 

    $url .= '&weight='.$packageParams['weight'].'&dimension_side1='.$packageParams['width'].'&dimension_side2='.$packageParams['length'].'&dimension_side3='.$packageParams['depth'];
    $url .= '&declared_price='.$packageParams['price'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);      
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);             
    $json = curl_exec($ch);
    $curlRes = json_decode($json);
    $objResult = $curlRes->response;

    if($objResult->message){     
      $arReturn['error'] = 'Не переданы данные о выбранном способе доставки!';
    }else{ 
      $companyFind = false;
      foreach($objResult as $delivery){
        if($delivery->delivery_company == $result['company_id']){ 
          $companyFind = true;
          $sum = $delivery->delivery_price+$delivery->sorting_price+$delivery->declared_price_fee+$delivery->payment_price_fee+$delivery->packing_price;
          
          if($result['type'] == 1){
            $price = ceil($sum);
          }else{
            $price = ceil($sum);
          }
          
          $arReturn['delivery_sum'] = $sum;
          break;
        }
      }            

      if(!$companyFind){
        $arReturn['error'] = 'При таком составе заказа, доставка через выбранную ранее компанию невозможна. Выберите другой вариант доставки.';
      }
      
      $dbRes = DB::query('SELECT `free` FROM `'.PREFIX.'delivery` WHERE `id` = '.DB::quote($options['delivery_id'], true));
        
      if($delivery = DB::fetchAssoc($dbRes)){          
        $cartData = SmalCart::getCartData();              
        $cartPrice = MG::numberDeFormat($cartData['cart_price']);          

        if($delivery['free'] > 0 && $cartPrice >= $delivery['free']){
          $arReturn['delivery_sum'] = 0;
        }
      }
    }          
    
    return $arReturn;
  }
  
  /**
   * Возвращает суммарный вес и габариты товара в заказе
   */
  private static function getPackageParams($items = array()){    
    $options = self::$options;
    $resParams = array(
      'weight' => 0,
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
        $value = intval($arRes['value']);                
        
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

    foreach($arParams['val'] as $param=>$values){
      if($param == $minParamName){
        continue;
      }

      $resParams[$param] = max($values);
    }          
    
    return $resParams;
  }
  
}
