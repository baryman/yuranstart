<?php
/*

  Plugin Name: Плагин Максима Кузнецова

  Description: Плагин мой :-)

  Author: Максим Кузнецова

  Version: 3.0.3

 */

new MyPlugin(); 

mgAddMeta('<link href="'.SITE.'/mg-plugins/my-plug/css/style.css" rel="stylesheet" type="text/css">');

class MyPlugin{
	
			public static $idProduct = null;
	
	public function __construct(){
		
		
		mgActivateThisPlugin(__FILE__, array(__CLASS__, 'createDateBaseNews'));
		mgAddShortcode('count-views', array(__CLASS__, 'Viewsik'));
		mgAddAction(__FILE__, array(__CLASS__, 'pagePluginmy'));
		mgAddAction('mg_start', array(__CLASS__, 'RefreshDate'));
		

		
	}
	
	public static function createDateBaseNews(){
		
		$numRowsDate = DB::numRows(DB::query("SELECT * FROM `".PREFIX."setting` WHERE `option` = 'datePrintMyPlug'"));
		$numRowsRows = DB::numRows(DB::query("SELECT * FROM `".PREFIX."setting` WHERE `option` = 'countPrintRowsMyPlug'"));
		
	if($numRowsDate == 0 ){
		
		DB::query("
			INSERT INTO `".PREFIX."setting`
			
			(`option`, `value`, `active`)
			
			VALUES ('datePrintMyPlug', '".date("j")."', 'N')
		
		");
		
		}
		
		if($numRowsRows == 0){
		DB::query("
			INSERT INTO `".PREFIX."setting`
			
			(`option`, `value`, `active`)
			
			VALUES ('countPrintRowsMyPlug', '100', 'N')
		
		");
		}
		
	}
	
	
	public static function pagePluginmy(){
		
		$lang = PM::plugLocales('my-plug');
		
		if ($_POST["page"])

				$page = $_POST["page"];
		
					$countRows = MG::getOption('countPrintRowsMyPlug');
		
					$navigator = new Navigator("SELECT  *  FROM `".PREFIX."product` ORDER BY `count_views_today` DESC", $page, $countRows); //определяем класс

					$myPlug = $navigator->getRowsSql();

    $pagination = $navigator->getPager('forAjax');
		
		
		include 'pagePlugin.php';
		
	}
	
	
	

	
	
	public static function Viewsik($arg){
				
			
			if(isset($arg['id'])){
				
				self::$idProduct = $arg['id'];
				
				if($arg['do'] == 'pls'){
					
					if(!User::AccessOnly("1")){ // если это не админ, то + , если админ, то ничего не прибавляем
				
						DB::query("UPDATE `".PREFIX."product` SET `count_views` = `count_views`+1 WHERE `id` = '".self::$idProduct."'");
						DB::query("UPDATE `".PREFIX."product` SET `count_views_today` = `count_views_today`+1 WHERE `id` = '".self::$idProduct."'");
						
					}	
				
				}else{
					
					$row = DB::fetchAssoc(DB::query("SELECT * FROM `".PREFIX."product` WHERE `id`='".self::$idProduct."'"));
					return $row['count_views'];
					
				}
				
				
			}
						
		
	}
	
	public static function RefreshDate(){
		
		if(idate('d') !== self::get_date()){
			
			DB::query("UPDATE `".PREFIX."setting` SET `value` = '".date("j")."' WHERE `option` = 'datePrintMyPlug'");
			
			DB::query('UPDATE `mg_product` SET `count_views_today` = 0 ');
			
			
		}
		
		
	}
	
	public static function get_date(){
		
		$sql= DB::query("SELECT `value` FROM `".PREFIX."setting` WHERE `option` = 'datePrintMyPlug'");
		$query = DB::fetchAssoc($sql);
		
		return (int)$query['value'];
		
	}
	
	
	
}
 
?>