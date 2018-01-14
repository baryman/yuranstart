<?php

/**
 * Класс Pactioner наследник стандарного Actioner
 * Предназначен для выполнения действий,  AJAX запросов плагина 
 *
 * @author Avdeev Mark <mark-avdeev@mail.ru>
 */
class Pactioner extends Actioner {

  private $pluginName = 'tagsclouds';

  /**
   * Сохраняет  опции плагина
   * @return boolean
   */
  public function saveBaseOption() {

    USER::AccessOnly('1,4', 'exit()');

    if (!empty($_POST['data'])) {

      MG::setOption(array('option' => 'tags-cloud', 'value' => addslashes(serialize($_POST['data']))));
    }
    $this->messageSucces = 'Настройки применены!';
    return true;
  }

}
