var interface = (function () {
  return {
  	schemes: ['colorMain', 'colorLink', 'colorSave', 'colorBorder', 'colorSecondary'],

  	init: function() {
  		// для повторной инициализации
  		$('.colorpicker-style').detach();
  		$('body').append('<div class="colorpicker-style"></div>');
  		$('.colorpicker-style').append('<link rel="stylesheet" type="text/css" href="' + admin.SITE + '/mg-core/script/colorPicker/css/colorpicker.css" />\
  		                  <link rel="stylesheet" media="screen" type="text/css" href="' + admin.SITE + '/mg-core/script/colorPicker/css/layout.css" />');
  		$('.colorpicker').detach();
  		// цветовая схема
		$('#colorMain').ColorPicker({
		  color: $('#colorMain div').css('background-color'),
		  onShow: function (colpkr) {
		    $(colpkr).fadeIn(0);
		    return false;
		  },
		  onHide: function (colpkr) {
		    $(colpkr).fadeOut(0);
		    return false;
		  },
		  onChange: function (hsb, hex, rgb) {
		    $('#colorMain div').css('backgroundColor', '#' + hex);
		  }
		});
		// цвет ссылок
		$('#colorLink').ColorPicker({
		  color: $('#colorLink div').css('background-color'),
		  onShow: function (colpkr) {
		    $(colpkr).fadeIn(0);
		    return false;
		  },
		  onHide: function (colpkr) {
		    $(colpkr).fadeOut(0);
		    return false;
		  },
		  onChange: function (hsb, hex, rgb) {
		    $('#colorLink div').css('backgroundColor', '#' + hex);
		  }
		});
		// цвет кнопок сохранения
		$('#colorSave').ColorPicker({
		  color: $('#colorSave div').css('background-color'),
		  onShow: function (colpkr) {
		    $(colpkr).fadeIn(0);
		    return false;
		  },
		  onHide: function (colpkr) {
		    $(colpkr).fadeOut(0);
		    return false;
		  },
		  onChange: function (hsb, hex, rgb) {
		    $('#colorSave div').css('backgroundColor', '#' + hex);
		  }
		});
		// цвет secondary
		$('#colorSecondary').ColorPicker({
		  color: $('#colorSecondary div').css('background-color'),
		  onShow: function (colpkr) {
		    $(colpkr).fadeIn(0);
		    return false;
		  },
		  onHide: function (colpkr) {
		    $(colpkr).fadeOut(0);
		    return false;
		  },
		  onChange: function (hsb, hex, rgb) {
		    $('#colorSecondary div').css('backgroundColor', '#' + hex);
		  }
		});
		// цвет рамок
		$('#colorBorder').ColorPicker({
		  color: $('#colorBorder div').css('background-color'),
		  onShow: function (colpkr) {
		    $(colpkr).fadeIn(0);
		    return false;
		  },
		  onHide: function (colpkr) {
		    $(colpkr).fadeOut(0);
		    return false;
		  },
		  onChange: function (hsb, hex, rgb) {
		    $('#colorBorder div').css('backgroundColor', '#' + hex);
		  }
		});

  		// сохранение и применение стилей
  		$('.admin-center').on('click', '.section-settings .save-interface', function() {  
  			interface.save();
  		});

  		$('.admin-center').on('click', '.section-settings .default-interface', function() {  
  			interface.default();
  		});
  	},

  	save: function() {
  		var data = {};
  		for(i = 0; i < interface.schemes.length; i++) {
  			data[interface.schemes[i]] = $('#'+interface.schemes[i]).find('div').css('background-color');
  		}

  		admin.ajaxRequest({
  		  mguniqueurl:"action/saveInterface",
  		  data: data,
  		  bg: $('#bg').val()
  		},
  		function(response) {
  		  location.reload();
  		});
  	},

  	default: function() {
  		admin.ajaxRequest({
  		  mguniqueurl:"action/defaultInterface",
  		},
  		function(response) {
  		  location.reload();
  		});
  	},

  }
})();