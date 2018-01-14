<?php
class Pactioner extends Actioner {
  private static $pluginName = 'oik-delivery-marshroute';
  private static $apiKey = '';
  
  /**
   * Сохраняет  опции плагина
   * @return boolean
   */
  public function saveBaseOption(){
    USER::AccessOnly('1,4','exit()');
    $this->messageSucces = $this->lang['SAVE_BASE'];
    $this->messageError = $this->lang['NOT_SAVE_BASE'];    
    
    if(!empty($_POST['data'])) {
      MG::setOption(array('option' => self::$pluginName.'-option', 'value' => addslashes(serialize($_POST['data']))));
    }
    
    return true;
  }
  
  //Функция вызывается после выбора города доставки
  public function getPrice(){        
    unset($_POST['pluginHandler']);
    unset($_POST['actionerClass']);
    unset($_POST['action']); 
    
    if(!empty($_POST['admin'])){
      unset($_POST['admin']);   
      $_SESSION['deliveryAdmin'][$_POST['deliveryId']] = $_POST;
    }else{
      $_SESSION['delivery'][$_POST['deliveryId']] = $_POST;
    }
    
    if(URL::isSection('mg-admin') && (empty($_POST['orderItems']) || empty($_POST['arrivalPoint']))){      
      $this->data['deliverySum'] = 0;
      unset($_SESSION['deliveryAdmin'][$_POST['deliveryId']]);
    }elseif($arDelivery = OikDeliveryMarshroute::getPrice($_POST['arrivalPoint'], $_POST['deliveryType'])){
      
      if($arDelivery['error'] != 1){        
        $this->data['deliverySum'] = $arDelivery['delivery_sum'];
      }else{
        $this->data['deliverySum'] = -1;
        $this->data['error'] = $arDelivery['error_message'];
      }                 
    }
        
    return true;
  }
  
  public function getCityVariant() {
    $data = array(
      'correct' => array(),
      'variant' => array(),
      'error' => ''
    );    
    
    $options = unserialize(stripcslashes(MG::getSetting(self::$pluginName.'-option')));
    unset($_SESSION['delivery'][$_POST['deliveryId']]);
    
    if (empty($options['api_key'])) {
      $data['error'] = 'Не задан ключ доступа к API. Проверьте настройки плагина';
      $this->data = $data;
      return true;
    }
    
    if ($city = $_POST['deliveryCity']) {
      $apiUrl = "https://api.marschroute.ru/" . $options['api_key'] . "/delivery_city?name=" . $city;
      $arResult = self::getCurlData($apiUrl);
      
      if (!empty($arResult->info)) {
        $data['error'] = $arResult->info->message;
        $this->data = $data;
        return true;
      }

      if (count($arResult->data) > 1) {
        foreach ($arResult->data as &$point) {
          $data['correct'][] = array(
            'name' => $point->type_name.' '.$point->name.'('.$point->region_name.')',
            'kladr' => $point->kladr
          );
        }
      } else {
        unset($_POST['deliveryCity']);
        $_POST['deliveryKladr'] = $arResult->data[0]->kladr;
        $_POST['transfer'] = true;
        $data = self::getCityVariant();        
      }
    } else if ($kladr = $_POST['deliveryKladr']) {
      $apiUrl = "https://api.marschroute.ru/" . $options['api_key'] . "/delivery_city?kladr=" . $kladr;
      
      $packageParams = OikDeliveryMarshroute::getPackageParams();
      
      $apiUrl .= '&weight='.($packageParams['weight']*1000).'&parcel_size=['.
          ($packageParams['length']*1000).','.($packageParams['width']*1000).','.
          ($packageParams['depth']*1000).']';

      $arResult = self::getCurlData($apiUrl);
      
      if (!empty($arResult->info)) {
        $data['error'] = $arResult->info->message;
        $this->data = $data;
        return true;
      }
      
      if (!empty($arResult->data)) {
        $data['variant'] = self::getDeliveryVarianList($arResult->data[0]);
        $data['kladr'] = $arResult->data[0]->kladr;
      }
    }
    
    if (!empty($_POST['transfer'])) {
      unset($_POST['transfer']);
      return $data;
    }
    
    $this->data = $data;
    return true;
  }
  
