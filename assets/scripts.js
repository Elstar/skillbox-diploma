import $ from 'jquery';

$(function () {
  "use strict"; // Start of use strict

  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      let target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  let menuActive = parseInt($.cookie('menuActive'));
  if (menuActive == null) {
    menuActive = 0;
  }

  let closeText = $('#menu-toggle').attr('data-close-text');
  let showText = $('#menu-toggle').attr('data-show-text');

  if (!menuActive) {
    $('#menu-toggle').text(showText);
    $("#wrapper").toggleClass("toggled");
  } else {
    $('#menu-toggle').text(closeText);
  }

  $("#menu-toggle").on('click', function(e) {
    menuActive = parseInt($.cookie('menuActive'));
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");

    if (!menuActive) {
      $(this).text(closeText);
      $.cookie('menuActive', 1);
    } else {
      $(this).text(showText);
      $.cookie('menuActive', 0);
    }
  });
});
