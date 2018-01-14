<?php
class Pactioner extends Actioner {
  private static $pluginName = 'mg-delivery-ddelivery';
  
  /**
   * Сохраняет  опции плагина
   * @return boolean
   */
  public function saveBaseOption(){
    USER::AccessOnly('1,4','exit()');
    $this->messageSucces = $this->lang['SAVE_BASE'];
    $this->messageError = $this->lang['NOT_SAVE_BASE'];
    unset($_SESSION['deliveryAdmin']);
    unset($_SESSION['delivery']);
    
    if(!empty($_POST['data'])) {
      MG::setOption(array('option' => self::$pluginName.'-option', 'value' => addslashes(serialize($_POST['data']))));
    }
    
    return true;
  }
  
  public function getDeliveryParamForm(){
    $form = MGDeliveryDDelivery::getDeliveryParamForm();
    $this->data['form'] = $form;
    return true;
  }
  
  public function getCityAutocomplete(){    
    $term = $_POST['term'];    
    $result = MGDeliveryDDelivery::getCityAutocomplete($term);
    
    foreach($result as $city){    
      $data[] = array(
        'id' => $city->_id,
        'value' => $city->name
      );
    }

    $this->data = json_encode($data);  
    
    return true;
  }


  public function getMapPoints(){
    $pointsList = MGDeliveryDDelivery::getMapPoints($_POST['cityId'], $_POST['companies']);
    $this->data['points'] = $pointsList;
    
    $index = array();
    $companies = (object)MGDeliveryDDelivery::getDCompanyList($pointsList[0]->city_id, 1);
    
    foreach($pointsList as $point){
      if(!in_array($point->company_id, $index)){
        $index[] = $point->company_id;
      }
    }
        
    $this->data['index'] = $index;
    $this->data['companies'] = (object)$companies;
    
    return true;
  }
  
  public function getPointInfo(){
    $pointInfo = MGDeliveryDDelivery::getPointInfo($_POST['point']);
    $this->data['point'] = $pointInfo;
    
    return true;
  }
  
  public function getForm(){        
//    if($_REQUEST['type'] != 'admin'){
      $_REQUEST['action'] = 'module';
//    }    
    
    include('form/ajax.php');  
    return true;
  }  
  
  public function setPrice(){  
    unset($_POST['pluginHandler']);
    unset($_POST['actionerClass']);
    unset($_POST['action']);
    
    if(!empty($_POST['admin'])){
      unset($_POST['admin']);   
      $_SESSION['deliveryAdmin'][$_POST['deliveryId']] = $_POST;
    }else{
      $_SESSION['delivery'][$_POST['deliveryId']] = $_POST;
    }

    return true;
  }
  
  /*Обязательная функция плагина, для получения стоимости доставки из админки, по параметрам заказа*/
  public function getPriceForParams(){  
    
    if(URL::isSection('mg-admin') && empty($_POST['orderItems'])){
      $this->data['deliverySum'] = 0;
      unset($_SESSION['deliveryAdmin'][$_POST['deliveryId']]);
    }elseif($arDelivery = MGDeliveryDDelivery::getPrice($_POST['deliveryId'])){
      if(!empty($arDelivery['error'])){
        $this->data['error'] = $arDelivery['error'];
      }else{
        $this->data['deliverySum'] = $arDelivery['delivery_sum'];
      }                  
    }                
      
    return true;
  }     
  
  /**
   * Обязательная функция плагина, для возможности пересчета стоимости, или изменения пункта достаки из админки
   * Возвращает дополнительную верстку для выборпа парметров доставки
   */
  public function getAdminDeliveryForm(){
    $this->data['form'] = ''; 
    
    if($_POST['firstCall'] == 'true'){
      //unset($_SESSION['delivery'][$_POST['deliveryId']]);  
      $this->data['form'] .= '<script type="text/javascript" src='.SITE.'/'.PLUGIN_DIR.'mg-delivery-ddelivery/js/dd-admin.js></script>';
    }    
    
    $_SESSION['orderItems'] = $_POST['orderItems'];
    
//    $_SESSION['delivery'][$_POST['deliveryId']] = array(
//      'orderId' => $_POST['orderId'],
//      'deliveryId' => $_POST['deliveryId'],
//      'orderItems' => $_POST['orderItems'],
//    );
    
    $this->data['form'] .= '';
    //mgAddMeta('<script type="text/javascript" src="http://sdk.ddelivery.ru/assets/js/ddelivery_v2.js"></script>');
    
    $this->data['form'] .= '            
      <script type="text/javascript" src='.SITE.'/'.PLUGIN_DIR.'mg-delivery-ddelivery/js/admin.js></script>
      <link rel="stylesheet" href="'.SITE.'/'.PLUGIN_DIR.'mg-delivery-ddelivery/css/style.css" type="text/css" />
      <span class="delivery-addition-info delivery'.$_POST['deliveryId'].'">
        <div class="ddelivery-popup-select" style="display:none;">
          <div class="map-loader"><img src="'.SITE.'/'.PLUGIN_DIR.'mg-delivery-ddelivery/images/loader-2.gif" width=200px" height="200px" /></div>
          <a href="javascript:void(0);" id="close_popup_ddelivery">&#10006;</a>
          <div id="ddelivery_container_place" style="background: #fff;"></div>
          <a href="javascript:void(0);" id="send_order_ddelivery" class="custom-btn"><span>Выбрать</span></a>
        </div>                
        <input type="hidden" value="'.$_POST['deliveryId'].'" name="dd_delivery_id" />              
        <a href="javascript:void(0);" id="ddelivery_select_params">выбрать</a>        
      </span>      
    ';
    
    return true;
  }
}
