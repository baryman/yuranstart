<style>
	.base-settings{
		border: 1px solid #e6e6e6;
	}
	.base-settings > ul{
		list-style: none;
		padding-left: 10px;
	}
	.base-setting-save{
		float: right;
	}
</style>

<div class="row">
	<div class="large-12 columns">
		<div class="widget table">
			<div class="widget-header clearfix">
				<i class="fa fa-puzzle-piece" aria-hidden="true"></i>
				<?php $lang=MG::get('lang'); echo $lang['SETTINGS_PLUGIN'];?> "<span class="plugin-name"><?php echo URL::getQueryParametr('pluginTitle')?></span>"
			</div>
			<div class="widget-body plug-container">
				<div class="widget-panel-holder">
					<div class="widget-panel">
						<a href="javascript:void(0);" onclick="$('a[id=plugins]').click();" class="go-back-plugins link"><span>&larr; <?php echo $lang['BACK_PLUGIN'];?></span></a>
					</div>
				</div>
			    <?php  MG::createHook(URL::getQueryParametr('mguniqueurl')); ?>
			</div>
		</div>
	</div>
</div>

