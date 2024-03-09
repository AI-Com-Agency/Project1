/*jslint browser: true*/
/*global $, jQuery, alert, lisner_data */
jQuery(document).ready(function ($) {
    "use strict";

    // add visual composer additional fields
    // radio image
    $(document).on('click', '.vc_wrapper-param-type-lisner_image_radio label', function () {
        var $this = $(this),
            $input = $this.closest('.edit_form_line').find('.lisner_radio_image');
        $('label').removeClass('checked');
        $this.addClass('checked').val();
        $input.val($this.find('input').val());
        if ('4' === $('.lisner_hiw_template').val()) {
            $('div[data-vc-shortcode-param-name=template_4_style]').show();
        } else {
            $('div[data-vc-shortcode-param-name=template_4_style]').hide();
        }
    });

    // instantiate select2 for visual composer and custom widgets
    $('.vc-select2').select2({
        width: '100%'
    });
    $('.select2-admin').select2({
        width: '100%'
    });

    var $adSwitch = $('.tbm-ad-switch');
    var $categorySwitch = $('.category-switcher');

    function categorySwitcher($selector) {
        let $this = $selector;
        $('.category-switch-item').hide();
        $('.' + $this.val()).show();
    }

    function adSwitcher($selector) {
        let $this = $selector;
        if ($this.val() === 'google_ad') {
            $this.closest('.widget-content').find('.custom_ad').hide();
            $this.closest('.widget-content').find('.google_ad').show();
        } else {
            $this.closest('.widget-content').find('.google_ad').hide();
            $this.closest('.widget-content').find('.custom_ad').show();
        }
    }

    if ((window.location.href.indexOf('widgets') > -1)) {
        $(document).ajaxSuccess(function (e, xhr, settings) {
            if (settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=lisner_widget_promo') != -1) {
                $('.select2-admin').select2({
                    width: '100%'
                });
            }
            if (settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=lisner_widget_pages') != -1) {
                $('.select2-admin').select2({
                    width: '100%'
                });
            }
            if (settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=tbm_widget_news_ads') != -1) {
                // custom news ads widget dynamic / after ajax
                $('.tbm-ad-switch').each(function () {
                    adSwitcher($(this));
                });
            }
            if (settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=lisner_widget_categories') != -1) {
                // custom news ads widget dynamic / after ajax
                $('.select2-admin').select2({
                    width: '100%'
                });
                $('.category-switcher').each(function () {
                    categorySwitcher($(this));
                });
            }

        });

        // custom news ads widget dynamic
        $adSwitch.each(function () {
            adSwitcher($(this));
        });
        $(document).on('change', '.tbm-ad-switch', function () {
            adSwitcher($(this));
        });

        //category switcher
        $categorySwitch.each(function () {
            categorySwitcher($(this));
        });
        $(document).on('change', '.category-switcher', function () {
            categorySwitcher($(this));
        });

    }

    $(':input[name^=listing-fields]').on('click', function () {
        let $this = $(this);
        if ($this.data('option-lisner') === 'lisner') {
            if (!$this.prop('checked')) {
                $this.closest('.rwmb-field').next().hide().next().hide();
            } else {
                $this.closest('.rwmb-field').next().show().next().show();
            }
        }
    });

    $(window).on('load', function () {
        $(':input[name^=listing-fields]').each(function () {
            let $this = $(this);
            if ($this.data('option-lisner') === 'lisner') {
                if (!$this.prop('checked')) {
                    $this.closest('.rwmb-field').next().hide().next().hide();
                } else {
                    $this.closest('.rwmb-field').next().show().next().show();
                }
            }
        });
    });



});
