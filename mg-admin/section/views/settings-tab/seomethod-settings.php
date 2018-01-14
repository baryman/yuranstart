<!-- modal short-url -->
<div class="reveal-overlay" style="display:none;">
  <div class="reveal xssmall" id="add-short-link-modal" style="display:block;">
    <button class="close-button closeModal" type="button"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
    <div class="reveal-header">
      <h2><i class="fa fa-pencil" aria-hidden="true"></i> <?php echo $lang['STNG_SEO_URL_REWRITE_MODAL_TITLE']?></h2>
    </div>
    <div class="reveal-body">
      <div class="row collapse">
        <div class="large-9 columns">
          <div class="row">
            <div class="small-12 medium-6 columns">
              <label class="middle"><?php echo $lang['STNG_SEO_URL_REWRITE_NAME']; ?>:</label>
            </div>
            <div class="small-12 medium-6 columns">
              <input type="text" name="titeCategory" title="<?php echo $lang['T_TIP_STNG_SEO_URL_REWRITE_NAME']; ?>">
            </div>
          </div>
          <div class="errorField"><?php echo $lang['ERROR_SPEC_SYMBOL']; ?></div>
          <div class="row">
            <div class="small-12 medium-6 columns">
              <label class="middle"><?php echo $lang['STNG_SEO_URL_REWRITE_URL']; ?>:</label>
            </div>
            <div class="small-12 medium-6 columns">
              <input type="text" name="url" title="<?php echo $lang['T_TIP_STNG_SEO_URL_REWRITE_URL']; ?>">
            </div>
          </div>
          <div class="errorField"><?php echo $lang['ERROR_EMPTY']; ?></div>
          <div class="row">
            <div class="small-12 medium-6 columns">
              <label class="middle"><?php echo $lang['STNG_SEO_URL_REWRITE_SHORT_URL']; ?>:</label>
            </div>
            <div class="small-12 medium-6 columns">
              <input type="text" name="short_url" title="<?php echo $lang['T_TIP_STNG_SEO_URL_REWRITE_SHORT_URL']; ?>">
            </div>
          </div>
        </div>
      </div>
      <ul class="accordion">
        <li class="accordion-item" data-accordion-item=""><a class="accordion-title" href="javascript:void(0);"><?php echo $lang['STNG_SEO_URL_REWRITE_DESC']; ?></a>
          <div class="accordion-content" data-tab-content="" style="padding: 0px; display: block;">
            <textarea name="cat_desc"></textarea>
          </div>
        </li>
        <li class="accordion-item" data-accordion-item=""><a class="accordion-title" href="javascript:void(0);"><?php echo $lang['STNG_SEO_URL_REWRITE_DESC_SEO']; ?></a>
          <div class="accordion-content" data-tab-content="" style="padding: 0px; display: block;">
            <textarea name="seo_content"></textarea>
          </div>
        </li>
        <li class="accordion-item" data-accordion-item=""><a class="accordion-title" href="javascript:void(0);">Блок для SEO</a>
          <div class="accordion-content" data-tab-content="">
            <div class="row">
              <div class="small-12 medium-3 columns">
                <label class="middle"><?php echo $lang['META_TITLE']; ?>:</label>
              </div>
              <div class="small-12 medium-9 columns">
                <input type="text" name="meta_title" title="<?php echo $lang['T_TIP_META_TITLE']; ?>">
              </div>
            </div>
            <div class="row">
              <div class="small-12 medium-3 columns">
                <label class="middle"><?php echo $lang['META_KEYWORDS']; ?>:</label>
              </div>
              <div class="small-12 medium-9 columns">
                <input type="text" name="meta_keywords" title="<?php echo $lang['T_TIP_META_KEYWORDS']; ?>">
              </div>
            </div>
            <div class="row">
              <div class="small-12 medium-3 columns">
                <div>
                  <label class="middle"><?php echo $lang['META_DESC']; ?>:</label>
                  <div class="symbol-text"><?php echo $lang['LENGTH_META_DESC']; ?>: <strong class="symbol-count"></strong></div>
                </div>
              </div>
              <div class="small-12 medium-9 columns">
                <textarea name="meta_desc" title="<?php echo $lang['T_TIP_META_DESC']; ?>"></textarea>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="reveal-footer text-right">
      <a class="link closeModal" href="javascript:void(0);" data-close>Отмена</a>
      <a class="button success save-button" href="javascript:void(0);"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</a>
    </div>
  </div>
