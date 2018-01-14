<script type="text/javascript">
    includeJS('<?php echo SITE ?>/<?php echo $path ?>/js/script.js');
</script>
<link rel="stylesheet" href="<?php echo SITE ?>/<?php echo $path ?>/css/style.css" type="text/css" />

<div class="section-deliveryсalc plugin-padding" style="min-height:120px;">
    <div class="widget-table-body"><!-- Содержимое окна, управляющие элементы -->

        <span id="select-caption">Выберите свой регион для отправки: &nbsp;</span>
        <div class="select-delivery-from">
            <select style="width:375px;">
<!--                <option>Выберите свой регион</option>-->
                <?php echo $cityListHtml?>
            </select>
            <div>
            <button class="tool-tip-bottom base-setting-save save-button custom-btn button success" data-id="" title="<?php echo $lang['SAVE'] ?>"><!-- Кнопка действия -->
                <i class="fa fa-floppy-o"><span><?php echo $lang['SAVE'] ?></span></i>
        </button>
            </div>
        </div>
        

    </div>
    
</div>
