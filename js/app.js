/* 
$(function () {
  let slidesToShowNum = 3; //slidesToShowに設定したい値を挿入
  // slidesToShowより投稿が少ない場合の処理▽ 
  let item = $('li').length; //liの個数を取得
  if (item <= slidesToShowNum) {
   for ( i = 0 ; i <= slidesToShowNum + 1 - item ; i++) { //足りていない要素数分、後ろに複製
    $('li:nth-child(' + i + ')').clone().appendTo('slideshow01');
   }
  }
  // slidesToShowより投稿が少ない場合の処理△
  $('slideshow01').slick({
   slidesToShow: slidesToShowNum, //slidesToShowNumに設定した値が入る
  });
 }); */

$(function(){
  $('#menu_list').slick({
    dots: false,
    speed: 1000,
    arrows: true,
    autoplay: false,
    infinite: true,
    fade: false
  });
});

$(function(){
  $('.slideshow02').slick({
    asNavFor:'.slideshowBGI',
    speed: 2000,
    dots: false,
    arrows: false,
    infinite: true,
    fade: true
  });
});

$('.slideshow01').slick({
  asNavFor:'.slideshowBGI',
  centerMode: true,
  centerPadding: '60px',
  slidesToShow: 5,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 4
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    }
  ]
});
$(function() {
  $(".nav-button").on("click", function() {
    if ($(this).hasClass("active")) {
      $(this).removeClass("active");
      $(".nav-wrap")
        .addClass("close")
        .removeClass("open");
    } else {
      $(this).addClass("active");
      $(".nav-wrap")
        .addClass("open")
        .removeClass("close");
    }
  });
});

$(function () {
  $('#openNaoshima').click(function(){
      $('#naoshima').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
    $('#naoshima').fadeOut();
  });
});
/* 
$('.slideshow01').on('beforeChange', function(){
  $('.slide-current').removeClass('is--active');
});

$('.slideshow01').on('afterChange', function(){
  $('.slick-current').addClass('is--active');
}); */