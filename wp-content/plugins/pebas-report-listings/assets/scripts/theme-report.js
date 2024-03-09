/*jslint browser: true*/
/*global $, jQuery, alert, pebas_data */
jQuery(document).ready(function ($) {
    "use strict";

    function loader(loaderWidth = 20, strokeWidth = 4) {
        var $loader =
            <!-- Loader -->
            '<div class="loader ajax-loader">' +
            '<svg class="circular">' +
            '<circle class="path" cx="50" cy="50" r="' + loaderWidth + '" fill="none" stroke-width="' + strokeWidth + '" stroke-miterlimit="10"/>' +
            '</svg>' +
            '</div>';
        return $loader;
    }

    // login user
    $(document).on('submit', '.ajax-report', function (e) {
        e.preventDefault();
        let $this = $(this),
            data = $this.serialize() + '&action=' + $this.find('button[type=submit]').attr('name');
        $this.find('.btn').css('color', '#f60158').append(loader(12));
        $.post(pebas_data.lisner_ajaxurl, data, function (result) {
            result.message = result.success ? result.success : result.error;
            iziToast.show({
                message: result.message,
                messageColor: '#37003c',
                position: 'bottomCenter',
                color: result.error ? '#f54444' : '#07f0ff',
                timeout: result.success ? 2000 : false,
                pauseOnHover: false
            });
            if (result.success) {
                $('#modal-report').modal('hide').stop(400).remove();
                $('.single-listing-report').remove();
                $this.find('.loader').remove();
            }
        });
    });

});
