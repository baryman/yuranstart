<?php

/**
 * Класс Pactioner наследник стандарного Actioner
 * Предназначен для выполнения действий,  AJAX запросов плагина 
 *
 * @author Avdeev Mark <mark-avdeev@mail.ru>
 */
class Pactioner extends Actioner {

  private $pluginName = 'sitemap-html';

  public function saveOption() {
  	$array = array(
  	  'isShowProduct' => $_POST['isShowProduct'],
  	  'isShowFilterPage' => $_POST['isShowFilterPage']
  	  ); 

  	MG::setOption(array('option' => 'sitemap-html', 'value' => addslashes(serialize($array))));
    return true;
  }
}