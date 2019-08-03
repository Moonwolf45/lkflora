$(document).ready(function() {
  $('.slider-header').slick({
     arrows:true,
     dots:false,
     slidesToShow: 5,
     slidesToScroll: 1,
     centerMode: true,
     variableWidth: true,
     nextArrow: '<div class="arrow arrow-right" aria-hidden="true"></div>',
     prevArrow: '<div class="arrow arrow-left" aria-hidden="true"></div>',
     responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });
});
