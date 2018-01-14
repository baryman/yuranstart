<?php
/*
  Plugin Name: Деловые Линии. Расчет стоимости доставки.
  Description: Для установки плагина необходимо поставить шорткод [oik-delivery-dellin] в любое место в верстке страницы оформления заказа: "views/order.php".<br /><b>Для работы плагина у товаров должны быть указаны характеристики длина, ширина, высота, вес.</b>
  Author: Иван Осипов
  Version: 1.0.4
 */

new OikDeliveryDellin;

class OikDeliveryDellin{
  private static $pluginName = ''; // название плагина (соответствует названию папки)
  private static $lang = array(); // массив с переводом плагина 
  private static $path = '';
  private static $options = '';
  private static $apiUrl = 'https://api.dellin.ru/v1/public/calculator.json';
  private static $packageParams = array();  
  
  public function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
    mgDeactivateThisPlugin(__FILE__, array(__CLASS__, 'deactivate'));
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin')); 
    //Шорткод для ввода/вывода дополнительной информации по способу доставки. 
    //Обязательно должен совпадать с именем папки плагина
    mgAddShortcode('oik-delivery-dellin', array(__CLASS__, 'addDeliveryParam'));
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
    self::createCitiesCladrTable();
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
      WHERE `plugin` = \'oik-delivery-dellin\'';
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
    
    self::uploadCitiesTable();
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
        'city_from_cladr' => 'Санкт-Петербург',
        'city_kladr' => '7800000000000000000000000',
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
    $result = array();
    $dbRes = DB::query('
      SELECT id
      FROM `'.PREFIX.'delivery`
      WHERE `plugin` = \'oik-delivery-dellin\'');
    
    if($result = DB::fetchAssoc($dbRes)){
      $sql = '
        UPDATE `'.PREFIX.'delivery` 
        SET `activity` = 1 
        WHERE `plugin` = \'oik-delivery-dellin\'';
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
            self::$lang['DELIVERY_NAME'].'\', 1, 0, \'oik-delivery-dellin\')';
    
    if(DB::query($sql)){
      return DB::insertId();
    }
  }
  
  private static function createCitiesCladrTable(){
    $sql = 'CREATE TABLE IF NOT EXISTS `'.PREFIX.'cities_kladr` (
      `id` INT(11) NOT NULL AUTO_INCREMENT, 
      `name` VARCHAR(255) NOT NULL, 
	    `code` VARCHAR(25) NOT NULL, 
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
    
    if(DB::query($sql)){
      return true;
    }else{
      return false;
    }       
  }

  private static function uploadCitiesTable(){
    USER::AccessOnly('1,4','exit()');   
    
    self::getCitiesTableFile();
  }
  
  private static function getCitiesTableFile(){    
    $apiUrl = 'https://api.dellin.ru/v1/public/cities.json';
    
    $postData = array(
      "appKey" => "D4785344-BAB0-11E5-9E41-00505683A6D3",// ключ для вашего приложения
    );
    
    $jsonData = json_encode($postData);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);   
    $json = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($json);
    
