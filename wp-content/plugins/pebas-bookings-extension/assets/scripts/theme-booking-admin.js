/*jslint browser: true*/
/*global $, jQuery, alert, pebas_bo_data */
jQuery(document).ready(function ($) {
    "use strict";

    var processing = false;
    $(document).on('click', '.process-payment', function () {
        if (!processing) {
            processing = true;
            var $this = $(this);
            var $html = $this.text();
            if (!window.confirm($this.data('confirm'))) {
                return false;
            }
            $this.text(pebas_bo_data.processing_payment);
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    post_id: $this.data('id'),
                    amount: $this.data('amount'),
                    email: $this.data('email'),
                    action: 'process_payment'
                },
                success: function (result) {
                    if (result.success) {
                        window.location.reload();
                    }
                },
                complete: function (result) {
                    $this.text($html);
                    processing = false;
                }
            });
        }
    });

    var mass_processing = false;
    $(document).on('click', '.process-mass-payment', function () {
        if (!mass_processing) {
            mass_processing = true;
            var $this = $(this),
                $html = $this.text();
            if (!window.confirm($this.data('confirm'))) {
                return false;
            }
            $this.text(pebas_bo_data.processing_payment);
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'process_mass_payment'
                },
                success: function (result) {
                    let message = result.success ? result.success : result.error;
                    iziToast.show({
                        message: message,
                        messageColor: '#37003c',
                        position: 'bottomCenter',
                        color: result.error ? '#f54444' : '#07f0ff',
                        timeout: result.success ? 2000 : false,
                        pauseOnHover: false
                    });
                    if (result.success) {
                        window.location.reload();
                    }
                },
                complete: function () {
                    $this.text($html);
                    mass_processing = false;
                }
            });
        }
    });

});
