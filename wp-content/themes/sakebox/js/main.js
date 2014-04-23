(function ($) {
  $(document).scroll(function() {
    var scroll = $(window).scrollTop();
    if (scroll >= 5) {
      $(".home #main-navigation").removeClass('homeHeader');
    } else {
      $(".home #main-navigation").addClass('homeHeader');
    }
  });
  document.addEventListener('touchmove', function(e) {
    $(".home #main-navigation").removeClass('homeHeader');
  }, false);
}(jQuery));