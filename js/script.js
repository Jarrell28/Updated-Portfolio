$(window).on('load', function(){
    setTimeout(function(){
        $('.loader').fadeOut(500);
        $('body').css('overflow', 'auto');
        $('.animated').appear(function(){
            var item = $(this);
            var animation = item.data('animation');
            var animationDelay = item.data('animation-delay');
            if ( animationDelay ) {
                setTimeout(function(){
                    item.addClass( animation + " visible" );
                }, animationDelay);
            } else {
                item.addClass( animation + " visible" );
            }
        });
    }, 500);


});

$(document).ready(function () {
    $("#navButton").click(function () {
        $(".nav-items").slideToggle({
            start: function(){
                $(this).css({
                    display: "flex"
                })
            }
        });
    });

   toggleNav();


    $("#filter-projects button").on('click', function(){
        const value = $(this).data('filter');
        $('.all').filter('.' + value).show(300);
        $('.all').not('.' + value).hide(300);
        $(this).removeClass('btn-outline-dark').addClass('btn-dark').siblings().removeClass('btn-dark').addClass('btn-outline-dark');
    });

    var scroll = new SmoothScroll('a[href*="#"]', {
        speed: 1000,
        speedAsDuration: true,
        easeInOutQuart: true

    });

    $('.scroll').on('click', function(){
       if($(window).width() <= 1024){
           $('.nav-items').slideUp();
       }
    });

    $('.all').on('click', function() {
        if($(window).width() < 1025){
            $('html').addClass('modal-open');

        }
    });

    $('.close').on('click', function() {
        $('html').removeClass('modal-open');
    });

});


$(window).scroll(function () {
    toggleNav();
});

function toggleNav() {
    if($(window).width() > 1024) {
        //sticky nav
        if ($(document).scrollTop() > 430) {
            $('nav').addClass('nav-fixed');
        } else {
            $('nav').removeClass('nav-fixed');
        }
    }
}

$(window).resize(function(){
   if($(window).width() > 1024){
       if($('.nav-items').css('display') === 'none'){
           $('.nav-items').css('display', 'flex');
       }
   } else {
       if($('.nav-items').css('display') === 'flex'){
           $('.nav-items').css('display', 'none');
       }
   }
});
