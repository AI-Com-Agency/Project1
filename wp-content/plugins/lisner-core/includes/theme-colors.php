<?php
$color_font         = isset( $option['color-secondary-font'] ) ? $option['color-secondary-font'] : ( ! empty( $option['color-primary-font'] ) ? $option['color-primary-font'] : '#fff' );
$color_font_primary = isset( $option['color-primary-font'] ) ? $option['color-primary-font'] : '#fff';
$hex2rgba           = 'lisner_hex2rgba';
if ( isset( $option['color-primary'] ) && ! empty( $option['color-primary'] ) ) {
	$css .= <<<CSS
body a,
.marker-icon,
.marker-icon i,
.leaflet-container .marker-cluster div span,
.alert a,
.hero-featured-taxonomy:hover,
.custom-radio .custom-control-label::before,
.custom-radio-label::before,
.custom-radio .custom-control-input:focus ~ .custom-control-label::before,
.custom-radio .custom-control-input:active ~ .custom-control-label::before,
.custom-radio .custom-control-input:checked ~ .custom-control-label::before,
.link,
.link:hover,
.submit-listing .btn.btn-primary.disabled,
.header .pbs-navbar.navbar .navbar-collapse li.menu-item-add-listing .sub-menu-alternative ul.sub-menu-wrapper li i,
.header .pbs-navbar.navbar .navbar-collapse li.menu-item-auth .sub-menu-alternative ul.sub-menu-wrapper li i,
.header .pbs-navbar.navbar .navbar-collapse li.menu-item-add-listing .sub-menu-alternative ul.sub-menu-wrapper li.menu-item-warning a,
.header .pbs-navbar.navbar .navbar-collapse li.menu-item-auth .sub-menu-alternative ul.sub-menu-wrapper li.menu-item-warning a,
.header .pbs-navbar.navbar .navbar-collapse li:not(.menu-item-auth):not(.menu-item-add-listing):hover i,
.header .pbs-navbar.navbar .navbar-collapse li > .sub-menu li:hover i,
.lisner-listing-likes .listing-likes-call.activated,
.lisner-listing-likes .listing-likes-call.active,
.more-filters-title-wrapper a:hover,
.orderby-group-item-wrapper i,
.select2-container--open .select2-dropdown--below .select2-results__option .select2-icons i,
.select2-container--open .select2-dropdown--below .select2-results__option[aria-selected=true] .select2-icons i,
.select2-container--open .select2-dropdown--below .select2-results__option--highlighted .select2-icons i,
.location-clear:hover,
.taxonomy-clear:hover,
.error404 .main-content .contact-info .info p a:hover,
.page-template-tpl-contact .main-content .contact-info .info p a:hover,
.lisner-profile-header__user-info a,
.lisner-profile-header__user-info a:hover,
.lisner-profile-content p a:hover,
.lisner-table tbody tr td.listing-actions-content ul li a.job-dashboard-action-delete,
.woocommerce-account .addresses .title .edit:hover,
.job-manager-form .link-auth,
.working-hours-day-time .working-hours-add,
.listing-package-title p.listing-package-title-user span,
.listing-package-title h2.distinctive,
.listing-package .btn-primary-bordered:not(:disabled):not(.disabled),
.listing-claim-content a,
.single-listing-meta-action.report-listing .dropdown-menu a:hover,
.single-listing-meta-action span.meta-likes,
.single-listing-main-meta .lisner-listing-meta-item a + i,
.working-hours-call,
.working-hours-call:hover,
.phone-nolink,
.phone-link,
.listing-widget-claim-listing .claim-link,
.single-post-author a:hover,
.page:not(.woocommerce-page-lisner) aside.single-listing-sidebar:not(.single-listing-sidebar-lisner):not(.single-listing-claim):not(.single-listing-report) .listing-widget a,
.widget a,
.page:not(.woocommerce-page-lisner) aside.single-listing-sidebar:not(.single-listing-sidebar-lisner):not(.single-listing-claim):not(.single-listing-report) .listing-widget a:hover,
.widget a:hover,
.page:not(.woocommerce-page-lisner) aside.single-listing-sidebar:not(.single-listing-sidebar-lisner):not(.single-listing-claim):not(.single-listing-report) .listing-widget.widget_calendar #wp-calendar tbody td a,
.widget.widget_calendar #wp-calendar tbody td a,
.page:not(.woocommerce-page-lisner) aside.single-listing-sidebar:not(.single-listing-sidebar-lisner):not(.single-listing-claim):not(.single-listing-report) .listing-widget.widget_calendar #wp-calendar tfoot #next:hover,
.widget.widget_calendar #wp-calendar tfoot #next:hover,
.page:not(.woocommerce-page-lisner) aside.single-listing-sidebar:not(.single-listing-sidebar-lisner):not(.single-listing-claim):not(.single-listing-report) .listing-widget.widget_calendar #wp-calendar tfoot #prev:hover,
.widget.widget_calendar #wp-calendar tfoot #prev:hover,
.footer .widget a:hover,
.footer .widget ul li a:hover,
.footer .widget .tagcloud a.tag-cloud-link:hover,
.footer-copyrights span,
.woocommerce .single-listing-sidebar.shop-sidebar section[class*=woocommerce_rating_filter] ul li a:hover,
.woocommerce .single-listing-sidebar.shop-sidebar section[class*=woocommerce_product_categories] ul li a:hover,
.woocommerce .single-listing-sidebar.shop-sidebar section[class*=woocommerce_recent_reviews] ul li a:hover,
.woocommerce-info a,
.woocommerce-error a,
.woocommerce-message a,
.woocommerce-single-lisner .lisner-shop div.woocommerce-product-rating .woocommerce-review-link:hover,
.woocommerce-single-lisner .lisner-shop div.product_meta span a,
.woocommerce-single-lisner .lisner-shop .woocommerce-tabs .nav-pills .nav-link.active,
.woocommerce .woocommerce-checkout-review-order #payment .place-order a,
.navbar-toggler i
{
    color: {$option['color-primary']};
}

body .header .pbs-navbar.navbar .mega-menu-container ::-webkit-scrollbar-thumb
{
    background: {$option['color-primary']};
}

.header .pbs-navbar.navbar .navbar-collapse li.menu-item-add-listing .btn,
button.btn-primary,
.btn.btn-primary,
button.btn-primary:focus,
.btn.btn-primary:focus,
button.btn-primary:active,
.btn.btn-primary:active,
button.btn-primary:not(:disabled):not(.disabled):active,
.btn.btn-primary:not(:disabled):not(.disabled):active,
button.btn-primary:hover,
.btn.btn-primary:hover,
.leaflet-container a.leaflet-popup-close-button,
.taxonomy-selected-item,
.btn-primary,
.btn-primary:not(:disabled):not(.disabled),
.lisner-map-field span i,
.input-group-append,
.input-group-append i,
.input-group-prepend,
.page-unit input[type=submit],
input[name=submit_job],
.page-unit.single-post .post-pagination ul li a,
.submit-listing .btn.btn-primary.disabled,
.how-it-works .tab-divider,
.more-filters-notification,
.wpcf7 input[type=submit],
.wpcf7 input[type=submit]:hover,
.lisner-taxonomy-field .chosen-container-single .chosen-choices .search-choice,
.lisner-taxonomy-field .chosen-container-multi .chosen-choices .search-choice,
.listing-package-wrapper-distinctive,
.listing-package .btn-primary-bordered:not(:disabled):not(.disabled):active,
.listing-package .btn-primary-bordered:not(:disabled):not(.disabled):focus,
.listing-package .btn-primary-bordered:not(:disabled):not(.disabled):hover,
.single-listing-style-2 .single-listing-main-meta-action a,
.comment-respond .form-submit .submit,
.comment-pagination ul li a:hover,
.comment-pagination ul li span:hover,
.comment-pagination ul li.active span,
aside.single-listing-sidebar:not(.single-listing-sidebar-lisner):not(.single-listing-claim):not(.single-listing-report) .listing-widget.widget_calendar #wp-calendar tbody td:hover,
.widget.widget_calendar #wp-calendar tbody td:hover,
.woocommerce a.added_to_cart,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
.woocommerce a.added_to_cart:hover,
.woocommerce #respond input#submit:hover,
.woocommerce a.button:hover,
.woocommerce button.button:hover,
.woocommerce input.button:hover,
.woocommerce .single-listing-sidebar.shop-sidebar .price_slider .ui-slider-handle,
.woocommerce .single-listing-sidebar.shop-sidebar .price_slider .ui-slider-range,
.woocommerce .single-listing-sidebar.shop-sidebar section[class*=woocommerce_product_search] form.woocommerce-product-search button[type=submit],
.woocommerce .single-listing-sidebar.shop-sidebar section[class*=woocommerce_product_search] form.woocommerce-product-search button[type=submit]:hover,
.woocommerce-single-lisner .lisner-shop div.product form.cart button,
.woocommerce table.shop_table tbody tr:not(.cart_item):not(.order_item):last-child td button.button[name=update_cart]:disabled:hover,
.woocommerce table.shop_table tbody tr:not(.cart_item):not(.order_item):last-child td button.button[name=update_cart].disabled:hover,
.woocommerce table.shop_table tbody tr:not(.cart_item):not(.order_item):last-child td button.button[name=update_cart]:disabled:focus,
.woocommerce table.shop_table tbody tr:not(.cart_item):not(.order_item):last-child td button.button[name=update_cart].disabled:focus,
.woocommerce table.shop_table tbody tr:not(.cart_item):not(.order_item):last-child td button.button[name=update_cart]:disabled:active,
.woocommerce table.shop_table tbody tr:not(.cart_item):not(.order_item):last-child td button.button[name=update_cart].disabled:active,
.woocommerce .woocommerce-checkout-review-order #payment #place_order,
.woocommerce .woocommerce-checkout-review-order #payment #place_order:hover
{
    background-color: {$option['color-primary']};
    color: {$color_font_primary};
}

.marker-icon i,
.submit-listing .btn.btn-primary.disabled,
.lisner-post-item.post-sticky,
.listing-package .btn-primary:not(:disabled):not(.disabled),
.listing-package .btn-primary:not(:disabled):not(.disabled):active,
.listing-package .btn-primary:not(:disabled):not(.disabled):focus,
.listing-package .btn-primary:not(:disabled):not(.disabled):hover,
.listing-package .btn-primary-bordered:not(:disabled):not(.disabled),
.listing-package .btn-primary-bordered:not(:disabled):not(.disabled):active,
.listing-package .btn-primary-bordered:not(:disabled):not(.disabled):focus,
.listing-package .btn-primary-bordered:not(:disabled):not(.disabled):hover,
.single-listing-style-2 .single-listing-main-meta-action a,
.comment-pagination ul li a:hover,
.comment-pagination ul li span:hover,
.comment-pagination ul li.active span,
.woocommerce a.added_to_cart,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button
{
    border-color: {$option['color-primary']};
}

.lisner-table tbody tr.is-expired td:first-child
{
    border-left-color: {$option['color-primary']};
}

CSS;

}
if ( isset( $option['color-secondary'] ) && ! empty( $option['color-secondary'] ) ) {
	$css .= <<<CSS
.single-listing-header .slick-dots li.slick-active button::before
{
    color: {$option['color-secondary']};
}

.hero-category-style-4 .hero-featured-taxonomy {
	background-color: {$hex2rgba( $option['color-secondary'], .4 )};
}

button.btn-secondary,
.btn.btn-secondary,
button.btn-secondary:focus,
.btn.btn-secondary:focus,
button.btn-secondary:active,
.btn.btn-secondary:active,
button.btn-secondary:not(:disabled):not(.disabled):active,
.btn.btn-secondary:not(:disabled):not(.disabled):active,
button.btn-secondary:hover,
.btn.btn-secondary:hover,
.pagination .page-item.active .page-link,
.header .pbs-navbar.navbar .navbar-collapse li > .menu-label,
.header .pbs-navbar.navbar .navbar-collapse li.woocommerce-cart a .count-number,
.lisner-listing-slider .slick-arrow::before,
.single-listing-header .slick-arrow::before,
.woocommerce .cart-collaterals .cart_totals div.wc-proceed-to-checkout a,
.woocommerce .cart-collaterals .cart_totals div.wc-proceed-to-checkout a:hover
{
    background-color: {$option['color-secondary']};
    color: {$color_font};
}

.doing-ajax-btn .loader.ajax-loader .circular .path {
	stroke: {$color_font};
}

.leaflet-container .marker-cluster
{
    background-image: linear-gradient({$option['color-secondary']}, #00949d);
}

.pagination .page-item.active .page-link,
.woocommerce .cart-collaterals .cart_totals div.wc-proceed-to-checkout a,
.woocommerce .cart-collaterals .cart_totals div.wc-proceed-to-checkout a:hover
{
    border-color: {$option['color-secondary']};
}

CSS;
}

echo $css;