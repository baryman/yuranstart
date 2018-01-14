<div class="section-<?php echo $pluginName ?>">
    <div class="widget-table-body">
        <div class="base-setting">
            <ul class="list-option" data-propertyid="<?php echo $settings['propertyId'] ?>">
                <li>
                    <label>
                        <span class="setting" >Выводить облако в 3D:</span>
                        <input type="checkbox" name="view3d" <?php if ($settings['view3d'] == 'true') echo "value = 'true' checked"; else echo "value = 'false'" ?> >
                    </label>
                </li>
                <li>
                    <span class="setting">Выбрать минимальный и максимальный размер шрифта:</span><br>

                        <span>10
                            <input name="font_size_min" type="range" min="10" max="40" step="2" <?php if ($settings['font_size_min']) echo "value=".$settings['font_size_min']; else echo "value='14'"; ?> > 40</span>
                    <span class="example-size-min" style="font-size: <?php if ($settings['font_size_min']) echo $settings['font_size_min']; else echo "14"; ?>px">Минимальный размер = <?php if ($settings['font_size_min']) echo $settings['font_size_min']; else echo "14"; ?>px</span><br>

                    <span>40<input name="font_size_max" type="range" min="40" max="80" step="2" <?php if ($settings['font_size_max']) echo "value=".$settings['font_size_max']; else echo "value='40'"; ?>> 80</span>
                    <span class="example-size-max" style="font-size: <?php if ($settings['font_size_max']) echo $settings['font_size_max']; else echo "40"; ?>px">Максимальный размер = <?php if ($settings['font_size_max']) echo $settings['font_size_max']; else echo "40"; ?>px</span>
                </li>
                <li>
                    <span class="setting">Выбрать цвет тегов:</span>
                    <div class="color-picker">
                        <input id="color" name="color" type="text" value="<?php if ($settings['color']) echo $settings['color']; else echo "#34652F"; ?>" /><br>
                    </div>

                </li>
                <li>
                  <a href="javascript:void(0);" class="base-setting-save custom-btn button success fa fa-save" style="float:left;"><span>Сохранить</span></a>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        
        <div class="clear"></div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
  $('input[name="view3d"]').on('change', function () {
    var newValue = ($("input[name='view3d']").val() == 'true') ? 'false' : 'true';
    $("input[name='view3d']").val(newValue);
  });
  $('input[name="font_size_min"]').on('change', function () {
    var min_size = $("input[name='font_size_min']").val();
    $(".example-size-min").html("<span class='example-size-min' style='font-size: " + min_size + "px'>Минимальный размер = " + min_size + "px</span>");
  });
  $('.list-option').on('change', 'input[name="font_size_max"]', function () {
    var max_size = $("input[name='font_size_max']").val();
    $(".example-size-max").html("<span class='example-size-max' style='font-size: " + max_size + "px'>Максимальный размер = " + max_size + "px</span>");
  });
  $('#color').colorPicker();
});
</script>



