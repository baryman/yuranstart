<?php

class Pactioner extends Actioner {

  private $pluginName = 'postcalc';
  
  /**
  * Сохранение опций
  */
  public function saveBaseOption() {
    $this->messageSucces = $this->lang['SAVE_BASE'];
    $this->messageError = $this->lang['NOT_SAVE_BASE'];
    if (!empty($_POST['data'])) {
      MG::setOption(array('option' => 'postcalcOption', 'value' => addslashes(serialize($_POST['data']))));

    }
    return true;
  }

  public function constructTable() {

    $weight = postcalc::getCartItemsWeight()*1000;//рассчет веса
    if ($weight <= 0) {
      $weight = 100;
    }

    $valuation = 0;

    foreach ($_SESSION['cart'] as $item) {//рассчет общей цены заказа без доставки
      $valuation += ($item['count']*$item['priceWithDiscount']);
    }

    $totalCount = 0;

    //рассчет количества товаров в заказе(количество почтовых отправлений должно быть меньше или равно количеству товаров)
    foreach ($_SESSION['cart'] as $item) {
      $totalCount += $item['count'];
    }

    $option = MG::getSetting('postcalcOption');
    $option = stripslashes($option);
    $options = unserialize($option);
    //MG::loger(print_r($options ,true));

    

    
    //создание запроса на API
    if(!empty($_POST['indexTo']) && !empty($options['indexFrom']) && !empty($options['site']) && !empty($options['mail'])){
      $request = 'http://api.postcalc.ru/?o=php&st='.$options['site'].
      '&ml='.$options['mail'].
      '&f='.$options['indexFrom'].
      '&t='.$_POST['indexTo'].
      '&w='.$weight.
      '&v='.$valuation;

      //добавление заголовка запроса
      $arrOptions = array('http' =>
      array( 'header'  => 'Accept-Encoding: gzip','timeout' => 5, 'user_agent' => phpversion() )
           );

      //соединение с сервером
      if ( !$response=file_get_contents($request, false , stream_context_create($arrOptions)) ) 
                         die('Не удалось соединиться с api.postcalc.ru!');


      //разархивация ответа
      if ( substr($response,0,3) == "\x1f\x8b\x08" )  $response=gzinflate(substr($response,10,-8));

      //перевод ответа в массив PHP
      $resultArr = unserialize($response);

      if ($resultArr['Status'] == 'OK' ){//ошибки нет

        $_SESSION['postcalc'] = array();
        $_SESSION['postcalc']['indexTo'] = $_POST['indexTo'];//запись индекса в сессию 

        //формирование таблицы для выбора способа доставки
        $html = '<div id="postcalcHeaderDiv">
        <a id="postcalcClose"></a>
        <span id="postcalcHeader">Выберите тип доставки на индекс <b>'.$_POST['indexTo'].'</b></span>
        </div>
        <table>
          <tr>
            <th class="postcalcButtonLeft">Кнопка<br>выбора</th>
            <th>Вид<br>отправления</th>
            <th>Стоимость<br>доставки</th>
            <th>Срок<br>доставки (дней)</th>
            <th class="postcalcShowCount">Число почтовых<br>отправлений</th>
            <th class="postcalcButtonRight">Кнопка<br>выбора</th>
          </tr>';
        
        foreach ($resultArr['Отправления'] as $key => $deliv) {
          if (($deliv['Доставка'] > 0 && $deliv['Количество'] <= $totalCount && $options[$key] == 'true') || ($deliv['Доставка'] > 0 && $key == 'EMS' && $valuation > 0 && $options[$key] == 'true')) {
            $ok = 1;
            $html .= '<tr>
              <td class="postcalcButtonLeft"><a class="postcalcResultId" id="'.$key.'">Выбрать ✓</a></td>
              <td>'.$deliv['Название'].'</td>
              <td><b>'.$deliv['Доставка'].' руб.</b></td>
              <td>'.$deliv['СрокДоставки'].'</td>
              <td class="postcalcShowCount">'.$deliv['Количество'].'</td>
              <td class="postcalcButtonRight"><a class="postcalcResultId" id="'.$key.'">Выбрать ✓</a></td>
            </tr>';

            $_SESSION['postcalc'][$key] = $deliv['Доставка'];//запись цен доставки в сессию

          }
        }
        $html .= '</table>';
        if ($ok != 1) {
          $html = 'Заказ пуст или превышен лимит доставки по весу.<input id="postcalcClose" type="button" value="Закрыть Х"/>';
        }
      }else{//вывод ошибки
        $html = $resultArr['Message'];
      }

    }else{//вывод ошибки
      $html = 'Заданы не все параметры';
    }

    //MG::loger(print_r($resultArr ,true));
    //echo json_encode($request);
    //echo json_encode($resultArr);

    echo json_encode($html);
  }

