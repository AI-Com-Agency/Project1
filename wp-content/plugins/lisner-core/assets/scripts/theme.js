/*jslint browser: true*/
/*global $, jQuery, alert, lisner_data */
jQuery(document).ready(function ($) {
    'use strict';

    // construct loader
    var $loaderWidth = 20,
        $loader =
            <!-- Loader -->
            '<div class="loader ajax-loader">' +
            '<svg class="circular">' +
            '<circle class="path" cx="50" cy="50" r="' + $loaderWidth +
            '" fill="none" stroke-width="4" stroke-miterlimit="10"/>' +
            '</svg>' +
            //'<p class="text-center">' + lisner_data.ajaxed_message + '</p>' +
            '</div>';

    function loader(loaderWidth = 20, strokeWidth = 4) {
        $loader =
            <!-- Loader -->
            '<div class="loader ajax-loader">' +
            '<svg class="circular">' +
            '<circle class="path" cx="50" cy="50" r="' + loaderWidth +
            '" fill="none" stroke-width="' + strokeWidth +
            '" stroke-miterlimit="10"/>' +
            '</svg>' +
            '</div>';
        return $loader;
    }

    var is_multi_category = lisner_data.is_multi_category,
        previewMap;

    // searching button loader and ajax
    $('body').on('click', '.btn-search', function (e) {
        $(this).text('');
        $(this).addClass('doing-ajax doing-ajax-btn');
        $(this).append(loader(8, 2));
    });

    // menu search etc
    $('body').on('click', '.mobile-search-call', function () {
        $('#main-menu').removeClass('show');
        $(this).toggleClass('active');
        $(this).next().toggleClass('active');
        if ($(this).hasClass('active')) {
            $(this).find('i').text('close');
        } else {
            $(this).find('i').text('search');
        }
    });
    $('body').on('click', '.navbar-toggler', function () {
        $('.mobile-search-call').find('i').text('search').removeClass('active');
        $('.menu-search-inner').removeClass('active');
    });
    $('body').on('click', function (e) {
        let container = $('.menu-search-inner, .mobile-search-call'),
            locations = $('.location-search').closest('.form-group'),
            customLocations = $('.custom-location-search').closest('.form-group');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            if (container.hasClass('active')) {
                $('.mobile-search-call').find('i').text('search').removeClass('active');
                $('.menu-search-inner').removeClass('active');
            }
        }
        if (!locations.is(e.target) && locations.has(e.target).length === 0) {
            $('.input-group-results').addClass('hidden');
        }
        if ($('.custom-location-search').length !== 0) {
            if (!customLocations.is(e.target) &&
                customLocations.has(e.target).length === 0) {
                $('.input-group-results').hide();
            }
        }
    });

    // activate chosen instead of default select
    $('select.chosen-select').on('chosen:ready', function (e, param) {
        $('.select-wrapper::before').css('display', 'none');
    });
    $('select[name^=listing_]').chosen();
    $('select[name^=job_category]').chosen();
    $('select.chosen-select, select.country_select').chosen({
        disable_search_threshold: 'yes' === $(this).data('disable-search')
            ? 100
            : 10,
        allowClear: 'yes' === $(this).data('clear') ? false : true,
    });
    if (lisner_data.is_rtl) {
        $('.chosen-container').addClass('chosen-rtl');
    }

    function iformat(icon) {
        var originalOption = icon.element,
            icon1 = undefined !== $(originalOption).data('icon')
                ? '<i class="material-icons mf">' + $(originalOption).data('icon') +
                '</i>'
                : '',
            icon2 = undefined !== $(originalOption).data('icon-2')
                ? '<i class="material-icons mf">' +
                $(originalOption).data('icon-2') + '</i>'
                : '',
            icon3 = undefined !== $(originalOption).data('icon-3')
                ? '<i class="material-icons mf">' +
                $(originalOption).data('icon-3') + '</i>'
                : '',
            icon4 = undefined !== $(originalOption).data('icon-4')
                ? '<i class="material-icons mf">' +
                $(originalOption).data('icon-4') + '</i>'
                : '',
            icon5 = undefined !== $(originalOption).data('icon-5')
                ? '<i class="material-icons mf">' +
                $(originalOption).data('icon-5') + '</i>'
                : '';
        return '<span class="select2-icons">' + icon1 + icon2 + icon3 + icon4 +
            icon5 + '</span><span class="select2-text">' + icon.text + '</span>';
    }

    $('select.select2-select').select2({
        templateSelection: iformat,
        templateResult: iformat,
        escapeMarkup: function (text) {
            return text;
        },
        width: '100%',
        minimumResultsForSearch: -1,
    });

    // working hours frontend listing submit functionality
    $(document).on('click', '.working-hours-day a', function () {
        let $this = $(this),
            $day = $this.data('working-day');
        $('.working-hours-day a').removeClass('active');
        $this.addClass('active');
        $('.working-days-form').addClass('hidden');
        $('.working-days-form-' + $day).removeClass('hidden');
    });
    $(document).on('click', '.working-hours-radio', function () {
        let $this = $(this),
            $day = $this.data('working-radio');
        if ($this.val() === 'custom') {
            $('.working-hours-day-time-' + $day).removeClass('hidden');
        } else {
            $('.working-hours-day-time-' + $day).addClass('hidden');
        }
    });
    $('body').on('click', '.working-hours-add', function () {
        let $this = $(this),
            $day = $this.data('hours-add'),
            $template = $('.working-hours-template-' + $day + '.hidden').clone(),
            $templatesCount = $('.working-hours-template-' + $day).length,
            $count = $templatesCount;
        $template.removeClass('hidden').find('input').removeAttr('disabled');
        $template.find('input').each(function () {
            $(this).attr('name', $(this).attr('name').replace('{#}', $count++));
        });
        $this.closest('.working-hours-fields').after($template);
        timePicker();
    });
    $('body').on('click', '.working-hours-remove', function () {
        let $this = $(this);
        $this.closest('.working-hours-template').remove();
    });

    function timePicker() {
        let timeformat = 'am_pm' === lisner_data.time_format ? 'h:mm p' : 'HH:mm';
        // call timepicker
        $('input.timepicker').timepicker({
            timeFormat: timeformat,
            startTime: '06',
            interval: '15',
            dropdown: true,
            scrollbar: true,
        });
    }

    // Instantiate timepicker
    timePicker();

    // Promo video && single listing ajax load
    $('body').on('click', '.ajax-icon', function () {
        let $this = $(this),
            data = {
                action: 'lisner_get_embed',
                url: $this.siblings('input').val(),
            };
        $this.append(loader(10, 2)).find('i').css('opacity', '0');
        $.post(lisner_data.lisner_ajaxurl, data, function (r) {
            $this.find('i').css('opacity', '1');
            $this.find('.loader').remove();
            $('.video-preview').addClass('video-preview-loaded').html(r.data);
        });
    });
    $('body').on('click', '.video-call', function () {
        let $this = $(this),
            data = {
                action: 'lisner_get_embed',
                url: $this.data('video'),
            };
        $this.parent().append(loader(20, 4)).addClass('doing-ajax');
        $this.find('i').remove();
        $.post(lisner_data.lisner_ajaxurl, data, function (r) {
            $this.parent().removeClass('doing-ajax').find('.loader').remove();
            $this.remove();
            $('.video-preview').addClass('video-preview-loaded').html(r.data);
        });
    });
    $('body').on('click', '.hiw-video-overlay', function () {
        let $this = $(this),
            data = {
                action: 'lisner_get_embed',
                url: $this.data('video'),
            };
        $this.parent().append(loader(20, 4)).addClass('doing-ajax');
        $.post(lisner_data.lisner_ajaxurl, data, function (r) {
            $this.parent().removeClass('doing-ajax').find('.loader').remove();
            $this.remove();
            $('.video-preview').addClass('video-preview-loaded').html(r.data);
        });
    });

    // Lisner submit listing map and location
    var map,
        marker;
    if ($('.lisner-map-instance').length !== 0) {
        var loc = $('#job_location'),
            lat = $('#location_lat').val(),
            long = $('#location_long').val();

        loc.on('change', function () {
            setTimeout(function () {
                $('#job_location').val(loc.val());
            });
            updateCoords(loc.val());
        });

        map = new L.Map('lisner-map-instance', {
            zoom: 16,
            center: new L.latLng([lat, long]),
        });
        let mapStyle = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        map.addLayer(new L.TileLayer(mapStyle)); //base layer
        marker = L.marker([lat, long],
            {
                draggable: true,
            },
        ).addTo(map);

        marker.on('moveend', function (ev) {
            updateAddress(marker.getLatLng());
            setTimeout(function () {
                var curPos = marker.getLatLng();
                if ($('#lisner-map-instance').length) {
                    $('#location_lat').val(curPos.lat);
                    $('#location_long').val(curPos.lng);
                }
            }, 300);
            $('.geolocate-submit').text('gps_not_fixed');
        });

        var input = document.getElementById('job_location');

        var options = {};
        if (lisner_data.country_restriction) {
            options.componentRestrictions = {country: lisner_data.country_restriction};
        }
        var searchBox = new google.maps.places.Autocomplete(input, options);

        searchBox.addListener('place_changed', function () {
            var place = searchBox.getPlace();

            if (place.length === 0) {
                return;
            }

            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            var newLatLng = new L.LatLng(latitude, longitude);
            marker.setLatLng(newLatLng);
            map.panTo(new L.LatLng(latitude, longitude), 18);
        });

    }

    function updateAddress(coords) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': coords},
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        if (results[0].formatted_address != null) {
                            var address = results[0].formatted_address;
                        }
                        if ($('#job_location').length !== 0) {
                            $('#job_location').val(address);
                            $('#location').val(loc.val());
                        }
                    }
                }
            });
    }

    function updateCoords(address) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address},
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var location = results[0].geometry.location;
                        var lat = location.lat;
                        var lng = location.lng;

                        if ($('#job_location').length !== 0) {
                            $('#location_lat').val(lat);
                            $('#location_long').val(lng);
                        }
                    }
                }
            });
    }

    function updateMapLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(updateMapPosition);
        } else {
            console.log('Geolocation is not supported by this browser.');
        }
    }

    function updateMapPosition(position) {
        let latlng = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
        };
        if ($('#job_location').length !== 0) {
            updateAddress(latlng);
            $('#location_lat').val(latlng.lat);
            $('#location_long').val(latlng.lng);
            marker.setLatLng(latlng);
            map.panTo(new L.LatLng(latlng.lat, latlng.lng), 18);
        }
    }

    $('body').on('click', '.geolocate-submit', function () {
        if ('gps_not_fixed' === $(this).text()) {
            updateMapLocation();
            $(this).text('gps_fixed');
        }
    });

    //todo move this to review plugin
    $('.comment-review .material-icons').hover(
        function () {
            var pos = $(this).index();
            var $parent = $(this).parents('.bottom-ratings');
            for (var i = 0; i <= pos; i++) {
                $parent.find('.material-icons:eq(' + i + ')').text('star');
            }
        },
        function () {
            $(this).parents('.bottom-ratings').find('.material-icons').each(function () {
                if (!$(this).hasClass('clicked')) {
                    $(this).text('star_border');
                }
            });
        },
    );

    $('.comment-review .material-icons').on('click', function () {
        var value = $(this).index();
        var $parent = $(this).parents('.bottom-ratings');
        $(this).parents('.comment-review').find('input[name*="review"]').val(value + 1);
        $(this).parents('.comment-review').find('.rating-current').text(value + 1);
        $parent.find('.material-icons').removeClass('clicked');
        for (var i = 0; i <= value; i++) {
            $parent.find('.material-icons:eq(' + i + ')').text('star').addClass('clicked');
        }
    });

    $(document).on('click', '.ajax-like', function () {
        let $this = $(this),
            value = $this.next('span').text(),
            data = {
                action: 'comment_like',
                like: $this.data('like'),
                ip: $this.closest('.comment-action').find('.client-ip').val(),
                id: $this.closest('.comment-action').find('.comment-id').val(),
            };
        $.post(lisner_data.lisner_ajaxurl, data, function (r) {
            if (false === r.data.error) {
                $this.addClass('active');
                $this.next('span').text(parseInt(value) + 1);
            }
            iziToast.show({
                message: r.data.notice,
                messageColor: '#37003c',
                position: 'bottomCenter',
                color: r.data.error ? '#f54444' : '#07f0ff',
                timeout: 2000,
                pauseOnHover: false,
            });
        });
    });
    //todo move upper part to review plugin

    // login user
    $(document).on('submit', '.ajax-auth', function (e) {
        e.preventDefault();
        let $this = $(this),
            btnText = $this.find('button').text(),
            data = $this.serialize() + '&action=' +
                $this.find('button[type=submit]').attr('name');
        if (lisner_data.is_demo && $this.closest('#modal-register').length !== 0) {
            $this.html(
                '<div class="alert alert-success mb-0">Registration has been disabled for demo purposes!</div>');
            return false;
        }
        $this.find('.form-group').removeClass('error').find('.error-desc').remove();
        $this.find('.alert').remove();
        $this.find('button').text('').append(loader(8, 2));
        $.post(lisner_data.lisner_ajaxurl, data, function (r) {
            $this.find('button').text(btnText);
            if (r.error) {
                if (r.error['data-error-username']) {
                    $('.form-group[data-error-username]').addClass('error').append('<p class="error-desc">' + r.error['data-error-username'] +
                        '</p>');
                }
                if (r.error['data-error-password']) {
                    $('.form-group[data-error-password]').addClass('error').append('<p class="error-desc">' + r.error['data-error-password'] +
                        '</p>');
                }
                if (r.error['data-error-wp']) {
                    $('.btn-submit-group').append('<div class="alert alert-danger">' +
                        r.error['data-error-wp'] + '</div>');
                }
            } else if (r.success) {
                $this.html('<div class="alert alert-success mb-0">' +
                    r.success['data-success'] + '</div>');
                window.location.href = r.redirect;
            }
        });
    });

    $('.btn-label').on('click', function () {
        $('.ajax-auth').find('.alert').remove();
    });

    /**
     * Debounce function for keyup events
     *
     * @param func
     * @param wait
     * @param immediate
     * @returns {Function}
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // update main search results on typing
    $('.tax-search').on('keyup', debounce(function () {
        if ($(this).val().length !== 0) {
            $('#s_keywords').val($(this).val());
            $('.main-search-results').triggerHandler('live_search', {proceed: true});
            $('.input-group-wrapper').find('.loader').remove();
            $(this).closest('.input-group-wrapper').addClass('doing-ajax doing-ajax-btn');
            $(this).closest('.input-group-wrapper').append(loader(8, 2));
            $('.search-categories').remove();
        } else {
            $('#s_keywords').val('');
            $('.search-categories').remove();
            $('.main-search-results').html('');
            $('.taxonomy-clear').removeClass('active');
        }
    }, 450));

    $('body').on('click', '.taxonomy-clear', function () {
        $('.tax-search').val('');
        $('#s_keywords').val('');
        if ($('.custom-category-search').length !== 0) {
            $('.custom-category-search').val('');
        }
        $('.search-categories').remove();
        $(this).removeClass('active');
        $('.input-group-default-search').removeClass('active').html('');
        $(this).closest('form').trigger('change');
    });

    $('body').on('click', '.location-clear', function () {
        $('.location-search').val('');
        $('.custom-location-search').val('');
        $('.geolocate').text('location_searching');
        $(this).removeClass('active');
        $(this).trigger('change');
        if ($('.location-search').length !== 0) {
            $('.input-group-results ul').html('');
        } else {
            $('.custom-location-result').show();
        }
    });

    // main search functionality
    $('.main-search-results').on('live_search', function (e, data) {
        let val = $('.tax-search').val(),
            wrapper = $(this);
        $('.input-group-default-search').remove();
        $('.taxonomy-clear').removeClass('active');
        $.ajax({
            url: lisner_data.lisner_ajaxurl,
            data: {
                action: 'get_keyword',
                search_keywords: val,
            },
            success: function (result) {
                $('.input-group-wrapper').find('.loader').remove();
                $('.input-group-wrapper').removeClass('doing-ajax doing-ajax-btn');
                $('.taxonomy-clear').addClass('active');
                if (!$('.input-group-default-search').length) {
                    wrapper.append(
                        $('<div class="input-group-default input-group-default-search"><ul class="list-unstyled"></ul></div>'));
                }
                if (!result.no_results) {
                    $.each(result, function (key, value) {
                        if ('taxonomy' === value.type) {
                            let iconImg = value.icon ? value.icon : 'menu';
                            let icon = '<i class="search-default-terms-icon material-icons mf">' +
                                iconImg + '</i>';
                            $('.input-group-default-search ul').prepend($('<li data-term-id="' + value.term_id +
                                '" data-keyword="' + val + '" data-cat-name="' +
                                value.name +
                                '" class="search-default-terms search-default-terms-searched">' +
                                icon + value.custom_name + '</li>'));
                        } else if ('keyword' === value.type) {
                            $('.input-group-default-search ul').prepend($('<li data-term-id="0" data-keyword="' + val +
                                '" data-cat-name="' + value.name +
                                '" class="search-default-terms search-default-terms-searched search-default-keyword">' +
                                value.custom_name + '</li>'));
                        } else if ('keyword_alt' === value.type) {
                            if (value.suggestion) {
                                $('.input-group-default-search ul').prepend($('<li data-term-id="0" data-keyword="' +
                                    value.suggestion + '" data-cat-name="' + value.name +
                                    '" class="search-default-terms search-default-terms-searched search-default-keyword">' +
                                    value.custom_suggestion + '</li>'));
                            }
                        } else {
                            let image = '' !== value.image && 'listing' === value.type
                                ? '<span class="search-default-listing-image"><img class="img-fluid" src="' +
                                value.image + '"/></span>'
                                : ('' !== value.image && 'product' === value.type
                                    ? value.image
                                    : '');
                            let city = value.city
                                ? '<span class="search-default-listing-wrapper"><span class="search-default-listing-city">' +
                                value.city + '</span>'
                                : '';
                            let price = value.price
                                ? '<span class="search-default-listing-wrapper"><span class="search-default-listing-city">' +
                                value.price + '</span>'
                                : '';
                            if (value.ID) {
                                $('.input-group-default-search ul').append(
                                    $('<li data-term-id="' + value.ID + '" data-keyword="' +
                                        val + '" data-cat-name="' + value.name +
                                        '" class="search-default-terms search-default-terms-searched search-default-listing">' +
                                        '<a href="' + value.guid + '">' +
                                        image +
                                        city +
                                        price +
                                        '<span class="search-default-listing-name">' +
                                        value.name + '</span></span>' +
                                        '</a></li>'));
                            }
                        }
                    });
                } else {
                    $('.input-group-default-search ul').append($('<li class="search-default no-results">' +
                        result.no_results + '</li>'));
                }
                $('.input-group-default').removeClass('active');
                $('.input-group-default-search').addClass('active');
            },
            error: function (jqXHR, textStatus, error) {
                if (window.console && 'abort' !== textStatus) {
                    window.console.log(textStatus + ': ' + error);
                }
            },
            statusCode: {
                404: function () {
                    if (window.console) {
                        window.console.log(
                            'There has been error with rest not being reachable, try again');
                    }
                },
            },
        });
    });

    $('body').on('click', function (e) {
        let container = $('#search-form');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('.input-group-default-search').removeClass('active');
        }
    });

    /**
     * Add main search keyword values on click
     *
     * @param keyword
     */
    function addSearchKeywordValue(keyword) {
        let keywords = $('#s_keywords');

        // add keyword to field value
        if (keyword !== keywords.val()) {
            keywords.val(keyword);
        }

    }

    function addSearchTaxonomyValue(term_id) {
        let taxonomy = $('.search-categories');

        // add term_id to field value
        if (term_id !== taxonomy.val()) {
            taxonomy.val(term_id);
        }

    }

    function clearTaxonomySearch() {
        $('.tax-search').val();
        $('#s_keywords').val();
        $('.search-categories').remove();
        $('.taxonomy-select').html();
    }

    $(document).on('click', '.tax-search', function () {
        $('.input-group-default-search').addClass('active');
    });

    $('.taxonomy-select').on('add_search_term', function (e, data) {
        clearTaxonomySearch();
        addSearchTaxonomyValue(data.term_id);
        let tax = $('.tax-search').val(),
            space = $('.tax-search').data('space');
        $('.tax-search').val(data.keyword + ' ' + space + ' ' + data.cat_name);
        $('#s_keywords').after(
            '<input type="hidden" name="search_categories[]" class="search-categories" value="' +
            data.term_id + '" />');
    });

    $('.taxonomy-select').on('add_keyword_term', function (e, data) {
        clearTaxonomySearch();
        addSearchKeywordValue(data.keyword);
        let tax = $('.tax-search').val(),
            space = $('.tax-search').data('space');
        $('.tax-search').val(data.keyword);
    });

    $('body').on('click', '.search-default-terms:not(.search-default-listing)',
        function (e) {
            let $this = $(this),
                keyword = $this.find('.searched-terms-keyword').data('keyword'),
                term_id = $this.data('term-id'),
                cat_name = $this.data('cat-name'),
                data = {keyword, term_id, cat_name};
            if ($this.hasClass('search-default-keyword') && keyword ===
                $('#s_keywords').val()) {
                $('.input-group-default-search').removeClass('active');
            } else if ($this.hasClass('search-default-keyword')) {
                $('.taxonomy-select').triggerHandler('add_keyword_term', data);
                $('.main-search-results').triggerHandler('live_search', {proceed: true});
                $('.input-group-wrapper').find('.loader').remove();
                $('.hero-search-field:first-child .input-group-wrapper').addClass('doing-ajax doing-ajax-btn');
                $('.hero-search-field:first-child .input-group-wrapper').append(loader(8, 2));
            } else {
                $('.taxonomy-select').triggerHandler('add_search_term', data);
            }

            $this.closest('.active').removeClass('active');
        });

    $('.location-search').on('click', function () {
        $('.input-group-default').removeClass('active');
        $('.input-group-results').removeClass('hidden');
    });
    $('.tax-search').on('click', function () {
        $('#location-results').html('');
    });

    // location search && google autocomplete
    function locationAutocomplete() {
        var input = document.querySelector('.location-search'),
            results = document.querySelector('.location-results');

        var getSuggestions = function (text, callback) {
            if (text === '') return callback([]);
            var service = new google.maps.places.AutocompleteService(),
                request = {};
            request.input = text;
            request.types = ['(cities)'];
            if (lisner_data.country_restriction) {
                request.componentRestrictions = {country: lisner_data.country_restriction};
            }
            service.getPlacePredictions(request, callback);
        };

        if (input) {
            input.addEventListener('keyup', function (e) {
                e && e.preventDefault();
                $('.location-clear').addClass('active');
                getSuggestions(input.value, function (predictions) {
                    if (predictions === null || predictions.length === 0) {
                        results.innerHTML = '<li class="no-results">' +
                            lisner_data.location_no_results + '</li>';
                        return;
                    }
                    // console.clear();
                    // console.log(JSON.stringify(predictions, null, 2));
                    results.innerHTML = predictions.map(function (p) {
                        return {
                            description: p.description,
                            place_id: p.place_id,
                            terms: p.terms,
                            formatting: p.structured_formatting,
                            city: p.structured_formatting.main_text,
                            country: p.structured_formatting.secondary_text,
                        };
                    }).map(function (city) {
                        let data_place = 'city' === lisner_data.autocomplete_format
                            ? city.city
                            : city.description;
                        let result = city.description;
                        let res = result.replace(new RegExp(input.value, 'ig'),
                            '<span>' + input.value + '</span>');
                        return '<li data-place="' + data_place +
                            '" class="location-result"><i class="material-icons">place</i>' +
                            '<span>' + res + '</span></li>';
                    }).join('');
                });
            });
        }

    }

    if ($('.location-search').length !== 0) {
        locationAutocomplete();
    }

    $('body').on('click', function () {
        if (!$('.location-result').hasClass('no-results')) {
            $('#location-results').html('');
        }
    });

    $(document).on('click', '.location-result', function (e) {
        let val = $(this).data('place');
        if (!$(this).hasClass('no-results')) {
            $('.location-search').val(val);
            $('#location-results').html('');
            $('.input-group-results ul').html('');
        }
        if ('my_location' === $('i.geolocate').text()) {
            $('i.geolocate').text('location_searching');
        }
    });

    // location search
    $('body').on('click', '.custom-location-result', function () {
        let $this = $(this),
            place = $this.data('place');
        $('.custom-location-search').val(place);
        $('.input-group-results').hide();
    });
    $('body').on('click', '.custom-location-search', function () {
        $('.input-group-results').show();
    });

    function matchLocation(el) {
        let $this = el,
            text = $this.val().toLowerCase();
        $('.custom-location-result').each(function (i, val) {
            let place = $(val).data('place'),
                check = place.toLowerCase().indexOf(text);
            $(val).hide();
            if (check > -1) {
                $(val).show();
            }
        });
    }

    $('body').on('keyup', '.custom-location-search', function () {
        matchLocation($(this));
    });
    $('body').on('click', '.custom-location-search', function () {
        matchLocation($(this));
    });

    // category search
    $('body').on('click', '.custom-category-result', function () {
        let $this = $(this),
            id = $this.data('id'),
            place = $this.data('place');
        $('.search-categories').remove();
        $('.custom-category-search').val(place);
        $('.main-search-results').hide();
        $('.search-categories').val($this.data('id'));
        $('.main-search-results').before(
            '<input type="hidden" name="search_categories[]" class="search-categories" value="' +
            id + '" />');
    });
    $('body').on('click', '.custom-category-search', function () {
        $('.main-search-results').show();
    });

    function matchCategory(el) {
        let $this = el,
            text = $this.val().toLowerCase();
        if ('' === $this.val()) {
            $('.search-categories').val('');
        }
        $('.custom-category-result').each(function (i, val) {
            let place = $(val).data('place'),
                check = place.toLowerCase().indexOf(text);
            $(val).hide();
            if (check > -1) {
                $(val).show();
            }
        });
    }

    $('body').on('keyup', '.custom-category-search', function () {
        if ($(this).val().length === 0) {
            $('.search-categories').remove();
        }
        matchCategory($(this));
    });
    $('body').on('click', '.custom-category-search', function () {
        matchCategory($(this));
    });

    // main search functionality
    //@todo do this through php in future version and just use jquery to change to city name
    //@todo think about MaxMind DB Cities too
    $(document).on('geolocate', function (e, loaded) {
        let provider = lisner_data.geolocation_provider,
            ipapi_key = lisner_data.ipapi_api,
            user_ip = lisner_data.user_ip,
            url = ipapi_key ? 'https://api.ipapi.com/' + user_ip + '?access_key=' +
                ipapi_key : 'https://ipapi.co/json';
        if ('geoip' === provider) {
            url = 'https://json.geoiplookup.io/' + user_ip;
        } else if ('ipinfo' === provider) {
            url = 'https://ipinfo.io/' + user_ip + '/json';
        }
        $.ajax({
            url: url,
            dataType: 'json',
            success: function (result) {
                if ('loaded' === loaded) {
                    $('.city-name-geo').addClass('geolocated').text(result.city);
                } else {
                    $('.location-search').val(result.city);
                }
            },
            error: function (jqXHR, textStatus, error) {
                if (window.console && 'abort' !== textStatus) {
                    window.console.log(textStatus + ': ' + error);
                }
            },
            statusCode: {
                404: function () {
                    if (window.console) {
                        window.console.log(
                            'There has been error with ajax not being reachable, try again');
                    }
                },
            },
        });
    });

    $('body').on('click', '.geolocate', function () {
        if ('my_location' !== $(this).text()) {
            $(this).text('my_location');
            $(document).triggerHandler('geolocate', 'clicked');
            $('.location-clear').addClass('active');
        } else {
            $(this).text('location_searching');
            $('.location-clear').removeClass('active');
            $('.geolocate').text('location_searching');
            $('.location-search').val('');
        }
    });

    // listing quick preview functionality
    $(document).on('listing_preview', function (e, id) {
        let mapZoom = lisner_data.listing_map_zoom;
        $.ajax({
            url: lisner_data.lisner_ajaxurl,
            data: {
                action: 'get_listing_preview',
                id: id,
            },
            success: function (result) {
                $('body').addClass('preview-loaded');
                removeLoader();
                $('body').after(result.html);
                preview_map(result.coords.lat, result.coords.long, {zoom: mapZoom});
                setTimeout(function () {
                    previewMap.invalidateSize();
                    window.dispatchEvent(new Event('resize'));
                    $('.map-preview').removeClass('map-preview-loading');
                }, 300);
            },
            error: function (jqXHR, textStatus, error) {
                if (window.console && 'abort' !== textStatus) {
                    window.console.log(textStatus + ': ' + error);
                }
            },
            statusCode: {
                404: function () {
                    if (window.console) {
                        window.console.log(
                            'There has been error with ajax not being reachable, try again');
                    }
                },
            },
        });
    });

    function removeLoader() {
        $('body').removeClass('doing-ajax');
        $('.loader').remove();
    }

    $('body').on('click', '.listing-preview-call', function () {
        let id = $(this).parent().data('listing-id');
        $(document).triggerHandler('listing_preview', id);
        $('body').addClass('doing-ajax').prepend(loader(10, 2));
    });

    $(document).on('click', function (e) {
        let container = $('.lisner-listing-preview-wrapper');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('.lisner-listing-preview-modal').remove();
            $('body').removeClass('preview-loaded');
        }
    });
    $(document).on('click', '.listing-preview-modal-close', function () {
        $('.lisner-listing-preview-modal').remove();
        $('body').removeClass('preview-loaded');
    });

    /**
     * Instantiate preview map
     *
     * @param lat
     * @param long
     * @param options
     * @param marker
     */
    function preview_map(lat, long, options = '', marker = '') {
        let mapStyle = '' !== lisner_data.mapbox_url
            ? lisner_data.mapbox_url
            : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            defaults;
        defaults = {
            zoom: 18,
            center: new L.latLng([lat, long]),
            dragging: $(window).width() >= 475,
        };
        defaults = $.extend({}, defaults, options);
        previewMap = new L.Map('map-preview', defaults);
        previewMap.addLayer(new L.TileLayer(mapStyle)); //base layer
        let icon = '' !== marker
            ? L.marker([lat, long], marker).addTo(previewMap)
            : L.marker([lat, long]).addTo(previewMap);
    }

    // Listing likes count functionality
    $(document).on('listing_likes', function (e, data) {
        $.ajax({
            url: lisner_data.lisner_ajaxurl,
            data: {
                action: 'update_listing_likes',
                id: data.id,
                ip: lisner_data.user_ip,
            },
            success: function (result) {
                data.el.next().text(result.likes_count);
            },
            error: function (jqXHR, textStatus, error) {
                if (window.console && 'abort' !== textStatus) {
                    window.console.log(textStatus + ': ' + error);
                }
            },
            statusCode: {
                404: function () {
                    if (window.console) {
                        window.console.log(
                            'There has been error with ajax not being reachable, try again');
                    }
                },
            },
        });
    });
    $('body').on('click', '.listing-likes-call', function () {
        let $this = $(this),
            $count = $this.next().text();
        if ($(this).hasClass('active') || $(this).hasClass('activated')) {
            $(this).removeClass('active');
            $(this).removeClass('activated');
            $this.next().text(parseInt($count) - 1);
        } else {
            $(this).addClass('active');
            $this.next().text(parseInt($count) + 1);
        }
        if (lisner_data.is_single_listing) {
            $count = $this.find('span').text();
            if ($(this).hasClass('active') || $(this).hasClass('activated')) {
                $(this).find('i').text('favorite');
                $this.find('span').text(parseInt($count) + 1);
            } else {
                $(this).find('i').text('favorite_border');
                $this.find('span').text(parseInt($count) - 1);
            }
        }
        $(document).triggerHandler('listing_likes',
            {el: $this, id: $this.data('listing-id')});
    });

    // initialize tooltips
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body',
    });

    // taxonomy label fix
    $('body').on('click', '.lisner-taxonomy-label', function () {
        $(this).parent().find('input[type=text]').focus();
    });

    //@todo fix input[type=file] title when not logged in
    if (!lisner_data.is_logged || lisner_data.is_single_listing) {
        $('.lisner-input-file').on('change', function (e) {
            let input = $(this),
                label = input.prev().find('.file-upload-files'),
                title = label.data('title'),
                labelVal = label.innerHTML;

            label = label.length ? label : input.closest('.input-group').find('.file-upload-files');
            var fileName = '';
            if (this.files && this.files.length > 1) {
                fileName = (this.getAttribute('data-multiple-caption') || '').replace(
                    '{count}', this.files.length);
            } else {
                fileName = e.target.value.split('\\').pop();
            }

            if (fileName) {
                label.html(fileName);
            } else {
                label.html(title);
            }
        });
    }

    // single page animate scroll
    $('body').on('click', '.animate', function (e) {
        e.preventDefault();
        let position = $($(this).attr('href')).offset();
        $('html,body').stop().animate({scrollTop: position.top - 120}, 500);
    });

    // single page gallery
    if ($('.lisner-gallery').length !== 0) {
        $('.lisner-gallery').magnificPopup({
            delegate: 'a',
            type: 'image',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1], // Will preload 0 - before current, and 1 after the current image
            },
        });
    }

    // single listing page working times
    $('body').on('click', '.working-hours-call', function () {
        let open = $(this).data('icon-open'),
            close = $(this).data('icon-close');
        $('.working-time-all').slideToggle('medium', function () {
            $('.working-time-all').toggleClass('active');
            if ($(this).is(':visible')) {
                $(this).css('display', 'flex');
            }
        });
        if ($('.working-time-all').hasClass('active')) {
            $(this).text(open);
        } else {
            $(this).text(close);
        }
    });

    // single page map instance
    if ( ( lisner_data.is_listing || $('.map-preview').length !== 0 ) && lisner_data.show_address ) {
        let marker,
            markerHtml;
        if ('' !== $('.map-preview').data('marker') && undefined !==
            $('.map-preview').data('marker')) {
            marker = new L.Icon({iconUrl: $('.map-preview').data('marker')});
            markerHtml = {icon: marker};
        } else {
            markerHtml = '';
        }
        let zoom = '' !== $('.map-preview').data('zoom') ? $('.map-preview').data('zoom') : 18;
        preview_map($('.map-preview').data('lat'), $('.map-preview').data('long'), {
            zoom: zoom,
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
        }, markerHtml);
    }

    // contact listing form
    $('.form-contact-listing').on('submit', function (e) {
        let $this = $(this),
            $btn = $this.find('button').html();
        e.preventDefault();
        $(this).find('button').html(loader(10, 3));
        $.post({
            url: lisner_data.lisner_ajaxurl,
            data: $(this).serialize(),
            method: 'post',
            success: function (result) {
                $('.success, .error').remove();
                if (result.error) {
                    result.message = result.error;
                }
                if (result.success) {
                    result.message = result.success;
                    $this.closest('section').remove();
                }
                $this.find('button').html($btn);
                iziToast.show({
                    message: result.message,
                    messageColor: '#37003c',
                    position: 'bottomCenter',
                    color: result.error ? '#f54444' : '#07f0ff',
                    timeout: 2000,
                    pauseOnHover: false,
                });

                options = {
                    listing_id: $this.find(':input[name^=listing_id]').val(),
                    user_id: $this.find(':input[name^=author_id]').val(),
                    nonce: $this.find(':input[name^=_contact_listing]').val(),
                };
                $('body').triggerHandler('lead_call', options);
            },
            error: function (jqXHR, textStatus, error) {
                if (window.console && 'abort' !== textStatus) {
                    window.console.log(textStatus + ': ' + error);
                }
            },
        });
    });

    // chose edit or submit listing form
    $('body').on('click', '.submit-action-call', function () {
        $(this).next().trigger('click');
    });

    // faq page specifics
    $('#faqAccordion').on('hide.bs.collapse', function () {
        $('.card-header').children('i').text('add_circle_outline');
    });
    $('#faqAccordion').on('shown.bs.collapse', function () {
        $('.collapse.show').parent('.card').find('.card-header i').text('remove_circle_outline');
    });

    // mailchimp subscription
    $('.newsletter-form').on('submit', function (e) {
        let $this = $(this),
            $btn = $this.find('button').html();
        $(this).find('button').html(loader(10, 3));
        $.post({
            url: lisner_data.lisner_ajaxurl,
            data: $(this).serialize(),
            method: 'post',
            success: function (result) {
                iziToast.show({
                    title: result.title,
                    message: result.text,
                    messageColor: '#37003c',
                    position: 'bottomCenter',
                    color: 'error' === result.type ? '#f54444' : '#07f0ff',
                    timeout: false,
                    pauseOnHover: false,
                });
                // remove loader
                $this.find('button').html($btn);
            },
            error: function (jqXHR, textStatus, error) {
                if (window.console && 'abort' !== textStatus) {
                    window.console.log(textStatus + ': ' + error);
                }
            },
        });
        return false;
    });

    // maintenance mode
    if ($('#clock').length !== 0) {
        $('#clock').countdown($('#clock').data('date'), function (event) {
            var $this = $(this).html(event.strftime(''
                + '<span class="days">' + event.offset.totalDays + ' <span>' +
                lisner_data.days + '</span></span>'
                + '<span class="hours">%H <span>' + lisner_data.hours +
                '</span></span>'
                + '<span class="minutes">%M <span>' + lisner_data.minutes +
                '</span></span>'
                + '<span class="seconds">%S <span>' + lisner_data.seconds +
                '</span></span>'));
        });
    }

    // taxonomy slider
    if ($('.lisner-taxonomy-slick').length !== 0) {
        $('.lisner-taxonomy-slick').on('init', function (slick) {
            $(this).css('visibility', 'visible');
        }).slick({
            centerMode: true,
            arrows: false,
            slidesToShow: 5,
            dots: true,
            responsive: [
                {
                    breakpoint: 770,
                    settings: {
                        centerMode: true,
                        slidesToShow: 2,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        centerMode: true,
                        slidesToShow: 1,
                    },
                },
            ],
        });
    }

    // listings slider
    if ($('.lisner-listings-slick').length !== 0) {
        $('.lisner-listings-slick').on('init', function (slick) {
            $(this).fadeIn();
        }).slick({
            arrows: true,
            slidesToShow: 4,
            dots: true,
            variableWidth: true,
            responsive: [],
        });
    }

    // disable menu auth on desktop
    if ($(window).width() > 1024) {
        $('li.menu-item-auth').find('> a.nav-link').on('click', function (e) {
            e.preventDefault();
        });
    }

    // display full phone number
    $('.phone-link').on('click', function () {
        let chars = $(this).data('number');
        $(this).text($(this).text().slice(0, -3) + chars);
    });

    // hide reviews section on listing on mobiles
    function hideReviewsMobile() {
        if ($(window).width() < 960 && !$('.listing-show-more-call').hasClass('active')) {
            $('.single-listing-reviews, .single-listing-comments-form').hide();
            $('.listing-show-more').show();
        } else {
            $('.single-listing-reviews, .single-listing-comments-form').show();
            $('.listing-show-more').hide();
        }
    }

    hideReviewsMobile();

    $('.listing-show-more-call').on('click', function (e) {
        e.preventDefault();
        let $this = $(this),
            less = $this.data('show-less'),
            more = $this.data('show-more');
        $this.toggleClass('active');
        if ($this.hasClass('active')) {
            $('.single-listing-reviews, .single-listing-comments-form').show();
            $this.children('span').text(less);
            $this.children('i').text('keyboard_arrow_up');
        } else {
            $('.single-listing-reviews, .single-listing-comments-form').hide();
            $this.children('span').text(more);
            $this.children('i').text('keyboard_arrow_down');
        }
    });

    if (lisner_data.is_demo) {
        $('.wp-social-login-provider').on('click', function (e) {
            e.preventDefault();
            iziToast.show({
                message: 'Social login has been disabled for demo purposes.',
                messageColor: '#37003c',
                position: 'bottomCenter',
                color: '#07f0ff',
                timeout: 3000,
                pauseOnHover: false,
            });
        });
    }

    $('.page-lisner-add-listing').on('click', '.job-manager-error', function () {
        $(this).remove();
    });

    // modal close button
    if ($(window).width() < 470) {
        $('.modal-close').show();
    }

    // hide submit listing button on click
    $('input[name=submit_job]').on('click', function () {
        $(this).css('color', lisner_data.color_primary); //todo readjust this in future versions
    });

    // Lisner Statistics
    $('body').on('lead_call', function (event, option) {
        let $this = $(this),
            data = {
                action: 'listing_lead_call',
                listing_id: option.listing_id,
                user_id: option.user_id,
                lead_nonce: option.nonce,
            };
        $.post(lisner_data.lisner_ajaxurl, data, function (result) {
        });
    });
    $('body').on('ctr_call', function (event, option) {
        let $this = $(this),
            data = {
                action: 'listing_ctr_call',
                listing_id: option.listing_id,
                user_id: option.user_id,
                ctr_nonce: option.nonce,
            };
        $.post(lisner_data.lisner_ajaxurl, data, function (result) {
        });
    });

    if (lisner_data.stats_ctr) {
        $('body').on('click',
            'h4.lisner-listing-title, .figure-call, .listing-preview-call',
            function () {
                let data = {
                    listing_id: $(this).closest('.lisner-listing-item').data('listing-id'),
                    user_id: $(this).closest('.lisner-listing-item').data('author-id'),
                    nonce: $(this).closest('.lisner-listing-item').data('nonce'),
                };
                $('body').triggerHandler('ctr_call', data);
            });
    }

    $('body').on('review_call', function (event, option) {
        let $this = $(this),
            data = {
                action: 'listing_review_call',
                listing_id: option.listing_id,
            };
        $.post(lisner_data.lisner_ajaxurl, data, function (result) {
        });
    });

    // lisner profile navigation
    function profileNavScroll() {
        let $window = $(window).outerHeight(),
            adminBar = $('#wpadminbar').outerHeight(),
            header = $('.header').outerHeight(),
            menu = $('.lisner-profile-header').outerHeight(),
            nav = $('.lisner-profile-nav').outerHeight();

        if (($window - (header + menu)) < nav) {
            $('.lisner-profile-nav ul').css('height', ($window - (header + menu + 50)));
            $('.lisner-profile-nav ul').css('overflow-y', 'auto');
        } else {
            $('.lisner-profile-nav ul').css('height', 'auto');
        }
    }

    profileNavScroll();

    function show_chart($data) {
        new Morris.Line({
            element: 'user-chart',
            data: $data.data,
            xkey: ['day'],
            ykeys: ['focus', 'ctr', 'view', 'lead', 'review'],
            labels: ['Focus', 'CTR', 'View', 'Lead', 'Review'],
            xLabels: 'week',
            parseTime: false,
        });

    }

    $('body').on('display_chart', function (event, stat) {
        $('.profile-charts').html('');
        $('.profile-charts').removeClass('loaded');
        let $this = $(this),
            data = {
                action: 'listing_show_chart',
                duration: stat ? stat.duration : 'weekly',
                clicked: stat ? stat.clicked : false,
                listing_id: $('.profile-charts').data('listing-id'),
            };
        $.post(lisner_data.lisner_ajaxurl, data, function (result) {
            if (data.clicked) {
                $('.stat-switch-call').removeClass('active');
                $('.stat-switch-call[data-stat=' + data.duration + ']').addClass('active');
            }
            show_chart(result.data);
            $('.stat-title').text(result.data.result);
            $('.profile-charts').addClass('loaded');
        });
    });

    $('.stat-switch-call').on('click', function () {
        let $this = $(this),
            stat = $this.data('stat'),
            data = {
                duration: stat,
                clicked: true,
            };
        $('body').triggerHandler('display_chart', data);
    });

    // trigger reviews click when clicking on its label
    $('.file-upload-label').on('click', function () {
        $(this).prev('input').trigger('click');
    });

    // on resize
    $(window).on('resize', function () {
        hideReviewsMobile();
        profileNavScroll();
    });

    // on load
    $(window).on('load', function () {
        $('#modal-reset-password').modal('show');
        $('#modal-notification').modal('show');
        if (lisner_data.is_home || lisner_data.is_home_template) {
            $(document).triggerHandler('geolocate', 'loaded');
        }
        if (lisner_data.is_profile && $('.profile-charts').length !== 0) {
            $('body').triggerHandler('display_chart');
        }
    });

});
document.body.addEventListener('touchstart', function () {
}, false);

