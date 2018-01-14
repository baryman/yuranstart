<?php

/*
  Plugin Name: Генератор html-карты сайта
  Description: Плагин создает карту сайта в формате HTML, карту можно вывести пр шорткоду [sitemap-html]
  Author: Daria Churkina, Gaydis Mikhail
  Version: 1.0.5
 */

new SitemapHtmlGenerator;

class SitemapHtmlGenerator {
  private static $category = array();
  private static $product = array();
  private static $filterPage = array();
  private static $newsPage = array();
  private static $blogCategory = array();
  private static $blogItem = array();

  private static $isActiveNews = false;
  private static $isActiveBlog = false;

  private static $settings = array();
  private static $isShow = array();

  private static $pluginName = ''; // название плагина (соответствует названию папки)
  private static $path = ''; //путь до файлов плагина 

  public function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate')); //Инициализация  метода выполняющегося при активации  
    mgAddShortcode('sitemap-html', array(__CLASS__, 'viewSitemap'));
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin')); //Инициализация  метода выполняющегося при нажатии на кнопку настроект плагина  

    self::$pluginName = PM::getFolderPlugin(__FILE__);
    self::$path = PLUGIN_DIR.self::$pluginName;

    self::$settings = MG::get('settings');
    self::$isShow = unserialize(stripcslashes(self::$settings['sitemap-html']));
  }
