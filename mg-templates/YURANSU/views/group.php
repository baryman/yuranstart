<?php
/**
 *  Файл представления Group - выводит сгенерированную движком информацию на странице сайта с новинками, рекомендуемыми и товарами распродажи.
 *  В этом  файле доступны следующие данные:
 *   <code>
 * 'items' => $items['catalogItems'],
 *    $data['items'] => Массив товаров
 *    $data['titeCategory'] => Название открытой категории
 *    $data['pager'] => html верстка  для навигации страниц
 *    $data['meta_title'] => Значение meta тега для страницы
 *    $data['meta_keywords'] => Значение meta_keywords тега для страницы
 *    $data['meta_desc'] => Значение meta_desc тега для страницы
 *    $data['currency'] => Текущая валюта магазина
 *    $data['actionButton'] => тип кнопки в мини карточке товара
 *   </code>
 *
 *   Получить подробную информацию о каждом элементе массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <?php viewData($data['items']); ?>
 *   </code>
 *
 *   Вывести содержание элементов массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <?php echo $data['items']; ?>
 *   </code>
 *
 *   <b>Внимание!</b> Файл предназначен только для форматированного вывода данных на страницу магазина. Категорически не рекомендуется выполнять в нем запросы к БД сайта или реализовывать сложную программную логику логику.
 * @author Авдеев Марк <mark-avdeev@mail.ru>
 * @package moguta.cms
 * @subpackage Views
 */
// Установка значений в метатеги title, keywords, description.
mgSEO($data);

switch($_REQUEST['type']){
  
 	case 'sale': $desc = 'Нет ничего лучше как товары по выгодным ценам и предложениям. В этой группе приведены товары по акциям, на которые установлены скидки! Поэтому успейте купить прямо сейчас по очень выгодным ценам!';break;
  	case 'recommend': $desc = 'Очень важным товары находятся именно в этом болке! Ведь YURAN.SU заботится о Вашем выборе, поэтому мы привели некоторые позиции, которые с уверенностью хотим Вам порекомендовать для покупки!';break;
	case 'latest': $desc = 'Новые поступления и новинки ждут Вас!';break;
default:$desc = 'В данном списке выведены все товары, которые входят в группы';
}

?>

<?php mgAddMeta('<script type="text/javascript" src="' . SCRIPT . 'jquery.bxslider.min.js"></script>'); ?>

<h1 class="new-products-title <?php echo $data['class_title'] ?>"><?php echo $data['titeCategory'] ?></h1>
<div class="groupDesc"><?echo $desc;?></div>
<div class="products-wrapper group">
    <?php
    if (!empty($data['items']))
        foreach ($data['items'] as $item):?>
            <div class="product-wrapper">
                <div class="product-stickers">
                    <?php
                    echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                    echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                    ?>
                </div>
              <?php if (!empty($item['variant_exist'])): ?>
                                <div class='variants-text'><i class='fa fa-bookmark-o'></i> Есть варианты</div>
                        <?php endif; ?>
                <div class="product-image">
                  
                    <a href="<?php echo $item["link"] ?>">
                        <?php echo mgImageProduct($item); ?>
                    </a>
                </div>
                <?php if (class_exists('Rating')): ?>
                    <div class="mg-rating">
                        [rating id = "<?php echo $item['id'] ?>"]
                    </div>
                <?php endif; ?>
                <div class="product-code">Артикул: <?php echo $item["code"] ?></div>
                <div class="product-name">
                    <a href="<?php echo $item["link"] ?>"><?php echo $item["title"] ?></a>
                </div>
              	<div class="product-description">

          			<?php echo $item['thisUserFields']['42']['value']?>
          
       			</div>
                <div class="product-footer">
                    <span
                        class="product-price">
                      <?php if ($_REQUEST['type'] == 'sale' || $item['old_price']): ?>
                        <span
                            class="product-old-price"><?php echo $item["old_price"] ?> <?php echo $data['currency']; ?></span>
                    <?php endif; ?>
                      <?php echo priceFormat($item["price"]) ?> <?php echo $data['currency']; ?>
                  		<?php echo $item['old_price'] ? '<img src="/uploads/sale-min.png" title="Спешите купить '.$item["title"].' по выгодной цене - '.$item["price"].' '.$data['currency'].'" alt="Спешите купить '.$item["title"].' по выгодной цене - '.$item["price"].' '.$data['currency'].'">' : '' ; ?>
                  </span>
                  	<div class="countViews"><span class="eye-count">[count-views id=<?php echo $item['id']; ?>]</span></div>

                    <div class="product-buttons">
                        <!--Кнопка, которая меняет свое значение с "В корзину" на "Подробнее"-->
                        <?php echo $item['buyButton']; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <div class="clear"></div>
    <?php echo $data['pager']; ?>
    <!-- / Верстка каталога -->
</div>

