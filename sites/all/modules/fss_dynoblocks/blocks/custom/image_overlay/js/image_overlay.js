(function($) {
    $(document).ready(function() {
        setOverlaySize($(window).width());
   
        $(window).on('resize', function() {
          setOverlaySize($(window).width());
        });
        
        function setOverlaySize(window_width){
        $('.image-overlay').each(function(){
          var height = $(this).find('.overlay-text-container-inner').outerHeight(true);
          if(window_width >= 767){
            $(this).find('img').css({
              minheight: 'auto',
              height: 'auto',
            });
          } else if(window_width <= 560){ // for mobile
            $(this).find('img').css({
              minheight: height,
              height: height,
            });
      		}
    		});
      }
      
    });
})(jQuery);