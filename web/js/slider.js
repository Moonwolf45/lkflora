
$(document).ready(function() {
$('.employees__slider').slick({
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

$(document).ready(function() {
$('.cabinet__slider').slick({
   arrows:true,
   dots:false,
   slidesToShow: 1,
   slidesToScroll: 1,
   nextArrow: '<div class="arrow-cab arrow-cab-right" aria-hidden="true"></div>',
   prevArrow: '<div class="arrow-cab arrow-cab-left" aria-hidden="true"></div>',
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




$(document).ready(function() {
$('.strong-normal').slick({
   arrows:true,
   dots:false,
   slidesToShow: 1,
   slidesToScroll: 1,
   adaptiveHeight: true,
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