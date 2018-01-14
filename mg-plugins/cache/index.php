<?php

/*
  Plugin Name: Кэширование страниц
  Description: Сокращает время загрузки страниц сайта. Администратор, всегда видит актуальную версию страницы, остальным пользователям показываеются сохраненные страницы из кэша.
  Author: Авдеев Марк
  Version: 1.0.1
 */

new Cache;

class Cache {

  private static $lang = array(); // Массив с локалью плагина.
  private static $pluginName = ''; // Название плагина (соответствует названию папки).
  private static $path = ''; // Путь до файлов плагина.
  private static $obStart = false; // Флаг начала записи в кэш.
  public static $options = null; // Параметры плагина.
  private static $pathCache = ''; // Путь к папке с кэшем.
  private static $urlMd5 = ''; // Хэшь страницы.

  /**
  * Конструктор кэша.
  */
  public function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate')); //Инициализация  метода выполняющегося при активации  
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin')); //Инициализация  метода выполняющегося при нажатии на кнопку настроект плагина     
    mgAddAction('mg_start', array(__CLASS__, 'startCache'));
    mgAddAction('mg_end', array(__CLASS__, 'endCache'), 1);

    self::$pluginName = PM::getFolderPlugin(__FILE__);
    self::$lang = PM::plugLocales(self::$pluginName);
    self::$obStart = false;
    include('mg-admin/locales/'.MG::getSetting('languageLocale').'.php');
    $lang = array_merge($lang, self::$lang);
    self::$lang = $lang;
    self::$path = PLUGIN_DIR.self::$pluginName;

    $sep = DIRECTORY_SEPARATOR;
    $realDocumentRoot = str_replace($sep.'mg-plugins'.$sep.'cache', '', dirname(__FILE__));
    self::$pathCache = $realDocumentRoot.$sep."mg-plugins".$sep."cache".$sep."cache".$sep;    
    self::$urlMd5 = md5(URL::getUrl());

    $option = MG::getSetting('cache-option');
    $option = stripslashes($option);
    self::$options = unserialize($option);
  
  }

  /**
   * Сбрасывает кэш.
   */
  static function resetCache() {
    $directories = scandir(self::$pathCache);
    foreach ($directories as $dir) {
      if (is_file(self::$pathCache.$dir)) {
        unlink(self::$pathCache.$dir);
      }
    }

    self::timeCacheUpdate();
  }

  /**
   * Обновляет отметку времени, последнего сброса кэша.
   */
  static function timeCacheUpdate() {
    self::$options['last_time_cache'] = time();
    MG::setOption(array(
      'option' => 'cache-option',
      'value' => addslashes(serialize(self::$options)))
    );
  }

  /**
   * Метод выполняющийся при активации палагина.
   */
  static function startCache() {
    
    // Если пришло время обнулить кэш.
    if ((time() - self::$options['last_time_cache']) > self::$options['time']) {
      self::resetCache();
    }
    
    // Поиск закэшированной страницы для всех кроме админа и модератора
    if (file_exists(self::$pathCache.self::$urlMd5.".html") && USER::getThis()->role!=1 && USER::getThis()->role!=4) {
      include(self::$pathCache.self::$urlMd5.".html");
      MG::createHook('mg_end');
      if (DEBUG_SQL) {
        echo DB::console();
      }
      exit();
    } else {
      //для всех кроме админа включаем запись кэша
      if(USER::getThis()->role!=1 && USER::getThis()->role!=4){        
        self::$obStart = true;
        ob_start();
      }
    }
   
  }

  /**
   * Проверяет, не находится ли страница в исключенных от кэширования.
   */
  static function isExeptionCache() {

    // Проверка в пользовательских настройках плагина.
    $exeption = explode("\n", self::$options['no_cache']);
	$exeption[] = PROTOCOL.'://'.$_SERVER['SERVER_NAME'].'/mg-admin/';
	
	foreach(array('mg-admin','personal') as $page){
	  $exeption[] = PROTOCOL.'://'.$_SERVER['SERVER_NAME'].URL::getCutPath().'/'.$page;
	  $exeption[] = PROTOCOL.'://'.$_SERVER['SERVER_NAME'].URL::getCutPath().'/'.$page.'/';
	}
	
	
    if (in_array(URL::getUrl(), $exeption)) {
      return true;
    }
   
    // Проверка на капчу.
    if (strpos(URL::getUrl(), 'captcha') !== false) {
      return true;
    }

    $dirrectory = scandir(CORE_DIR.'controllers');
   
    foreach ($dirrectory as $items) {
      if( $items!= '.' && $items!='..' && $items!='catalog.php' && $items!='product.php'){
        $haystackExeption[] =  str_replace('.php', '', $items);      
      }
    }
    $haystackExeption[] = 'affiliate';
    $haystackExeption[] = 'download';
    
    $sections = URL::getSections();  
    return (in_array($sections[1], $haystackExeption)) ? true : false;
  }

  /**
   * Завершает запись страницы в кэш, сохраняет страницу в html формате.
   */
  static function endCache($args) {
   
    if (self::$obStart == false || $args['args']['type'] == 404) {
      return false;
    }
 
    
    $buffer = ob_get_contents();
    ob_end_clean();
    $file = self::$pathCache.self::$urlMd5.".html";

    // Если страница не находится в исключенных, то кешируем ее.
    if (!self::isExeptionCache() && self::$options['enable_cache'] == 'true') {
      $buffer.="<!-- cache: ".self::$urlMd5." [".date("d.m.Y H:i")."]-->";
      file_put_contents($file, $buffer, LOCK_EX);
    }

    echo $buffer;
  }

  /**
   * Метод выполняющийся при активации палагина. 
   */
  static function activate() {
    $option = MG::getSetting('cache-option');
    if (empty($option)) {
      $array = Array(
        'time' => '',
        'last_time_cache' => time(),
        'no_cache' => '',
        'enable_cache' => 'true',
      );
      MG::setOption(array(
        'option' => 'cache-option', 
        'value' => addslashes(serialize($array)))
      );
    }

    self::resetCache();
  }

  /**
   * Метод выполняющийся перед генераццией страницы настроек плагина.
   */
  static function preparePageSettings() {

    echo '       
      <link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/style.css" type="text/css" />    
      <script type="text/javascript">
        includeJS("'.SITE.'/'.self::$path.'/js/script.js");  
      </script> 
    ';
  }

  /**
   * Выводит страницу настроек плагина в админке.
   */
  static function pageSettingsPlugin() {
    $lang = self::$lang;
    $pluginName = self::$pluginName;
    $options = self::$options;
    self::preparePageSettings();
    include('pageplugin.php');
  }

}