    if(!empty($result->url)){
      if($file = self::uploadCitiesTableFile($result->url)){
        self::importCitiesFromFile($file);
        unlink($file);
      }      
    }
  }
  
  private static function importCitiesFromFile($file, $start = 0){ 

    $splFile = new SplFileObject($file);    
    $rowCount = -1; 
    $totalCount = 0; 
    //Чистим таблицу городов, чтобы загрузить новую
    DB::query('DELETE FROM `'.PREFIX.'cities_kladr`');
    
    $sql = 'INSERT INTO `'.PREFIX.'cities_kladr` (`name`,`code`) VALUES ';

    while(!$splFile->eof()){
      
      
      $rowCount = -1;
      
      if($totalCount > 0){
        $rowCount = 0;
      }
      
      while($rowCount < 3){        
        if($splFile->eof()){
          break;
        }
        
        $data = $splFile->fgetcsv(",");
        $rowCount++;

        if($rowCount == 0 && $totalCount == 0){        
          continue;
        }

        if(!empty($data[1]) && !empty($data[2])){
          $cities .= '(\''.$data[1].'\', \''.$data[2].'\'),';            
        }        
      }
    }  
    
    DB::query(substr($sql.$cities, 0, -1));
    $totalCount += $rowCount;                          
  }

  private static function uploadCitiesTableFile($url){
    $fileName = 'cities-kladr.csv';
    $ch = curl_init($url);
    $fp = fopen($fileName, 'wb');
    $errFile = fopen('KladrCitiesUploadErr.txt', 'wb');
    
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_STDERR, $errFile);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    if(!curl_exec($ch)){
      MG::loger('Ошибка curl: '.curl_errno($ch).': '.curl_error($ch));
      MG::loger('Error URL: '.$url);
      return false;
    }
    
    curl_close($ch);
    fclose($fp);
    fclose($errFile);
    
    return $fileName;
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
    } else {
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
    $deliveryId = self::$options['delivery_id'];        
    $selectedDeliveryId = empty($_POST['delivery']) ? 0 : $_POST['delivery'];    
    $deliveryInfo = '';
    $show = 0;
    
    if($selectedDeliveryId == $deliveryId){
      $show = 1;            
    }    
    
    $res = '
      <span class="delivery-addition-info oik-delivery-dellin delivery'.$deliveryId.'" style="display:none;">      
      <div class="deliveryInfo" show="'.$show.'">
        <input type="hidden" name="dellin_delivery_id" value="'.$deliveryId.'"/>        
        <input type="text" placeholder="Укажите город доставки" value="" name="arrivalCity" 
                id="oik-dellin-delivery-city" />
        <div class="popupList"></div>
        <input type="hidden" name="arrivalPoint" value="" />
        <a href="javascript:void(0);" class="oik-dellin-clear-field">Очистить</a>
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
            $arDelivery = self::getPrice($args['args'][0]['arrivalPoint']);
            
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
          //Если все данные на месте, вызываем функцию расчета стоимости доставки
          $arDelivery = self::getPrice($_SESSION['delivery'][$deliveryId]['arrivalPoint']);   
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
  public static function getPrice($arrivalPoint){

    //Объявляем необходимые переменные
    $options = self::$options;
    $apiUrl = "https://api.dellin.ru/v1/public/calculator.json";
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
      //Собираем массив данных для расчета стоимости доставки
      $postData = array(
        "appKey" => "D4785344-BAB0-11E5-9E41-00505683A6D3",// ключ приложения
        "derivalPoint" => $options['city_kladr'], // код КЛАДР пункта отправки  (обязательное поле)
        "derivalDoor" => false, // необходима доставка груза от адреса     (необязательный параметр), true/false
        "arrivalPoint" => $arrivalPoint, // код КЛАДР пункта прибытия (обязательный параметр)
        "arrivalDoor" => false, // необходима доставка груза до адреса (необязательный параметр), true/false
        "sizedVolume" => $packageParams['volume'], // общий объём груза в кубических метрах (обязательный параметр)
        "sizedWeight" => $packageParams['weight'], // общий вес груза в килограммах (обязательный параметр)
        //"length" => $packageParams['length'], // длинна самого длинного из мест (необязательный параметр)
        //"width" => $packageParams['width'],  // ширина самого широкого из мест (необязательный параметр)
        //"height" => $packageParams['depth'], // высота самого высокого из мест (необязательный параметр)
      );
      $jsonData = json_encode($postData);
      //Формируем и выполняем CURL запрос
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $apiUrl);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); 
      $result = curl_exec($ch);      
      curl_close($ch);
      //Разбираем полученные JSON данные
      $arResult = json_decode($result);    
      
      //Обрабатываем ошибки
      if(!empty($arResult->errors) || empty($arResult)){
        $arReturn['error'] = 1;
        //Чтобы не вывалило ошибку, если вдруг просто с интернетом какая-то беда
        $arReturn['error_message'] = 'Не возможно получить ответ от сервера расчета стоимости';
        
        if(!empty($arResult->errors->messages)){
          $arReturn['error_message'] = '<br />Невозможно рассчитать стоимость:';
          
          foreach($arResult->errors->messages as $field => $cond){
            $arReturn['error_message'] .= '<br />'.$cond;
          }
        }elseif(!empty($arResult->errors->message)){
          $arReturn['error_message'] = $arResult->errors->message;
        }elseif(is_object($arResult->errors)){
          $arReturn['error_message'] = '<br />Обнаружены ошибки в передаваемых серверу расчета данных:';
          
          foreach($arResult->errors as $field => $cond){
            $arReturn['error_message'] .= '<br />'.$field.': '.$cond;
          }
        }else{
          $arReturn['error_message'] = $arResult->errors;
        }
      }else{
        $arReturn['delivery_sum'] = $arResult->price;
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
