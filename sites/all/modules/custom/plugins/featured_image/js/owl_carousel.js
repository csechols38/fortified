(function($) {
	//our drupal behaviors
	Drupal.behaviors.fi = {
		attach: function(context, settings) {
				$("#featured-image-carousel").owlCarousel({
					autoPlay: 3000, //Set AutoPlay to 3 seconds
					items : 1,
					pagination: false,
				});
		}
	};
})(jQuery);