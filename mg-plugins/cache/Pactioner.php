<?php
/**
 * Класс Pactioner наследник стандарного Actioner
 * Предназначен для выполнения действий,  AJAX запросов плагина 
 *
 * @author Avdeev Mark <mark-avdeev@mail.ru>
 */
class Pactioner extends Actioner {

  private $pluginName = 'cache';        
  
  /**
   * Сохраняет  опции плагина
   * @return boolean
   */
  public function saveBaseOption() {
    $this->messageSucces = 'Настройки применены, кэш сброшен!';
    if (!empty($_POST['data'])) {
      MG::setOption(array(
        'option' => 'cache-option', 
        'value' => addslashes(serialize($_POST['data'])))
      );
    }   
    return true;
  }

   /**
   * Сбрасывает закэшированные данные.
   * @return boolean
   */
  public function resetCache() {
    $this->messageSucces = 'Кэш обнулен';
    $this->messageError = 'Кэш не обнулен';   
    Cache::resetCache();     
    return true;
  }
}