/*jslint browser: true*/
/*global $, jQuery, alert, pebas_data */
jQuery(document).ready(function ($) {
    "use strict";

    $('body').on('click', '.package-call', function () {
        $(this).next().prop('checked', true);
        $(this).closest('form').submit();
    });

});
