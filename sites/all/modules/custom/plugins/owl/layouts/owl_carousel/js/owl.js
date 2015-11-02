(function($) {

	$(document).ready(function() {
		var carousel_setting = Drupal.settings.owl.settings;
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
			afterInit: afterOWLinit // do some work after OWL init
		});

		function afterOWLinit() {
			if(carousel_setting.bullets == 'background'){
				$('.owl-controls .owl-page').append('<a class="item-link" href="#"></a>');
				var pafinatorsLink = $('.owl-controls .item-link');
				$.each(this.owl.userItems, function(i) {
					$(pafinatorsLink[i])
					// i - counter
					// Give some styles and set background image for pagination item
					.css({
						'background': 'url(' + $(this).find('img').attr('src') + ') center center no-repeat',
						'-webkit-background-size': 'cover',
						'-moz-background-size': 'cover',
						'-o-background-size': 'cover',
						'background-size': 'cover'
					})
					// set Custom Event for pagination item
					.click(function() {
						carousel.trigger('owl.goTo', i);
					});
				});
			}
		}
		
		
		$('.owl-controls .item-link').click(function(e){
			e.preventDefault();
		});
		
	});
	
})(jQuery);