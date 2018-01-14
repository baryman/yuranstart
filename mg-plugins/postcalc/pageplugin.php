<?php mgAddMeta('<link href='.SITE.'/'.self::$path.'/css/style.css rel="stylesheet" type="text/css">');?>

<div class="section-<?php echo $pluginName?>">

<div class="widget-table-body">
    <div class="wrapper-slider-setting">
        <div class="widget-table-action base-settings">
            <h3>Настройки отправлений</h3><br>

            <ul class="list-option"><!-- список опций из таблицы setting-->
              <li>
                <span>Почтовый индекс магазина:</span> 
                <input type="text" name="indexFrom" id="indexFrom" value="<?php echo $options['indexFrom']; ?>">
              </li>
              <p id="postcalcIndexError" style="display: none;">Индекс отправителя может быть только 6-значным числом</p>
              <li>
                <span>Адрес сайта:</span>
                <input type="text" name="site" value="<?php echo $options['site']; ?>">
              </li>              
              <li>
                <span>Email администратора сайта:</span>
                <input type="text" name="mail" value="<?php echo $options['mail']; ?>">
              </li>   
            </ul>

            <?php if($options['ПростоеПисьмо']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Простое письмо":</span> <input type="checkbox" name="ПростоеПисьмо" <?php echo $checkbox?>><br><br>

            <?php if($options['ЗаказноеПисьмо']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Заказное письмо":</span> <input type="checkbox" name="ЗаказноеПисьмо" <?php echo $checkbox?>><br><br>

            <?php if($options['ЦенноеПисьмо']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Ценное письмо":</span> <input type="checkbox" name="ЦенноеПисьмо" <?php echo $checkbox?>><br><br>

            <?php if($options['ПростоеПисьмо1Класс']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Простое письмо 1 класс":</span> <input type="checkbox" name="ПростоеПисьмо1Класс" <?php echo $checkbox?>><br><br>

            <?php if($options['ЗаказноеПисьмо1Класс']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Заказное письмо 1 класс":</span> <input type="checkbox" name="ЗаказноеПисьмо1Класс" <?php echo $checkbox?>><br><br>

            <?php if($options['ЦенноеПисьмо1Класс']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Ценное письмо 1 класс":</span> <input type="checkbox" name="ЦенноеПисьмо1Класс" <?php echo $checkbox?>><br><br>

            <?php if($options['ПростойМультиконверт']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Простой мультиконверт":</span> <input type="checkbox" name="ПростойМультиконверт" <?php echo $checkbox?>><br><br>

            <?php if($options['ЗаказнойМультиконверт']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Заказной мультиконверт":</span> <input type="checkbox" name="ЗаказнойМультиконверт" <?php echo $checkbox?>><br><br>

            <?php if($options['ПростаяБандероль']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Простая бандероль":</span> <input type="checkbox" name="ПростаяБандероль" <?php echo $checkbox?>><br><br>

            <?php if($options['ЗаказнаяБандероль']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Заказная бандероль":</span> <input type="checkbox" name="ЗаказнаяБандероль" <?php echo $checkbox?>><br><br>

            <?php if($options['ЦеннаяБандероль']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Ценная бандероль":</span> <input type="checkbox" name="ЦеннаяБандероль" <?php echo $checkbox?>><br><br>

            <?php if($options['ЦеннаяПосылка']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Ценная посылка":</span> <input type="checkbox" name="ЦеннаяПосылка" <?php echo $checkbox?>><br><br>

            <?php if($options['ЗаказнаяБандероль1Класс']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Заказная бандероль 1 класс":</span> <input type="checkbox" name="ЗаказнаяБандероль1Класс" <?php echo $checkbox?>><br><br>

            <?php if($options['ЦеннаяБандероль1Класс']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Ценная бандероль 1 класс":</span> <input type="checkbox" name="ЦеннаяБандероль1Класс" <?php echo $checkbox?>><br><br>

            <?php if($options['EMS']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Курьерская доставка EMS":</span> <input type="checkbox" name="EMS" <?php echo $checkbox?>><br><br>

            <?php if($options['КурьерОнлайн']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Посылка Онлайн (оптовые клиенты)  ":</span> <input type="checkbox" name="КурьерОнлайн" <?php echo $checkbox?>><br><br>

            <?php if($options['ПосылкаОнлайн']=="true"){ $checkbox = " value='true' checked=checked ";} else{ $checkbox = " value='false' "; }?>
            <span class="custom-text">Выводить "Курьер Онлайн (оптовые клиенты)":</span> <input type="checkbox" name="ПосылкаОнлайн" <?php echo $checkbox?>><br><br>



  


            <button class="tool-tip-bottom base-setting-save save-button custom-btn button success" data-id="" title="<?php echo $lang['SAVE_MODAL'] ?>"><!-- Кнопка действия -->
                <span><i class="fa fa-floppy-o"></i> <?php echo $lang['SAVE_MODAL'] ?></span>
            </button>
            <div class="clear"></div>
        </div>
    </div>
</div>

<script>
admin.sortable('.entity-table-tbody','postcalc');
</script>