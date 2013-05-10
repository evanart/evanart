/*
Bones Scripts File
Author: Eddie Machado

This file should contain any js scripts you want to add to the site.
Instead of calling it in the header or throwing it inside wp_head()
this file will be called automatically in the footer so as not to
slow the page load.

*/

// IE8 ployfill for GetComputed Style (for Responsive Script below)
if (!window.getComputedStyle) {
    window.getComputedStyle = function(el, pseudo) {
        this.el = el;
        this.getPropertyValue = function(prop) {
            var re = /(\-([a-z]){1})/g;
            if (prop == 'float') prop = 'styleFloat';
            if (re.test(prop)) {
                prop = prop.replace(re, function () {
                    return arguments[2].toUpperCase();
                });
            }
            return el.currentStyle[prop] ? el.currentStyle[prop] : null;
        }
        return this;
    }
}

// as the page loads, call these scripts
jQuery(document).ready(function($) {

    /*
    Responsive jQuery is a tricky thing.
    There's a bunch of different ways to handle
    it, so be sure to research and find the one
    that works for you best.
    */
    
    /* getting viewport width */
    var responsive_viewport = $(window).width();
    
    /* if is below 481px */
    if (responsive_viewport < 481) {
    
    } /* end smallest screen */
    
    /* if is larger than 481px */
    if (responsive_viewport > 481) {
        
    } /* end larger than 481px */
    
    /* if is above or equal to 768px */
    if (responsive_viewport >= 768) {
    
        /* load gravatars */
        $('.comment img[data-gravatar]').each(function(){
            $(this).attr('src',$(this).attr('data-gravatar'));
        });
        
    }
    
    /* off the bat large screen actions */
    if (responsive_viewport > 1030) {
        
    }
    
	
	// add all your scripts here
	
 
}); /* end of as page load scripts */


/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w){
	// This fix addresses an iOS bug, so return early if the UA claims it's something else.
	if( !( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( "AppleWebKit" ) > -1 ) ){ return; }
    var doc = w.document;
    if( !doc.querySelector ){ return; }
    var meta = doc.querySelector( "meta[name=viewport]" ),
        initialContent = meta && meta.getAttribute( "content" ),
        disabledZoom = initialContent + ",maximum-scale=1",
        enabledZoom = initialContent + ",maximum-scale=10",
        enabled = true,
		x, y, z, aig;
    if( !meta ){ return; }
    function restoreZoom(){
        meta.setAttribute( "content", enabledZoom );
        enabled = true; }
    function disableZoom(){
        meta.setAttribute( "content", disabledZoom );
        enabled = false; }
    function checkTilt( e ){
		aig = e.accelerationIncludingGravity;
		x = Math.abs( aig.x );
		y = Math.abs( aig.y );
		z = Math.abs( aig.z );
		// If portrait orientation and in one of the danger zones
        if( !w.orientation && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ){
			if( enabled ){ disableZoom(); } }
		else if( !enabled ){ restoreZoom(); } }
	w.addEventListener( "orientationchange", restoreZoom, false );
	w.addEventListener( "devicemotion", checkTilt, false );
})( this );



/*! Custom Stuff for zAccordion
*/


jQuery(document).ready(function() {
	var example = jQuery('#slider'), defaults = {
		buildComplete: function () {
			example.css('visibility', 'visible');
		},
		timeout: 3000,
		speed: 300,
		auto: true,
		pause: true,
		slideWidth: 500,
		width: 1098,
		height: 250,
		easing: "easeInOutQuint",
		trigger: "mouseover"
	};
	function build(x) {
		var opts, current;
		if (!jQuery.isEmptyObject(example.data())) { /* If an zAccordion is found, rebuild it with new settings. */
			example.css('visibility', 'hidden');
			current = example.data('current');
			opts = jQuery.extend({
				startingSlide: current
			}, defaults, x);
			example.zAccordion('destroy', {
				removeStyleAttr: true,
				removeClasses: true,
				destroyComplete: {
					afterDestroy: function() {
						try {
							console.log('zAccordion destroyed! Attempting to rebuild...');
						} catch (e) {}
					},
					rebuild: opts
				}
			});
		} else { /* If no zAccordion is found, build one from scratch. */
			example.css('visibility', 'hidden');
			opts = jQuery.extend(defaults, x);
			example.zAccordion(opts);
		}
	}
	/* A unique Media Query is registered for each screen size. */
	enquire.register('screen and (min-width: 1030px)', { /* Standard screen sizes and a default build for browsers that are unsupported. */
		match : function () {
			build({
				slideWidth: 500,
				width: 1098,
				height: 250
			});
		} /* The *true* value below means this media query will fire by default. */
	}, true).register('screen and (min-width: 768px) and (max-width: 1029px)', {
		match : function () {
			build({
				slideWidth: 500,
				width: 935,
				height: 250
			});
		}
	}).register('screen and (min-width: 481px) and (max-width: 767px)', {
		match : function () {
			build({
				slideWidth: 500,
				width: 695,
				height: 250
			});
		}
	}).register('screen and (max-width: 480px)', {
		match : function () {
			if (!jQuery.isEmptyObject(example.data())) {
				example.zAccordion('destroy', {
					removeStyleAttr: true,
					removeClasses: true,
					destroyComplete: {
						afterDestroy: function() {
							try {
								console.log('zAccordion destroyed!');
							} catch (e) {}
						}
					}
				});
			}
		}
	});
});

jQuery(document).ready(function() {
    jQuery("#slider li").click(function () {
      jQuery(this).addClass("touched");
       setTimeout(function() {
      jQuery('#slider li').removeClass("touched");
     },1000);
    });
   
});



