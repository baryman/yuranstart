<?php

/*
  Plugin Name: Облако тегов
  Description: Облако тегов добавляется на сайт с помощью шорткода [cloud]. В  настройках товара появляется характеристика "Теги". Указывайте теги через запятую.
  Author: Авдеев Марк Чуркина Дарья
  Version: 1.2.7
 */
new TagsCloud();

class TagsCloud {

  private static $tags;  // массив [тэг]=> вес
  private static $options;
  private static $pluginName = ''; // название плагина (соответствует названию папки)
  private static $path = ''; //путь до файлов плагина 
  private static $i = 1;

  function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activateTagsCloud')); //Инициализация  метода выполняющегося при активации  
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin')); //Инициализация  метода выполняющегося при нажатии на кнопку настроек плагина 
    mgAddShortcode('cloud', array(__CLASS__, 'printtagscloud')); // Инициализация шорткода [cloud] - доступен в любом HTML коде движка.

    $option = MG::getSetting('tags-cloud');
    $option = stripslashes($option);
    self::$options = unserialize($option);
    self::$pluginName = PM::getFolderPlugin(__FILE__);
    self::$path = PLUGIN_DIR.self::$pluginName;
  }

  /**
   * Активирует плагин, создает файл в папке mg-pages
   * @return type
   */
  public static function activateTagsCloud() {
    $res = DB::query(
        "SELECT id FROM `".PREFIX."property`  WHERE name = 'Теги'"
    );
    if (!DB::numRows($res)) {
      DB::query(
        "INSERT INTO `".PREFIX."property` 
          (`id`, `name`, `type`, `default`, `data`, `all_category`, `activity`) 
          VALUES (NULL, 'Теги', 'string', '', '', '1', '1')"
      );
      
      $propId = DB::insertId();
    
    $category = DB::query(
        "SELECT id FROM `".PREFIX."category` "
    );
    
    while ($cat_id = DB::fetchArray($category)) {
 
      DB::query("
            INSERT IGNORE INTO `".PREFIX."category_user_property`
            VALUES (".DB::quote($cat_id['id']).", ".DB::quote($propId).")");
      }

      $array = Array(
        'propertyId' => $propId,
        'font_size_min' => '14',
        'font_size_max' => '40',
        'view3d' => 'false',
        'color' => '#34652F',
      );


      MG::setOption(array('option' => 'tags-cloud', 'value' => addslashes(serialize($array))));
    }
    $file = PLUGIN_DIR.'tagsclouds/tagviews.php';
    $newfile = 'tags.php';
    if (!file_exists(PAGE_DIR.$newfile)) {
      copy($file, PAGE_DIR.$newfile);
    }
  }
 
  static function pageSettingsPlugin() {
    $pluginName = self::$pluginName;
    $settings = self::$options;
    echo '       
      <script type="text/javascript">
        includeJS("'.SITE.'/'.self::$path.'/js/script.js");
        includeJS("'.SITE.'/'.self::$path.'/js/jquery.colorPicker.js");
  	</script>
    <link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/colorPicker.css" type="text/css" />
    ';
    include('pageplugin.php');
  }

  /**
   * Получает минимальное количество из всех тегов и его название
   * @return type
   */
  static function getMinCount() {
    $tagsList = array_keys(self::$tags);  // массив всех тегов
    $tagsCount = self::$tags;
    $min_tag = $tagsList[0]; 
    $min_count = $tagsCount[$min_tag]; 
    foreach ($tagsList as $key => $name) {
      if ($tagsCount[$name] < $min_count) {
        $min_count = $tagsCount[$name];
        $min_tag = $name;
      }
    }
    $min_array = array(
      'tag' => $min_tag,
      'count' => $min_count,
    );
    return $min_count;
  }

  /**
   * Формирует массив ссылок в облаке
   */
  static function getCloud() {
    $cloud = array();
    if (empty(self::$tags)) {
      return $cloud;
    }
    $cloud[] = "<div class='cloud' id='tags".self::$i."' data-color = ".self::$options['color']." >";
    self::$i++;
    $color_font = self::$options['color'];
    $tags_list = self::$tags;
    $min_count = self::getMinCount();
    $max_count = max(self::$tags);

    if (self::$options['view3d'] == 'true') {
      $cloud[] = '   
        <script type="text/javascript"
        src ="'.SITE.'/mg-plugins/tagsclouds/3dcloud/swfobject.js" ></script>
        <script type="text/javascript"
        src = "'.SITE.'/mg-plugins/tagsclouds/3dcloud/cloud.js"></script>';
      $style = "";
    }
    foreach ($tags_list as $tag => $count) {

      $fontname = array("Arial", "Calibri", "Century", "Optima");
      $font = rand(0, count($fontname) - 1);
      if ($count > $min_count) {
        $font_size = self::$options['font_size_max'] * ($count - $min_count) / ($max_count - $min_count);
      } else {
        $font_size = self::$options['font_size_min'];
      }
      if ((self::$options['view3d']) == 'true') {
        $style = "";
      } else {
        $style = "color:".$color_font."; font-family: ".$fontname[$font].";";
      }
      $cloud[] = "<a style='".$style." font-size:".$font_size."px;' href='".SITE."/tags?tag=".str_replace(' ', '%20',$tag)."'>".$tag."</a>";
    }
    $cloud[] = "</div>";
    return $cloud;
  }

  /**
   * Выводит облако в публичной части вместо шорткода [cloud]
   */
  static function printtagscloud() {
    $whereActive = 'p.activity = 1 ';

    if(MG::getSetting('printProdNullRem') == "true"){
      $whereActive .= 'AND p.count != 0 ';
    }
    
    $result = DB::query("
      SELECT pup.product_id, pup.value 
      FROM ".PREFIX."product_user_property pup
        LEFT JOIN ".PREFIX."product p ON pup.product_id = p.id
      WHERE property_id = (SELECT id FROM mg_property WHERE name = 'Теги') AND ".$whereActive."   
      ORDER BY product_id ");

    $tags = array();
    while ($row = DB::fetchAssoc($result)) {
      $tags = explode(",", $row['value']);
      foreach ($tags as $value) {
        self::$tags[self::lower(trim($value))] += 1;
      }
    }

    $tags_list = self::getCloud();
    $result = '';
    foreach ($tags_list as $tag) {
      $result .= $tag.' ';
    }

    return $result;
  }

  /**
   * Возвращает массив продуктов  по запрошенному тэгу
   * @param $tag - название тега
   */
  static function getProductsByTag($tag) {
    $whereActive = 'p.activity = 1 ';
    
    if(MG::getSetting('printProdNullRem') == "true"){
      $whereActive .= 'AND p.count != 0 ';
    }
    
    $result = DB::query("
       SELECT pup.product_id, pup.value 
       FROM ".PREFIX."product_user_property pup
         LEFT JOIN ".PREFIX."product p ON pup.product_id = p.id
       WHERE property_id = (SELECT id FROM mg_property WHERE name = 'Теги') AND ".$whereActive."   
       ORDER BY product_id ");

    $tags = array();
    while ($row = DB::fetchAssoc($result)) {
      $tags = explode(",", $row['value']);
      foreach ($tags as $value) {
        if (self::lower($tag) == self::lower(trim($value))) {
          $productsId[] = $row['product_id'];
        }
      }
    }
    if (empty($productsId)) {
      return array('catalogItems' => array());
    }
    
    $model = new Models_Catalog;
    // собираем все ID продуктов в один запрос.
    if ($prodSet = DB::quote(implode(',', $productsId), true)) {
      $where = ' IN ('.$prodSet.')';
    } else {
      $where = ' IN (0)';
    }

    $items = $model->getListByUserFilter(20, 'p.id '.$where.' AND '.$whereActive);
    
    foreach($items['catalogItems'] as $cell=>$item){
      $imagesUrl =  explode("|", $item['image_url']);      
      $src = SITE.'/uploads/no-img.jpg';
      if(!empty($imagesUrl[0])){        
        if(function_exists('mgImageProductPath')){
         $src = SITE.'/uploads/'.$imagesUrl[0];        
        }else{
          $src = SITE.'/uploads/thumbs/70_'.$imagesUrl[0];
        }        
      }
      
      $items['catalogItems'][$cell]['image_url'] = $src;
    }    

    return $items;
  }

  static function lower($text) {
    $text = mb_convert_case($text, MB_CASE_LOWER, "UTF-8");
    return $text;
  }

}
