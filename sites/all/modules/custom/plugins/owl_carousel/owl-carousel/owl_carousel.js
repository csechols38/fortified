(function($) {
	//our drupal behaviors
	Drupal.behaviors.owl_carousel = {
		attach: function(context, settings) {
			var owl_settings = settings.owl_carousel.settings;
			for (var s = 0; s < owl_settings.length; s++) {
				var carousel;
				var carousel_setting = owl_settings[s];
				var carousel = $("#owl-carousel-" + owl_settings[s]['id']);
				carousel_setting['navigation'] = carousel_setting['navigation'] == 'false' ? false : true;
				carousel_setting['pagination'] = carousel_setting['pagination'] == 'false' ? false : true;
				carousel.owlCarousel({
					items: carousel_setting['items'],
					itemsDesktop : carousel_setting['itemsDesktop'], 
					itemsDesktopSmall : carousel_setting['itemsDesktopSmall'],
					itemsTablet: carousel_setting['itemsTablet'],
					itemsMobile : carousel_setting['itemsMobile'],
					navigation: carousel_setting['navigation'],
					pagination: carousel_setting['pagination'],
					autoHeight: carousel_setting['autoHeight'],
					slideSpeed: 1000,
					responsiveRefreshRate: 200,
					paginationSpeed: 400,
				});
				
			}
		}
	};
})(jQuery);