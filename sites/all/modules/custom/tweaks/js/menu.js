(function($) {
	Drupal.behaviors.tweaks_menu = {
		attach: function(context, settings) {
  		
  		setMenuWidth($(window).width());
  		$(window).on('resize', function(){
    		setMenuWidth($(window).width());
  		});
      
      function setMenuWidth(window_width){
        if(window_width <= 1000 && window_width > 767){
          var num_of_links =  $('#navbar ul li').length;
          var max_width = $('.navbar-collapse').width() / num_of_links;
          $('#navbar ul li a').css({
            width: max_width,
            padding: "10px 5px",
          });
        } else {
          $('#navbar ul li a').css({
            width: 'auto',
            padding: "10px 15px",
          });
        }
      }

		}
	};
})(jQuery);