/*-----------------------------------------------------------------
Theme Name: Reluxe
Author: Mugli
Author URI: https://themeforest.net/user/mugli 
Version: 1.0.0 
Description: Reluxe - Beauty spa, Barber Shop & Cosmetic Surgery Html Template  <

-------------------------------------------------------------------
JS TABLE OF CONTENTS
-------------------------------------------------------------------

        01. Mobile Menu 
        02. Sidebar Toggle 
        03. Body Overlay  
        04. Sticky Header   
        05. Counterup 
        06. Wow Animation 
        07. Set Background Image Color & Mask 
        08. Testimonial Slider   
        09. Project Slider
        10. Client Slider
        11. Testimonial Slider
        12. Team Slider   
        13. Back to top 
        14. MagnificPopup  view 
        15. NiceSelect   
        16. Mouse Cursor 
        17. Before After Slider   
        18. Preloader  


------------------------------------------------------------------*/

(function ($) {
    "use strict";

    $(document).ready(function () {


        /*-----------------------------------
          01. Mobile Menu  
        -----------------------------------*/
        $('#mobile-menu').meanmenu({
            meanMenuContainer: '.mobile-menu',
            meanScreenWidth: "1199",
            meanExpand: ['<i class="far fa-plus"></i>'],
        });



        /*-----------------------------------
          02. Sidebar Toggle  
        -----------------------------------*/
        $(".offcanvas__close,.offcanvas__overlay").on("click", function () {
            $(".offcanvas__info").removeClass("info-open");
            $(".offcanvas__overlay").removeClass("overlay-open");
        });
        $(".sidebar__toggle").on("click", function () {
            $(".offcanvas__info").addClass("info-open");
            $(".offcanvas__overlay").addClass("overlay-open");
        });



        /*-----------------------------------
          03. Body Overlay 
        -----------------------------------*/
        $(".body-overlay").on("click", function () {
            $(".offcanvas__area").removeClass("offcanvas-opened");
            $(".df-search-area").removeClass("opened");;
            $(".body-overlay").removeClass("opened");
        });



        /*-----------------------------------
          04. Sticky Header 
        -----------------------------------*/
        $(window).scroll(function () {
            if ($(this).scrollTop() > 250) {
                $("#header-sticky").addClass("sticky");
            } else {
                $("#header-sticky").removeClass("sticky");
            }
        });



        /*-----------------------------------
          05. Counterup 
        -----------------------------------*/
        $(".counter-number").counterUp({
            delay: 10,
            time: 10000,
        });



        /*-----------------------------------
          06. Wow Animation 
        -----------------------------------*/
        new WOW().init();



        /*-----------------------------------
          07. Set Background Image Color & Mask   
        -----------------------------------*/
        if ($("[data-bg-src]").length > 0) {
            $("[data-bg-src]").each(function () {
                var src = $(this).attr("data-bg-src");
                $(this).css("background-image", "url(" + src + ")");
                $(this).removeAttr("data-bg-src").addClass("background-image");
            });
        }


        if ($('[data-mask-src]').length > 0) {
            $('[data-mask-src]').each(function () {
                var mask = $(this).attr('data-mask-src');
                $(this).css({
                    'mask-image': 'url(' + mask + ')',
                    '-webkit-mask-image': 'url(' + mask + ')'
                });
                $(this).addClass('bg-mask');
                $(this).removeAttr('data-mask-src');
            });
        };


        /*-----------------------------------
           08. Banner Slider
        -----------------------------------*/
        // Function to initialize Swiper with animation
        function initializeSlider(sliderClass, paginationClass) {
            const sliderInit = new Swiper(sliderClass, {
                loop: true,
                slidesPerView: 1,
                effect: "fade",
                speed: 4000,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: paginationClass, // Use the passed paginationClass
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + '">' + (index + 1) + '</span>';
                    },
                },
            });

            function animated_swiper(selector, init) {
                const animated = function animated() {
                    $(selector + " [data-animation]").each(function () {
                        let anim = $(this).data("animation");
                        let delay = $(this).data("delay");
                        let duration = $(this).data("duration");
                        $(this)
                            .removeClass("anim" + anim)
                            .addClass(anim + " animated")
                            .css({
                                webkitAnimationDelay: delay,
                                animationDelay: delay,
                                webkitAnimationDuration: duration,
                                animationDuration: duration,
                            })
                            .one("animationend", function () {
                                $(this).removeClass(anim + " animated");
                            });
                    });
                };
                animated();
                init.on("slideChange", function () {
                    $(selector + " [data-animation]").removeClass("animated");
                });
                init.on("slideChange", animated);
            }

            animated_swiper(sliderClass, sliderInit);
        }

        // Initialize multiple sliders
        initializeSlider(".introSliderOne", ".pagination-class");


        /*-----------------------------------
           09. Project Slider
        -----------------------------------*/
        if ($('.projectSliderOne').length > 0) {
            const projectSliderOne = new Swiper(".projectSliderOne", {
                spaceBetween: 30,
                speed: 2000,
                loop: true,
                centeredSlides: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".arrow-prev",
                    prevEl: ".arrow-next",
                },
                breakpoints: {
                    1499: {
                        slidesPerView: 2,
                    },
                    1399: {
                        slidesPerView: 2,
                    },
                    1199: {
                        slidesPerView: 2,
                    },
                    991: {
                        slidesPerView: 2,
                    },
                    767: {
                        slidesPerView: 1,
                    },
                    575: {
                        slidesPerView: 1,
                    },
                    0: {
                        slidesPerView: 1,
                    },
                },
            });
        }


        /*-----------------------------------
           10. Client Slider
        -----------------------------------*/
        if ($('.clientSliderOne').length > 0) {
            const clientSliderOne = new Swiper(".clientSliderOne", {
                spaceBetween: 30,
                speed: 2000,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    1499: {
                        slidesPerView: 6,
                    },
                    1399: {
                        slidesPerView: 6,
                    },
                    1199: {
                        slidesPerView: 5,
                    },
                    991: {
                        slidesPerView: 4,
                    },
                    767: {
                        slidesPerView: 3,
                    },
                    575: {
                        slidesPerView: 2,
                    },
                    0: {
                        slidesPerView: 2,
                    },
                },
            });
        }

        /*-----------------------------------
           11. Testimonial Slider
        -----------------------------------*/
        if ($('.testimonialSliderOne').length > 0) {
            const testimonialSliderOne = new Swiper(".testimonialSliderOne", {
                spaceBetween: 30,
                speed: 2000,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    767: {
                        slidesPerView: 1,
                    },
                    575: {
                        slidesPerView: 1,
                    },
                    0: {
                        slidesPerView: 1,
                    },
                },
            });
        }

        if ($('.testimonialSliderTwo').length > 0) {
            const testimonialSliderTwo = new Swiper(".testimonialSliderTwo", {
                spaceBetween: 30,
                speed: 2000,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    767: {
                        slidesPerView: 1,
                    },
                    575: {
                        slidesPerView: 1,
                    },
                    0: {
                        slidesPerView: 1,
                    },
                },
            });
        }

        /*-----------------------------------
            12. Team Slider     
        -----------------------------------*/
        if ($('.teamSliderOne').length > 0) {
            const teamSliderOne = new Swiper(".teamSliderOne", {
                spaceBetween: 30,
                speed: 2000,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    1499: {
                        slidesPerView: 3,
                    },
                    1399: {
                        slidesPerView: 3,
                    },
                    1199: {
                        slidesPerView: 3,
                    },
                    991: {
                        slidesPerView: 2,
                    },
                    767: {
                        slidesPerView: 2,
                    },
                    575: {
                        slidesPerView: 1,
                    },
                    0: {
                        slidesPerView: 1,
                    },
                },
            });
        }


        /*-----------------------------------
           13. Back to top    
        -----------------------------------*/
        $(window).scroll(function () {
            if ($(this).scrollTop() > 20) {
                $("#back-top").addClass("show");
            } else {
                $("#back-top").removeClass("show");
            }
        });
        $("#back-top").click(function () {
            $("html, body").animate({ scrollTop: 0 }, 800);
            return false;
        });



        /*-----------------------------------
            14. MagnificPopup  view    
        -----------------------------------*/
        $(".popup-video").magnificPopup({
            type: "iframe",
            removalDelay: 260,
            mainClass: 'mfp-zoom-in',
        });

        $(".img-popup").magnificPopup({
            type: "image",
            gallery: {
                enabled: true,
            },
        });



        /*-----------------------------------
            15. NiceSelect     
        -----------------------------------*/
        if ($('.single-select').length) {
            $('.single-select').niceSelect();
        }


        /*-----------------------------------
            16. Mouse Cursor    
        -----------------------------------*/
        function mousecursor() {
            if ($("body")) {
                const e = document.querySelector(".cursor-inner"),
                    t = document.querySelector(".cursor-outer");
                let n,
                    i = 0,
                    o = !1;
                (window.onmousemove = function (s) {
                    o ||
                        (t.style.transform =
                            "translate(" + s.clientX + "px, " + s.clientY + "px)"),
                        (e.style.transform =
                            "translate(" + s.clientX + "px, " + s.clientY + "px)"),
                        (n = s.clientY),
                        (i = s.clientX);
                }),
                    $("body").on("mouseenter", "a, .cursor-pointer", function () {
                        e.classList.add("cursor-hover");
                        t.classList.add("cursor-hover");
                    }),
                    $("body").on("mouseleave", "a, .cursor-pointer", function () {
                        ($(this).is("a") && $(this).closest(".cursor-pointer").length) ||
                            (e.classList.remove("cursor-hover"),
                                t.classList.remove("cursor-hover"));
                    }),
                    (e.style.visibility = "visible"),
                    (t.style.visibility = "visible");
            }
        }
        $(function () {
            mousecursor();
        });

        /*-----------------------------------
            17. Before After Slider   
        -----------------------------------*/ 
        $("#slider").on("input change", (e) => {
            const a = e.target.value;
            $(".foreground-img").css("width", a + "%");
            $(".slider-button").css("left", `calc(${a}% - 43px)`);
        });
        

    }); // End Document Ready Function


    /*-----------------------------------
        18. Preloader   
    -----------------------------------*/

    function loader() {
        $(window).on('load', function () {
            // Animate loader off screen
            $(".preloader").addClass('loaded');
            $(".preloader").delay(600).fadeOut();
        });
    }

    loader();


})(jQuery); // End jQuery

