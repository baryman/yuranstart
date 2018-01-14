<?php mgAddMeta('<link href="'.SCRIPT.'standard/css/layout.related.css" rel="stylesheet" type="text/css" />'); ?>
<?php mgAddMeta('<script type="text/javascript" src="'.SCRIPT.'jquery.bxslider.min.js"></script>'); ?>

<div class="mg-recent-products">
    <div class="title"><span><?php echo $data['title'] ?></span></div>
    <div class="m-p-products-slider">
        <div class="m-p-products-slider-start">
            <?php foreach ($data['products'] as $item):?>
                <div class="product-wrapper" itemscope itemtype="http://schema.org/Product">
                    <a href="<?php echo $item["url"] ?>" class="product-image">
                        <div class="product-stickers">
                            <?php
                            echo $item['recommend'] ? '<span class="sticker-recommend">Хит!</span>' : '';
                            echo $item['new'] ? '<span class="sticker-new">Новинка</span>' : '';
                            ?>
                        </div>
                        <?php
                        $item['image_url'] = $item['img'];
                        echo mgImageProduct($item);
                        ?>
                    </a>
                    <div class="product-details">
                        <a href="<?php echo $item["url"] ?>" class="product-name" itemprop="name" content="<?php echo $item["title"] ?>" title="<?php echo $item["title"] ?>">
                            <?php echo MG::textMore($item["title"], 18) ?>
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

                            <div class="product-buttons related-buttons">
                                <div class="double-buttons">
                                    <!-- Плагин купить одним кликом-->
                                    <?php if (class_exists('BuyClick')): ?>
                                        [buy-click id="<?php echo $item['id'] ?>"]
                                    <?php endif; ?>
                                    <!--/ Плагин купить одним кликом-->

                                    <a class="default-btn buy-product" href="<?php echo SITE ?>/catalog?inCartProductId=<?php echo $item['id']; ?>" data-item-id="<?php echo $item['id']; ?>">
                                        Добавить
                                    </a>
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
    </div>
</div>