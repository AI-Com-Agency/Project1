/*jslint browser: true*/
/*global $, jQuery, alert, pbs_data */
jQuery(document).ready(function ($) {
    "use strict";

    // declare global vars
    var body = $('body'),
        wpAdminBar = $('#wpadminbar');

    // add mobile class to mobile devices
    function addMobileClasses() {
        if ($(window).width() < 1030) {
            $('body').addClass('mobile');
            $('.collapse.navbar-collapse').addClass('mobile-nav');
            $('.search-on-mobiles').removeClass('hidden');
            $('.hidden-on-mobile').css('display', 'none');
        } else {
            $('body').removeClass('mobile');
            $('.collapse.navbar-collapse').removeClass('mobile-nav');
            $('.search-on-mobiles').addClass('hidden');
            $('.hidden-on-mobile').css('display', 'block');
        }
    }

    $(window).on('resize', function () {
        addMobileClasses();
        addMobileNavClick();
    });


    // Sticky Navigation
    if (pbs_data.sticky_header) {
        if ($(window).width() > 1030) {
            var header = $('header[class*=header-top]'),
                headerHeight = header.outerHeight(),
                headerFixed = header.clone().addClass('fixed-top');
            header.after(headerFixed);
            headerFixed.find('form').remove();
            headerFixed.remove();
            $(window).on('scroll', function () {
                if ($(window).scrollTop() > 150) {
                    headerFixed.addClass('sticky');
                } else {
                    headerFixed.removeClass('sticky');
                }
            });
        }
    }

    /**
     * Calculate position of the element
     *
     * @param element
     * @returns {{x: number, y: number}}
     */
    function getPosition(element) {
        let xPosition = 0;
        let yPosition = 0;

        while (element) {
            xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
            yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
            element = element.offsetParent;
        }
        return {
            x: xPosition,
            y: yPosition
        };
    }

    // Header Alignments
    if ($(window).width() > 1024) {
        // Auto align sub menu if it is out of viewport boundaries
        $('.menu-item-has-children').on('hover', function () {
            let ww = Math.max(document.documentElement.clientWidth, window.innerWidth || 0),
                pos = getPosition($(this).find('.sub-menu')[0]),
                ew = $(this).children('.sub-menu').width();
            if (pos.x > (ww - ew)) {
                $(this).children('.sub-menu').css({right: ww - pos.x, left: 'auto'});
            }
        });
        // align menu item label to top of the header
        let menuSpacing = $('#main-menu').length != 0 ? $('#main-menu').offset().top : '';
        if (pbs_data.is_logged_in) {
            $('.menu-label').animate({
                top: '-' + (menuSpacing - wpAdminBar.outerHeight())
            }, 500);
        } else {
            $('.menu-label').animate({
                top: '-' + menuSpacing
            }, 500);
        }
        $('.menu-label').show();
    }

    function addMobileNavClick() {
        if ($(window).width() < 1280) {
            $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
                if (!$(this).next().next().hasClass('show')) {
                    $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
                }
                var $subMenu = $(this).next().next(".dropdown-menu");
                $subMenu.toggleClass('show');

                $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
                    $('.dropdown-menu .show').removeClass("show");
                });

                return false;
            });
        }
    }

    addMobileNavClick();

    // activate chosen select
    if (!pbs_data.is_unit) {
        $('.woocommerce').find('select[name^=orderby].orderby').chosen({
            disable_search_threshold: true,
        });
        $('.woocommerce').find('select.dropdown_product_cat').chosen({
            disable_search_threshold: true,
        });
    }
    $('#wc-pills-tab a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show')
    });

    // unit test
    if (pbs_data.is_unit || pbs_data.is_archive) {
        $('.blog-masonry').masonry();
    }

    // single page animate scroll
    $('body').on('click', '.animate', function (e) {
        e.preventDefault();
        let position = $($(this).attr('href')).offset();
        $('html,body').stop().animate({scrollTop: position.top - 120}, 500);
    });

    // on load
    $(window).on('load', function () {
        addMobileClasses();
    });

});

