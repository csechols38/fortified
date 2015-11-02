(function($) {
	//our drupal behaviors
	Drupal.behaviors.fc = {
		attach: function(context, settings) {
				$("#featured-content-carousel").owlCarousel({
					items : 4,
					itemsDesktop : [1199, 3],
					itemsDesktopSmall : [979, 3],
					pagination: false,
					autoPlay: 6000, //Set AutoPlay to 3 seconds
				});
		}
	};
})(jQuery);