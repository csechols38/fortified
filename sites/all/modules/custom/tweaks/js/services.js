(function($) {
	Drupal.behaviors.fss_tweaks = {
		attach: function(context, settings) {
			$('.service-container').on('mouseenter mouseleave', function(){
  			var $this = $(this);
  			setTimeout(function(){
    			$this.find('.service-item-body').toggleClass('active');
  			}, 200);
			});
		}
	};
})(jQuery);