<?php
	$scheme = unserialize(stripslashes(getOption('interface')));
?>

<style type="text/css">
	/* основная цветовая схема */
	.mg-admin-html table.main-table tr.selected td:first-child{ 
		border-left-color: <?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .checkbox label:after{
		border-left:2px solid <?php echo $scheme['colorMain']; ?>;
		border-bottom:2px solid <?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .checkbox input[type=checkbox]:checked+label,
	.checkbox input[type=radio]:checked+label,
	.radio input[type=checkbox]:checked+label,
	.radio input[type=radio]:checked+label{
		border-color:<?php echo $scheme['colorMain']; ?>!important;
	}
	.mg-admin-html .radio label:after{
		background:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .table-pagination .pagination li.current a{
		color:#fff;background:<?php echo $scheme['colorMain']; ?>;
		border-color:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .modal .fa{
		color:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .ui-slider .ui-slider-range{
		background:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .tabs.custom-tabs{
		border:1px solid <?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .tabs.custom-tabs li a{
		color:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .tabs.custom-tabs li.is-active a{
		background:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .wrapper .header .header-top{
		background:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .wrapper .header .header-nav .top-menu .nav-list>li>a:before{
		background:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .section-settings .file-template.editing-file{
		background-color:<?php echo $scheme['colorMain']; ?>;
	}
	.mg-admin-html .button.primary,
	.mg-admin-html .button.primary:focus,
	.mg-admin-html .button.primary:hover,
	.mg-admin-html .button,
	.mg-admin-html .button:focus,
	.mg-admin-html .button:hover {
		background-color:<?php echo $scheme['colorMain']; ?>;
	}

	/* цвета ссылок */
	.mg-admin-html .link,
	.mg-admin-html a {
		color: <?php echo $scheme['colorLink']; ?>;
	}
	.mg-admin-html a.link {
		border-bottom: 1px dashed <?php echo $scheme['colorLink']; ?>;
	}

	/* кнопка сохранения */
	.mg-admin-html .button.success,
	.mg-admin-html .button.success:focus,
	.mg-admin-html .button.success:hover {
		background-color: <?php echo $scheme['colorSave']; ?>;
	}

	/* рамки */
	.mg-admin-html .widget.add-order .widget-footer, 
	.mg-admin-html .widget.settings .widget-footer, 
	.mg-admin-html .widget.table .widget-footer,
	.mg-admin-html .widget-panel,
	.mg-admin-html .main-table td,
	.mg-admin-html .checkbox label,
	.mg-admin-html select,
	.mg-admin-html .linkPage,
	.mg-admin-html input,
	.mg-admin-html textarea,
	.mg-admin-html .reveal-header,
	.mg-admin-html .reveal-footer,
	.mg-admin-html label,
	.mg-admin-html .accordion-item,
	.mg-admin-html .price-settings,
	.mg-admin-html .price-footer {
		border-color: <?php echo $scheme['colorBorder']; ?> !important;
	}
	.mg-admin-html .main-table td {
		border-left: 0 !important;
	}

	/* прочие кнопки */
	.mg-admin-html .button.secondary,
	.mg-admin-html .button.secondary:focus,
	.mg-admin-html .button.secondary:hover {
		background-color: <?php echo $scheme['colorSecondary']; ?>;
	}

	/**/
	/*.mg-admin-html p,
	.mg-admin-html label,
	.mg-admin-html input,
	.mg-admin-html textarea,
	.mg-admin-html h1,
	.mg-admin-html h2,
	.mg-admin-html h3,
	.mg-admin-html h4,
	.mg-admin-html h5,
	.mg-admin-html h6,
	.mg-admin-html b,
	.mg-admin-html span,
	.mg-admin-html td,
	.mg-admin-html th,
	.mg-admin-html table thead th,
	.mg-admin-html .widget-header {
		color: <?php echo $scheme['colorText']; ?> !important;
	}*/

	/**/
	/*.mg-admin-html .widget,
	.mg-admin-html .tabs-panel {
		background-color: <?php echo $scheme['colorBg']; ?>;
	}*/
</style>