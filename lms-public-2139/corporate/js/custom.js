
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
    $(".sidebar").removeClass("open");
    $("body").removeClass("sidebar-open");
    $("#filterGroupBox").removeClass('show');
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

function myFunction() {
    var x = document.getElementById("myDIV");
    var y = document.getElementById("filter_search1");
    if (x.style.display === "none") {
      x.style.display = "block";
      y.style.display = "none";
    } else {
      x.style.display = "none";
      y.style.display = "block";
    }
  }
// QtyInput Start
var QtyInput = (function () {
    var $qtyInputs = $(".qty-input");
    if (!$qtyInputs.length) {
        return;
    }
    $qtyInputs.each(function () {
        var $input = $(this).find(".product-qty");
        var $countBtn = $(this).find(".qty-count");
        var qtyMin = parseInt($input.attr("min"));
        var qtyMax = parseInt($input.attr("max"));

        function updateButtons(qty) {
            var $minusBtn = $countBtn.filter(".qty-count--minus");
            var $addBtn = $countBtn.filter(".qty-count--add");

            $minusBtn.prop("disabled", qty <= qtyMin);
            $addBtn.prop("disabled", qty >= qtyMax);
            $input.val(qty);
        }
        $input.on("change", function () {
            var qty = parseInt($(this).val());

            if (isNaN(qty) || qty < qtyMin) {
                qty = qtyMin;
            } else if (qty > qtyMax) {
                qty = qtyMax;
            }
            updateButtons(qty);
        })
        $countBtn.on("click", function () {
            var operator = this.dataset.action;
            var qty = parseInt($input.val());

            if (operator === "add") {
                qty = Math.min(qtyMax, qty + 1);
            } else {
                qty = Math.max(qtyMin, qty - 1);
            }

            updateButtons(qty);
        });

        updateButtons(parseInt($input.val()));
    });
})();
// QtyInput end

$("#checkInDate, #checkOutDate").daterangepicker(
    {
        autoApply: true,
        autoUpdateInput: false,
    },
    function (start, end, label) {
        let startDate = start.format("YYYY-MM-DD").toString();
        let endDate = end.format("YYYY-MM-DD").toString();
        document.getElementById("checkInDate").value = startDate;
        document.getElementById("checkOutDate").value = endDate;

    }
);
$('.propertiesSlider').slick({
    arrows: true,
    autoplay: true,
    fade: false,
    dots: false,
    infinite: true,
    speed: 500,
    prevArrow: "<button class='prevBtntestmonial'> <i class='bi bi-chevron-left'></i></button>",
    nextArrow: "<button class='nextBtntestmonial'> <i class='bi bi-chevron-right'></i></button>",
    slidesToShow: 4,
    slidesToScroll: 1,
    centerMode: true,
    centerPadding: '60px',
    responsive: [
        {
            breakpoint: 1300,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
            }
        },
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

$('.galleryCarousel').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    fade: false,
    asNavFor: '.slider-nav'
  });
  $('.slider-nav').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    asNavFor: '.galleryCarousel',
    dots: false,
    arrows: false,
    centerMode: true,
    focusOnSelect: false,
    responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            dots: false
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            dots: false,
            arrows: false,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: false,
            arrows: false,
          }
        }
    ]
  });