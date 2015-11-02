(function($) {
	//our drupal behaviors
	Drupal.behaviors.owl_content = {
		attach: function(context, settings) {
		var carousel_setting = Drupal.settings.owl_content.settings;
		var carousel = $("." + carousel_setting['wrapper_class']);
		carousel_setting['navigation'] = carousel_setting['navigation'] == 'false' ? false : true;
		carousel_setting['pagination'] = carousel_setting['pagination'] == 'false' ? false : true;
		carousel.owlCarousel({
			autoPlay: carousel_setting['autoPlay'],
			items: carousel_setting['items'],
			itemsDesktop: carousel_setting['itemsDesktop'],
			itemsDesktopSmall: carousel_setting['itemsDesktopSmall'],
			itemsTablet: carousel_setting['itemsTablet'],
			itemsMobile: carousel_setting['itemsMobile'],
			navigation: carousel_setting['navigation'],
			pagination: carousel_setting['pagination'],
			autoHeight: carousel_setting['autoHeight'],
			slideSpeed: 1000,
			responsiveRefreshRate: 200,
			paginationSpeed: 400,
		});
		
		}
	};
})(jQuery);