  public function getPrice(){

    $id = $_POST['delivId'];//получение id доставки,выбранное пользователем

    //установка финальной цены и способа доставки в соответствии с id доставки
    $_SESSION['postcalc']['finalDiliv'] = $_SESSION['postcalc'][$id];
    $_SESSION['postcalc']['chosenMethod'] = array_search($_SESSION['postcalc']['finalDiliv'], $_SESSION['postcalc']);
    //MG::loger(print_r($_SESSION ,true));

    $html = '+ доставка: <span class="order-delivery-summ">'.$_SESSION['postcalc'][$id].' руб.</span>';
    echo json_encode($html);

  }

  /*Обязательная функция плагина, для получения стоимости доставки из админки, по параметрам заказа*/
  public function getPriceForParams(){ 
    $this->data['deliverySum'] = 0;  
    return true;
  }

  public function getAdminDeliveryForm(){
    $path = PLUGIN_DIR.PM::getFolderPlugin(__FILE__);

    $this->data['form'] = '<link href='.SITE.'/'.$path.'/css/style.css rel="stylesheet" type="text/css">';
    
    if($_POST['firstCall'] == 'true'){
      $this->data['form'] .= '<script type="text/javascript" src='.SITE.'/'.$path.'/js/adminEditScript.js></script>';
    }
    
    $this->data['form'] .= '<div id="postcalcScreenBlock" style="display: none;"></div>
      <div id="postcalcInput">
        <input type="text" id="indexTo" placeholder="Индекс получателя"/><br>
        <p id="postcalcIndexError" style="display: none;">Индекс получателя может быть только 6-значным числом</p>
        <input id="postcalcSend" type="button" value="Рассчитать стоимость"/>
      </div>
      <div style="display: none;"  id="postcalcShow">
        <div id="postcalcResult"></div>
      </div>';
     
    return true;
  }

  public function constructTableAdmin() {

    $option = MG::getSetting('postcalcOption');
    $option = stripslashes($option);
    $options = unserialize($option);

    
    //создание запроса на API
    if(!empty($_POST['indexTo']) && !empty($options['indexFrom']) && !empty($options['site']) && !empty($options['mail'])){
      $request = 'http://api.postcalc.ru/?o=php&st='.$options['site'].
      '&ml='.$options['mail'].
      '&f='.$options['indexFrom'].
      '&t='.$_POST['indexTo'].
      '&w='.$_POST['weight'].
      '&v='.$_POST['price'];

      //добавление заголовка запроса
      $arrOptions = array('http' =>
      array( 'header'  => 'Accept-Encoding: gzip','timeout' => 5, 'user_agent' => phpversion() )
           );

      //соединение с сервером
      if ( !$response=file_get_contents($request, false , stream_context_create($arrOptions)) ) 
                         die('Не удалось соединиться с api.postcalc.ru!');


      //разархивация ответа
      if ( substr($response,0,3) == "\x1f\x8b\x08" )  $response=gzinflate(substr($response,10,-8));

      //перевод ответа в массив PHP
      $resultArr = unserialize($response);

      if ($resultArr['Status'] == 'OK' ){//ошибки нет

        //формирование таблицы для выбора способа доставки
        $html = '<div id="postcalcHeaderDiv">
        <a id="postcalcClose"></a>
        <span id="postcalcHeader">Выберите тип доставки на индекс <b>'.$_POST['indexTo'].'</b></span>
        </div>
        <table>
          <tr>
            <th class="postcalcButtonLeft">Кнопка<br>выбора</th>
            <th>Вид<br>отправления</th>
            <th>Стоимость<br>доставки</th>
            <th>Срок<br>доставки (дней)</th>
            <th class="postcalcShowCount">Число почтовых<br>отправлений</th>
            <th class="postcalcButtonRight">Кнопка<br>выбора</th>
          </tr>';
        
        foreach ($resultArr['Отправления'] as $key => $deliv) {
          if (($deliv['Доставка'] > 0 && $deliv['Количество'] <= $_POST['count']) || ($deliv['Доставка'] > 0 && $key == 'EMS' && $_POST['price'] > 0)) {
            $ok = 1;
            $html .= '<tr>
              <td class="postcalcButtonLeft"><a class="postcalcResultId" id="'.$key.'">Выбрать ✓</a></td>
              <td>'.$deliv['Название'].'</td>
              <td id="'.$key."price".'" price="'.$deliv['Доставка'].'"><b>'.$deliv['Доставка'].' руб.</b></td>
              <td>'.$deliv['СрокДоставки'].'</td>
              <td class="postcalcShowCount">'.$deliv['Количество'].'</td>
              <td class="postcalcButtonRight"><a class="postcalcResultId" id="'.$key.'">Выбрать ✓</a></td>
            </tr>';
          }
        }
        $html .= '</table>';

        if ($ok != 1) {
          $html = 'Заказ пуст или превышен лимит доставки по весу.<input id="postcalcClose" type="button" value="Закрыть Х"/>';
        }
      }else{//вывод ошибки
        $html = $resultArr['Message'];
      }

    }else{//вывод ошибки
      $html = 'Заданы не все параметры';
    }

    //MG::loger(print_r($resultArr ,true));
    // header('Content-Type: application/json');

    //echo json_encode($request);
    echo json_encode($html);
    //echo json_encode($resultArr);
  }

}