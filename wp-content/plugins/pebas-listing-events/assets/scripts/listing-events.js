/*jslint browser: true*/
/*global $, jQuery, alert, le_data */
jQuery(document).ready(function ($) {
    'use strict';

    function loader(loaderWidth = 20, strokeWidth = 4) {
        let $loader =
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

    // initialize timepicker
    if ($('input.event-timepicker').length !== 0) {
        $('input.event-timepicker').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm',
            stepMinute: 15,
            controlType: 'slider',
        });
    }

    // save event
    $('body').on('submit', '.form-event', function (e) {
        e.preventDefault();
        $('.save-event').attr('disabled', 'disabled').addClass('disabled');
        $('.remove-event').attr('disabled', 'disabled').addClass('disabled');
        let $this = $(this),
            data = $this.serialize(),
            permalink = $(':input[name=permalink]').val(),
            event_id = $this.closest('.lisner-coupons').data('event-id');
        $.post(le_data.lisner_ajaxurl, data, function (result) {
            result.message = result.success ? result.success : result.error;
            iziToast.show({
                message: result.message,
                messageColor: '#37003c',
                position: 'bottomCenter',
                color: result.error ? '#f54444' : '#07f0ff',
                timeout: result.success ? 2000 : 5000,
                pauseOnHover: false,
                displayMode: 2,
            });
            if (result.error) {
                $('.save-event').removeAttr('disabled').removeClass('disabled');
                $('.remove-event').removeAttr('disabled').removeClass('disabled');
            }
            if (result.success) {
                $this.find('i').text(result.icon);
                $('.save-event').removeAttr('disabled').removeClass('disabled');
                $('.remove-event').removeAttr('disabled').removeClass('disabled');
                if (permalink) {
                    window.location.href = permalink;
                } else {
                    $('.lisner-coupons').html(result.html);
                    $this.closest('.lisner-coupon').find('.coupon-action').find('i').text('keyboard_arrow_down');
                    $this.closest('.lisner-coupon').find('.lisner-coupon-form').slideToggle('hidden');
                    let position = $this.closest('.lisner-coupon').offset();
                    $('html,body').animate({scrollTop: position.top}, 500);
                }
                createMaps();
            }
        });
    });

    // delete event
    $('body').on('click', '.remove-event', function (e) {
        e.preventDefault();
        let $this = $(this),
            data = {
                action: 'remove_event',
                event_id: $(':input[name=event_id]').val(),
            };
        if (!window.confirm($this.data('confirm'))) {
            return false;
        }
        $.post(le_data.lisner_ajaxurl, data, function (result) {
            result.message = result.success ? result.success : result.error;
            iziToast.show({
                message: result.message,
                messageColor: '#37003c',
                position: 'bottomCenter',
                color: result.error ? '#f54444' : '#07f0ff',
                timeout: result.success ? 2000 : false,
                pauseOnHover: false,
            });
            if (result.success) {
                $this.find('i').text(result.icon);
                $this.closest('.lisner-coupon').remove();
                window.scrollTo(0, 0);
            }
        });
    });

    // remove uploaded image
    $('body').on('click', '.remove-image', function () {
        $(this).parent().html('').addClass('hidden');
        $(':input[name=_event_image]').val('');
    });

    // Lisner submit listing map and location
    function createMaps() {
        $('.event-map-instance').each(function (i, e) {
            var map,
                marker;
            let id = $(e).attr('id');
            var loc = $(e).prev('.input-group').find('input[name=_event_address]'),
                lat_input = $(e).prev('.input-group').find('input[name=location_lat]'),
                long_input = $(e).prev('.input-group').find('input[name=location_long]'),
                lat = $(e).prev('.input-group').find('input[name=location_lat]').val(),
                long = $(e).prev('.input-group').find('input[name=location_long]').val();

            loc.on('change', function () {
                setTimeout(function () {
                    loc.val(loc.val());
                });
                updateCoords(loc.val(), lat_input, long_input);
            });

            map = new L.Map(id, {
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
                updateAddress(marker.getLatLng(), loc);
                setTimeout(function () {
                    var curPos = marker.getLatLng();
                    if (id.length) {
                        lat_input.val(curPos.lat);
                        long_input.val(curPos.lng);
                    }
                }, 300);
                $('.geolocate-submit').text('gps_not_fixed');
            });

            var input = jQuery(this).closest('.event-map').find('.form-control')[0];

            var options = {};
            if (le_data.country_restriction) {
                options.componentRestrictions = {country: le_data.country_restriction};
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

            function updateAddress(coords, input) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': coords},
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                if (results[0].formatted_address != null) {
                                    var address = results[0].formatted_address;
                                }
                                loc.val(address);
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

                                lat_input.val(lat);
                                long_input.val(lng);
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
                updateAddress(latlng);
                lat_input.val(latlng.lat);
                long_input.val(latlng.lng);
                marker.setLatLng(latlng);
                map.panTo(new L.LatLng(latlng.lat, latlng.lng), 18);
            }

            $('.geolocate-submit').on('click', function () {
                if ('gps_not_fixed' === $(this).text()) {
                    updateMapLocation();
                    $(this).text('gps_fixed');
                }
            });

        });

    }

    createMaps();

    $('body').on('click', '.event-action-view', function () {
        let $form = $(this).closest('.lisner-coupon').find('.lisner-coupon-form');
        $(this).toggleClass('active');
        $form.slideToggle('hidden');
        if ($(this).hasClass('active')) {
            $(this).children('i').text('keyboard_arrow_up');
            setTimeout(function () {
                var resizeEvent = window.document.createEvent('UIEvents');
                resizeEvent.initUIEvent('resize', true, false, window, 0);
                window.dispatchEvent(resizeEvent);
            }, 0);
        } else {
            $(this).children('i').text('keyboard_arrow_down');
        }
    });

    // demo styles
    $('.confirm-button, .coupon-uploader, .remove-style').on('click', function () {
        window.confirm('Disabled for the demo purposes');
    });

    // Listing likes count functionality
    $(document).on('event_attending', function (e, data) {
        $.ajax({
            url: le_data.lisner_ajaxurl,
            data: {
                action: 'update_event_attendees',
                id: data.id,
                ip: le_data.user_ip,
            },
            success: function (result) {
                data.el.find('.going-count').text(result.attendees_count);
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
    $('body').on('click', '.attendees-call', function () {
        let $this = $(this),
            $count = $this.find('.going-count').text();
        if (!$(this).hasClass('active')) {
            $this.find('.event-call-text').text(le_data.event_not_going);
            $this.find('.going-count').text(parseInt($count) + 1);
            $(this).addClass('active');
        } else {
            $this.find('.event-call-text').text(le_data.event_going);
            $this.find('.going-count').text(parseInt($count) - 1);
            $(this).removeClass('active');
        }
        $(document).triggerHandler('event_attending',
            {el: $this, id: $this.data('event-id')});
    });

    // on load
    $(window).on('load', function () {
    });

});
