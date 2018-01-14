<div class="row" style="overflow:auto;min-width:700px;">
  <div class="large-12 columns">
    <h4>Шаблон сайта</h4>
    <ul class="template-tabs-menu">
      <li class="is-active template-tabs button primary"><a href="javascript:void(0)" data-target="#ttab1">Шаблоны страниц</a></li>
      <li class="template-tabs button primary"><a href="javascript:void(0)" data-target="#ttab3">Шаблон блоков</a></li>
      <li class="template-tabs button primary"><a href="javascript:void(0)" data-target="#ttab2">Шаблоны писем</a></li>
      <li class="template-tabs button primary"><a href="javascript:void(0)" data-target="#ttab4">Шаблон печати</a></li>
      <li class="browseImage button primary"><a href="javascript:void(0);">Графика</a></li>
    </ul>
    <div class="tabs-content template-tabs-content">
      <div class="tabs-panel is-active" id="ttab1" style="display:block">
        <?php foreach($data['setting-template']['files'] as $filename=>$path):?>
            <?php if(file_exists($pathTemplate.'/'.$path[0])):?>
                <a href="javascript:void(0);" class="file-template tab-email-views tool-tip-bottom" title="<?php echo $path[1];?>" data-path="<?php echo $path[0]?>"><?php echo $filename?></a>
            <?php endif;?>
        <?php endforeach;?>
      </div>
      <div class="tabs-panel" id="ttab2">
        <?php foreach($data['setting-template']['email_layout'] as $filename=>$path):?>
            <?php if(file_exists($pathTemplate.'/'.$path[0])):?>
                <a href="javascript:void(0);" class="file-template tab-email-layout tool-tip-bottom" title="<?php echo $path[1];?>" data-path="<?php echo $path[0]?>"><?php echo $filename?></a>
            <?php endif;?>
        <?php endforeach;?>
      </div>
      <div class="tabs-panel" id="ttab3">
        <?php foreach($data['setting-template']['layout'] as $filename=>$path):?>
            <?php if(file_exists($pathTemplate.'/'.$path[0])):?>
                <a href="javascript:void(0);" class="file-template tab-block-layout tool-tip-bottom" title="<?php echo $path[1];?>" data-path="<?php echo $path[0]?>"><?php echo $filename?></a>
            <?php endif;?>
        <?php endforeach;?>
      </div>
      <div class="tabs-panel" id="ttab4">
        <?php foreach($data['setting-template']['print_layout'] as $filename=>$path):?>
            <?php if(file_exists($pathTemplate.'/'.$path[0])):?>
                <a href="javascript:void(0);" class="file-template tab-print-layout tool-tip-bottom" title="<?php echo $path[1];?>" data-path="<?php echo $path[0]?>"><?php echo $filename?></a>
            <?php endif;?>
        <?php endforeach;?>
      </div>
      <div class="tabs-panel" id="ttab5">Графика</div>
    </div>

    <textarea id="codefile" style="width:100%; height:0;"></textarea>
    <div class="error-not-tpl" style="display:none"><?php echo $lang['NOT_FILE_TPL'] ?></div>
  </div>
</div>
<div class="widget-footer text-right">
  <button class="save-file-template button success"><span><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo $lang['SAVE'] ?></span></button>
</div>