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
 *     $data['liteFormData'] => Упрощенная форма для карточки товара
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
 * @author Авдеев Марк <mark-avdeev@mail.ru>
 * @package moguta.cms
 * @subpackage Views
 */
// Установка значений в метатеги title, keywords, description.
mgSEO($data);
mgAddMeta('<link href="' . SCRIPT . 'standard/css/layout.related.css" rel="stylesheet" type="text/css" />');
mgAddMeta('<script type="text/javascript" src="' . SCRIPT . 'standard/js/layout.related.js"></script>');
?>

<div class="product-details-block">

    <!--Плагин хлебных крошек-->
    <?php if (class_exists('BreadCrumbs')): ?>
        <div class="breadcrumbs-holder">
            [brcr]
        </div>
    <?php endif; ?>
    <!--/Плагин хлебных крошек-->

    <div class="product-status clearfix" itemscope itemtype="http://schema.org/Product">
        <?php mgGalleryProduct($data); ?>
        <div class="buy-block">
         
            <div class="buy-block-inner">
                <h1 class="page-title" itemprop="name"><?php echo $data['title'] ?></h1>

                <div class="product-bar clearfix">
                    <!-- Плагин рейтинг товаров-->
                    <?php if (class_exists('Rating')): ?>
                        <div class="mg-rating">
                            [rating id = "<?php echo $data['id'] ?>"]
                        </div>
                    <?php endif; ?>
                    <!--/ Плагин рейтинг товаров-->
                    <div class="product-code">
                        Артикул: <span class="label-article code" itemprop="productID"><?php echo $data['code'] ?></span>
                    </div>

                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="available">
                        <?php layout('count_product', $data); ?>
                        <span class="price">
                                <span itemprop="price" content="<?php echo str_replace(' ', '', $data['price'])?>"></span><span itemprop="priceCurrency" content="<?php echo $data['currency']; ?>"></span>
                            </span>
                    </div>
                </div>
              <?php if (class_exists('NonAvailable')): ?>
              	[non-available id="<?php echo $data['id']?>"]
              <?php endif; ?>
                <div>
                    <div class="default-price">
                        <div class="product-price">
                            <ul class="product-status-list">
                                <li <?php echo (!$data['old_price']) ? 'style="display:none"' : 'style="display:block"' ?>>
                                    <div class="old">
                            <span class="old-price">
                                <?php echo MG::numberFormat($data['old_price']) . " " . $data['currency']; ?>
                            </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="normal-price">
                            <span class="price">
                                <span><?php echo $data['price'] ?></span><span ><?php echo $data['currency']; ?></span>
                            </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                   

                    <ul class="product-status-list">
                        <!--если не установлен параметр - старая цена, то не выводим его-->
                        <li <?php echo (!$data['weight']) ? 'style="display:none"' : 'style="display:block"' ?>>Вес:
                            <span class="label-black weight"><?php echo $data['weight'] ?></span> кг.
                        </li>
                    </ul>
                </div>
                <?php if (class_exists('Pluso')): ?>
                    [pluso]
                <?php endif; ?>
                <!--Кнопка, кототорая меняет свое значение с "В корзину" на "Подробнее"-->
                <?php echo $data['propertyForm'] ?>
              	  
            </div>
         		<?php if (class_exists('timerSale')): ?>
              	[timer-sale id = "<?php echo $data['id']?>"]
         		<?php endif; ?>
           		<?php if (class_exists('Pluso')): ?>
                    [pluso]
                <?php endif; ?>
          		        </div>
    

    <div class="product-details-wrapper clearfix">
        <ul class="product-tabs">
            <li><a href="#tab1">Описание</a></li>
            <?php if (class_exists('mgTreelikeComments')): ?>
                <li>
                    <a href="#tree-comments">Комментарии</a>
                </li>
            <?php endif; ?>
            <?php if (class_exists('CommentsToMoguta')): ?>
                <li>
                    <a href="#comments-mg">Комментарии</a>
                </li>
            <?php endif; ?>
            <?php foreach ($data['thisUserFields'] as $key => $value) {
                if ($value['type']=='textarea'&&$value['value']) {?>
                    <li>
                        <a href="#tab<?php echo $key?>">
                            <?php echo $value['name']?>
                        </a>
                    </li>
                <?php   }
            }?>
        </ul>
        <div class="product-tabs-container">
            <div id="tab1" itemprop="description"><?php echo $data['description'] ?></div>
            <?php if(class_exists('mgTreelikeComments')): ?>
                <div id="tree-comments" itemscope itemtype="http://schema.org/Review">
                    <span style="display: none;" itemprop="itemReviewed" content="<?php echo $data['title'] ?>"></span>
                   [mg-treelike-comments type="product"]
                </div>
            <?php endif; ?>            

            <?php if(class_exists('CommentsToMoguta')): ?>
                <div id="comments-mg" itemscope itemtype="http://schema.org/Review">
                    <span style="display: none;" itemprop="itemReviewed" content="<?php echo $data['title'] ?>"></span>
                    [comments]
                </div>
            <?php endif; ?>

            <?php foreach ($data['thisUserFields'] as $key => $value) {
                if ($value['type']=='textarea') {?>
                    <div id="tab<?php echo $key?>">
                        <?php echo preg_replace('/\<br(\s*)?\/?\>/i', "\n", $value['value'])?>
                    </div>
                <?php  }
            }?>
        </div>
    </div>
 
    <?php
    /* Следующая строка для вывода свойств в таблицу характеристик */
    /* $data['stringsProperties'] */
    ?>
    </div>
    <?php echo $data['related'] ?>

    <!-- Плагин недавно просмотренные товары -->
    <?php if(class_exists('RecentlyViewed')): ?>
    [recently-viewed count=5 random=1]
    <?php endif; ?>
    <!--/ Плагин Недавно просмотренные товары -->
    <?php if(class_exists('SetGoods')): ?>
        [set-goods id="<?php echo $data['id']?>"]
    <?php endif; ?>

</div><!-- End product-details-block-->



