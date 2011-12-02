(function($) {
	
	$.fn.jqnotify = function(options) {

        options = $.extend({}, $.fn.jqnotify.defaults, options);
        
        return this.each(function() {
        	
        	var opts = $.fn.jqnotify.elementOptions(this, options);
        	
        	//add to page notifications
        	if (opts.level==1) {
        		addNotification("Error","There are errors in the form.");
        	} else {
        		addNotification(opts.type,opts.text);
        	}
        	
        	//add error css for form field
        	$(this).addClass('error');
        	
        	//add form field error notifications using tipsy
        	$(this).tipsy({gravity:'se',html:true,align:'right',trigger:'focus / hover'});
        	var temptitle=$(this).attr("title");
        	if (temptitle!="") {
        		temptitle+="<br />"+opts.text;
        	} else {
        		temptitle=opts.text;
        	}
        	$(this).attr("title",temptitle);
        	
        	//show invalid image
        	$(this).parent('div').find("img").css('display','inline');
        });
        
	};
	
	$.fn.jqnotify.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };
	
	$.fn.jqnotify.defaults = {
        type: "Error",	
        level: 1,
        text: "Field Error",
        subElements: []
    };
	
})(jQuery);

function addNotification(errType,errText) {
	
	errType=trim(errType);
	if (errType=="") {
		errType="Error";
	}
	
	switch (errType.toLowerCase()) {
		case 'error':
			cssclass="error";
			title="Error: ";
			break;
		case 'success':
			cssclass="success";
			title="Success: ";
			break;
		case 'information','info':
			cssclass="information";
			title="Information: ";
			break;
		case 'attention','att':
			cssclass="attention";
			title="Attention: ";
			break;
		case 'note':
			cssclass="note";
			title="Note: ";
			break;
		default:
			cssclass="error";
			title="Error: ";
			break;
	
	}
	
	//remove duplicate notifications search via text
	var $div = $('#notification-container');
	var canAddNotification=true;
	var $divs = $div.find("div");
	for (var i=0; i<$divs.length; i++) {
		if ($divs.eq(i).find('p').html().indexOf(errText)>-1) {
			canAddNotification=false;
		}
	}
	if (canAddNotification) {
		var htmlcontent = '<div class="notification '+cssclass+'"><a href="#" class="close-notification" title="Hide Notification" rel="tooltip">x</a><p><strong>'+title+'</strong>'+errText+'</p></div>'; 
		if ($divs.length>0) {
			$divs.last().after(htmlcontent);
		} else {
			$div.html(htmlcontent); 
		}
		
	}
}