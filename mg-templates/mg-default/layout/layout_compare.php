<?php mgAddMeta('<link type="text/css" href="' . SCRIPT . 'standard/css/compare.css" rel="stylesheet"/>'); ?>
<?php mgAddMeta('<script type="text/javascript" src="' . PATH_SITE_TEMPLATE . '/js/layout.compare.js"></script>'); ?>

<div class="mg-product-to-compare">
    <div class="compare-icon">
        <span class="mg-compare-count" style="<?php echo ($_SESSION['compareCount']) ? 'display:block;' : 'display:none;'; ?>"><?php echo $_SESSION['compareCount']?$_SESSION['compareCount']:0?></span>
    </div>
    <a href="<?php echo SITE ?>/compare" title="Перейти к списку сравнений" class="compare-btn">
        <span>Сравнить</span>
    </a>
</div>