</div>
  <!-- modal -->

<h4><?php echo $lang['STNG_SEO']; ?></h4>
<div class="row inline-label">
  <div class="large-7 columns">
  <?php
  foreach ($data['setting-shop']['options'] as $option) {
    if (in_array($option['option'], $data['seo-setting'])) :
      ?>
      <?php if ($option['option'] == 'cacheCssJs') : ?>
        <?php
        $cacheCssJs = $option['value'];
        $warning = '';
        ?>
          
          <div class="row">
            <div class="small-10 medium-7 columns">
              <a href = "javascript:void(0);" class="create-images-for-css-cache button success fl-right" style="display:<?php echo $cacheCssJs != 'true' ? 'none' : 'block' ?>">
                <span><?php echo $lang['CREATE_IMG_CSS']; ?></span>
              </a>                  
            </div>
            <div class="small-2 medium-5 columns">
            <?php if (!file_exists(PATH_TEMPLATE . '/cache/images')) { ?>
              <span class="warning-create-images" style="display:<?php echo $cacheCssJs != 'true' ? 'none' : 'block' ?>; color:#cc0000;font-size:11px;"><?php echo $lang['WARN_CREATE_IMG_CSS']; ?></span>
            <?php } ?>
            </div>
          </div>
          
          <div class="row">
            <div class="small-10 medium-7 columns">
              <label class="middle with-help dashed"><?php echo $lang[$option['name']]; ?><i class="fa fa-question-circle tip" aria-hidden="true" title="<?php echo $lang['DESC_' . $option['name']] ?>"></i></label>
              </div>
              <div class="small-2 medium-5 columns">
              <div class="checkbox margin">
                <input id="q2-<?php echo $option['option']; ?>" type="checkbox" class="option" name="<?php echo $option['option']; ?>" value="<?php echo $option['value']; ?>" <?php echo ($option['value'] == 'true' ? 'checked=checked' : ''); ?>>
                <label for="q2-<?php echo $option['option']; ?>"></label>
              </div>
            </div>
          </div>
      <?php else: ?>
        <div class="row">
          <div class="small-10 medium-7 columns">
            <label class="middle with-help dashed"><?php echo $lang[$option['name']]; ?><i class="fa fa-question-circle tip" aria-hidden="true" title="<?php echo $lang['DESC_' . $option['name']] ?>"></i></label>
            </div>
            <div class="small-2 medium-5 columns">
            <div class="checkbox margin">
              <input id="q2-<?php echo $option['option']; ?>" type="checkbox" class="option" name="<?php echo $option['option']; ?>" value="<?php echo $option['value']; ?>" <?php echo ($option['value'] == 'true' ? 'checked=checked' : ''); ?>>
              <label for="q2-<?php echo $option['option']; ?>"></label>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php endif;
  } ?>
  </div>
