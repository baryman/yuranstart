<h4><?php echo $lang['STNG_MAIN_SITE'];?></h4>

<?php
$propertyHtml = '';

foreach($groups as $key=>$group) {
    $propertyHtml .= "<li class='accordion-item'>
    <a class='accordion-title' href='javascript:void(0);'>".$lang[$key]."</a>
    <div class='accordion-content inline-label' data-tab-content=''><ul>";

    if($key == 'STNG_GROUP_7') {
      $propertyHtml .=  '<a href = "javascript:void(0);" class="clear-cache button primary" style="margin-right:10px;"><span>Очистить кэш</span></a>';
      $propertyHtml .=  '<a href = "javascript:void(0);" class="memcache-conection button primary"><span>Проверить соединение для MEMCACHE</span></a>';
    }    

    if($key == 'STNG_GROUP_4') {
      $propertyHtml .= '
                      <div class="row">
                        <div class="large-12 columns">
                          <div class="alert-block warning">
                            Внимание! Указанные параметры из данного раздела настроек будут применяться только для новых загруженных изображений.
                            Все изображения, которые уже есть на сайте, останутся без изменений.
                             Если вы хотите изменить только внешнее представление изображений, а не их фактический размер, вам необходимо изменить css стили шаблона.
                              Водяной знак тоже будет изменен только для новых загруженных картинок.
                          </div>
                        </div>
                      </div>';          
    }   
    
    foreach($group as $optionName) {
        $input = '';
        $option = $data['setting-shop']['options'][$optionName];
        $alias = $option['name'];
        $numericProtection=""; if (in_array($alias,$data['numericFields'])) {$numericProtection = "numericProtection";}
        if (in_array($option['option'],$data['smtpSettings'])) {$numericProtection = " smtpSettings";}

        if (in_array($option['option'],$data['checkFields'])) {
            $checked = ('true' == $option['value'])?"checked='checked'":"";
            $checkUp = "";
            if($option['option']=="waterMark") {
                if(in_array($option['option'], array("waterMark"))) {
                    $checkUp = "check-up";
                }
            }
            
            $input = '<div class="checkbox margin">
                        <input id="t'.$option['option'].'" type="checkbox" '.$checked.' name="'.$option['option'].'" class="option '.$checkUp.' '.$numericProtection.'">
                        <label for="t'.$option['option'].'"></label>
                      </div>';
        }

        if (in_array($option['option'],$data['textFields'])) {
            $input = '<textarea name="'.$option['option'].'" class="settings-input option">'.$option['value'].'</textarea>';
        }

        if($option['option'] == 'templateName') {

                $style='';
                if($option['value']!="default") {
                  $style="display:none;";
                }

               $input = '<div class="wrapp-templ"><div class="install-templ"><span>'.$lang['SETTING_BASE_1'].':</span><br/><span class="default-info" style="'.$style.'">Все изменения шаблона defaul будут отменены при обновлении версии системы,<br/>чтобы избежать этого, пожалуйста, используйте другоЙ шаблон!</span></div>
                  <select class="option last-items-dropdown medium" name="'.$option['option'].'" style="margin-top:5px;" >';
            foreach($data['setting-shop']['templates'] as $template) {
                     $input .=  '<option data-schemes=\''.json_encode($template['colorScheme']).'\' value="'.$template['foldername'].'" ';
                       if($template['foldername'] == $option['value']) {
                         $input .=  "selected";

                         // для выбранного строим перечень доступных схем
                         foreach($template['colorScheme']  as $scheme) {
                           $active = '';
                           if($scheme==$template['colorSchemeActive']) {
                             $active = 'active';
                           }
                           $schemeHtml .= '<li class="color-scheme '.$active.'" data-scheme="'.$scheme.'" style="background:#'.$scheme.';"></li>';
                         }

                       }
                     $input .=  '  > '. $template['foldername'].'
                     </option>';
                   }
            $input .= '</select>';

                  if(empty($schemeHtml)) {
                    $style = 'style="display:none"';
                  }

                  $input .= '<div class="template-schemes" '.$style.'><span>'.$lang['SETTING_BASE_14'].':</span><ul class="color-list">'.$schemeHtml.'</ul></div>';
            $input .= '<br><div class="clearfix"></div><div>'.$lang['SETTING_BASE_2'].' <i class="fa fa-question-circle tip" aria-hidden="true" data-hasqtip="1" title="Шаблон определяет внешний вид сайта. Название шаблона определяется названием папки, в которой он находится." style="cursor:pointer;"></i>
                        <div class="upload-form">
                          <form class="newTemplateForm" id="newTemplateForm" method="post" noengine="true" enctype="multipart/form-data">
                            <div class="move-form" data-target="newTemplateForm">
                              <label class="button tip" style="border:0;" for="addTempl" data-hasqtip="2" title="" aria-describedby="qtip-2"><i class="fa fa-picture-o" aria-hidden="true"></i> '.$lang['SETTING_BASE_3'].'</label>
                              <input type="file" id="addTempl" name="addTempl">
                            </div>
                          </form>
                        </div>
                      </div>';

        }

        if($option['option'] == 'priceFormat') {
            $input = '
                  <select class="option last-items-dropdown" name="'.$option['option'].'" style="margin-top:5px;" >';
            foreach(array(
                        '1234.56'=>'1234.56 - без форматирования',
                        '1 234,56'=>'1 234,56 - разделять тысячи пробелами, а копейки запятыми',
                        '1,234.56'=>'1,234.56 - разделять тысячи запятыми, а копейки точками',
                        '1234'=>'1234 - без копеек, без форматирования',
                        '1 234'=>'1 234 - без копеек, разделять тысячи пробелами',
                        '1,234'=> '1,234 - без копеек, разделять тысячи запятыми'
                    ) as $key =>$item) {
                $input .=  '<option value="'.$key.'" ';
                if($key == $option['value']) {
                    $input .=  "selected";
                }
                $input .=  '  > '. $item.'
                     </option>';
            }
            $input .= '</select>';

        }


        if($option['option'] == 'cacheMode') {
            $input = '
                  <select class="option last-items-dropdown medium" name="'.$option['option'].'" style="margin-top:5px;" >';
            foreach(array('DB','MEMCACHE') as $item) {
                $input .=  '<option value="'.$item.'" ';
                if($item == $option['value']) {
                    $input .=  "selected";
                }
                $input .=  '  > '. $item.'
                     </option>';
            }
            $input .= '</select>';

        }

        if($option['option'] == 'currencyShopIso') {
            $input = '
                  <select class="option last-items-dropdown medium" name="'.$option['option'].'" style="margin-top:5px;" >';
            $currencyShopIso = MG::getSetting('currencyShopIso');
            foreach(MG::getSetting('currencyShort') as $iso => $short) {
                $input .=  '<option value="'.$iso.'" ';
                if($currencyShopIso == $iso) {
                    $input .=  "selected";
                }
                $input .=  '  > '. $short.'
                     </option>';
            }
            $input .= '</select>';


        }

        if($option['option']=="waterMark") {
            $input .= '
                  <div class="upload-img-block">
                    <div class="uploaded-img watermark-img">
                      <img src="'.SITE.'/uploads/watermark/watermark.png">
                    </div>
                    <div class="upload-form">
                      <form class="watermarkform text-left" method="post" noengine="true" enctype="multipart/form-data" id="form-1">
                        <div class="move-form" data-target="form-1">
                          <label class="button tip" for="upload-watermark" data-hasqtip="54" oldtitle="'.$lang['SETTING_LOCALE_27'].'" title="" aria-describedby="qtip-54" style="border:0;"><i class="fa fa-picture-o" aria-hidden="true"></i> '.$lang['SETTING_LOCALE_27'].'</label>
                          <input type="file" name="photoimg" id="upload-watermark">
                        </div>
                      </form>
                    </div>
                  </div>';

        }

        if($option['option']=="shopLogo") {
            if(empty($option['value'])) {
              $displaynone=" display:none";
            }
            $displayImg = '';
            if(SITE.$option['value'] == SITE) {
              $displayImg=" display:none";
            }
            $input .= '
                <div class="upload-img-block" >
                  <div class="uploaded-img logo-img" style="'.$displayImg.'">
                    <img  style="max-width:200px; '.$displaynone.' "  src="'.SITE.$option['value'].' ">
                    <a class="fa fa-trash tip remove-added-logo" href="javascript:void(0);" title="Удалить логотип" style="'.$displaynone.'"></a>
                  </div>
                  <input type="hidden" name="'.$option['option'].'" class="settings-input option" value="'.$option['value'].'">
                </div>
                <div>
                <a href="javascript:void(0);" class="button add-logo browseImageLogo">
                  <span><i class="fa fa-picture-o" aria-hidden="true"></i> '.$lang['SETTING_LOCALE_30'].'</span>
                </a></div>';
               
        }
        if($option['option']=="backgroundSite") {
          $displaynone ='';
          $displayImg = '';
            if(empty($option['value'])) {
              $displaynone=" display:none";
            }
            if(SITE.$option['value'] == SITE) {
              $displayImg=" display:none";
            }
            $input .= '
                 <div class="upload-img-block" >
                     <div class="uploaded-img background-img" style="'.$displayImg.'">
                       <img  style="max-width:200px; '.$displaynone.' "  src="'.SITE.$option['value'].' ">
                         <a class="remove-added-background fa fa-trash tip" style="'.$displaynone.'" href="javascript:void(0);"><span></span></a>
                     </div>
                      <input type="hidden" name="'.$option['option'].'" class="settings-input option" value="'.$option['value'].'">
                     
                </div>
                <div>
                <a href="javascript:void(0);" class="button add-background browseBackgroundSite">
                  <span><i class="fa fa-picture-o" aria-hidden="true"></i> '.$lang['SETTING_LOCALE_31'].'</span>
                </a></div>';
        }
          if($option['option']=="favicon") {
            $input .= '
                  <div class="upload-img-block">
                    <div class="uploaded-img">
                      <img id="favicon-image" src="'.SITE.'/'.($option['value'] == '' ? 'favicon.ico' : $option['value']).'?t='.time().'"></div>
                    <div class="upload-form">
                      <form class="imageform" method="post" noengine="true" enctype="multipart/form-data" id="form-2">
                        <div class="move-form" data-target="form-2">
                          <label class="button tip" for="upload-favicon" data-hasqtip="64" oldtitle="'.$lang['CHANGE_FAVICON'].'" title="" aria-describedby="qtip-64" style="border:0;"><i class="fa fa-picture-o" aria-hidden="true"></i> '.$lang['CHANGE_FAVICON'].'</label>
                          <input type="file" id="upload-favicon" name="favicon" class="add-img tool-tip-top" title="'.$lang['CHANGE_FAVICON'].'">
                        </div>
                      </form>
                      <input type="hidden"  name="'.$option['option'].'" class="settings-input option" value="">
                    </div>
                  </div>';

        }
          if($option['option'] == 'filterSort') {
            $input = '
                  <select class="option last-items-dropdown medium" name="'.$option['option'].'" style="margin-top:5px;" >';
            $compareArray = array(
              "sort|asc" => 'по порядку',
              "price_course|asc" => 'по цене, сначала недорогие',
              "price_course|desc" => 'по цене, сначала дорогие',
              "id|desc" => 'по новизне',
              "count_buy|desc" => 'по популярности',
              "recommend|desc" => 'сначала рекомендуемые',
              "new|desc" => 'сначала новинки',
              "old_price|desc" => 'сначала распродажа',
              "count|desc" => 'по наличию',
              "count|asc" => 'возрастанию количества',
              "title|asc" => 'наименованию А-Я',
              "title|desc" => 'наименованию Я-А'
            );
          foreach($compareArray as $key => $item) {
                $input .=  '<option value="'.$key.'" ';
                if($key == $option['value']) {
                    $input .=  "selected";
                }
                $input .=  '  > '. $item.'
                     </option>';
            }
            $input .= '</select>';

        }
        if($option['option'] == 'filterSortVariant') {
            $input = '
                  <select class="option last-items-dropdown medium" name="'.$option['option'].'" style="margin-top:5px;" >';
            $compareArray = array(
              "sort|asc" => 'по порядку',
              "price_course|asc" => 'по цене, сначала недорогие',
              "price_course|desc" => 'по цене, сначала дорогие',
              "id|desc" => 'по новизне',
              "count|desc" => 'по наличию'
            );
          foreach($compareArray as $key => $item) {
                $input .=  '<option value="'.$key.'" ';
                if($key == $option['value']) {
                    $input .=  "selected";
                }
                $input .=  '  > '. $item.'
                     </option>';
            }
            $input .= '</select>';
        }

        if($option['option'] == 'imageResizeType') {
          $input = '
            <select class="option last-items-dropdown medium" 
                  name="'.$option['option'].'" style="margin-top:5px;">
              <option value="PROPORTIONAL" '.($option['value']=='PROPORTIONAL'?'selected="selected"':'').'>Пропорционально</option>
              <option value="EXACT" '.($option['value']=='EXACT'?'selected="selected"':'').'>Точно</option>
            </select>';
        }

        if($option['option'] == 'imageSaveQuality') {
          $input = '<input type="number"  name="'.$option['option'].'"
              class="small settings-input option'.$numericProtection.'"
              value="'.$option['value'].'" min="0" max="100">';
        }

        if(empty($input)) {
            $type = "text";
            $condition= '';
            if($option['option']=="smtpPass") {
                $type = "password";
                $option['value'] = CRYPT::mgDecrypt($option['value']);
            }
            $unit = '';
            if (in_array($option['option'],  array('categoryImgHeight','categoryImgWidth','heightPreview','widthPreview','heightSmallPreview','widthSmallPreview',
                                                      'countСatalogProduct', 'countNewProduct', 'countRecomProduct','countSaleProduct','sessionLifeTime'))) {
              if (stristr($option['option'], 'height')!==FALSE || stristr($option['option'], 'width')!==FALSE) {$unit = " px";}
              $condition = 'min="0"';
              $type = "number";
              
              if ($option['option'] == "sessionLifeTime") {
                $unit = $lang['TIME_SECOND'];
                $condition = 'min="1440"';
              }
            }

            // для отрисовки полей с нужными тегами и версткой
            switch ($type) {
              case 'number':
                $input = '<div class="input-with-text">
                            <input type="'.$type.'" name="'.$option['option'].'" class="small settings-input option'.$numericProtection.'" value="'.str_replace('"','&quot;',$option['value']).'" '.$condition.'>'.$unit.'
                          </div>';                            
                break;
              
              default:
                $input = '<input type="'.$type.'" name="'.$option['option'].'" class="settings-input option'.$numericProtection.'" value="'.str_replace('"','&quot;',$option['value']).'" '.$condition.'>'.$unit;
                break;
            }
        }

        $textUp = "";
        if(in_array($option['option'], array("waterMark","widgetCode","templateName"))) {
            $textUp = "text-up";
            if($option['option']=="waterMark") {
                $textUp = 'watermark-text';
            }
        }

        if($key == 'STNG_GROUP_2') {
          $columnsWidthParent = 'large-12';
          $columnsWidth = 'small-10 medium-5';
          $columnsWidthInput = 'small-2 medium-7';
        } else {
          $columnsWidthParent = 'large-8';
          $columnsWidth = 'small-7 medium-5';
          $columnsWidthInput = 'small-5 medium-7';
        }

        $propertyHtml .= '
               <div class="row">
                <div class="'.$columnsWidthParent.' columns">
                  <div class="row">
                    <div class="'.$columnsWidth.' columns '.$textUp.'">
                      <label class="middle with-help">'.$lang[$alias].'<i class="fa fa-question-circle tip" aria-hidden="true" title="'.$lang['DESC_'.$option['name']].'"></i></label>
                    </div>
                    <div class="'.$columnsWidthInput.' columns">
                      '.$input.'
                    </div>
                  </div>
                </div>
              </div>';
        
    }
    if($key == 'STNG_GROUP_5') {
          $propertyHtml .= '<a class="button email-conection" href="javascript:void(0);"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> '.$lang['CHECK_EMAIL_SEND'].'</a>';
        }
    $propertyHtml .=  "</ul></div></div></div></li>";
}
?>

<table class="main-settings-list">
    <tr id="data">
        <ul class="accordion" data-accordion data-multi-expand="false" data-allow-all-closed="true"><?php echo $propertyHtml;?></ul>
    </tr>
</table>
<br>
<div class=row>
  <div class="text-right">
    <button class="save-button save-settings button success" style="margin-right:10px;"><span><?php echo $lang['SAVE'] ?></span></button>
  </div>
</div>

<script type="text/javascript">
  setTimeout(function() {$('.accordion-item').show()},1);
</script>