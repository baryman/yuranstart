<?php
/**
 *  Файл представления Product - выводит сгенерированную движком информацию на странице карточки товара.
 *  В этом файле доступны следующие данные:
 *   <code>
 *   $data['category_url'] => URL категории в которой находится продукт
 *   $data['product_url'] => Полный URL продукта
 *   $data['id'] => id продукта
 *   $data['sort'] => порядок сортировки в каталоге
 *   $data['cat_id'] => id категории
 *   $data['title'] => Наименование товара
 *   $data['description'] => Описание товара
 *   $data['price'] => Стоимость
 *   $data['url'] => URL продукта
 *   $data['image_url'] => Главная картинка товара
 *   $data['code'] => Артикул товара
 *   $data['count'] => Количество товара на складе
 *   $data['activity'] => Флаг активности товара
 *   $data['old_price'] => Старая цена товара
 *   $data['recommend'] => Флаг рекомендуемого товара
 *   $data['new'] => Флаг новинок
 *   $data['thisUserFields'] => Пользовательские характеристики товара     
 *   $data['images_product'] => Все изображения товара 
 *   $data['currency'] => Валюта магазина.
 *   $data['propertyForm'] => Форма для карточки товара
 * 	 $data['liteFormData'] => Упрощенная форма для карточки товара
 *   $data['meta_title'] => Значение meta тега для страницы,
 *   $data['meta_keywords'] => Значение meta_keywords тега для страницы,
 *   $data['meta_desc'] => Значение meta_desc тега для страницы
 *   </code>
 *   
 *   Получить подробную информацию о каждом элементе массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>     
 *    <php viewData($data['thisUserFields']); ?>  
 *   </code>
 * 
 *   Вывести содержание элементов массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>     
 *    <php echo $data['thisUserFields']; ?>  
 *   </code>
 * 
 *   <b>Внимание!</b> Файл предназначен только для форматированного вывода данных на страницу магазина. Категорически не рекомендуется выполнять в нем запросы к БД сайта или реализовывать сложную программную логику логику.
 *   @author Авдеев Марк <mark-avdeev@mail.ru>
 *   @package moguta.cms
 *   @subpackage Views
 */
