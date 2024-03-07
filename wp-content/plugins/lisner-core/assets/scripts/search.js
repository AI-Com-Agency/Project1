/*jslint browser: true*/
/*global $, jQuery, alert, lisner_data */
jQuery(document).ready(function ($) {
    "use strict";

    var geolocated = false;
    // construct loader
    var $loader = '<div class="spinner">\n' +
        '  <div class="rect1"></div>\n' +
        '  <div class="rect2"></div>\n' +
        '  <div class="rect3"></div>\n' +
        '  <div class="rect4"></div>\n' +
        '  <div class="rect5"></div>\n' +
        '</div>';

    function loader(loaderWidth = 20, strokeWidth = 4) {
        $loader =
            <!-- Loader -->
            '<div class="loader ajax-loader">' +
            '<svg class="circular">' +
            '<circle class="path" cx="50" cy="50" r="' + loaderWidth + '" fill="none" stroke-width="' + strokeWidth + '" stroke-miterlimit="10"/>' +
            '</svg>' +
            '</div>';
        return $loader;
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, errorCallback);
        } else {
            console.log("Geolocation is not supported by this browser.");
        }
    }

    function errorCallback(error) {
        geolocated = false;
        if (error.code) {
            $('.btn-nearby.active').removeClass('active');
            $('.btn-nearby .btn-explore-parent.active').removeClass('active');
            $('input[name^="nearby_coords"]').val(''); //add radius field
            alert('Near Me will not work until you share your location.');
        }
    }

    function showPosition(position) {
        geolocated = true;
        $('input[name="nearby"]').prop('checked');
        $('input[name^="nearby_coords"]').val(position.coords.latitude + ',' + position.coords.longitude);
        //$('input[name^="nearby_coords"]').val('40.75427831881216' + ',' + '-73.9783313'); // used for testing in new york
    }

    function listingsLoader(number = 10) {
        var listings =
            '<div class="listing-load-item">' +
            '<div class="listing-load-item__figure"></div>' +
            '<div class="listing-load-item-content">' +
            '<div class="listing-load-item__top-meta">' +
            '<span class="listing-load-item__top-meta__item"></span>' +
            '<span class="listing-load-item__top-meta__item"></span>' +
            '</div>' +
            '<div class="listing-load-item__title"></div>' +
            '<div class="listing-load-item__text">' +
            '<span class="listing-load-item__text__item"></span>' +
            '<span class="listing-load-item__text__item"></span>' +
            '<span class="listing-load-item__text__item"></span>' +
            '</div>' +
            '<div class="listing-load-item__bottom-meta">' +
            '<span class="listing-load-item__bottom-meta__item"></span>' +
            '<span class="listing-load-item__bottom-meta__item"></span>' +
            '<span class="listing-load-item__bottom-meta__item"></span>' +
            '</div>' +
            '</div>' +
            '</div>';
        let i, text = '';
        for (i = 1; i <= number; i++) {
            text += listings;
        }
        return '<div class="lisner_listings-load">' + text + '</div>';
    }

    // Search Map
    var mapSearch = new L.Map('map-search');
    $('.page-header-form').remove();

    var xhr = [];
    $('.lisner_listings').on('update_results', function (event, data) {
        if (!data.loaded) {
            $('.lisner_listings').html(listingsLoader());
            if ($('.listing-wrapper').hasClass('map-active')) {
                $('.lisner_listings-load').addClass('map-active');
            }
        }
        let form = $('.form-ajax'),
            form2 = $('.search-page-form'),
            target = $(this),
            page = data.page ? data.page : 1,
            page_id = $('.container-search').data('page-id'),
            location = form2.find(':input[name^="search_location"]').val(),
            keywords = form2.find(':input[name^="search_keywords"]').val(),
            price_range = form.find(':input[name^="price_range"]').val(),
            open_now = form.find(':input[name^="open_now"]:checked').val(),
            nearby = form.find(':input[name^="nearby"]:checked').val(),
            nearby_coords = form.find(':input[name^="nearby_coords"]').val(),
            orderby = form.find(':input[name^="search_orderby"]:checked').val(),
            categories, amenities, tags,
            index = $('div.lisner_listings').index(this);
        categories = form2.find(':input[name^="search_categories"]').map(function () {
            return $(this).val();
        }).get();
        amenities = form.find(':input[name^="search_amenities"]:checked').map(function () {
            return $(this).val()
        }).get();
        tags = form.find(':input[name^="search_tags"]:checked').map(function () {
            return $(this).val();
        }).get();
        if (index < 0) {
            return;
        }

        if (xhr[index]) {
            xhr[index].abort();
        }
        var formData = {
            page: page,
            page_id: page_id,
            search_categories: categories,
            search_amenities: amenities,
            search_keywords: keywords,
            search_location: location,
            search_tags: tags,
            price_range: price_range,
            open_now: open_now,
            nearby_coords: nearby ? nearby_coords : '',
            search_orderby: orderby,
            index: index,
            action: 'get_listings',
        };
        if(geolocated) {
            formData.nearby = nearby;
        }
        xhr[index] = $.ajax({
            url: !lisner_data.is_custom_tax ? lisner_data.ajaxurl : lisner_data.ajaxurl,
            data: formData,
            method: 'post',
            success: function (result) {
                if (result.html) {
                    target.html(result.html).hide().fadeIn();
                }
                if ($('.show-map-call').find('.btn').hasClass('active')) {
                    $('.col-custom').addClass('col-custom-map');
                }

                refreshMap();
                $('.lisner_listings-load').remove();
                if (data.paginated) {
                    let position = $('.lisner_listings').offset();
                    $('html,body').stop().animate({scrollTop: position.top - 250}, 600);
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
                        window.console.log('Error 404: Ajax Endpoint cannot be reached. Go to Settings > Permalinks and save to resolve.');
                    }
                }
            }
        });
    });

    $('body').on('click', '.btn-nearby', function (e) {
        getLocation();
    });
    $('body').on('click', '.search-default-terms:not(.search-default-listing)', function (e) {
        $('.search-page-form').trigger('change');
    });
    $('body').on('change', '.form-ajax', function (e) {
        let target = $('.lisner_listings'),
            data = {loaded: false};
        $('.lisner_listings').triggerHandler('update_results', data);
        listing_store_state(target, 1);
    });
    $('body').on('change', '.search-page-form', function (e) {
        let target = $('.lisner_listings'),
            data = {loaded: false};
        setTimeout(function () {
            $('.lisner_listings').triggerHandler('update_results', data);
        }, 400);
        listing_store_state(target, 1);
    });
    $('body').on('click', '.geolocate', function (e) {
        let target = $('.lisner_listings'),
            data = {loaded: false};
        $('.location-search').val('');
        setTimeout(function () {
            $('.lisner_listings').triggerHandler('update_results', data);
        }, 600);
    });
    $('body').on('keypress', '.location-search', function (e) {
        let target = $('.lisner_listings'),
            data = {loaded: false};
        if (e.which === 13) {
            $('.lisner_listings').triggerHandler('update_results', data);
        }
        listing_store_state(target, 1);
    });
    $('body').on('click', '.custom-location-result', function (e) {
        let target = $('.lisner_listings'),
            data = {loaded: false};
        $('.lisner_listings').triggerHandler('update_results', data);
        listing_store_state(target, 1);
    });
    $('body').on('click', '.custom-category-result', function (e) {
        let target = $('.lisner_listings'),
            data = {loaded: false};
        $('.lisner_listings').triggerHandler('update_results', data);
        listing_store_state(target, 1);
    });

    // pagination
    $('body').on('click', '.listing-pagination a', function () {
        let target = $(this).closest('div.lisner_listings'),
            page = $(this).data('page'),
            data = {page: page, paginated: true};
        listing_store_state(target, page);
        $('.lisner_listings').triggerHandler('update_results', data)
    });

    var $supports_html5_history = false;
    if (window.history && window.history.pushState) {
        $supports_html5_history = true;
    }

    var location = document.location.href.split('#')[0];

    function listing_store_state(target, page) {
        if ($supports_html5_history) {
            var form = $('.form-ajax');
            var data = $(form).serialize();
            var index = $('div.lisner_listings').index(target);
            window.history.replaceState({
                id: 'listing_state',
                page: page,
                data: data,
                index: index
            }, '', location + '#l=1');
        }
    }

    function refreshMap() {
        mapSearch.off();
        mapSearch.remove();
        searchMap();
        mapSearch.scrollWheelZoom.disable();
        $('.map-preloader').remove();
    }

    function searchMap() {
        mapSearch = new L.Map('map-search');
        var mapStyle = '' !== lisner_data.mapbox_url ? lisner_data.mapbox_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            mapViewport = $(window).height(),
            headerHeight = $('header').outerHeight(),
            formHeight = $('.container-form').outerHeight(),
            wpAdmin = $('#wpadminbar').outerHeight(),
            spacing = headerHeight + formHeight + wpAdmin;
        mapSearch.addLayer(new L.TileLayer(mapStyle));
        $('.map-search').css('height', (mapViewport - spacing + 2));

        let listing = $('.listing-el'),
            cluster = L.markerClusterGroup();

        listing.each(function () {
            let $this = $(this),
                id = $this.data('id'),
                icon = $this.data('icon') ? $this.data('icon') : 'done_outline',
                image = $this.find('figure').data('figure') ? $this.find('figure').data('figure') : '',
                logo = $this.find('logo').data('logo') ? $this.find('logo').data('logo') : '',
                title = $this.find('.lisner-listing-title').html() ? $this.find('.lisner-listing-title').html() : '',
                logo_div = '' !== logo ? '<div class="lisner-popup-logo"><img src="' + logo + '"></div>' : '',
                iconHTML =
                    '<div class="marker-icon" data-id="' + id + '">' +
                    '<i class="material-icons mf">' + icon + '</i>' +
                    '</div>',
                CustomHtmlIcon = L.divIcon({
                    html: iconHTML,
                    iconSize: [40, 40], // size of the icon
                    iconAnchor: [20, 40], // point of the icon which will correspond to marker's location
                    popupAnchor: [1, 5] // point from which the popup should open relative to the iconAnchor
                }),
                LatLng = L.latLng($this.data('lat'), $this.data('lng')),
                markers = [LatLng.lat, LatLng.lng],
                marker = L.marker(markers, {
                    icon: CustomHtmlIcon
                });
            cluster.addLayer(marker, {
                width: 50,
                height: 50
            });

            let popupContent;
            popupContent = (
                '<div class="card text-center listing-card-popup">' +
                '<figure class="listing-card-popup-image">' +
                '<img src="' + image + '" /></figure>' +
                '<div class="listing-card-popup-inner">' +
                '<div class="listing-card-popup-title">' +
                '<h6>' + title + '</h6>' +
                '</div>' +
                '</div>'
            );
            marker.bindPopup(popupContent).openPopup();
            L.popup(markers);

        });

        // center marker icon on click
        mapSearch.on('popupopen', function (e) {
            let px = mapSearch.project(e.popup._latlng); // find the pixel location on the map where the popup anchor is
            px.y -= e.popup._container.clientHeight / 10; // find the height of the popup container, divide by 2, subtract from the Y axis of marker location
            mapSearch.panTo(mapSearch.unproject(px), {animate: true});
        });

        // Show whole world if there are no results
        if (listing.length) {
            mapSearch.addLayer(cluster);
            mapSearch.fitBounds(cluster.getBounds().pad(0.5));
        } else {
            if ('0' !== $('.nearby-coords').val() && $('.nearby-coords').val() && $('input[name^="nearby"]:checked')) {
                let coords = $('.nearby-coords').val().split(',');
                mapSearch.setView(coords, 12, {animation: true});
            } else {
                mapSearch.fitWorld().setZoom(2);
            }
        }

        // locate element on map
        $(document).on('click', '.search-map', function (e) {
            if (!$(e.target).hasClass('material-icons')) {
                $('.el-locate i').text('gps_not_fixed');
            }
        });
        $(document).on('click', '.el-locate', function () {
            $('.el-locate i').text('gps_not_fixed');
            var $this = $(this).closest('.craftsman-el');
            $(this).find('i').text('gps_fixed');
            mapSearch.panTo(L.latLng($this.data('lat'), $this.data('lng')), 5);
        });

        // close popup if clicked outside of it
        $(document).on('click', '.search-container, .header, .footer', function () {
            mapSearch.closePopup();
        });

    }

    $('body').on('click', '.show-map-call', function () {
        $('.listing-wrapper').toggleClass('map-active');
        $('.map-wrapper').toggleClass('hidden map-active');
        $('.col-custom').toggleClass('col-custom-map');
        setTimeout(function () {
            mapSearch.invalidateSize();
            window.dispatchEvent(new Event('resize'));
        }, 0);
    });

    // reset search
    function removeFilters() {
        $('.tax-search').val('');
        $('.location-search').val('');
        $('.s_keywords').val('');
        $('.search-categories').remove();
        $('.btn-group-toggle').removeClass('active');
        $('.chosen-single').removeClass('active');
        $('.order-filters-explore').removeClass('active');
        $('.btn-explore-parent').removeClass('active');
        $('.order-label').text($('.order-label').data('name'));
        $('.order-filters-explore').find('i').text($('.order-filters-explore').find('i').data('icon'));
        $('#price-range').prop('selectedIndex', 0).trigger('change.select2');
        $('#price-range').next().removeClass('activated');
        $('.form-group').find('input').prop('checked', false);
        $('.orderby-group').find('input').prop('checked', false);
        $('.more-filters-item').find('input').prop('checked', false);
        $('.more-filters-notification').removeClass('active').text();
        $('.lisner_listings').triggerHandler('update_results', {'loaded': false});
        $('.location-clear').removeClass('active');
        $('.taxonomy-clear').removeClass('active');
        $('.geolocate').text('location_searching');
        $('.filter-clear, .reset-taxonomies-call').addClass('hidden');
    }

    $('body').on('click', '.reset-filters-call', function () {
        removeFilters();
        $(this).removeClass('active').children().removeClass('active');
        $(this).hide();
    });

    $('body').on('change', '.form-ajax, .search-page-form', function () {
        let len = $(".more-filters-item-inner input:checked").length;
        let length = $(".btn-explore-parent input:checked").length;
        if ('' === $('.tax-search').val() && '' === $('.location-search').val() && $('#price-range').prop('selectedIndex') === 0 && $('#search-orderby').prop('selectedIndex') === 0 && len === 0 && length === 0) {
            $('.reset-filters-call').hide();
        } else {
            $('.reset-filters-call').show();
        }
    });

    $('body').on('click', '.reset-taxonomies-call', function () {
        $(this).addClass('hidden');
        $(this).parent().next().find('input').prop('checked', false);
        let len = $(".more-filters-item-inner input:checked").length;
        if (len === 0) {
            $('.more-filters-notification').removeClass('active').text();
            $('.reset-filters-call').hide();
        } else {
            $('.more-filters-notification').text(len);
        }
        $('.lisner_listings').triggerHandler('update_results', {'loaded': false});
    });

    // chosen search bg
    $('body').on('click', '.explore', function () {
        if ($('.chosen-container').hasClass('chosen-container-active')) {
            $('.container-search').addClass('doing-ajax');
        } else {
            $('.container-search').removeClass('doing-ajax');
        }
    });
    $('body').on('click', function (e) {
        let container = $('.chosen-single').parent();
        if ((!container.is(e.target) && container.has(e.target).length === 0) || !container.hasClass('chosen-with-drop')) {
            $('.container-search').removeClass('doing-ajax');
        }
    });

    $('body').on('change', '.chosen-select', function () {
        if ($(this).prop('selectedIndex') > 0) {
            $(this).next().find('.chosen-single').addClass('active');
        } else {
            $(this).next().find('.chosen-single').removeClass('active');
        }
    });

    $('body').on('click', '.btn-explore:not(.more-filters-explore)', function () {
        $(this).toggleClass('active');
    });
    $('body').on('click', '.btn-explore-parent', function () {
        $(this).parent().toggleClass('active');
    });

    // price range
    $('#price-range').on('change', function () {
        if ($(this).selectedIndex !== 0) {
            $(this).next().addClass('activated');
        } else {
            $(this).next().removeClass('activated');
        }
    });

    // more && order filters
    $('body').on('click', '.order-filters-explore', function () {
        let len = $(".order-filters-call input:checked").length;
        if ($('.orderby-group').hasClass('active')) {
            $('.orderby-group').removeClass('active');
        } else {
            $('.orderby-group').addClass('active');
            $(this).parent().addClass('active');
        }
        if (len !== 0) {
            $('.order-filters-call, .order-filters-explore').addClass('active');
        }
    });
    $('.orderby-item').on('click', function () {
        let text = $(this).find('label').text(),
            icon = $(this).closest('.orderby-group-item-wrapper').find('i').text(),
            input = $(this).children('input');
        $('.order-label').text(text);
        $('.order-filters-explore').find('i').text(icon);
        $('.orderby-group').removeClass('active');
        $('.filter-clear').removeClass('hidden');
    });

    $('body').on('click', '.more-filters-call', function () {
        let len = $(".more-filters-item-inner input:checked").length;
        if ($('.more-filters').hasClass('active')) {
            $('.more-filters').removeClass('active');
        } else {
            $('.more-filters').addClass('active');
            $(this).find('.more-filters-explore').addClass('active');
        }
        if (len !== 0) {
            $(this).find('.more-filters-explore').addClass('active');
        }
    });
    $('body').on('click', '.more-filters-close', function () {
        $('.more-filters').removeClass('active');
        $('.more-filters-call').children('.btn-explore').removeClass('active');
    });
    $('body').on('click', function (e) {
        let container = $('.more-filters'),
            filterButton = $('.more-filters-call'),
            orderCall = $('.order-filters-call'),
            orderbyGroup = $('.orderby-group');
        if (!container.is(e.target) && container.has(e.target).length === 0 && !filterButton.is(e.target) && filterButton.has(e.target).length === 0) {
            let len = $(".more-filters-item-inner input:checked").length;
            $('.more-filters').removeClass('active');
            if (len === 0) {
                filterButton.children('.btn-explore').removeClass('active');
            }
        }
        if (!orderbyGroup.is(e.target) && orderbyGroup.has(e.target).length === 0 && !orderCall.is(e.target) && orderCall.has(e.target).length === 0) {
            $('.orderby-group').removeClass('active');
            let len = $(".order-filters-call input:checked").length;
            if (0 === len) {
                $('.order-filters-explore, .order-filters-call').removeClass('active');
            }
        }
    });
    $('body').on('click', '.more-filters-item-inner input', function () {
        let len = $(".more-filters-item-inner input:checked").length,
            thisLen = $(this).closest('.more-filters-item-wrapper').find('input:checked').length;
        if (0 !== thisLen) {
            $(this).closest('.more-filters-item').find('.reset-taxonomies-call').removeClass('hidden');
        } else {
            $(this).closest('.more-filters-item').find('.reset-taxonomies-call').addClass('hidden');
        }
        if (0 !== len) {
            $('.more-filters-notification').addClass('active').text(len);
        } else {
            $('.more-filters-notification').removeClass('active').text(len);
        }
    });

    // Window resize event
    $(window).on('resize', function () {
        refreshMap();
    });

    if ($(window).width() <= 768) {
        $('.search-filters-call').on('click', function () {
            $('.form-wrapper-inner').toggleClass('active');
            if (!$(this).hasClass('active')) {
                $(this).find('i').text('remove_circle_outline');
            } else {
                $(this).find('i').text('add_circle_outline');
            }
        });
    }

    $('body').on('click', '.filter-clear', function () {
        let orderLen = $(".orderby-group input:checked").length;
        let len = $(".more-filters-item-inner input:checked").length;
        let length = $(".btn-explore-parent input:checked").length;
        if (0 !== orderLen) {
            $('.orderby-group').find('input').prop('checked', false);
            $('.order-label').text($('.order-label').data('name'));
            $('.order-filters-explore').find('i').text($('.order-filters-explore').find('i').data('icon'));
            $('.search-page-form').trigger('change');
            $('.orderby-group').removeClass('active');
            $('.order-filters-explore, .order-filters-call').removeClass('active');
        }
        $('.reset-filters-call').hide();
        $(this).addClass('hidden');
    });

    $("select#price-range").on("select2:select", function (evt) {
        if ('none' === $(this).val()) {
            $('.select2-text').text($(this).data('placeholder'));
            $(this).next('span').removeClass('activated');
            $('.reset-filters-call').hide();
        }
        $('body').find('#select2-price-range-results li:last-child').hide();

    });

    // hide map on search template 2 on small screens
    function mapDisplay() {
        if ($(window).width() <= 1030) {
            $('.container-search.search-template-2').find('.map-wrapper').removeClass('map-active').addClass('hidden');
        } else {
            $('.container-search.search-template-2').find('.map-wrapper').addClass('map-active').removeClass('hidden');
        }
    }

    mapDisplay();
    $(window).on('resize', function () {
        mapDisplay();
    });

    // On Load
    $(window).on('load', function () {
        let target = $('div.lisner_listings'),
            initial_page = 1,
            index = $('div.lisner_listings').index(target);
        //console.log(window.history.state);
        if (window.history.state && window.location.hash) {
            let state = window.history.state;
            if (state.id && 'listing_state' === state.id && index === state.index) {
                initial_page = state.page;
            }
        }
        let data = {loaded: true, page: initial_page};
        $('.lisner_listings').triggerHandler('update_results', data);

        // reset filter
        let len = $(".more-filters-item-inner input:checked").length;
        let orderLen = $(".orderby-group input:checked").length;
        let length = $(".btn-explore-parent input:checked").length;
        if ('' === $('.tax-search').val() && '' === $('.location-search').val() && $('#price-range').prop('selectedIndex') === 0 && len === 0 && length === 0 && orderLen === 0) {
            $('.reset-filters-call').hide();
        } else {
            $('.reset-filters-call').show();
        }
    });

});
document.body.addEventListener('touchstart', function () {
}, false);