<!-- Все группы -->

<?php if (!empty($data['newProducts'])): ?>

    <div class="m-p-products latest">
        <div class="title"><a href="<?php echo SITE; ?>/group?type=latest">Новинки</a></div>
        <div class="m-p-products-slider">
            <div class="<?php echo count($data['newProducts']) > 3 ? "m-p-products-slider-start" : "" ?>">
                <?php foreach ($data['newProducts'] as $item): ?>
                    <div class="product-wrapper">
                        <div class="product-stickers">
                            <?php
                            echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                            echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                            ?>
                        </div>
                      <?php if (!empty($item['variant_exist'])): ?>
                                <div class='variants-text'><i class='fa fa-bookmark-o'></i> Есть варианты</div>
                        <?php endif; ?>
                        <div class="product-image">
                            <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>">
                                <?php echo mgImageProduct($item); ?>
                            </a>
                        </div>
                        <?php if (class_exists('Rating')): ?>
                            <div class="mg-rating">
                                [rating id = "<?php echo $item['id'] ?>"]
                            </div>
                        <?php endif; ?>
                        <div class="product-code">Артикул: <?php echo $item["code"] ?></div>
                        <div class="product-name">
                            <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a>
                        </div>
                        <div class="product-footer">
                            <span
                        class="product-price"><?php echo priceFormat($item["price"]) ?> <?php echo $data['currency']; ?></span>

                            <div class="product-buttons">
                                <!--Кнопка, которая меняет свое значение с "В корзину" на "Подробнее"-->
                                <?php echo $item['buyButton']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<?php endif; ?>

<?php if (!empty($data['recommendProducts'])): ?>
    <div class="m-p-products recommend">
        <div class="title"><a href="<?php echo SITE; ?>/group?type=recommend">Хит продаж</a></div>
        <div class="m-p-products-slider">
            <div class="<?php echo count($data['recommendProducts']) > 3 ? "m-p-products-slider-start" : "" ?>">
                <?php foreach ($data['recommendProducts'] as $item): ?>
                    <div class="product-wrapper">
                        <div class="product-stickers">
                            <?php
                            echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                            echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                            ?>
                        </div>
                      <?php if (!empty($item['variant_exist'])): ?>
                                <div class='variants-text'><i class='fa fa-bookmark-o'></i> Есть варианты</div>
                        <?php endif; ?>
                        <div class="product-image">
                            <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>">
                                <?php echo mgImageProduct($item); ?>
                            </a>
                        </div>
                        <?php if (class_exists('Rating')): ?>
                            <div class="mg-rating">
                                [rating id = "<?php echo $item['id'] ?>"]
                            </div>
                        <?php endif; ?>
                        <div class="product-code">Артикул: <?php echo $item["code"] ?></div>
                        <div class="product-name">
                            <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a>
                        </div>
                        <div class="product-footer">
                            <span
                                class="product-price"><?php echo priceFormat($item["price"]) ?> <?php echo $data['currency']; ?></span>

                            <div class="product-buttons">
                                <!--Кнопка, которая меняет свое значение с "В корзину" на "Подробнее"-->
                                <?php echo $item['buyButton']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<?php endif; ?>

<?php if (!empty($data['saleProducts'])): ?>
    <div class="m-p-products sale">
        <div class="title"><a href="<?php echo SITE; ?>/group?type=sale">Распродажа</a></div>
        <div class="m-p-products-slider">
            <div class="<?php echo count($data['saleProducts']) > 3 ? "m-p-products-slider-start" : "" ?>">
                <?php foreach ($data['saleProducts'] as $item): ?>
                    <div class="product-wrapper">
                        <div class="product-stickers">
                            <?php
                            echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                            echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                            ?>
                        </div>
                      <?php if (!empty($item['variant_exist'])): ?>
                                <div class='variants-text'><i class='fa fa-bookmark-o'></i> Есть варианты</div>
                        <?php endif; ?>
                        <div class="product-image">
                            <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>">
                                <?php echo mgImageProduct($item); ?>
                            </a>
                        </div>
                        <?php if (class_exists('Rating')): ?>
                            <div class="mg-rating">
                                [rating id = "<?php echo $item['id'] ?>"]
                            </div>
                        <?php endif; ?>
                        <div class="product-code">Артикул: <?php echo $item["code"] ?></div>
                        <div class="product-name">
                            <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a>
                        </div>
                        <div class="product-footer">
                            <span class="product-price">
                              <span class="product-old-price"><?php echo $item["old_price"] ?> <?php echo $data['currency']; ?></span>
                                <?php echo priceFormat($item["price"]) ?> <?php echo $data['currency']; ?>
                            </span>

                            <div class="product-buttons">
                                <!--Кнопка, которая меняет свое значение с "В корзину" на "Подробнее"-->
                                <?php echo $item['buyButton']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<?php endif; ?>
