/*jslint browser: true*/
/*global $, jQuery, alert, pebas_bo_data */
jQuery(document).ready(function ($) {
    "use strict";

    function loader(loaderWidth = 20, strokeWidth = 4) {
        let $loader =
            <!-- Loader -->
            '<div class="loader ajax-loader">' +
            '<svg class="circular">' +
            '<circle class="path" cx="50" cy="50" r="' + loaderWidth + '" fill="none" stroke-width="' + strokeWidth + '" stroke-miterlimit="10"/>' +
            '</svg>' +
            '</div>';
        return $loader;
    }

    // bookings tabs functionality
    $('body').on('click', '.booking-products__tab', function () {
        let $this = $(this),
            tab = $this.data('booking-tab');

        // remove classes
        $('.booking-products__tab').removeClass('active');
        $('.booking-products__content').removeClass('active');

        // add classes
        $this.addClass('active');
        $('.booking-products__content[data-booking-content=' + tab + ']').addClass('active');
    });
    $('body').on('change', 'input[name="_virtual"]', function () {
        let $this = $(this),
            tab = $this.closest('.booking-checkbox').data('hide-tab');

        if ($this.prop('checked')) {
            $('.booking-products__tab[data-hide-tab=' + tab + ']').hide();
        } else {
            $('.booking-products__tab[data-hide-tab=' + tab + ']').show();
        }
    });
    $('body').on('change', 'input[name^="_wc_booking_has"]', function () {
        let $this = $(this),
            tab = $this.closest('.booking-checkbox').data('product-data');

        if ($this.prop('checked')) {
            $('.booking-products__tab[data-show-tab=' + tab + ']').show();
        } else {
            $('.booking-products__tab[data-show-tab=' + tab + ']').hide();
        }
    });

    // woocommerce close and open wc-metabox-content
    $('.close_all').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.toolbar').next().find('.wc-metabox-content').hide();
    });
    $('.expand_all').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.toolbar').next().find('.wc-metabox-content').show();
    });

    // create booking product and attach it to a listing
    $('.job-dashboard-action-create_booking_product').on('click', function (e) {
        e.preventDefault();
        let $this = $(this),
            data = {
                action: $this.data('action'),
                listing_id: $this.data('listing-id'),
                nonce: $this.data('nonce')
            };
        $.post(lisner_data.lisner_ajaxurl, data, function (result) {
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
                window.location.href = $this.data('url');
            }
        });
    });

    // update booking product for a specified listing
    $('.form-booking').on('submit', function (e) {
        e.preventDefault();
        let $this = $(this),
            btn = $this.find('button[type=submit]'),
            btnText = btn.text(),
            data = $this.serialize();
        btn.text('').append(loader(10, 3));
        $.post(lisner_data.lisner_ajaxurl, data, function (result) {
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
                btn.text(btnText).find('.loader').remove();
            }
        });
    });

    // confirm booking availability
    $('body').on('click', '.confirm_booking', function (e) {
        e.preventDefault();
        let $this = $(this),
            data = {
                action: 'confirm_booking',
                nonce: $this.data('nonce'),
                id: $this.data('id')
            };
        $.post(lisner_data.lisner_ajaxurl, data, function (result) {
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
                $this.closest('.lisner-table').html(result.html);
                if (0 !== result.pending_count) {
                    $('.bookings-confirmation-count').text(result.pending_count);
                } else {
                    $('.bookings-confirmation-count').remove();
                }
            }
        });
    });
    $('body').on('click', '.demo-notice-call', function (e) {
        e.preventDefault();
        iziToast.show({
            message: 'Updating products has been disabled for demo purposes!',
            messageColor: '#fff',
            position: 'bottomCenter',
            color: '#f54444',
            timeout: 3000,
            pauseOnHover: false
        });
    });

    // disable for the demo
    if (pebas_bo_data.is_demo) {
        $('.add_resource').after('<a href="javascript:" class="button button-primary demo-notice-call">Add/link Resource</a>');
        $('.add_person').after('<a href="javascript:" class="button button-primary demo-notice-call">Add Person</a>');
        $('.add_resource, .add_person').remove();
    }

    // load select 2
    $('.wc-product-search').select2();

    // load tooltips
    $('.tips').tooltip();

});
