/*jslint browser: true*/
/*global $, jQuery, alert, pebas_data */
jQuery(document).ready(function ($) {
    "use strict";

    $('body').on('click', '.package-call', function () {
        $(this).next().prop('checked', true);
        $(this).closest('form').submit();
    });
    $('body').on('click', '.listing-user-packages-call', function () {
        if (!$(this).hasClass('inactive')) {
            $(this).addClass('inactive');
            $(this).text('keyboard_arrow_up');
            $('.listing-user-package').addClass('hidden');
        } else {
            $(this).removeClass('inactive');
            $(this).text('keyboard_arrow_down');
            $('.listing-user-package').removeClass('hidden');

        }
    });

});
