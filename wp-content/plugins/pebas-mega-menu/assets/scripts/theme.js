/*jslint browser: true*/
/*global $, jQuery, alert, tbm_data */
jQuery(document).ready(function ($) {
    "use strict";

    // set mega menu top offset
    var $headerHeight = $('.header-top').height(),
        $adminBarHeight = $('#wpadminbar').height();
    /* if ($(window).width() > 1024) {
         if ($('body').hasClass('logged-in-admin')) {
             $('.mega-menu-container').css('padding-top', $headerHeight + $adminBarHeight);
         } else {
             $('.mega-menu-container').css('padding-top', $headerHeight);
         }
     }*/

    if ($(window).width() <= 1024) {
        $('.menu-item-object-pebas_mega_menu').on('click', function () {
            var $this = $(this);
            $this.css('z-index', '10000');
            $this.find('.mega-menu-container').addClass('active');
        });
    }

    function mega_menu_close() {
        if ($(window).width() < 1025) {
            $('.mega-menu-nav').append('<span class="material-icons mega-menu-close">close</span>');
        }
    }

    $('body').on('click', '.mega-menu-close', function () {
        $('.menu-item-object-pebas_mega_menu').css('z-index', 9999);
        $('.mega-menu-container').removeClass('active');
    });

    mega_menu_close();
    $(window).on('resize', function () {
        mega_menu_close();
    });

    if ($(window).width() > 1024) {
        $('body').on('mouseover', '.menu-item-object-pebas_mega_menu', function () {
            var $this = $(this);
            $('.mega-menu-gallery').slick('setPosition');
        });
        $(document).on('mouseenter', '.mega-menu-container [data-toggle="tab"]', function () {
            $(this).tab('show');
        });
        $('body').on('click', '.mega-menu-container [data-toggle="tab"]', function () {
            let href = $(this).data('href');
            window.location.href = href;
        });
    }

    $.fn.imageLoad = function (fn) {
        this.load(fn);
        this.each(function () {
            if (this.complete && this.naturalWidth !== 0) {
                $(this).trigger('load');
            }
        });
    };

    // mega menu gallery
    if ($('.mega-menu-gallery').length !== 0) {
        $('.mega-menu-gallery').slick();
    }

    $('.mega-menu-image').imageLoad(function () {
        $(this).prev().fadeOut(600, function () {
            $(this).remove();
        });
    });

});
