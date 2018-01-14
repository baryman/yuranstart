<?php mgAddMeta('<link type="text/css" href="' . SCRIPT . 'standard/css/layout.cart.css" rel="stylesheet"/>'); ?>
<?php mgAddMeta('<script type="text/javascript" src="' . PATH_SITE_TEMPLATE . '/js/layout.cart.js"></script>'); ?>

<?php if (MG::getOption('popupCart') == 'true') { ?>
    <?php mgAddMeta('<link type="text/css" href="' . SCRIPT . 'standard/css/layout.fake.cart.css" rel="stylesheet"/>'); ?>
    <div class="mg-layer" style="display: none"></div>
    <div class="mg-fake-cart" style="display: none;">
        <a class="mg-close-fake-cart mg-close-popup" href="javascript:void(0);"></a>
        <div class="popup-header">
            <div class="title">Корзина товаров</div>
        </div>
        <div class="popup-body">
            <table class="small-cart-table">

                <?php if (!empty($data['cartData']['dataCart'])) { ?>

                    <?php foreach ($data['cartData']['dataCart'] as $item): ?>
                        <tr>
                            <td class="small-cart-img">
                                <a href="<?php echo SITE . "/" . (isset($item['category_url']) ? $item['category_url'] : 'catalog/') . $item['product_url'] ?>">
                                    <img src="<?php echo $item["image_url_new"] ?>" alt="<?php echo $item['title'] ?>"/>
                                </a>
                            </td>
                            <td class="small-cart-name">
                                <ul class="small-cart-list">
                                    <li>
                                        <a href="<?php echo SITE . "/" . (isset($item['category_url']) ? $item['category_url'] : 'catalog/') . $item['product_url'] ?>"><?php echo $item['title'] ?></a>
                                        <span class="property"><?php echo $item['property_html'] ?> </span>
                                    </li>
                                    <li class="qty">
                                        x<?php echo $item['countInCart'] ?>
                                        <span><?php echo $item['priceInCart'] ?></span>
                                    </li>
                                </ul>
                            </td>
                            <td class="small-cart-remove">
                                <a href="#" class="deleteItemFromCart" title="Удалить"
                                   data-delete-item-id="<?php echo $item['id'] ?>"
                                   data-property="<?php echo $item['property'] ?>"
                                   data-variant="<?php echo $item['variantId'] ?>">&#215;</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php } else { ?>

                <?php } ?>
            </table>
        </div>
        <div class="popup-footer clearfix">
            <div class="footer-left">
                <ul class="total sum-list">
                    <li class="total-sum">
                        <div class="text">Всего к оплате:</div>
                        <span class="total-payment">
                            <?php echo $data['cartData']['cart_price_wc'] ?>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="footer-right">
                <ul class="total">
                    <li class="checkout-buttons">
                        <a href="javascript:void(0);" class="mg-close-popup">Продолжить покупки</a>
                        <a href="<?php echo SITE ?>/order" class="default-btn success">Оформить заказ</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<?php }; ?>


<div class="mg-desktop-cart">
    <div class="cart">
        <div class="cart-inner">
            <a href="<?php echo SITE ?>/cart">
                <span class="small-cart-icon"></span>
                <div class="title">Корзина</div>
                <ul class="cart-list">
                    <li>
                        <span class="count-text">Товаров</span> <span class="countsht"><?php echo $data['cartCount'] ? $data['cartCount'] : 0 ?></span> шт.
                    </li>
                    <li class="cart-qty">
                        <span class="price-text">на сумму:</span>
                        <span class="pricesht"><?php echo $data['cartPrice'] ? $data['cartPrice'] : 0 ?></span> <?php echo $data['currency']; ?>
                    </li>
                </ul>
            </a>
            <a href="<?php echo SITE ?>/order" class="default-btn success order-btn">Оформить заказ</a>
        </div>
        <div class="small-cart">
            <div class="title">Товары в корзине</div>
            <table class="small-cart-table">

                <?php if (!empty($data['cartData']['dataCart'])) { ?>

                    <?php foreach ($data['cartData']['dataCart'] as $item): ?>
                        <tr>
                            <td class="small-cart-img">
                                <a href="<?php echo SITE . "/" . (isset($item['category_url']) ? $item['category_url'] : 'catalog/') . $item['product_url'] ?>">
                                    <img src="<?php echo $item["image_url_new"] ?>" alt="<?php echo $item['title'] ?>"/>
                                </a>
                            </td>
                            <td class="small-cart-name">
                                <ul class="small-cart-list">
                                    <li>
                                        <a href="<?php echo SITE . "/" . (isset($item['category_url']) ? $item['category_url'] : 'catalog/') . $item['product_url'] ?>"><?php echo $item['title'] ?></a>
                                        <span class="property"><?php echo $item['property_html'] ?> </span>
                                    </li>
                                    <li class="qty">
                                        x<?php echo $item['countInCart'] ?>
                                        <span><?php echo $item['priceInCart'] ?></span>
                                    </li>
                                </ul>
                            </td>
                            <td class="small-cart-remove">
                                <a href="#" class="deleteItemFromCart" title="Удалить"
                                   data-delete-item-id="<?php echo $item['id'] ?>"
                                   data-property="<?php echo $item['property'] ?>"
                                   data-variant="<?php echo $item['variantId'] ?>">&#215;</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php } else { ?>

                <?php } ?>
            </table>
            <ul class="total">
                <li class="total-sum">Всего к оплате:
                    <span><?php echo $data['cartData']['cart_price_wc'] ?></span>
                </li>
                <li class="checkout-buttons">
                    <a href="<?php echo SITE ?>/order" class="default-btn success">Оформить заказ</a>
                    <a href="<?php echo SITE ?>/cart" class="default-btn">Корзина</a>
                </li>
            </ul>
        </div>
    </div>
</div>
