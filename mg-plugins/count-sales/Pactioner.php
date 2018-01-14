<?php
	class Pactioner extends Actioner {  

		public function loadProducts() {
			$this->data = countSales::loadProducts();
		  	return true;
		}

		public function addPrice() {
		  	return countSales::addPrice();
		}

		public function deletePrice() {
		  	return countSales::deletePrice();
		}

		public function loadPrice() {
			$this->data = countSales::loadPrice();
		  	return true;
		}

		public function savePrice() {
		  	return countSales::savePrice();
		}

	}
?>