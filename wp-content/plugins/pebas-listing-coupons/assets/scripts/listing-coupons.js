/*jslint browser: true*/
/*global $, jQuery, alert, lc_data */
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

    /**
     * Switch to proper coupon type
     *
     * @param $val
     */
    function couponType($val) {
        $('.coupon-type-wrapper').addClass('hidden');
        $('.coupon-type-wrapper[data-coupon-type=' + $val + ']').removeClass('hidden');
    }

    $('body').on('change', '.coupon-select', function () {
        couponType($(this).val());
    });

    // initialize timepicker
    if ($('input.coupon-timepicker').length !== 0) {
        $('input.coupon-timepicker').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm',
            stepMinute: 15,
            controlType: 'slider'
        });
    }

    // save coupon
    $('body').on('submit', '.form-coupon', function (e) {
        e.preventDefault();
        $('.save-coupon').attr('disabled', 'disabled').addClass('disabled');
        $('.remove-coupon').attr('disabled', 'disabled').addClass('disabled');
        let $this = $(this),
            data = $this.serialize(),
            permalink = $(':input[name=permalink]').val(),
            coupon_id = $this.closest('.lisner-coupon').data('coupon-id');
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
                $('.save-coupon').removeAttr('disabled').removeClass('disabled');
                $('.remove-coupon').removeAttr('disabled').removeClass('disabled');
                if (permalink) {
                    window.location.href = permalink;
                } else {
                    $('.lisner-coupons').html(result.html);
                    $this.closest('.lisner-coupon').find('.coupon-action').find('i').text('keyboard_arrow_down');
                    $this.closest('.lisner-coupon').find('.lisner-coupon-form').slideToggle('hidden');
                    let position = $this.closest('.lisner-coupon').offset();
                    $('html,body').animate({scrollTop: position.top}, 500);
                    couponType($this.closest('.lisner-coupon').find('select[name=_coupon_type]').val());
                }
            }
        });
    });

    // delete coupon
    $('body').on('click', '.remove-coupon', function (e) {
        e.preventDefault();
        let $this = $(this),
            data = {
                action: 'remove_coupon',
                coupon_id: $(':input[name=coupon_id]').val()
            };
        if (!window.confirm($this.data('confirm'))) {
            return false;
        }
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
                $this.closest('.lisner-coupon').remove();
                window.scrollTo(0, 0);
            }
        });
    });

    // view coupons
    $('body').on('click', '.coupon-action-view', function () {
        let $form = $(this).closest('.lisner-coupon').find('.lisner-coupon-form');
        $(this).toggleClass('active');
        $form.slideToggle('hidden');
        if ($(this).hasClass('active')) {
            $(this).children('i').text('keyboard_arrow_up');
        } else {
            $(this).children('i').text('keyboard_arrow_down');
        }
    });

    // remove uploaded image
    $('body').on('click', '.remove-image', function () {
        $(this).parent().html('').addClass('hidden');
        $(':input[name=_coupon_print]').val('');
    });

    // start coupon countdown
    if ($('.coupon-countdown').length !== 0) {
        $('div[id^=coupon-countdown]').each(function (e, i) {
            $(i).countdown($(i).data('date'), function (event) {
                var $this = $(i).html(event.strftime(''
                    + (0 !== event.offset.totalDays ? '<div class="coupon-countdown-item"><span class="days">' + event.offset.totalDays + ' <span>' + lc_data.days + '</span></span></div>' : '')
                    + '<div class="coupon-countdown-item"><span class="hours">%H <span>' + lc_data.hours + '</span></span></div>'
                    + '<div class="coupon-countdown-item"><span class="minutes">%M <span>' + lc_data.minutes + '</span></span></div>'
                    + '<div class="coupon-countdown-item"><span class="seconds">%S <span>' + lc_data.seconds + '</span></span></div>'));
            });
        });
    }

    // copy to clipboard
    if ($('.copy').length !== 0) {
        var clipboard = new ClipboardJS('.copy');
        clipboard.on('success', function (e) {
            e.clearSelection();
        });
        $('.copy').on('click', function () {
            $(this).parent().find('.coupon-description').text($(this).parent().find('.coupon-description').data('code-copied'));
        });
    }

    // print code
    $('.print-coupon').on('click', function () {
        $('#' + $(this).data('print')).printThis();
    });


    // demo styles
    $('.confirm-button, .coupon-uploader, .remove-style').on('click', function () {
        window.confirm('Disabled for the demo purposes');
    });

    // on load
    $(window).on('load', function () {
        couponType($('.coupon-select').val());
    });

});