</div>
<ul class="accordion" data-accordion data-multi-expand="false" data-allow-all-closed="true">
  <?php foreach($seoGroups as $key=>$group) { ?>
  <?php if($key == 'STNG_SEO_GROUP_1'){?>
  <li class="accordion-item" data-accordion-item><a class="accordion-title" href="javascript:void(0);"><?php echo $lang[$key]?></a>
    <div class="accordion-content" data-tab-content="">
      <div class="widget-inner">
        <div class="widget-panel-holder">
          <div class="widget-panel clearfix">
            <div class="buttons-holder fl-left"><a class="button success addShortLink" href="javascript:void(0);" data-open="add-short-link-modal"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить выборку</a></div>
          </div>
        </div>
        <div class="table-wrapper">
          <table class="main-table" style="table-layout: fixed; margin-bottom: 0;">
            <thead>
              <tr>
                <th style="width: 150px;"><?php echo $lang['STNG_SEO_URL_REWRITE_NAME']?></th>
                <th style="width: 200px;"><?php echo $lang['STNG_SEO_URL_REWRITE_SHORT_URL']?></th>
                <th style="width: 100px;"><?php echo $lang['STNG_SEO_URL_REWRITE_URL']?></th>
                <th style="width: 150px;" class="text-right" style="width:100px;"><?php echo $lang['STNG_SEO_URL_REWRITE_ACTION']?></th>
              </tr>
            </thead>
            <tbody class="filterShortLinkTable">
              <?php foreach($group['data'] as $row){ ?>
                <tr class="rewrite-line" id="<?php echo $row['id']?>">
                  <td><?php echo $row['titeCategory']?></td>
                  <td>
                    <?php echo SITE.'/'.$row['short_url']?>
                    <a class="link-to-site tool-tip-bottom" target="_blank" href="<?php echo SITE.'/'.$row['short_url']?>" title="<?php echo $lang['T_TIP_STNG_SEO_URL_REWRITE_GO']?>">
                        <img alt="" src="<?php echo SITE?>/mg-admin/design/images/icons/link.png">
                    </a>
                  </td>
                  <td style="word-wrap: break-word;">
                    <span class="show-long-url">Показать</span>
                    <span style="display: none;" class="url-long"><?php echo $row['url']?></span>
                  </td>
                  <td class="actions">
                    <ul class="action-list text-right">
                        <li class="edit-row" id="<?php echo $row['id']?>"><a class="tool-tip-bottom fa fa-pencil" href="javascript:void(0);" title="<?php echo $lang['EDIT']?>"></a></li>
                        <li class="visible tool-tip-bottom" data-id="<?php echo $row['id']?>" title="" ><a href="javascript:void(0);" class="fa fa-eye <?php echo ($row['activity']) ? 'active' : '';?>"></a></li>
                        <li class="delete-row" id="<?php echo $row['id']?>"><a class="tool-tip-bottom fa fa-trash" href="javascript:void(0);"  title=""></a></li>
                    </ul>
                  </td>
                </tr>
              <?php }?>
            </tbody>
            <?php if(!empty($group['pager'])):?>
            <tfoot style="border-width: 1px 0 0 0;">
              <tr><td colspan="4">
                <div class="table-pagination clearfix" id="urlRewritePager">
                  <?php echo $group['pager']?>
                </div>
              </td></tr>
            </tfoot>
            <?php endif;?>
          </table>
        </div>
      </div>
    </div>
  </li>
  <?php } ?>
  <?php if($key == 'STNG_SEO_GROUP_2'){?>
  <li class="accordion-item" data-accordion-item><a class="accordion-title" href="javascript:void(0);"><?php echo $lang['STNG_SEO_URL_REDIRECT_ADD']?></a>
    <div class="accordion-content" data-tab-content="">
      <div class="widget-inner">
        <div class="widget-panel-holder">
          <div class="widget-panel clearfix">
            <div class="buttons-holder fl-left">
              <a class="button success addRedirect" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить запись перенаправления</a>
            </div>
          </div>
        </div>
        <div class="table-wrapper">
          <table class="main-table urlRedirectList">
            <thead>
              <tr>
                <th><?php echo $lang['STNG_SEO_URL_REDIRECT_OLD_URL']?></th>
                <th><?php echo $lang['STNG_SEO_URL_REDIRECT_NEW_URL']?></th>
                <th class="text-center"><?php echo $lang['STNG_SEO_URL_REDIRECT_CODE']?></th>
                <th class="text-right"><?php echo $lang['STNG_SEO_URL_REWRITE_ACTION']?></th>
              </tr>
            </thead>
            <tbody class="urlRedirectTable">
              <?php foreach($group['data'] as $row){?>
                <tr class="rewrite-line" id="<?php echo $row['id']?>">
                  <td class="url_old"><?php echo $row['url_old']?></td>
                  <td class="url_new"><?php echo $row['url_new']?></td>
                  <td class="code" width="250px" value="<?php echo $row['code'];?>"><?php echo $lang['REDIRECT_MESSAGE_'.$row['code']]?></td>
                  <td class="actions">
                    <ul class="action-list text-right">
                      <li class="save-row" id="<?php echo $row['id']?>" style="display:none"><a class="tool-tip-bottom fa fa-check" href="javascript:void(0);" title="<?php echo $lang['SAVE']?>"></a></li>
                      <li class="cancel-row" id="<?php echo $row['id']?>" style="display:none"><a class="tool-tip-bottom fa fa-times" href="javascript:void(0);" title="<?php echo $lang['CANCEL']?>"></a></li>
                      <li class="edit-row" id="<?php echo $row['id']?>"><a class="tool-tip-bottom fa fa-pencil" href="javascript:void(0);" title="<?php echo $lang['EDIT']?>"></a></li>
                      <li class="visible tool-tip-bottom" data-id="<?php echo $row['id']?>" title="<?php echo $lang['ACTIVITY']?>" ><a href="javascript:void(0);" class="fa fa-eye <?php echo ($row['activity']) ? 'active' : '';?>"></a></li>
                      <li class="delete-row" id="<?php echo $row['id']?>"><a class="tool-tip-bottom fa fa-trash" href="javascript:void(0);"  title="<?php echo $lang['DELETE']?>"></a></li>
                    </ul>
                  </td>
                </tr>
              <?php }?>
            </tbody>
            </tbody>
            <?php if(!empty($group['pager'])):?>
            <tfoot style="border-width: 1px 0 0 0;">
              <tr><td colspan="4">
                <div class="table-pagination clearfix urlRedirectListPager">
                  <?php echo $group['pager']?>
                </div>
              </td></tr>
            </tfoot>
            <?php endif;?>
          </table>
        </div>
      </div>
    </div>
  </li>
  <?php } ?>
  <?php if($key == 'STNG_SEO_GROUP_3'){?>
  <li class="accordion-item" data-accordion-item><a class="accordion-title" href="javascript:void(0);"><?php echo $lang['CREATE_SITEMAP']?></a>
    <div class="accordion-content" data-tab-content="">
      <div class="widget-inner">
        <div class="widget-panel-holder">
          <div class="widget-panel clearfix">
            <div class="buttons-holder fl-left">
              <a class="button success createSitemap" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $lang['CREATE_SITEMAP']?></a>
            </div>
          </div>
        </div>
        <div class="sitemap-msg">
          <?php if ($group['msg'] ) {
            echo '<div class="alert-block success text-center">'.$lang['MSG_SITEMAP1'].$group['msg'].'</div>';
          } else {
            echo '<div class="alert-block warning text-center">'.$lang['MSG_SITEMAP0'].'</div>';
          } ?>
        </div>
        <!-- <div class="alert-block warning text-center">В последний раз файл sitemap.xml был изменен 30 июня 2011. Количество ссылок в файле: 59</div> -->
        <div class="row-padding">
          <div class="row inline-label">
            <div class="large-8 columns">
              <div class="row">
                <div class="small-12 medium-5 columns">
                  <label class="middle with-help"><?php echo $lang['EXCLUDE_SITEMAP']?>:
                    <i class="fa fa-question-circle tip" aria-hidden="true" title='<?php echo $lang['DESC_EXCLUDE_SITEMAP'] ?>'></i>
                  </label>
                </div>
                <div class="small-12 medium-7 columns">
                  <textarea name="excludeUrl" class="option" placeholder="<?php echo $lang['RELATED_2'].' '.SITE.'/example'?>"><?php echo $group['excludeUrl']?></textarea>
                </div>
              </div>
              <div class="row">
                <div class="small-12 medium-5 columns">
                  <label class="middle with-help"><?php echo $lang[$data['setting-shop']['options']['autoGeneration']['name']]; ?>:<i class="fa fa-question-circle tip" aria-hidden="true" title='<?php echo $lang['DESC_' . $data['setting-shop']['options']['autoGeneration']['name']] ?>'></i></label>
                </div>
                <div class="small-12 medium-7 columns">
                  <div class="check-with-input">
                    <div class="checkbox margin">
                      <input id="re1" type="checkbox" class="option" name="autoGeneration" value="<?php echo $data['setting-shop']['options']['autoGeneration']['value'] ; ?>" <?php echo ($data['setting-shop']['options']['autoGeneration']['value'] == 'true' ? 'checked=checked' : ''); ?>>
                      <label for="re1"></label>
                    </div>
                    <span>раз в </span>
                    <div class="input-with-text">
                      <input class="small option" type="text" name="generateEvery" value="<?php echo $data['setting-shop']['options']['generateEvery']['value'] ; ?>"><?php echo $lang['SETTING_BASE_5']?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </li>
  <?php } ?>
  <?php if($key == 'STNG_SEO_GROUP_4') { $fields = $group['data']; ?>
  <li class="accordion-item" data-accordion-item><a class="accordion-title" href="javascript:void(0);"><?php echo $lang[$key]?></a>
    <div class="accordion-content" data-tab-content="">
      <div class="tmpl-seo-wrapper">
        <div class="title">Категории</div>
        <div class="side-holder clearfix">
          <div class="left-side">
            <table class="form-list catalog-seo">
              <tbody>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_TITLE']?></label></td>
                  <td>
                    <input type="text" class="option medium" 
                        name="catalog_meta_title" 
                        value="<?php echo $fields['catalog_meta_title'];?>"
                        placeholder="<?php echo $lang['SEO_TMPL_META_TITLE_PROD_PLCHOLD']?>" />
                  </td>
                </tr>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_DESC']?></label></td>
                  <td>
                    <textarea class="option medium" 
                        name="catalog_meta_description"
                        placeholder="{cat_desc,160}"><?php echo $fields['catalog_meta_description'];?></textarea>
                  </td>
                </tr>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_KEYWORDS']?></label></td>
                  <td><input type="text" class="option medium" 
                          name="catalog_meta_keywords" 
                          value="<?php echo $fields['catalog_meta_keywords'];?>"
                          placeholder="{meta_keywords}" />
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <a href="javascript:void(0)" class="link fl-right" status="active" id="setCatalogSeoForTemplate"><span>Применить шаблон тегов для всех категорий</span></a>
                  </td>
                </tr>
            </table>
          </div>
          <div class="right-side">
            <div class="tmpl-seo-description">
              <ul>
                <li><span>{titeCategory}</span> - <?php echo $lang['STNG_SEO_TMPL_CAT_TITLE']?></li>
                <li><span>{cat_desc[,<?php echo $lang['STNG_SEO_TMPL_DESC_LENGTH']?>]}</span> - <?php echo $lang['STNG_SEO_TMPL_CAT_DESC']?></li>
                <li><span>{meta_title}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_TITLE']?></li>
                <li><span>{meta_keywords}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_KEYWORDS']?></li>
                <li><span>{meta_desc}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_DESC']?></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="tmpl-seo-wrapper">
        <div class="title">Товары</div>
        <div class="side-holder clearfix">
          <div class="left-side">
            <table class="form-list product-seo">
              <tbody>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_TITLE']?></label></td>
                  <td>
                    <input type="text" class="option medium" 
                        name="product_meta_title" 
                        value="<?php echo $fields['product_meta_title'];?>"
                        placeholder="<?php echo $lang['SEO_TMPL_META_TITLE_PROD_PLCHOLD']?>" />
                  </td>
                </tr>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_DESC']?></label></td>
                  <td>
                    <textarea class="option medium" 
                        name="product_meta_description"
                        placeholder="<?php echo $lang['SEO_TMPL_META_DESC_PROD_PLCHOLD']?>"><?php echo $fields['product_meta_description'];?></textarea>
                  </td>
                </tr>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_KEYWORDS']?></label></td>
                  <td>
                    <input type="text" class="option medium" 
                        name="product_meta_keywords" 
                        value="<?php echo $fields['product_meta_keywords'];?>"
                        placeholder="{meta_keywords}" />
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <a href="javascript:void(0)" class="link fl-right" status="active" id="setProductSeoForTemplate"><span>Применить шаблон тегов для всех товаров</span></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="right-side">
            <div class="tmpl-seo-description">
              <ul>
                <li><span>{title}</span> - <?php echo $lang['STNG_SEO_TMPL_PROD_TITLE']?></li>
                <li><span>{category_name}</span> - <?php echo $lang['STNG_SEO_TMPL_PROD_CAT_TITLE']?></li>
                <li><span>{description[,<?php echo $lang['STNG_SEO_TMPL_DESC_LENGTH']?>]}</span> - <?php echo $lang['STNG_SEO_TMPL_PROD_DESC']?></li>
                <li><span>{code}</span> - <?php echo $lang['STNG_SEO_TMPL_PROD_ARTICLE']?></li>
                <li><span>{stringsProperties:характеристика}</span> - <?php echo $lang['STNG_SEO_TMPL_PROD_PROPERTIY']?></li>
                <li><span>{meta_title}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_TITLE']?></li>
                <li><span>{meta_keywords}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_KEYWORDS']?></li>
                <li><span>{meta_desc}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_DESC']?></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="tmpl-seo-wrapper">
        <div class="title">Страницы</div>
        <div class="side-holder clearfix">
          <div class="left-side">
            <table class="form-list page-seo">
              <tbody>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_TITLE']?></label></td>
                  <td>
                    <input type="text" class="option medium" 
                        name="page_meta_title" 
                        value="<?php echo $fields['page_meta_title'];?>"
                        placeholder="{title}" />
                  </td>
                </tr>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_DESC']?></label></td>
                  <td>
                    <textarea class="option medium" 
                        name="page_meta_description"
                        placeholder="{html_content,160}"><?php echo $fields['page_meta_description'];?></textarea>
                  </td>
                </tr>
                <tr>
                  <td><label><?php echo $lang['STNG_SEO_TMPL_META_KEYWORDS']?></label></td>
                  <td>
                    <input type="text" class="option medium" 
                        name="page_meta_keywords" 
                        value="<?php echo $fields['page_meta_keywords'];?>"
                        placeholder="{meta_keywords}" />
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <a href="javascript:void(0)" class="link fl-right" status="active" id="setPageSeoForTemplate"><span>Применить шаблон тегов для всех страниц</span></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="right-side">
            <div class="tmpl-seo-description">
              <ul>
                <li><span>{title}</span> - <?php echo $lang['STNG_SEO_TMPL_PAGE_TITLE']?></li>
                <li><span>{html_content[,<?php echo $lang['STNG_SEO_TMPL_DESC_LENGTH']?>]}</span> - <?php echo $lang['STNG_SEO_TMPL_PAGE_CONTENT']?></li>
                <li><span>{meta_title}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_TITLE']?></li>
                <li><span>{meta_keywords}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_KEYWORDS']?></li>
                <li><span>{meta_desc}</span> - <?php echo $lang['STNG_SEO_TMPL_CUR_META_DESC']?></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </li>
  <?php } ?>
  <?php if($key == 'STNG_SEO_ROBOTS'){?>
  <li class="accordion-item" data-accordion-item><a class="accordion-title" href="javascript:void(0);"><?php echo $lang[$key]?></a>
    <div class="accordion-content" data-tab-content="">
      <textarea class="robots-option option" name="robots"><?php echo $group; ?></textarea>
    </div>
  </li>
  <?php } ?>
  <?php } ?>
  
</ul>
<br>
<div class="row">
  <div class="small-12 columns">
    <button class="save-button save-settings button success fl-right"><span><?php echo $lang['SAVE'] ?></span></button>
  </div>
</div>

