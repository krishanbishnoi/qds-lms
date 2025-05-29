$(document).ready(function () {
    var sticky = $('.mainheader'),
        scroll = $(window).scrollTop();
    if (scroll >= 33) {
        sticky.addClass('fixed');
    } else {
        sticky.removeClass('fixed');
    }


    $(window).scroll(function () {
        var sticky = $('.mainheader'),
            scroll = $(window).scrollTop();
        if (scroll >= 33) {
            sticky.addClass('fixed');
        } else {
            sticky.removeClass('fixed');
        }
    });

    $('.trustedBySlider').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        arrows: false,
        autoplaySpeed: 0,
        speed: 8000,
        pauseOnHover: false,
        cssEase: 'linear',
        dots: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    $('.test_slider').slick({
        arrows: true,
        autoplay: true,
        fade: false,
        dots: false,
        infinite: true,
        speed: 500,
        prevArrow: "<button class='prevBtntestmonial'> <i class='bi bi-arrow-left'></i></button>",
        nextArrow: "<button class='nextBtntestmonial'> <i class='bi bi-arrow-right'></i></button>",
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    arrows: false,
                    dots: true,
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            },
            {
                breakpoint: 767,
                settings: {
    
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: true,
                }
            }
        ]
    })

    if ($(window).width() > 576) {
        var rev = $('.rev_slider');
        rev.on('init', function (event, slick, currentSlide) {
            var
                cur = $(slick.$slides[slick.currentSlide]),
                next = cur.next(),
                prev = cur.prev();
            next.addClass('slick-snext');
            slick.$prev = prev;
            slick.$next = next;
        }).on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            var cur = $(slick.$slides[nextSlide]);
            slick.$next.removeClass('slick-snext');
            next = cur.next(),
                next.addClass('slick-snext');
            slick.$next = next;
        });

        rev.slick({
            speed: 1000,
            arrows: true,
            dots: false,
            vertical: true,
            verticalSwiping: true,
            focusOnSelect: true,
            prevArrow: "<button class='prevBtn'> <i class='bi bi-chevron-up'></i></button>",
            nextArrow: "<button class='nextBtn'> <i class='bi bi-chevron-down'></i></button>",
            infinite: true,
            centerMode: true,
            slidesPerRow: 1,
            slidesToShow: 1,
            slidesToScroll: 1,
            centerPadding: '0',
            swipe: true,
            customPaging: function (slider, i) {
                return '';
            },
        });
    }
    else {
        $('.rev_slider').slick({
            arrows: false,
            autoplay: true,
            fade: false,
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
        })
    }



    $(".navbar-toggler").click(function () {
        $(this).toggleClass("show")
        $(".menu-overly").toggleClass("show")
        $(".navbar-collapse").toggleClass("show");

    });
    $(".menu-overly").click(function () {
        $(this).removeClass("show");
        $(".navbar-toggler").removeClass("show");
        $(".navbar-collapse").removeClass("show");
    });

    $(".menutoggle").click(function () {
        $(".sidebar").toggleClass("open");
        $("body").toggleClass("sidebar-open");
    });
    
    $(".searchToggle").click(function () {
        $(".search-main").hide();
        $(".searchFilter").show();
    });
    $(".categoryToggle").click(function () {
        $(".search-main").show();
        $(".searchFilter").hide();
    });
    $(".hotelFilterToggle").click(function () {
        $("#filterGroupBox").addClass('show');
        $(".menu-overly").addClass("show");
    });
    $(".hotelFilterClose").click(function () {
        $("#filterGroupBox").removeClass('show');
        $(".menu-overly").removeClass("show");
    });

});
// progress-bar
var ctx = document.getElementById("myCharts").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {

        datasets: [{
            data: [40, 10],
            // Add custom color border 
            backgroundColor: ['#3BC900', '#D6D2E0'], // Add custom color background (Points and Fill)

            borderWidth: 0 // Specify bar border width
        }]
    },
    options: {
        responsive: false, // Instruct chart js to respond nicely.
        maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
        cutout: '80%',


    }
});
//progress bar end

function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    }else {
        return true;
    }
}

function reOrderRoomNumber(){
    var i = 1;
    jQuery(".row.roomRow").each(function(){
        jQuery(this).find(".rNo").html(i);
        i++;
    });
}