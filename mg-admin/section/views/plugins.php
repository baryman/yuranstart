<div class="row section-plugins">
    <div class="large-12 columns">
      <div class="widget table">
        <div class="widget-header clearfix"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> <?php echo $lang['TITLE_PLUGINS']; ?>
        </div>
        <div class="widget-body">
          <div class="widget-panel-holder">
            <div class="widget-panel">
              <form class="newPluginForm" method="post" noengine="true" enctype="multipart/form-data">    
                <button type="button" name="checkPluginsUpdate" id="checkPluginsUpdate" class="custom-btn button success fl-right" style="margin: 0 0 10px 0;"><span>Проверить обновления</span></button>
                <div class="type-file tip">
                  <label class="install-plugin button" for="addPlugin"><span><?php echo $lang['PLUG_UPLOAD']; ?></span></label>
                  <div ></div>
                  <input type="file" style="display: none;" name="addPlugin" id="addPlugin" size="1">
                </div>
              </form>      
            </div>
          </div>
          <div class="table-wrapper">
            <table class="main-table">
              <thead>
                <tr>
                  <th>Активность</th>
                  <th class="text-left">Название</th>
                  <th>Описание</th>
                  <th class="text-right">Действия</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($pluginsList)) {
                  $counter = 1;
                  foreach ($pluginsList as $item):
                    ?>
                    <?php
                    $class = 'plugin-settings-off';
                    if (PM::isHookInReg($item['folderName'])) {
                      $class = 'plugin-settings-on';
                    }

                    if($item['Active'] == 1) {
                      $checked = 'checked';
                    } else {
                      $checked = '';
                    }
                    ?>
                    <tr id="<?php echo $item['folderName'] ?>" class="<?php echo $class ?>">
 
                      <td class="switch">
                        <div class="switch small tip" title="Выключить">
                          <input class="switch-input" id="sw<?php echo $counter; ?>" type="checkbox" name="sw1" <?php echo $checked; ?>>
                          <label class="switch-paddle plugins-active" for="sw<?php echo $counter; ?>"></label>
                        </div>
                      </td>

                      <td>
                        <div class="plugins-name-wrapper">
                          <ul class="plugins-author-list">
                            <li class="p-name"><?php echo $item['PluginName'] ?></li>
                            <li>Версия <span class="plugin-version"><?php echo $item['Version'] ? $item['Version'] : '-'; ?></span> </li>
                            <li><?php echo $item['Author'] ? $item['Author'] : ''; ?></li>
                            <li><?php if (!empty($item['PluginURI'])): ?><a href="<?php echo $item['PluginURI'] ?>"><?php echo $lang['PLUG_PAGE']; ?></a><?php endif; ?></li>
                            <?php if (!empty($item['update'])): ?>
                              <li class="new-plugin-version">
                                <?php 
                                echo $lang['PLUGIN_NEW_VERSION']; ?>: <?php echo $item['update']['last_version']; 
                                $desc = '<ul style=\'max-width:600px;\'>';
                                foreach($item['update']['description'] as $version=>$description){
                                  $desc .= '<li><b>'.$version.'</b><br />'.$description.'</li>';
                                }
                                $desc .= '</ul>';
                                ?>
                                <div class="about-plugin-update" style="float:right; margin: -27px -30px 27px 30px;">
                                    <a class="tool-tip-right desc-property" href="javascript:void(0);" title="<?php echo $desc;?>">
                                      <i class="fa fa-question-circle tip" aria-hidden="true"></i>
                                    </a>
                                </div>
                              </li>
                            <?php endif; ?>
                          </ul>
                        </div>
                      </td>
                      <td class="plugin-desc"><?php echo $item['Description'] ?></td>
                      <td class="actions">
                        <ul class="action-list">
                          <?php if ($class !== 'plugin-settings-off') { ?>
                            <li><a class="fa fa-cog tip plugSettings" href="javascript:void(0);" aria-hidden="true" title="<?php echo $lang['T_TIP_GOTO_PLUG']; ?>"></a></li>
                          <?php } ?>
                          <?php if (!empty($item['update'])){ ?>
                            <li class="update-plugin"><a class="fa fa-refresh tip" href="javascript:void(0);" aria-hidden="true" title="<?php echo $lang['PLUGIN_UPDATE_START']; ?>"></a></li>
                          <?php } ?>
                          <li class="delete-order"><a class="fa fa-trash tip" href="javascript:void(0);" aria-hidden="true" title="<?php echo $lang['DELETE']; ?>"></a></li>
                        </ul>
                      </td>
                    </tr>

                  <?php
                  $counter++;
                  endforeach;
                }else {
                  ?>

                  <tr class="no-results"><td colspan="4"><?php echo $lang['PLUG_NONE'] ?></td></tr>

                <?php } ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="h-height"></div>
</div>