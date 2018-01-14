<?php
$model = new Models_Catalog;

$model->category_id = MG::get('category')->getCategoryList();
$model->category_id[] = 0;

$listCategories = MG::get('category')->getCategoryTitleList();
$arrayCategories = $model->category_id = MG::get('category')->getHierarchyCategory(0);
$this->select_categories = MG::get('category')->getTitleCategory($arrayCategories);
$this->categories = MG::get('category')->getCategoryListUl(0, 'admin');

$this->countCategory =  MG::get('category')-> getCategoryCount();

// 
$array = array();
$res = DB::query('SELECT DISTINCT * FROM '.PREFIX.'category WHERE parent = 0 GROUP BY sort ASC');
while($row = DB::fetchAssoc($res)) {
	$array[] = $row;
}

// необходимо для корректного вывода таблицы
$_SESSION['categoryCountToAdmin'] = 0;
$categoryList = Category::getPages($array, 0, 0);
if($categoryList == '') {
	$categoryList = '<tr><td colspan="6" style="text-align:center;">Категории не найдены</td></tr>';
}
$this->getCategories = $categoryList;
unset($_SESSION['categoryCountToAdmin']);