if (jQuery(window).width() > 768) {
    var tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    var player;

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            videoId: jQuery('#hero-video').data('video'),
            suggestedQuality: 'large',
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange,
            },
            playerVars: {
                controls: 0,
                disablekb: 1,
                modestbranding: 1,
                iv_load_policy: 3,
                rel: 0,
                start: jQuery('#hero-video').data('start') ? parseInt(
                    jQuery('#hero-video').data('start')) : 0,
                end: jQuery('#hero-video').data('end') ? parseInt(
                    jQuery('#hero-video').data('end')) : 0,
            },
        });
    }

    function onPlayerReady(event) {
        player.mute();
        event.target.playVideo();
    }

    var done = false;

    function onPlayerStateChange(event) {
        if (jQuery('.single-job_listing').length != 0) {
            if (event.data === 0) {
                event.target.playVideo();
            }
            if (event.data === 3 || event.data === -1) {
                jQuery('.single-listing-header-video-loader').fadeIn();
            }
            if (event.data === 1) {
                jQuery('.single-listing-header-video-loader').fadeOut();
            }
        } else {
            if ('1' == jQuery('#hero-video').data('loop')) {
                if (event.data === 0) {
                    event.target.playVideo();
                }
            } else {
                if (event.data === 0) {
                    jQuery('#player').css('opacity', '0');
                }
            }
        }
        if (event.data === 3 || event.data === -1) {
            jQuery('#player').css('opacity', '0');
        }
        if (event.data === 1) {
            jQuery('#player').css('opacity', '1');
        }
    }

    function stopVideo() {
        player.stopVideo();
        player.remove();
    }

}

