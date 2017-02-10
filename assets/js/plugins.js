// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Example plugin
(function($) {

	$.fn.yourFunction = function() {
		
	}

	$(function() {
		$('.class').yourFunction();
	});

})(jQuery);


// Add lightgallery classes to anchor tags in wp post galleries
(function($) {

	$.fn.addLightboxWPGallery = function() {
		
		var wpgallery = $('.entry-content .gallery');
		
		wpgallery.find('a').addClass('lightgallery-item');
		
	}
	$('body').addLightboxWPGallery();

})(jQuery);


// Initiate lightgallery
(function($) {

	$(function() {
	
		$("#page").lightGallery({
			selector: '.lightgallery-item',
			download: false,
			getCaptionFromTitleOrAlt: false
		});
	
	});

})(jQuery);