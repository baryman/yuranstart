<?php

/*
  Plugin Name: Стоимость доставки по почте (postcalc.ru)
  Description: Плагин для расчета стоимости доставки по почте. Бесплатно обрабатывает до 500 запросов (вызовов таблиц) в сутки (<a href="http://postcalc.ru/conditions.html" target="_blank">подробнее</a>).
  Author: Nikita F
  Version: 1.0.2
 */
//в /mg-core/models/order.php заменить private $info на public $info
new postcalc;

class postcalc{

	private static $pluginName = ''; // название плагина (соответствует названию папки)
	private static $path = '';//путь к плагину

	public function __construct() {

		mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate')); //Активация плагина
    mgDeactivateThisPlugin(__FILE__, array(__CLASS__, 'deactivate'));//Деактивация плагина
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin')); //Настройки плагина
    mgAddShortcode('postcalc', array(__CLASS__, 'shortCode'));//шорткод плагина
    mgAddAction('models_order_isvaliddata', array(__CLASS__, 'hookFunction'),1);//хук плагина

    self::$pluginName = PM::getFolderPlugin(__FILE__);//имя плагина
    self::$path = PLUGIN_DIR.self::$pluginName;//папка плагина
    mgAddMeta('<link href='.SITE.'/'.self::$path.'/css/style.css rel="stylesheet" type="text/css">');//подключение css
    mgAddMeta('<script type="text/javascript" src='.SITE.'/'.self::$path.'/js/script.js></script>');//подключение javascript
	}

	/**
   * Возвращает запись с доставкой из БД для плагина
   */
  static function getDeliveryForPlugin(){    
    $dbRes = DB::query("
      SELECT `id`
      FROM ".PREFIX."delivery
      WHERE `plugin` = 'postcalc'
    ");
    
    if($result = DB::fetchAssoc($dbRes)){
      $sql = '
        UPDATE `'.PREFIX.'delivery` 
        SET `activity` = 1 
        WHERE `plugin` = \'postcalc\'';
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
      ('Postcalc (плагин)', 0, 'Почта (postcalc)', 1, 0, 'postcalc')
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

	static function activate(){
    $getDeliv = self::getDeliveryForPlugin();
    if($getDeliv <= 0){
      $setDeliv = self::setDeliveryForPlugin();
      MG::setOption(array('option' => 'postcalcId', 'value' => $setDeliv));
    }
    else{
      MG::setOption(array('option' => 'postcalcId', 'value' => $getDeliv[0]['id']));
    }
    if (!MG::getSetting('postcalcOption')) {
    	$arr= array('indexFrom' => 123182, 
    		'site' => MG::getSetting('sitename'), 
    		'mail' => MG::getSetting('adminEmail'),
    		'ПростоеПисьмо' => 'true',
    		'ЗаказноеПисьмо' => 'true',
    		'ЦенноеПисьмо' => 'true',
    		'ПростоеПисьмо1Класс' => 'true',
		    'ЗаказноеПисьмо1Класс' => 'true',
		    'ЦенноеПисьмо1Класс' => 'true',
		    'ПростойМультиконверт' => 'true',
		    'ЗаказнойМультиконверт' => 'true',
		    'ПростаяБандероль' => 'true',
		    'ЗаказнаяБандероль' => 'true',
		    'ЦеннаяБандероль' => 'true',
		    'ЦеннаяПосылка' => 'true',
		    'ЗаказнаяБандероль1Класс' => 'true',
		    'ЦеннаяБандероль1Класс' => 'true',
		    'EMS' => 'true',
		    'КурьерОнлайн' => 'true',
		    'ПосылкаОнлайн' => 'true');
    	MG::setOption(array('option' => 'postcalcOption', 'value' => addslashes(serialize($arr))));
    }
  }

  static function deactivate(){
    $sql = '
      UPDATE `'.PREFIX.'delivery` 
      SET `activity` = 0 
      WHERE `plugin` = \'postcalc\'';
    DB::query($sql);
  }

  static function shortCode(){//функция шорткода

    $idForPlugin = MG::getSetting('postcalcId');
    //вывод верстки
    $html = '<div id="postcalcScreenBlock" style="display: none;"></div>
    	<div id="postcalcInput" style="display: none;">
	    	<input type="text" id="indexTo" placeholder="Введите ваш индекс"/><br>
	    	<p id="postcalcIndexError" style="display: none;">Индекс получателя может быть только 6-значным числом</p>
				<input id="postcalcSend" type="button" value="Рассчитать стоимость"/>
			</div>
			<div style="display: none;"  id="postcalcShow">
				<div id="postcalcResult"></div>
			</div>';

		//viewData($_SESSION);

    return $html;
  }

  static function hookFunction($args){//функция хука

    $bError = isset($arg['result']) ? true : false;
    if(!$bError){
      $idForPlugin = MG::getSetting('postcalcId');
      $deliveryId = $args['args'][0]['delivery'];
      if($deliveryId == $idForPlugin){
        $deliveryCost = $_SESSION['postcalc']['finalDiliv'];
        $comment = ' "Индекс получателя = '.$_SESSION['postcalc']['indexTo'].'; Тип доставки = '.$_SESSION['postcalc']['chosenMethod'].'"';
        $args['args']['this']->delivery_cost = $deliveryCost;
        $args['args']['this']->info = $args['args'][0]['info'].$comment;
        if(is_null($args['args']['this']->delivery_cost) && is_null($args['result'])){
            $args['result'] = "Выберите ваш индекс и способ доставки.";
        }
        // $_SESSION['postcalc'] = array();//очистка сессии
      }
    }

    //viewData($args);
    //viewData($_SESSION);
    //end;
    return $args['result'];
  }

  /**
   * Метод выполняющийся перед генераццией страницы настроек плагина
   */
  static function preparePageSettings() {
    echo '   
      <link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/style.css" type="text/css" />
     
      <script type="text/javascript">
        includeJS("'.SITE.'/'.self::$path.'/js/adminScript.js");  
      </script> 
    ';
  }

    /**
   * Вывод страницы плагина в админке
   */
  static function pageSettingsPlugin() {
    $pluginName = self::$pluginName;
    $lang = PM::plugLocales('postcalc');

    self::preparePageSettings();

    $option = MG::getSetting('postcalcOption');
    $option = stripslashes($option);
    $options = unserialize($option);

    $path = self::$path;

    // подключаем view для страницы плагина
    include 'pageplugin.php';
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

		for($i=0; $i<count($itemsCart['items']); $i++){
      $sumWeight += $itemsCart['items'][$i]['weight']*$itemsCart['items'][$i]['countInCart'];
    }
    return $sumWeight;

  }





}