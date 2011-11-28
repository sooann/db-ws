(function($) {
	
	$.fn.jqnotify = function(options) {

        options = $.extend({}, $.fn.jqnotify.defaults, options);
        
        return this.each(function() {
        	
        	var opts = $.fn.jqnotify.elementOptions(this, options);
        	
        	//remove duplicate notifications search via text
        	/*
        	if (opts.text!="") {
        		alert($(".notification"));
        		each();
        	}
        	*/
        	
        	//add notification DOM
        	
        	
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