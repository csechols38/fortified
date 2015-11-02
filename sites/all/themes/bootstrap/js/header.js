(function($){

var cbpAnimatedHeader = (function() {

	var docElem = document.documentElement,
		didScroll = false,
		changeHeaderOn = 300;

	function init() {
		window.addEventListener( 'scroll', function( event ) {
			if( !didScroll ) {
				didScroll = true;
				setTimeout( scrollPage, 0 );
			}
		}, false );
	}

	function scrollPage() {
		var sy = scrollY();
		if ( sy >= changeHeaderOn ) {
			$('.bottom-menu').addClass('stuck');
		}
		else {
			$('.bottom-menu').removeClass('stuck');
		}
		didScroll = false;
	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	init();

})();

})(jQuery);