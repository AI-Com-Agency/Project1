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

    // bookmark listing
    $('body').on('click', '.bookmark-call', function (e) {
        e.preventDefault();
        let $this = $(this),
            data = {
                action: 'bookmark',
                bookmark_nonce: $this.data('nonce'),
                user_id: $this.data('user-id'),
                listing_id: $this.data('listing-id')
            };
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
                $this.find('i').text(result.icon);
            }
        });
    });

    $('body').on('click', '.bookmark-delete-call', function (e) {
        e.preventDefault();
        let $this = $(this),
            data = {
                action: 'bookmark_delete',
                bookmark_nonce: $this.data('nonce'),
                listing_id: $this.data('listing-id')
            };
        if (confirm($this.data('confirm'))) {
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
                    $this.closest('tr').remove();
                }
            });
        } else {
            return false;
        }
    });

});
