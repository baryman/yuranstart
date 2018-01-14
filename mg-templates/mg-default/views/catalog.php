<?php
/**
 *  Файл представления Catalog - выводит сгенерированную движком информацию на странице сайта с каталогом товаров.
 *  В этом  файле доступны следующие данные:
 *   <code>
 *    $data['items'] => Массив товаров
 *    $data['titeCategory'] => Название открытой категории
 *    $data['cat_desc'] => Описание открытой категории
 *    $data['pager'] => html верстка  для навигации страниц
 *    $data['searchData'] =>  результат поисковой выдачи
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
 *    <php echo $data['items']; ?>
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

<!-- Верстка каталога -->
<?php if (empty($data['searchData'])): ?>
    <?php if (MG::getSetting('picturesCategory') == 'true'): ?>
        <?php echo mgSubCategory($data['cat_id']); ?>
    <?php endif; ?>

    <h1 class="page-title"><?php echo $data['titeCategory'] ?></h1>
    <?php if ($cd = str_replace("&nbsp;", "", $data['cat_desc'])): ?>
        <div class="cat-desc clearfix">
            <?php if ($data['cat_img']): ?>
                <div class="cat-desc-img">
                    <img src="<?php echo SITE . $data['cat_img'] ?>" alt="<?php echo $data['titeCategory'] ?>"
                         title="<?php echo $data['titeCategory'] ?>">
                </div>
            <?php endif; ?>
            <?php if (URL::isSection('catalog') || (((MG::getSetting('catalogIndex') == 'true') && (URL::isSection('index') || URL::isSection(''))))): ?>
                <!-- Здесь можно добавить описание каталога - информация для пользователей (выводится только на странице каталог (не в категории)) -->
            <?php else : ?>
                <div class="cat-desc-text"><?php echo $data['cat_desc'] ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="form-group clearfix clearfix">
        <div class="view-switcher">
            <div class="btn-group clearfix" data-toggle="buttons-radio">
                <button class="view-btn list" title="Списком" data-type="list"></button>
                <button class="view-btn grid" title="Плиткой" data-type="grid"></button>
            </div>
        </div>
        <div class="count-viewed"></div>
    </div>

    <div class="products-wrapper catalog clearfix">

        <?php layout("apply_filter", $data['applyFilter']); ?>

        <?php foreach ($data['items'] as $item): ?>
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
                                    <?php echo priceFormat($item["price"]) ?> <?php echo $data['currency']; ?>
                                </span>
                        </div>

                        <div class="product-buttons">
                            <!--Кнопка, которая меняет свое значение с "В корзину" на "Подробнее"-->
                            <?php echo $item['buyButton']; ?>
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
    </div>
    <?php echo $data['pager']; ?>

    <?php if (URL::isSection('catalog') || (((MG::getSetting('catalogIndex') == 'true') && (URL::isSection('index') || URL::isSection(''))))): ?>
        <div class="cat-desc-text"><?php echo $data['cat_desc'] ?></div>
    <?php endif; ?>
    <div class="cat-desc-text">
        <?php echo $data['cat_desc_seo'] ?>
    </div>
    <!-- Верстка поиска -->
<?php else: ?>

    <h1 class="page-title">При поиске по фразе: <strong>"<?php echo $data['searchData']['keyword'] ?>"</strong>
        найдено
        <strong><?php echo mgDeclensionNum($data['searchData']['count'], array('товар', 'товара', 'товаров')); ?></strong>
    </h1>

    <div class="search-results products-wrapper list">
        <?php foreach ($data['items'] as $item): ?>
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
                                    <?php echo priceFormat($item["price"]) ?> <?php echo $data['currency']; ?>
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
    </div>

    <?php echo $data['pager'];?>
<?php endif;?>
<!-- / Верстка поиска -->