  private static function getDeliveryVarianList($obData) {
    $arResult = array();
    $options = unserialize(stripcslashes(MG::getSetting(self::$pluginName.'-option')));
    
    foreach ($obData->delivery_types as $deliveryType) {
      $date = $deliveryType->delivery_date;
      
      if(!empty($options['delivery_margin'])){
        $date = date('d.m.Y', strtotime('+'.intval($options['delivery_margin']).' day', 
            strtotime($deliveryType->delivery_date)));
      }
      
//      $deliveryTypeDesc = $deliveryType->name.' '.$date.' - '.
//          MG::numberFormat($deliveryType->cost).' '.MG::getSetting('currency');
      
      $deliveryTypeDesc = $deliveryType->name.' '.$date;

      if (!empty($deliveryType->address)) {
        $deliveryTypeDesc .= '('.$deliveryType->address.')';
      }

      $arResult[] = array(
        'name' => $deliveryTypeDesc,
        'delivery_code' => $deliveryType->place_id,
        'courier' => (in_array($deliveryType->delivery_id, array(1,4))) ? 1 : 0
      );
    }
    
    return $arResult;
  }

  private static function getCurlData($url){    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result);
  }

  /**
   * Обязательная функция плагина, для получения стоимости доставки из админки, по параметрам заказа
   */
  public function getPriceForParams(){
    $_POST['arrivalPoint'] = $_SESSION['deliveryAdmin'][$_POST['deliveryId']]['arrivalPoint'];
    $_POST['deliveryType'] = $_SESSION['deliveryAdmin'][$_POST['deliveryId']]['deliveryType'];
    $this->getPrice();
    return true;
  }
  
  /**
   * Обязательная функция плагина, для возможности пересчета стоимости, или изменения пункта достаки из админки
   * Возвращает дополнительную верстку для выборпа парметров доставки
   */
  public function getAdminDeliveryForm(){
    $this->data['form'] = '';
    
    if($_POST['firstCall'] == 'true'){      
      $this->data['form'] .= '';
    }
            
    $model = new Models_Order();
    $orderInfo = $model->getOrder(' id = '.DB::quote($_POST['orderId']));
    //Вызываем две функции последовательно, потому что у php бывают гюлки при вложенном вызове функций
    $orderOptions = stripslashes($orderInfo[$_POST['orderId']]['delivery_options']);
    $orderOptions = unserialize($orderOptions);      
    $arrivalPoint = $orderOptions['arrivalPoint'];
    $deliveryType = $orderOptions['deliveryType'];
    //Составляем форму для окна редактирования заказа
    $this->data['form'] .= ' 
      <script type="text/javascript" src='.SITE.'/'.PLUGIN_DIR.self::$pluginName.'/js/admin.js></script>
      <link rel="stylesheet" href="'.SITE.'/'.PLUGIN_DIR.self::$pluginName.'/css/style.css" type="text/css" />
      <div class="oik-delivery-marshroute delivery-addition-info delivery'.$_POST['deliveryId'].'">
        <input type="text" placeholder="Укажите город доставки" value="" 
          name="arrivalCity" id="oik-marshroute-delivery-city" style="width:256px;margin-top:5px;" /><br />
        <a href="javascript:void(0);" class="oik-marshroute-apply-city">Показать варианты доставки</a>
        <a href="javascript:void(0);" class="oik-marshroute-clear-field">Очистить</a><br />
        <select id="oik-marshroute-delivery-correct" style="display:none;width:279px;margin-top:5px;">
          <option value="0">Уточните населенный пункт</option>
        </select><br />
        <select id="oik-marshroute-delivery-variant" style="display:none;width:279px;margin-top:5px;">
          <option value="0">Уточните населенный пункт</option>
        </select><br />
        <input type="hidden" name="arrivalPoint" value="'.$arrivalPoint.'" />
        <input type="hidden" name="deliveryType" value="'.$deliveryType.'" />
        <span class="error"></span>
      </div>
    ';
    
    return true;
  }
}