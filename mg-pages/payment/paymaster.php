<div class="payment-form-block">


<?php 

	if (MG::getSetting('currencyShopIso') == 'RUR') {
		$currency = 'RUB';
	}
	else{
		$currency = MG::getSetting('currencyShopIso');
	}

	if ($data['paramArray'][3]['value'] == 'true' || $data['paramArray'][3]['value'] == true || $data['paramArray'][3]['value'] == 1) {


		$content = unserialize(stripslashes($data['orderInfo'][$data['id']]['order_content']));

		if (count($content) < 11) {

			switch ($data['paramArray'][4]['value']) {
				case 'без НДС':
					$tax = 'no_vat';
					break;
				case '0%':
					$tax = 'vat0';
					break;
				case '10%':
					$tax = 'vat110';
					break;
				
				default:
					$tax = 'vat118';
					break;
			}

			echo '<form id="pay" name="pay" method="POST" action="https://paymaster.ru/Payment/Init">';
			echo '<input type="hidden" name="LMI_MERCHANT_ID" value="'.$data['paramArray'][0]['value'].'">';
			echo '<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="'.$data['summ'].'">';
			echo '<input type="hidden" name="LMI_CURRENCY" value="'.$currency.'">';
			echo '<input type="hidden" name="LMI_PAYMENT_NO" value="'.$data['id'].'">';
			echo '<input type="hidden" name="LMI_PAYMENT_DESC" value="Oplata zakaza # '.$data['orderNumber'].'">';

			foreach ($content as $key => $value) {

				$tmp = explode(PHP_EOL, $content[$key]['name']);

				echo '<input type="hidden" name="LMI_SHOPPINGCART.ITEM['.$key.'].NAME" value="'.MG::textMore($tmp[0], 125).'">';
				echo '<input type="hidden" name="LMI_SHOPPINGCART.ITEM['.$key.'].QTY" value="'.(float)round($content[$key]['count'], 3).'">';
				echo '<input type="hidden" name="LMI_SHOPPINGCART.ITEM['.$key.'].PRICE" value="'.(float)round($content[$key]['price'], 2).'">';
				echo '<input type="hidden" name="LMI_SHOPPINGCART.ITEM['.$key.'].TAX" value="'.$tax.'">';

				unset($item);
				unset($tmp);
			}

			echo '<input type="submit" class="btn" value="Оплатить" style="padding: 10px 20px;">';
			echo '</form>';
			echo '<p>';
			echo '<em>';
			echo 'Вы можете изменить способ оплаты данного заказа из Вашего личного кабинета в разделе "<a href="'.SITE.'/personal">История заказов</a>".';
			echo '</em>';
			echo '</p>';

		}
		else{
			echo '<p>Этот метод оплаты не поддерживает более 10 позиций товара в одном заказе.</p>';
			echo '<p>Разбейте заказ на несколько частей или выберите другой способ оплаты.</p>';
		}
	}
	else{
		echo '<form id="pay" name="pay" method="POST" action="https://paymaster.ru/Payment/Init">';
		echo '<input type="hidden" name="LMI_MERCHANT_ID" value="'.$data['paramArray'][0]['value'].'">';
		echo '<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="'.$data['summ'].'">';
		echo '<input type="hidden" name="LMI_CURRENCY" value="'.$currency.'">';
		echo '<input type="hidden" name="LMI_PAYMENT_NO" value="'.$data['id'].'">';
		echo '<input type="hidden" name="LMI_PAYMENT_DESC" value="Oplata zakaza # '.$data['orderNumber'].'">';
		echo '<input type="submit" class="btn" value="Оплатить" style="padding: 10px 20px;">';
		echo '</form>';
		echo '<p>';
		echo '<em>';
		echo 'Вы можете изменить способ оплаты данного заказа из Вашего личного кабинета в разделе "<a href="'.SITE.'/personal">История заказов</a>".';
		echo '</em>';
		echo '</p>';
	}
?>

</div>