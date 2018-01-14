<?php echo $data['htmlProperty']; ?>
<?php echo $data['blockVariants']; ?>

<?php echo $data['addHtml'];?>

<div class="buy-container <?php echo (MG::get('controller') == 'controllers_product') ? 'product' : '' ?>"
    <?php if (MG::get('controller') == 'controllers_product') {
        echo($data['maxCount'] == "0" || !$data['activity'] ? 'style="display:none"' : '');
    } ?> >
    <?php if (!$data['noneAmount']) { ?>
        <div class="property-price">
            <div class="product-price">
                <div class="title">Итого к оплате:</div>

                <ul class="product-status-list">
                    <li>
                        <div class="normal-price">
                        <span class="price">
                            <?php echo $data['price'] ?> <?php echo $data['parentData']['productData']['currency']; ?>
                        </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="hidder-element clearfix amount-holder" <?php echo($data['maxCount'] == "0" ? 'style="display:none"' : '') ?> >
            <div class="cart_form">
                <input type="text" name="amount_input" class="amount_input"
                       data-max-count="<?php echo $data['maxCount'] ?>" value="1"/>
                <div class="amount_change">
                    <a href="#" class="up">+</a>
                    <a href="#" class="down">-</a>
                </div>
            </div>

            <ul class="product-status-list old-price-list" <?php echo (!$data['old_price']) ? 'style="display:none"' : 'style="display:block"' ?>>
                <li>Старая цена</li>
                <li>
                    <div class="old">
                            <span class="old-price">
                                <?php echo MG::numberFormat($data['old_price']) . " " . $data['parentData']['productData']['currency']; ?>
                            </span>
                    </div>
                </li>
            </ul>
        </div>
    <?php } ?>

    <div class="hidder-element" <?php echo($data['noneButton'] ? 'style="display:none"' : '') ?> >
        <input type="hidden" name="inCartProductId" value="<?php echo $data['id'] ?>">

        <?php
        $count = $data['maxCount'];
        if ($count == 0) {
            $model = new Models_Product();
            $variants = $model->getVariants($data['id']);

            if ($variants) {
                $count = 0;
                // вычисляем общее число вариантов
                foreach ($variants as $variant) {
                    $count += $variant['count'];
                }
            }
        }
        ?>

        <div class="buttons-holder clearfix">
            <?php if (!$data['noneButton'] || ($count > 0 || $count < 0)) { ?>
                <?php if ($data['ajax']) {
                    if ($data['buyButton']) {
                        ?>
                        <?php echo $data['buyButton']; ?>
                    <?php } else { ?>

                        <a class="<?php echo $data['classForButton'] ?>"
                           href="<?php echo SITE . '/catalog?inCartProductId=' . $data['id'] ?>"
                           data-item-id="<?php echo $data['id'] ?>">

                            <?php echo $data['titleBtn']; ?>

                        </a>

                        <input type="submit" name="buyWithProp" onclick="return false;" style="display:none">
                        <?php
                    }
                } else {
                    ?>

                    <input type="submit" name="buyWithProp">

                <?php } ?>
                <?php if ($data['printCompareButton'] == 'true') { ?>

                    <a href="<?php echo SITE . '/compare?inCompareProductId=' . $data['id'] ?>"
                       data-item-id="<?php echo $data['id'] ?>" class="addToCompare">
                        <?php echo MG::getSetting('buttonCompareName'); ?>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>

        <!-- Плагин купить одним кликом-->
        <?php if (class_exists('BuyClick')): ?>
            [buy-click id="<?php echo $data['id'] ?>"]
        <?php endif; ?>
        <!--/ Плагин купить одним кликом-->
    </div>
    <?php if (class_exists('RecentlyViewed')): ?>
      [recently-viewed count=5 random=1]
    <?php endif; ?>
</div>