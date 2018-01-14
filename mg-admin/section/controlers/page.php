<?php
/**
 *
 * Раздел управления страницами сайта.
 * Позволяет управлять заказами пользователей.
 *
 * @autor Авдеев Марк <mark-avdeev@mail.ru>
 */


$array = MG::get('pages')->getHierarchyPage(0);
$this->selectPages = MG::get('pages')->getTitlePage($array);
$this->pages = MG::get('pages')->getPagesUl(0, 'admin');

$this->countPages =  MG::get('pages')->getCountPages();

// 
$array = array();
$res = DB::query('SELECT DISTINCT * FROM '.PREFIX.'page GROUP BY sort ASC');
while($row = DB::fetchAssoc($res)) {
	$array[] = $row;
}

// необходимо для вывода таблицы со страницами
MG::set('pageCountToAdmin',0);
$pageList = Page::getPages($array, 0, 0);
if($pageList == '') {
	$pageList = '<tr><td colspan="6" style="text-align:center;">Страницы не найдены</td></tr>';
}
$this->getPages = $pageList;