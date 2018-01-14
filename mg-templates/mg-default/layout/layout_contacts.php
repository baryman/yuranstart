<div class="mg-contacts-block" itemscope itemtype="http://schema.org/Organization">
    <div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <div class="address-item" itemprop="streetAddress">
            <?php echo MG::getSetting('shopAddress') ?>
        </div>
    </div>
<!--    <div class="opening" itemscope itemtype="http://schema.org/Store">-->
<!--        <div class="hours-item" itemprop="openingHours">Работаем с 10 до 19, без выходных</div>-->
<!--    </div>-->
    <div class="phone">
        <div class="phone-item" itemprop="telephone">
            <?php echo MG::getSetting('shopPhone') ?>
        </div>
    </div>
</div>
