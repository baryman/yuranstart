<?php 

/* 
 Plugin Name: Массовое изменение цен на товары
 Description: Плагин позволяет массово изменить стоимость всех товаров в каталоге, на указанный процент относительно дествующей стоимости. Допускается использование отрицательных значений. После применения плагина все действующие цены товаров будут изменены безвозвратно. Перед применением изменений обязательно создайте копию базы данных для восстановления сайта в случае сбоя.
 Author: Andrey Serko 
 Version: 1.0.1
*/

new Discount;

class Discount{

	 public static $discount = '';
	 public static $pluginName = ''; // название плагина (соответствует названию папки)	
	 public static $path = ''; //путь до файлов плагина 
	 public function __construct(){
		mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlagin')); //создание метода, когда нажимаем на кнопку настроек пл.; ДобавитьАктивацию страницыНастроекПлагина
		self::$pluginName = PM::getFolderPlugin(__FILE__);
		self::$path = PLUGIN_DIR.self::$pluginName;
		}
	/**
   	* Метод выполняющий подключение css
   	*/	
	static function preparePageSettings(){
		    USER::AccessOnly('1,4','exit()');
		    echo '  
		      <link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/style.css" type="text/css" />  
		      <script type="text/javascript">
      		  includeJS("'.SITE.'/'.self::$path.'/js/script.js");  
      		  </script> 
		      ';
		  }
	//настроки плагина
	static function pageSettingsPlagin(){

		self::preparePageSettings();//вызываем функцию подключения css
		
		if(isset($_POST['discount']) && is_numeric($_POST['discount'])){
			self::$discount = floatval($_POST['discount'])*(-1);//приведение числа к вещественному типу
			DB::query("UPDATE `".PREFIX."product` SET `price_course` = `price_course` - (  `price_course` /100 * ".DB::quote(self::$discount)." ) ");
			DB::query("UPDATE `".PREFIX."product_variant` SET `price_course` = `price_course` - (  `price_course` /100 * ".DB::quote(self::$discount)." ) ");
			DB::query("UPDATE `".PREFIX."product` SET `price` = `price` - (  `price` /100 * ".DB::quote(self::$discount)." ) ");
			DB::query("UPDATE `".PREFIX."product_variant` SET `price` = `price` - (  `price` /100 * ".DB::quote(self::$discount)." ) ");
			$sql = "
	    	SELECT `product_margin`,`value`, `property_id`
	    	FROM `".PREFIX."product_user_property`
	    	WHERE `product_margin` != '' ";
	    	
	    	$res = DB::query($sql);//выбираем БД 
			while($row = DB::fetchAssoc($res)){//пробегаем по каждому значению полей product_margin И value
				$expMargin = explode("|", $row['product_margin']);//разделяем строку с помощью |, выходит массив 
				$expValue = explode("|", $row['value']);
				//viewData($expValue);	
				$arrForStringMargin = self::CountDiscount($expMargin);								
				if(!empty($expValue[0])){//проверка. если не пустой нулевой элемент, то делаем цикл
					$arrForStringValue = self::CountDiscount($expValue);
				}else{
					$arrForStringValue = array();
				};				
				$newStringsMargin = implode("|", $arrForStringMargin);//преобразуем массив в строку, разделенную | Это есть новая строка
				$newStringsValue = implode("|", $arrForStringValue);//преобразуем массив в строку, разделенную | 
				
				DB::query("
					UPDATE `".PREFIX."product_user_property` SET 
					`product_margin` = ".DB::quote($newStringsMargin).", 
					`value` = ".DB::quote($newStringsValue)."  
					WHERE  `property_id` = ".DB::quote(intval($row['property_id']))." ");						
			}
			//проверка на значение скидки > либо < нуля
			if ($_POST['discount']>0) {
				$markup = substr($_POST['discount'], 0);
				echo "<div class='important-message'> Установлена наценка в ".$markup."%.</div>";		
			} else{
				echo "<div class='important-message'> Установлена скидка в ".self::$discount."%.</div>";
			}
		}
		else{
			echo '<div class="important-message" style="text-align:center;"><p class="yellow-string link-result " 
        style="text-align: center; color: #92862e;border: 1px solid #e1d260;background: #fff6ae;padding: 10px;margin: 0 0 10px 0; margin-right: 10px;">
        Плагин позволяет массово изменить стоимость всех товаров в каталоге, на указанный процент относительно дествующей стоимости. Допускается использование отрицательных значений.</p>
				<p class="link-fail" 
        style="padding: 10px;color: #c2646d;background: #fdd6da;border: 1px solid #eca8a8; text-align:center;margin-right: 10px;">
        Перед применением изменений обязательно создайте копию базы данных для восстановления сайта в случае сбоя.</p>
				</div>';		
		}
		self::preparePageSettings(); 
		echo '
		<div class="block-discount">
			<form methot="post" action="">
				<span style="float:left; width:250px;">Увеличить стоимость товаров на</span> <input  type="number" name="discount" value="'.$_POST['discount'].'" style="float:left;"/><span>%</span>
				
				<input  type="submit" id="buttom-ok" class="success button fa fa-save" value="Применить"/>
			</form>
		</div>
		';

	}
	static function CountDiscount($exp){//функция счета скидки 
		$arrForString = array();
		foreach ($exp as $v) {
				$v = explode("#", $v);//разбиваем строку решеткой 
				$value = $v[1]-($v[1]/100*self::$discount);// формула скидки
				$arrForString[] = $v[0]."#".$value."#";//объединяем описание со значением, помещаем объединение в массив					
			}
			return $arrForString;//возвращаем массив со значениями со скидкой с описанием
	}
}