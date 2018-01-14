<style type="text/css">
	.countSalesCenter table {
		width: 100%;
		padding: 10px;
	}
	.countSalesCenter table th,
	.countSalesCenter table tr {
		border: 1px solid #ccc !important; 
		padding: 5px;
		/*border-width: 1px 1px 1px 1px !important;*/
	}
	.countSalesCenter table tr th {
		text-align: center;
	}
	.countSalesCenter table tr td,
	.countSalesCenter table tr th {
		border: 1px solid #ccc !important; 
		border-width: 1px 0 0 1px !important;
	}
	.countSalesCenter table .left-col {
		padding-right: 10px;
		width: 50%;
	}
	.countSalesCenter table .right-col {
		padding-left: 10px;
		vertical-align: top;
	}
	.countSalesCenter .main-table .no-border {
		border: 0;
	}
	.countSalesCenter #product-list tr:hover {
		background-color: #eee!important;
		cursor: pointer;
	}
	.countSalesCenter #product-list tr.active {
		background-color: #c0c0c0;
	}
	.countSalesCenter .add-price {
		float: right;
	}
	.countSalesCenter #settings-list {
		text-align: center;
	}
	.countSalesCenter #settings-list td {
		border: 1px solid #ccc; 
		padding: 5px;
	}
	.countSalesCenter #settings-list td .delete-price {
		color: red;
		cursor: pointer;
	}
	.countSalesCenter #settings-list td input {
		width: auto;
	}
	/*.countSalesCenter .add-price {
		border-radius: 0;
		padding: 2px 7px;
	}*/
	.countSalesCenter .search {
		margin-bottom: 10px;
		width: auto;
	}
	.countSalesCenter tr:hover {
		background-color: #fff;
	}
	.mg-admin-html table.main-table tbody tr:hover {
		background-color: #fff;
	}
	.countSalesCenter .button.primary,
	.countSalesCenter input[type=text] {
		margin-bottom: 0;
	}
</style>

<div class="section-<?php echo $pluginName ?> countSalesCenter">

	<table class="main-table">
		<tr class="no-border">
			<td>
				<input class="search" type="text" placeholder="Поиск">
			</td>
			<td>
				<button class="add-price button primary">Добавить цену</button>
			</td>
		</tr>
		<tr class="no-border">
			<td class="left-col">
				<table class="products">
					<thead>
						<th>id</th>
						<th>артикул</th>
						<th>название</th>
					</thead>
					<tbody id="product-list">
						<?php
							foreach ($prodList as $item) {
								echo 
									'<tr data-id="'.$item['id'].'">
										<th>'.$item['id'].'</th>
										<th>'.$item['code'].'</th>
										<th>'.$item['title'].'</th>
									</tr>';
							}
						?>
					</tbody>
				</table>
			</td>
			<td class="right-col">
				<table class="settings">
					<thead>
						<th>От (кол-во)</th>
						<th>Стоимость</th>
						<th>Действия</th>
					</thead>
					<tbody id="settings-list"></tbody>
				</table>
			</td>
		</tr>
	</table>

</div>

<script type="text/javascript">
if(countSales == undefined) {

	var countSales = {
		id: 0,

		init: function() {
			$('.admin-center').on('click', '.countSalesCenter #product-list tr', function() {
				$('.countSalesCenter #product-list tr').removeClass('active');
				$(this).addClass('active');

				countSales.id = $(this).data('id');
				countSales.loadPrice();
			});

			$('.admin-center').on('click', '.countSalesCenter .add-price', function() {
				countSales.add();
			});

			$('.admin-center').on('click', '.countSalesCenter .delete-price', function() {
				if(confirm('Удалить?')) {
					countSales.deletePrice($(this).parents('.save-row').data('id'));
				}
			});

			$('.admin-center').on('change', '.countSalesCenter .save', function() {
				var id = $(this).parents('.save-row').data('id');
				var count = $(this).parents('.save-row').find('.count').val();
				var price = $(this).parents('.save-row').find('.price').val();
				countSales.savePrice(id, count, price);
			});

			$('.admin-center').on('keyup', '.countSalesCenter .search', function() {
				countSales.loadProducts();
			});
		},

		add: function() {
			admin.ajaxRequest({
			  mguniqueurl: "action/addPrice",
			  pluginHandler: 'count-sales',
			  id: countSales.id,
			},
			function(response) {
				countSales.loadPrice();
			});
		},

		deletePrice: function(id) {
			admin.ajaxRequest({
			  mguniqueurl: "action/deletePrice",
			  pluginHandler: 'count-sales',
			  id: id,
			},
			function(response) {
				countSales.loadPrice();
			});
		},

		savePrice: function(id, count, price) {
			admin.ajaxRequest({
			  mguniqueurl: "action/savePrice",
			  pluginHandler: 'count-sales',
			  id: id,
			  count: count,
			  price: price,
			},
			function(response) {
				countSales.loadPrice();
			});
		},

		loadProducts: function() {
			var search = $('.search').val();
			admin.ajaxRequest({
			  mguniqueurl: "action/loadProducts",
			  pluginHandler: 'count-sales',
			  search: search,
			},
			function(response) {
				if(response.data == null) {
					$('#product-list').html('<tr><td colspan="3" style="text-center">Товары не найдены</td></tr>');
				} else {
					$('#product-list').html('');
					for(i = 0; i < response.data.length; i++) {
						$('#product-list').append('\
							<tr data-id="'+response.data[i].id+'">\
								<th>'+response.data[i].id+'</th>\
								<th>'+response.data[i].code+'</th>\
								<th>'+response.data[i].title+'</th>\
							</tr>');
					}
				}
			});
		},

		loadPrice: function() {
			admin.ajaxRequest({
			  mguniqueurl: "action/loadPrice",
			  pluginHandler: 'count-sales',
			  id: countSales.id,
			},
			function(response) {
				if(response.data == null) {
					$('#settings-list').html('<tr><td colspan="3" style="text-center">Цены не установлены</td></tr>');
				} else {
					$('#settings-list').html('');
					for(i = 0; i < response.data.length; i++) {
						$('#settings-list').append('\
							<tr data-id="'+response.data[i].id+'" class="save-row">\
								<td><input type="text" class="count save" value="'+response.data[i].count+'"></td>\
								<td><input type="text" class="price save" value="'+response.data[i].price+'"></td>\
								<td><span class="delete-price"><i class="fa fa-trash"></i></span></td>\
							</tr>');
					}
				}
			});
		},
	} 

	countSales.init();

}
</script>