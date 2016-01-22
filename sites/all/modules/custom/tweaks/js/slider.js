(function($) {
	Drupal.behaviors.tweaks_slider = {
		attach: function(context, settings) {
  		
  		setSlickHtmlWidth($(window).width());
  		
  		if($('.slick').get(0)){
  			$(window).resize(function() {
          var window_width = $(window).width();
          setSlickHtmlWidth(window_width);
        });
      }
      
      function setSlickHtmlWidth(window_width){
        $('.slick-html-inner').each(function(){
          var height = $(this).outerHeight(true);
          if(window_width > 767 && window_width < 1200){
            
      		} else if(window_width <= 767 && window_width > 480){ // for tablet
        		$(this).closest('.slick-list').css({
              minHeight: ($(this).outerHeight(true)),
            });
            $(this).parents('.node').find('img').css({
              minHeight: ($(this).outerHeight(true) + 100),
            });

      		} else if(window_width <= 480){ // for mobile
            $(this).closest('.slick-list').css({
              minHeight: ($(this).outerHeight(true) + 100),
            });
            $(this).parents('.node').find('img').css({
              minHeight: ($(this).outerHeight(true) + 100),
            });
      		}
    		});
      }
		}
	};
})(jQuery);