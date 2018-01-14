<?php

/*
  Plugin Name: Цены от количества
  Description: Плагин позволяет устанавливать цены в зависимости от количества добавленных товаров этого типа в корзину
  Author: Гайдис Михаил
  Version: 1.1.0
 */

new countSales();

class countSales {

	private static $pluginName = ''; // название плагина (соответствует названию папки)
	private static $path = ''; //путь до файлов плагина  

	public function __construct() {
	  //Инициализация  метода выполняющегося при активации  
	  mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
	  //Инициализация  метода выполняющегося при нажатии на кнопку настроект плагина
	  mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin'));
	  //Проверка наличия правила для товара, при расчете стоимости для корзины
	  mgAddAction('models_cart_customprice', array(__CLASS__, 'getPrice'), 1, 11);
	  self::$pluginName = PM::getFolderPlugin(__FILE__);
	  self::$path = PLUGIN_DIR . self::$pluginName;
	}

	//========================== системные методы ==========================//

	static function activate() {
	  self::createDBTable();
	}

	static function createDBTable() {
	DB::query("CREATE TABLE IF NOT EXISTS `".PREFIX.self::$pluginName."` (
	  `id` int(8) NOT NULL AUTO_INCREMENT,
	  `prod_id` int(8) NOT NULL DEFAULT '0',
	  `count` int(8) NOT NULL DEFAULT '0',
	  `price` float NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}

	static function pageSettingsPlugin() {
		$res = DB::query('SELECT * FROM '.PREFIX.'product LIMIT 20');
		while ($row = DB::fetchAssoc($res)) {
		  $prodList[] = $row;
		}
	  include('pageplugin.php');
	}

	//========================== для Применения цены ==========================//

	public function getPrice($args) {
		$id = $args['args'][0]['product']['id'];
		$price = $args['result'];
		$count = 1;

		foreach ($_SESSION['cart'] as $item) {
			if($item['id'] == $id) {
				$count = $item['count'];
			}
		}

		$res = DB::query('SELECT * FROM `'.PREFIX.self::$pluginName.'` 
			WHERE prod_id = '.DB::quote($id).' AND count <= '.DB::quote($count).' ORDER BY count DESC');
		while ($row = DB::fetchAssoc($res)) {
		  $resa[] = $row;
		}

		foreach ($resa as $item) {
			return $item['price'];	
		}

	 	return $price = $price;
	}

	//========================== для Pactioner ==========================//

	public function savePrice() {
		DB::query('UPDATE `'.PREFIX.self::$pluginName.'` SET count = '.DB::quote($_POST['count']).', price = '.DB::quote($_POST['price']).' 
			WHERE id = '.DB::quote($_POST['id']));
		return true;
	}

	public function deletePrice() {
		DB::query('DELETE FROM `'.PREFIX.self::$pluginName.'` WHERE id = '.DB::quote($_POST['id']));
		return true;
	}

	public function loadPrice() {
		$res = DB::query('SELECT * FROM `'.PREFIX.self::$pluginName.'` WHERE prod_id = '.DB::quote($_POST['id']).' ORDER BY count ASC');
		while ($row = DB::fetchAssoc($res)) {
		  $resa[] = $row;
		}
		return $resa;
	}

	public function loadProducts() {
		$res = DB::query('SELECT * FROM '.PREFIX.'product 
			WHERE code LIKE "'.DB::quote($_POST['search'], true).'%" OR title LIKE "%'.DB::quote($_POST['search'], true).'%" LIMIT 20');
		while ($row = DB::fetchAssoc($res)) {
		  $resa[] = $row;
		}
		return $resa;
	}

	public function addPrice() {
		DB::query('INSERT INTO `'.PREFIX.self::$pluginName.'` (prod_id, count, price) VALUES 
			('.DB::quote($_POST['id']).', 0, (SELECT price FROM '.PREFIX.'product WHERE id = '.DB::quote($_POST['id']).'))');
		return true;
	}

}