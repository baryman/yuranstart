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
?>

<?php mgAddMeta('<script type="text/javascript" src="' . SCRIPT . 'jquery.bxslider.min.js"></script>'); ?>

<h1 class="page-title <?php echo $data['class_title'] ?>"><?php echo $data['titeCategory'] ?></h1>
<div class="products-wrapper group">
    <?php
    if (!empty($data['items']))
        foreach ($data['items'] as $item):?>
            <div class="product-wrapper" itemscope itemtype="http://schema.org/Product">
                <a href="<?php echo $item["link"] ?>" class="product-image">
                    <div class="product-stickers">
                        <?php
                        echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                        echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                        ?>
                    </div>
                    <?php echo mgImageProduct($item); ?>
                </a>
                <div class="product-details">
                    <a href="<?php echo $item["link"] ?>" class="product-name" itemprop="name" content="<?php echo $item["title"] ?>">
                        <?php echo $item["title"] ?>
                    </a>
                    <div class="product-description">
                        <?php echo MG::textMore($item["description"], 50) ?>
                    </div>
                    <div class="product-footer">
                        <div class="product-price">
                                <span class="product-normal-price">
                                    <?php echo priceFormat($item["price"]) ?><?php echo $data['currency']; ?>
                                </span>
                        </div>

                        <div class="product-buttons">
                            <div class="single-button">
                                <!--Кнопка, кототорая меняет свое значение с "В корзину" на "Подробнее"-->
                                <?php echo $item[$data['actionButton']] ?>
                            </div>

                            <div class="double-buttons">
                                <!-- Плагин купить одним кликом-->
                                <?php if (class_exists('BuyClick')): ?>
                                    [buy-click id="<?php echo $item['id'] ?>"]
                                <?php endif; ?>
                                <!--/ Плагин купить одним кликом-->

                                <?php echo $item['actionCompare'] ?>
                            </div>
                        </div>
                    </div>
                    <!-- Плагин рейтинг товаров-->
                    <?php if (class_exists('Rating')): ?>
                        <div class="mg-rating">
                            [rating id = "<?php echo $item['id'] ?>"]
                        </div>
                    <?php endif; ?>
                    <!--/ Плагин рейтинг товаров-->
                </div>
            </div>
        <?php endforeach; ?>
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
                    <div class="product-wrapper" itemscope itemtype="http://schema.org/Product">
                        <a href="<?php echo $item["link"] ?>" class="product-image">
                            <div class="product-stickers">
                                <?php
                                echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                                echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                                ?>
                            </div>
                            <?php echo mgImageProduct($item); ?>
                        </a>
                        <a href="<?php echo $item["link"] ?>" class="product-name" itemprop="name" content="<?php echo $item["title"] ?>">
                            <?php echo $item["title"] ?>
                        </a>
                        <div class="product-description">
                            <?php echo MG::textMore($item["description"], 50) ?>
                        </div>
                        <div class="product-footer">
                            <div class="product-price">
                                <span class="product-normal-price">
                                    <?php echo priceFormat($item["price"]) ?><?php echo $data['currency']; ?>
                                </span>
                            </div>

                            <div class="product-buttons">
                                <div class="single-button">
                                    <!--Кнопка, кототорая меняет свое значение с "В корзину" на "Подробнее"-->
                                    <?php echo $item[$data['actionButton']] ?>
                                </div>

                                <div class="double-buttons">
                                    <!-- Плагин купить одним кликом-->
                                    <?php if (class_exists('BuyClick')): ?>
                                        [buy-click id="<?php echo $item['id'] ?>"]
                                    <?php endif; ?>
                                    <!--/ Плагин купить одним кликом-->

                                    <?php echo $item['actionCompare'] ?>
                                </div>
                            </div>
                        </div>
                        <!-- Плагин рейтинг товаров-->
                        <?php if (class_exists('Rating')): ?>
                            <div class="mg-rating">
                                [rating id = "<?php echo $item['id'] ?>"]
                            </div>
                        <?php endif; ?>
                        <!--/ Плагин рейтинг товаров-->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($data['recommendProducts'])): ?>
    <div class="m-p-products recommend">
        <div class="title"><a href="<?php echo SITE; ?>/group?type=recommend">Хит продаж</a></div>
        <div class="m-p-products-slider">
            <div class="<?php echo count($data['recommendProducts']) > 3 ? "m-p-products-slider-start" : "" ?>">
                <?php foreach ($data['recommendProducts'] as $item): ?>
                    <div class="product-wrapper" itemscope itemtype="http://schema.org/Product">
                        <a href="<?php echo $item["link"] ?>" class="product-image">
                            <div class="product-stickers">
                                <?php
                                echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                                echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                                ?>
                            </div>
                            <?php echo mgImageProduct($item); ?>
                        </a>
                        <a href="<?php echo $item["link"] ?>" class="product-name" itemprop="name" content="<?php echo $item["title"] ?>">
                            <?php echo $item["title"] ?>
                        </a>
                        <div class="product-description">
                            <?php echo MG::textMore($item["description"], 50) ?>
                        </div>
                        <div class="product-footer">
                            <div class="product-price">
                                <span class="product-normal-price">
                                    <?php echo priceFormat($item["price"]) ?><?php echo $data['currency']; ?>
                                </span>
                            </div>

                            <div class="product-buttons">
                                <div class="single-button">
                                    <!--Кнопка, кототорая меняет свое значение с "В корзину" на "Подробнее"-->
                                    <?php echo $item[$data['actionButton']] ?>
                                </div>

                                <div class="double-buttons">
                                    <!-- Плагин купить одним кликом-->
                                    <?php if (class_exists('BuyClick')): ?>
                                        [buy-click id="<?php echo $item['id'] ?>"]
                                    <?php endif; ?>
                                    <!--/ Плагин купить одним кликом-->

                                    <?php echo $item['actionCompare'] ?>
                                </div>
                            </div>
                        </div>
                        <!-- Плагин рейтинг товаров-->
                        <?php if (class_exists('Rating')): ?>
                            <div class="mg-rating">
                                [rating id = "<?php echo $item['id'] ?>"]
                            </div>
                        <?php endif; ?>
                        <!--/ Плагин рейтинг товаров-->
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
                    <div class="product-wrapper" itemscope itemtype="http://schema.org/Product">
                        <a href="<?php echo $item["link"] ?>" class="product-image">
                            <div class="product-stickers">
                                <?php
                                echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                                echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                                ?>
                            </div>
                            <?php echo mgImageProduct($item); ?>
                        </a>
                        <a href="<?php echo $item["link"] ?>" class="product-name" itemprop="name" content="<?php echo $item["title"] ?>">
                            <?php echo $item["title"] ?>
                        </a>
                        <div class="product-description">
                            <?php echo MG::textMore($item["description"], 50) ?>
                        </div>
                        <div class="product-footer">
                            <div class="product-price">
                                <span class="product-normal-price">
                                    <?php echo priceFormat($item["price"]) ?><?php echo $data['currency']; ?>
                                </span>
                                <span class="product-old-price">
                                      <?php echo $item["old_price"] ?><?php echo $data['currency']; ?>
                                  </span>
                            </div>

                            <div class="product-buttons">
                                <div class="single-button">
                                    <!--Кнопка, кототорая меняет свое значение с "В корзину" на "Подробнее"-->
                                    <?php echo $item[$data['actionButton']] ?>
                                </div>

                                <div class="double-buttons">
                                    <!-- Плагин купить одним кликом-->
                                    <?php if (class_exists('BuyClick')): ?>
                                        [buy-click id="<?php echo $item['id'] ?>"]
                                    <?php endif; ?>
                                    <!--/ Плагин купить одним кликом-->

                                    <?php echo $item['actionCompare'] ?>
                                </div>
                            </div>
                        </div>
                        <!-- Плагин рейтинг товаров-->
                        <?php if (class_exists('Rating')): ?>
                            <div class="mg-rating">
                                [rating id = "<?php echo $item['id'] ?>"]
                            </div>
                        <?php endif; ?>
                        <!--/ Плагин рейтинг товаров-->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