// admin.refreshPanel();
  /**
   * вывод html-карты сайта по шорт-коду [sitemap-html]
   */
  static function viewSitemap() {
    $sitemap = Storage::get(md5('mgPluginSitemapHtml'));
    if ($sitemap == null) {
      $pages = self::getPages();
      $catalog = self::getCatalog();
      $html = '
    <div class="sitemap-html">
     <h2>Карта сайта</h2>
    <ul>';
      foreach ($pages as $url => $title) {
        $partsUrl = URL::getSections($url);
        $priority = count($partsUrl);
        if (is_array($title)) {
          $html .='<li><a href="'.SITE.'/'.$url.'">'.$title[$url].
            '<ul>';
          foreach ($title as $suburl => $subtitle) {
            if ($suburl != $url) {
              $html .='<li><a href="'.SITE.'/'.$suburl.'" title="'.$subtitle.'">'.$subtitle.'</a></li>';
            }
          }
          $html .= '</ul></li>';
        } else {
          $html .='<li><a href="'.SITE.'/'.$url.'" title="'.$title.'">'.$title.'</a></li>';
          if ($url == 'catalog') {
            $html .='<ul>'.$catalog.'</ul>';
          }
        }
      }
      
      // странички новостей
      if(self::$isActiveNews) {
        $html .= '<li><a href="news">Новости</a><ul>';
        foreach (self::$newsPage as $oneNews) {
          $html .= '<li><a href="news/'.$oneNews['url'].'">'.$oneNews['title'].'</a></li>';
        }
        $html .= '</ul></li>';
      }

      // странички блога
      if(self::$isActiveBlog) {
        $html .= '<li><a href="/news">Блог</a><ul>';
        foreach (self::$blogCategory as $oneBlogCategory) {
          $html .= '<li><a href="blog/'.$oneBlogCategory['url'].'">'.$oneBlogCategory['title'].'</a><ul>';
          // для записей блога с категорией
          foreach (self::$blogItem as $oneBlogItem) {
            if($oneBlogCategory['id'] == $oneBlogItem['category_id']) {
              $html .= '<li><a href="blog/'.$oneBlogCategory['url'].'/'.$oneBlogItem['url'].'">'.$oneBlogItem['title'].'</a></li>';
            }
          }
          $html .= '</ul></li>';
        }
        //  для записей блога без категории
        foreach (self::$blogItem as $oneBlogItem) {
          if($oneBlogItem['category_id'] == null) {
            $html .= '<li><a href="blog/'.$oneBlogItem['url'].'">'.$oneBlogItem['title'].'</a></li>';
          }
        }
        
        $html .= '</ul></li>';
      }

      // странички с фильтрами
      if(self::$isShow['isShowFilterPage'] == "true") {
        foreach (self::$filterPage as $oneFilterPage) {
          $html .= '<li><a href="'.$oneFilterPage['short_url'].'">'.$oneFilterPage['titeCategory'].'</a></li>';
        }
      }



      $sitemap = $html.'</ul></div>';
      Storage::save(md5('mgPluginSitemapHtml'), $sitemap);
    }
    return $sitemap;
  }

  /**
   * формирует массив с адресами и заголовками страниц.
   * @param type- массив с адресами и загодловками страниц
   * @return array
   */
  static function getPages() {
    $urls = array();
    $catalog = array();
    $product = array();

    $result = DB::query('
      SELECT  * 
      FROM `'.PREFIX.'category` WHERE `invisible`=0');
    while ($row = DB::fetchAssoc($result)) {
      $catalog[] = $row;
    }
    self::$category = $catalog;

    /*
     * страницы с товарами
     */

    $result = DB::query('
      SELECT cat_id, title, url 
      FROM `'.PREFIX.'product` WHERE activity = 1');
    while ($row = DB::fetchAssoc($result)) {
      $product[] = $row;
    }
    self::$product = $product;

    /*
     * статические страницы сайта
     */
    $result = DB::query('
      SELECT  `parent_url`, `url`, `title`
      FROM `'.PREFIX.'page`');
    while ($row = DB::fetchAssoc($result)) {
      if ($row['url'] != 'index') {
        $urls[$row['parent_url'].$row['url']] = $row['title'];
      }
    }
    /*
     * страницы с фильтрами
     */
    $result = DB::query('
      SELECT  `short_url`, `titeCategory`
      FROM `'.PREFIX.'url_rewrite` WHERE activity = 1');
    while ($row = DB::fetchAssoc($result)) {
      $filterPage[] = $row;
    }
    self::$filterPage = $filterPage;
    /**
     * если подключены плагин новостей
     */
    $res = DB::query("SELECT * FROM ".PREFIX."plugins WHERE folderName = 'news' and active = '1'");
    while ($row = DB::fetchAssoc($res)) {
      self::$isActiveNews = true;
    }
    if(self::$isActiveNews) {
      $result = DB::query('
        SELECT  `title`, `url`
        FROM `mpl_news`');
      while ($row = DB::fetchAssoc($result)) {
        $newsPage[] = $row;
      }
      self::$newsPage = $newsPage;
    }
    /**
     * если подключены плагин блога
     */
    $res = DB::query("SELECT *  FROM ".PREFIX."plugins WHERE folderName = 'blog' and active = '1'");
    while ($row = DB::fetchAssoc($res)) {
      self::$isActiveBlog = true;
    }
    if(self::$isActiveBlog) {
      // получение самих записей блога
      $result = DB::query('
        SELECT item.id, url, title, category_id 
        FROM `'.PREFIX.'blog_items` AS item
        LEFT JOIN `'.PREFIX.'blog_item2category` AS i2c ON item.id = item_id WHERE activity = 1');
      while ($row = DB::fetchAssoc($result)) {
        $blogItem[] = $row;
      }
      self::$blogItem = $blogItem;
      // получение категорий блога
      $result = DB::query('
        SELECT id, url, title 
        FROM `'.PREFIX.'blog_categories`');
      while ($row = DB::fetchAssoc($result)) {
        $blogCategory[] = $row;
      }
      self::$blogCategory = $blogCategory;
    }    
    return $urls;
  }

  /**
   * формирует список категорий и подкатегорий, 
   * @param type $parent
   * @return string
   */
  static function getCatalog($parent = 0) {
    $print = '';
    $categoryArr = self::$category;
    foreach ($categoryArr as $category) {
      if (!isset($category['id'])) {
        break;
      }//если категории неceotcndetn
      if ($parent == $category['parent']) {
        $flag = false;
        foreach (self::$category as $sub_category) {
          if ($category['id'] == $sub_category['parent']) {
            $flag = true;
            break;
          }
        }
        $print.= '<li>
        <a href="'.SITE.'/'.$category['parent_url'].$category['url'].'" title="'.$category['title'].'">'.$category['title'].'</a>';
        // product
        if (self::$isShow['isShowProduct'] == 'true') {
          $print .= '<ul>';
          foreach (self::$product as $oneProduct) {
            if ($category['id'] == $oneProduct['cat_id']) {
              if(self::$settings['shortLink'] == 'true') {
                $link = SITE.'/'.$oneProduct['url']; 
              } else {
              if($category['parent_url'] != null) {
                $parentUrl = $category['parent_url'].'/'; 
              } else {
                $parentUrl = '';
              }
                $link = SITE.'/'.$parentUrl.$category['url'].'/'.$oneProduct['url'];
              }
              $print .= '<li><a href="'.$link.'" title="'.$oneProduct['title'].'">'.$oneProduct['title'].'</a></li>';
            }
          }
          $print .= '</ul>';
        }
        // sub menu 
        if ($flag) {
          $sub_menu = '
              <ul>
                [li]
              </ul>';

          $li = self::getCatalog($category['id']);
          $print .= (strlen($li) > 0 && $li != '') ? str_replace('[li]', $li, $sub_menu) : "";
          $print .= '</li>';
        } else {
          $print .= '</li>';
        }
      }
    }
    return $print;
  }

  /**
   * Выводит страницу настроек плагина в админке
   */
  static function pageSettingsPlugin() {
    self::preparePageSettings();
    include('pageplugin.php');
  }

  /**
   * Метод выполняющийся перед генераццией страницы настроек плагина
   */
  static function preparePageSettings() {
    echo '   
      <link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/style.css" type="text/css" />     
      <script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/script.js"></script> 
    ';
  }

  /**
   * Метод выполняющийся при активации палагина 
   */
  static function activate() {
    self::createRowInDB();
  }

  /**
    * Создает запись в таблице настроек
   */
  static function createRowInDB() {
    $array = array(
      'isShowProduct' => 'false',
      'isShowFilterPage' => 'false'
      ); 

    MG::setOption(array('option' => 'sitemap-html', 'value' => addslashes(serialize($array))));
  }

}
