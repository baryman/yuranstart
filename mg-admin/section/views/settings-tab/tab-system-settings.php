<div class="row">
  <div class="large-12 columns" style="margin: 0 10px;">
    <h4>Обновление системы</h4>

    <div class="tab-inner">

      <?php if($newFirstVersiov):?>
          <div class="alert-block success step-info">Доступна более новая версия движка - <strong><?php echo $newFirstVersiov?></strong> <a href="javascript:void(0);" class="button" onclick="$('#go').click();"><i class="fa fa-download" aria-hidden="true"></i> Скачать</a></div>
      <?php endif; ?>

      <?php if($newFirstVersiov):?>

      <div class="row">
        <div class="small-12 columns" style="margin-left: -40px;">
          <ul class="step-form" style="margin-top: 0!important;">
            <li class="step-update-li-1" >
              <span class="corner"></span>
              <h2>Шаг 1</h2>
              <strong>Загрузка обновлений</strong>
              <img style="display: none" class="loading-update-step-1 loader" src="<?php echo SITE ?>/mg-admin/design/images/small-loader.gif" class="loader" width="16" height="16" alt=""/>
            </li>
            <li class="step-update-li-2 current">
              <span class="corner"></span>
              <h2>Шаг 2</h2>
              <strong>Применение обновлений</strong>
              <img style="display:none" class="loading-update-step-2 loader" src="<?php echo SITE ?>/mg-admin/design/images/small-loader.gif" class="loader" width="16" height="16" alt=""/>
            </li>
            <li class="step-update-li-3 current">
              <span class="corner"></span>
              <h2>Шаг 3</h2>
              <strong>Система обновлена!</strong>
            </li>
          </ul>
        </div>
        
        <div>

          <div class="step-block">
            <div class="step1">
              <div style="display:none" class="step-process-info link-result"></div>
              <div class="step-1-info link-result">
                <ul class="system-version-list">
                  <li>
                    <strong>Описание изменений: </strong>
                    <?php
                    if($newVersionMsg){
                        echo $newVersionMsg;
                    }
                    ?>
                  </li>
                </ul>
              <div style="display:none" class="step-eror-info link-fail" style="margin-bottom:5px;"></div>
                <button rel="preDownload" class="update-now tool-tip-bottom button primary<?php echo $updataOpacity ?>" title="<?php $lang['SETTING_BASE_6']?>" <?php echo $updataDisabled ?> >
                    <span id="go">Скачать <?php echo strip_tags( $newFirstVersiov)?></span>
                </button>
              </div>
            </div>
            <div class="step2" style="display:none">
              <div style="display:none" class="step-process-info link-result"></div>
              <div class="step-2-info link-result">
                <ul class="system-version-list">
                  <li>
                    Вы подтверждаете, что резервная копия сайта и базы данных создана?<br/>
                    Вы принимаете риск несовместимости установленных плагинов и шаблонов с новой версией?<br/>
                    <div style="display:none" class="step-eror-info link-fail" style="margin-bottom:5px;"></div>
                    <button style="display:none" rel="preDownload" class="update-archive button">
                      <span id="go"><?php echo $lang['APPLY_UPDATE']?></span>
                    </button>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

          <?php else: ?>
        <div class="row">
        <div class="small-12 columns">
            <div class="row">
              <div class="small-12 columns" style="margin-left: -40px;">
                <ul class="step-form" style="margin-top: 0!important;">

                  <li class="step-update-li-1 current completed" >
                      <span class="corner"></span>
                      <h2>Шаг 1</h2>
                      <strong>Загрузка обновлений</strong>
                  </li>
                  <li class="step-update-li-2 current completed">
                      <span class="corner"></span>
                      <h2>Шаг 2</h2>
                      <strong>Применение обновлений</strong>
                  </li>
                 <li class="step-update-li-3">
                      <h2>Шаг 3</h2>
                      <strong>Система обновлена до актуальной версии <?php echo VER?>!</strong>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
          <?php endif; ?>
            <div class="row">
              <div class="large-7 columns">
                <div class="row">
                  <div class="small-10 medium-5 columns">
                    <label class="middle">Временно закрыть сайт для посетителей:</label>
                  </div>
                  <div class="small-2 medium-7 columns">
                    <div class="checkbox margin">
                      <?php
                        $downtime = $data['setting-system']['options']['downtime']['value'];
                        $checked = '';
                        $value = 'value="false"';

                        if($downtime=="true"){
                            $checked = 'checked="checked"';
                            $value = 'value="'.$downtime.'"';
                      }?>
                      <input id="r1" type="checkbox" class="option downtime-check" <?php echo $value ?> <?php echo $checked ?> name="downtime">
                      <label for="r1"></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="large-7 columns">
                <div class="row">
                  <div class="small-12 medium-5 columns">
                    <label class="middle">Ваш лицензионный ключ:</label>
                  </div>
                  <div class="small-12 medium-7 columns">

                    <?php
                    $displayKey = "display:inline-block";
                    if($data['setting-system']['options']['licenceKey']['value']){ $displayKey = "display:none";}?>
                      <div class="add-key">
                        <input style="<?php echo $displayKey; ?>" placeholder="Ключ" type="text"  name="licenceKey" 
                        class="settings-input option licenceKey" value="<?php echo $data['setting-system']['options']['licenceKey']['value']?>">
                        <button style="<?php echo $displayKey; ?>" class="save-button save-settings save-settings-system button success">
                          <i class="fa fa-floppy-o"></i> <span>Сохранить ключ</span>
                        </button>
                      </div>
                    <?php if($displayKey == "display:none"):?>
                      <a href="javascript:void(0);" class ="edit-key edit-row" >
                        <?php echo $data['setting-system']['options']['licenceKey']['value'] ?>
                      </a>
                    <?php endif;?>

                    <div class="error-key" style="color:red;padding-top:5px;display: <?php echo (($updataDisabled!="disabled")?'none':'block'); ?>">
                      <?php echo $lang['SETTING_LOCALE_1']?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="large-7 columns">
                <div class="row">
                  <div class="small-12 medium-5 columns">
                    <label class="middle">Данный ключ будет активен еще:</label>
                  </div>
                  <div class="small-12 medium-7 columns">
                    <?php
                    $dateActivate = MG::getOption('dateActivateKey');
                    if($dateActivate!='0000-00-00 00:00:00'){
                        $now_date = strtotime($dateActivate);
                        $future_date = strtotime(date("Y-m-d"));
                        $dayActivate = (365-(floor(($future_date - $now_date) / 86400 )));
                        if($dayActivate<=0){$dayActivate=0; $extend=" [<a href='http://moguta.ru/extendcenter'>Продлить</a>]";}
                        $activeDate =   " ".$lang['SETTING_BASE_4']." <span class='key-days-number'>".$dayActivate." ".$lang['SETTING_BASE_5']."</span>".$extend;

                    } else{
                        $activeDate = " <span class='link-result'>".$lang['SETTING_LOCALE_2']."</span>";
                    }
                    ?>
                    <div class="margin"><?php echo $activeDate ?></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="large-6 columns">
                <a class="button primary clearLastUpdate" href="javascript:void(0);"><i class="fa fa-refresh" aria-hidden="true"></i> Проверить обновления</a>
              </div>
            </div>
          <!-- </div>
        </div> -->
    </div>

    <table style="display:none" class="main-settings-list-table">
      <tr>
        <td>
          <dl>
            <dt><?php echo $lang['STNG_CUR_VER']?><span><?php echo VER?></span></dt>
            <dd id="updataMsg">
              <?php if(!$errorUpdata):
                if($newVersionMsg):
                  echo $newVersionMsg;?>
                  <span class="custom-text" style="color:red"><?php echo $lang['SETTING_LOCALE_3']?></span>
                  <br/><button rel="preDownload" class="update-now tool-tip-bottom <?php echo $updataOpacity ?>" title="<?php $lang['SETTING_BASE_6']?>" <?php echo $updataDisabled ?> >
                  <span id="go"><?php echo $lang['SETTING_LOCALE_5']?></span>
                </button>
                <?php else:?>
                  <strong><span style="color:green;"><?php echo $lang['SETTING_LOCALE_6']?></span></strong>
                  (<a href="javascript:void(0);" class="clearLastUpdate"><?php echo $lang['SETTING_LOCALE_7']?></a> )
                <?php endif?>
              <?php  else:?>
                <span style="color:red">
                  <?php echo $errorUpdata; ?> <?php echo $lang['SETTING_LOCALE_8']?>
                </span>
              <?php endif?>
            </dd>
          </dl>
        </td>
      </tr>
    </table>
  </div>
</div>