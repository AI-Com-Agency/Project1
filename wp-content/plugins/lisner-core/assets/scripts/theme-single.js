/*jslint browser: true*/
/*global $, jQuery, alert, lisner_data */
jQuery(document).ready(function ($) {
    "use strict";

    var pswpElement = document.querySelectorAll('.pswp')[0];

    // build items array
    var images = [],
        image;
    if ($('.listing-data-images').length !== 0) {
        $('.listing-data-images').each(function (i, e) {
            image = {src: $(e).data('image'), w: 1200, h: 800};
            images.push(image);
        });
    } else if ($('.single-header-gallery-item').length !== 0) {
        $('.single-header-gallery-item').each(function (i, e) {
            image = {src: $(e).data('image'), w: 1200, h: 800};
            images.push(image);
        });
    }
    var items = [
        $.each(images, function (i, e) {
            return e
        }),
    ];
    items = items.shift();

    // define options (if needed)
    var options = {
        // optionName: 'option value'
        // for example:
        index: 0 // start at first slide
    };

    // Initializes and opens PhotoSwipe
    if ($('.listing-data-images').length !== 0) {
        $('body').on('click', '.listing-gallery-call', function () {
            var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();
        });
    }
    if ($('.single-header-gallery').length !== 0) {
        $('body').on('click', '.single-header-gallery-item', function () {
            options = {
                index: $(this).data('slick-index')
            };
            var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();
        });
    }

    $('.single-header-gallery').on('init', function (slick) {
        $('.slider-loader-wrapper').fadeOut(1000);
    }).slick({
        rtl: !!lisner_data.rtl,
        slidesToShow: $('.single-listing-header').hasClass('single-listing-header-style-3') ? 1 : 3,
        swipeToSlide: true,
        dots: false,
        accessibility: true,
        center: true,
        arrows: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 475,
                settings: {
                    slidesToShow: 1,
                }
            },
        ]
    });
    if (lisner_data.is_rtl) {
        $('.slick-slide').hide();
        setTimeout(function () {
            $('.slick-slide').show();
        }, 200);
    }

});

