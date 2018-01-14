<?php
class Pactioner extends Actioner {
  private static $pluginName = 'oik-delivery-dellin';
  
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
    }elseif($arDelivery = OikDeliveryDellin::getPrice($_POST['arrivalPoint'])){
      
      if($arDelivery['error'] != 1){        
        $this->data['deliverySum'] = $arDelivery['delivery_sum'];
      }else{
        $this->data['deliverySum'] = -1;
        $this->data['error'] = $arDelivery['error_message'];
      }            
      
      //unset($_SESSION['delivery'][$_POST['deliveryId']]);      
    }  
        
    return true;
  }
  
  public function getCitiesList(){
    if($term = $_POST['term']){
      $sql = 'SELECT `code`, `name` 
      FROM `'.PREFIX.'cities_kladr` 
      WHERE `name` LIKE '.DB::quote('%'.$term.'%').' ORDER BY `name` asc';
      
      $dbRes = DB::query($sql);
    
      while($row = DB::fetchAssoc($dbRes)){    
        $data[] = array(
          'id' => $row['code'],
          'value' => $row['name']
        );
      }
      
      $this->data = json_encode($data);  
      
      return true;
    }
  }

    /**
   * Обязательная функция плагина, для получения стоимости доставки из админки, по параметрам заказа
   */
  public function getPriceForParams(){      
    $_POST['arrivalPoint'] = $_SESSION['deliveryAdmin'][$_POST['deliveryId']]['arrivalPoint'];
    $_POST['arrivalCity'] = $_SESSION['deliveryAdmin'][$_POST['deliveryId']]['arrivalCity'];
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
    $arrivalCity = $orderOptions['arrivalCity'];
    //Составляем форму для окна редактирования заказа
    $this->data['form'] .= ' 
      <script type="text/javascript" src='.SITE.'/'.PLUGIN_DIR.self::$pluginName.'/js/admin.js></script>
      <link rel="stylesheet" href="'.SITE.'/'.PLUGIN_DIR.self::$pluginName.'/css/style.css" type="text/css" />
      <div class="oik-delivery-dellin delivery-addition-info delivery'.$_POST['deliveryId'].'">
        <input type="text" placeholder="Укажите город доставки" value="'.$arrivalCity.'" 
            name="deliveryCity" id="oik-dellin-delivery-city" style="width:256px;margin-top:5px;" />        
        <div class="popupList" style="width:278px"></div>        
        <a href="javascript:void(0);" class="oik-dellin-clear-field" style="font-size:12px;">Очистить</a>
        <input type="hidden" name="arrivalPoint" value="" />
      </div>
    ';
    
    return true;
  }
}