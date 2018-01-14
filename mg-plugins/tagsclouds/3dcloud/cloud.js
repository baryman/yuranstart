function  myTags(mytags, j){
		mytags=mytags.replace(/<A/g, '<a')
			.replace(/\/A>/g, "/a>")
			.replace(/(\starget=_)(\w*)/g, ' target="_$2"')
			.replace(/(\sclass=)(?!")(\w*)/g, ' class="$2"')
			.replace(/(\sname=)(?!")(\w*)/g, ' name="$2"')
			.replace(/(\sid=)(?!")(\w*)/g, ' id="$2"')
			.replace(/(\srel=)(?!")(\w*)/g, ' rel="$2"');
		mytags=encodeURIComponent(mytags).replace(/!/g, '%21')
			.replace(/'/g, '%27').replace(/\(/g, '%28')
			.replace(/\)/g, '%29').replace(/\*/g, '%2A');
		var rnumber = Math.floor(Math.random()*9999999);
    var colorTag = $('.cloud').data('color');
    var color_tag = "0x"+ colorTag.substring(1);
		var flashvars = {
			tcolor: color_tag,
			tcolor2:color_tag,
			hicolor:"546842",
			tspeed:"110",
			distr:"true",
			mode:"tags",
			tagcloud:mytags
		};

		var params = {
			allowScriptAccess:"always",
			wmode:'transparent'
		};
		var attributes = {
			id:"flash_cloud"
		};

    swfobject.embedSWF(mgBaseDir + "/mg-plugins/tagsclouds/3dcloud/tagcloud.swf?r="+rnumber,
						   "tags"+j, "200", "200", "9.0.0",
						   "expressInstall.swf", flashvars,
						   params, attributes);
	}

	window.onload=function(){
    var id;
    var j=1;
    $('.cloud').each(function(){
    id = $(this).attr("id");
    var mytags="<tags>"
		+document.getElementById(id).innerHTML
		+"</tags>";
		myTags(mytags, j);
    j++;
  });
	};