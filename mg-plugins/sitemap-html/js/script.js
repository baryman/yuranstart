 /* 
 * Модуль  sitemap-html, подключается на странице настроек плагина.
 */

var sitemapHtml = (function() {
   
  return { 
    init: function() {     
      $('#save-button-map').click(function() {
        var data = {
          isShowProduct: $('.option-map').prop('checked'),
          isShowFilterPage: $('.option-filterPage').prop('checked')
        }
        // var isShowProduct = $('.option-map').prop('checked');
        sitemapHtml.saveOption(data);
      });
    },

    saveOption: function(data) {
      admin.ajaxRequest({
        pluginHandler: 'sitemap-html', // имя папки в которой лежит данный плагин
          actionerClass: "Pactioner", 
          action: "saveOption", // название действия в пользовательском классе          
          isShowProduct: data.isShowProduct,
          isShowFilterPage: data.isShowFilterPage,
        },
        function(response) {
          admin.indication(response.status, response.msg);
          // admin.refreshPanel();     
        }
      );    
    },

  }
})();

sitemapHtml.init();   