// Установка значений в метатеги title, keywords, description.
mgSEO($data);
mgAddMeta('<link href="'.SCRIPT.'standard/css/layout.related.css" rel="stylesheet" type="text/css" />');
mgAddMeta('<script type="text/javascript" src="'.SCRIPT.'standard/js/layout.related.js"></script>');
?>
<div class="product-details-block" itemscope itemtype="http://schema.org/Product">
  
  <?php if(class_exists('BreadCrumbs')): ?>
    [brcr]
  <?php endif; ?>
    
  <h1 class="product-title" itemprop="name"><?php echo $data['title'] ?></h1>

  <?php mgGalleryProduct($data); ?>
  <div class="product-status">
    <div class="buy-block">
      <div class="buy-block-inner">
        <div class="product-code">
          Артикул: <span class="label-article code" itemprop="productID"><?php echo $data['code'] ?></span>
        </div>
        <?php if(class_exists('Rating')): ?>
            [rating id = "<?php echo $data['id'] ?>"]
          <?php endif; ?>
        <div  itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <div class="product-price">
          <ul class="product-status-list">
            <div class="mini-sale" <?php echo (!$data['old_price'])?'style="display:none"':'style="display:block"' ?>>Экономия <?php echo ($data['old_price']-$data['price']).' '.$data['currency'] ;?></div>
            <li <?php echo (!$data['old_price'])?'style="display:none"':'style="display:block"' ?>>
              <div class="old">
                <s><span class="old-price"><?php echo MG::numberFormat($data['old_price'])." ".$data['currency']; ?></span></s>
              </div>
            </li>
            <li>
              <div class="normal-price">
                 <span class="price" ><span itemprop="price"><?php echo $data['price'] ?></span><span itemprop="priceCurrency"> <?php echo $data['currency']; ?></span></span>
              </div>
            </li>
          </ul>
        </div>
          

        <ul class="product-status-list">
          <!--если не установлен параметр - старая цена, то не выводим его-->
          <li class="count-product-info">
              <?php layout('count_product', $data); ?>
          </li>
          
          <li <?php echo (!$data['weight'])?'style="display:none"':'style="display:block"' ?>>Вес: <span class="label-black weight"><?php echo $data['weight'] ?></span> кг. </li>
        </ul>
        </div>
        <!--Кнопка, кототорая меняет свое значение с "В корзину" на "Подробнее"-->
        <?php echo $data['propertyForm'] ?>
        <div class="product-price mobile">
          <ul class="product-status-list">
            <li>
              <div class="mini-sale"<?php echo (!$data['old_price'])?'style="display:none"':'style="display:block"' ?>>Экономия <?php echo ($data['old_price']- $data['price']).' '.$data['currency'];?></div>
              <div class="old" <?php echo (!$data['old_price'])?'style="display:none"':'style="display:block"' ?>>
                <span class="old-price"><?php echo MG::numberFormat($data['old_price'])." ".$data['currency']; ?></span>
              </div>
            </li>
            <li>
              <div class="normal-price">
                <span class="price"><?php echo $data['price'] ?> <?php echo $data['currency']; ?></span>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div><!-- End product-status-->
  [count-views id=<?php echo $data['id'] ?> do="pls"]
  
  [trigger-guarantee id=" 2"]
  
  

  <?php if(class_exists('Pluso')): ?>
    [pluso]
  <?php endif; ?>

  <div class="clear"></div>

  <div class="product-details-wrapper">
    <ul class="product-tabs">
      <li><a href="#tab1">Описание</a></li>
      
      <?php foreach ($data['thisUserFields'] as $key => $value) {
        if ($value['type']=='textarea'&&$value['value']) {?>
      <li><a href="#tab<?php echo $key?>"><?php echo $value['name']?></a></li>        
      <?php   }
        
      }?>
      
      <li><a href="#tab2">Отзывы</a></li>
      <li><a href="#tab3">Оплата</a></li>
      
    </ul>
    <div class="product-tabs-container">
      <div id="tab1" itemprop="description"><?php echo $data['description'] ?></div>
      
      <?php foreach ($data['thisUserFields'] as $key => $value) {
        if ($value['type']=='textarea') {?>
      <div id="tab<?php echo $key?>" itemscope><?php echo preg_replace('/\<br(\s*)?\/?\>/i', "\n", $value['value'])?>
      </div>        
      <?php  }
        
      }?>
      
      <div id="tab2" itemscope itemtype="http://schema.org/Review">
        
        <?php if(class_exists('CommentsToMoguta')): ?>
          [comments]
        <?php endif; ?>    
          
      </div>
      <div id="tab3">
        
		<table class="payments-product">
			<tr>
              <td><a href="/dostavka"><img src="<?php echo PATH_SITE_TEMPLATE ?>/images/payments/cash-delivery.png" alt="Наличными курьеру (в пределах Москвы)"></a></td>
              <td><h3>Наличными курьеру (в пределах Москвы)</h3> Самый простой и надежнай способ оплаты из рук в руки с курьером. Осмотр товара на месте.</td>
			</tr>
			<tr>
				<td><a href="/dostavka/pay-sberbank"><img src="<?php echo PATH_SITE_TEMPLATE ?>/images/payments/credit-cart.png" alt="Перевод средств на карту Сбербанка"></a></td>
				<td><h3>Перевод средств на карту Сбербанка</h3> Быстрый и надежный способ оплаты с помощью карты. Пополняйте наш баланс посредством перевода через Сбербанк.Онл@йн или Мобильный.Банк</td>
			</tr>
			<tr>
				<td><a href="/dostavka/pay-yandex-money"><img src="<?php echo PATH_SITE_TEMPLATE ?>/images/payments/yandex-money.png" alt="Яндекс.Деньги"></a></td>
				<td><h3>Онлайн перевод с помощью Яндекс.Деньги</h3>Быстрый и надежный способ оплаты. Для это требуется наличие кошелька в Яндекс.Деньгах.</td>
			</tr>
			<tr>
				<td><a href="/dostavka/pay-webmoney"><img src="<?php echo PATH_SITE_TEMPLATE ?>/images/payments/webmoney.png" alt="WebMoney"></a></td>
				<td><h3>Онлайн перевод с помощью WebMoney</h3>Быстрый и надежный способ оплаты. Для это требуется наличие кошелька в WebMoney.</td>
			</tr>
		</table>
        
     </div>
      
    </div>
    
    <div class="delivery-info">[site-block id=1]</div>
    
    <div class="info-yuran-product">
      <p>Наш интернет-магазин дает возможность приобрести <strong><?php echo $data['title']?></strong> всего за <strong><?php echo $data['price'].' '.$data['currency'] ?></strong> ! 
      <p>Покупая у нас Вы можете быть на 100% увернным в качестве данного продукта. Если же Вас вдруг что-то не устроило, Вы всегда можете вернуть товар в течении 14 дней со дня получения, но заверям Вас, что этого не произойдет.
   	  <p>Так же, мы стараемся оперативно отправлять заказы в регионы, курьером по Москве так, что бы не заставлять Вас ожидать.
   	  <p>Итак, наши преимущества перед другими интернет-магазинами:
      	<ul>
          <li>Соответствие качества товара, даже если Вы не можете его потрогать вживую, Вы всегда останетесь довольны покупкой
          <li>Доставка осущствляется на следующий день, нежели в течении нескольких
          <li>Много товаров со скидками, которые обновляются каждый день
          <li>Низкие цены на товары  
      	</ul>
    </div>
  </div>
  

  <?php
  /* Следующая строка для вывода свойств в таблицу характеристик */
  /* $data['stringsProperties'] */
  ?>  
  <?php echo $data['related'] ?>

</div><!-- End product-details-block-->



