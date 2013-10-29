/*
	jQuery Editable
	Author: Ole-Kenneth Rangnes
	Author URI: http://olekenneth.com
*/
(function($) {
  //
  // jQuery Editable enable you to easy edit any HTML DOM object
  //
jQuery.fn.editable = function(options) {
	var defaults = {
		post_id: 0,
		url: "/wp-content/plugins/live-edit/live-edit.php?save=y"
	};
	var opts = jQuery.extend(defaults, options);
	var editable = jQuery(this);

	editable.addClass("liveEdit_editable")
		.click(function(clickEvent) {
			clickEvent.preventDefault();
			$(this).unbind("focus").unbind("blur");

        	jQuery(".active")
        		.removeClass("liveEdit_active")
        		.attr('contenteditable', '');

			editable.removeClass("liveEdit_editable");
			
		    editable.focus(function(e){
	    		jQuery(this).addClass("liveEdit_active");
		    });

			editable.blur(function(e){
	        	editable.addClass("liveEdit_editable");
	        	jQuery.post(opts['url'], { id: opts['post_id'], nonce: opts['nonce'],field: opts['field'], content: jQuery(this).html()}, function() {
		        	jQuery(".liveEdit_active")
		        		.removeClass("liveEdit_active")
		        		.attr('contenteditable', '');
		        	editable.addClass("liveEdit_saved");
		        	
		        	setTimeout(function() {
			        	editable.removeClass("liveEdit_saved");
		        	}, 1250);
	        	})
	        });
	        editable.attr('contenteditable', true);
	        editable.focus();
		});
	return editable;
};
//
// end of jQuery Editable
//
})(jQuery);