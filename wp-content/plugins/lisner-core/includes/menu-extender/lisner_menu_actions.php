<?php

/**
 * Class lisner_menu_actions
 *
 * @author pebas
 * @version 1.0.0
 *
 */

class lisner_menu_actions {

	protected static $_instance = null;

	/**
	 * @return null|lisner_menu_actions
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_menu_actions constructor.
	 */
	public function __construct() {
		$can_register = get_option( 'users_can_register' );
		if ( $can_register ) {
			add_action( 'wp_footer', array( $this, 'menu_auth_modal' ) );
			add_filter( 'wp_nav_menu_items', array( $this, 'menu_item_auth' ), 10, 2 );
			add_filter( 'wp_nav_menu_items', array( $this, 'menu_item_authorized' ), 10, 2 );
		}
		add_filter( 'wp_nav_menu_items', array( $this, 'menu_item_add_listing_button' ), 10, 2 );

		// add WooCommerce cart
		$option    = get_option( 'pbs_option' );
		$show_cart = isset( $option['general-hide-cart-icon'] ) ? $option['general-hide-cart-icon'] : 1;
		if ( $show_cart ) {
			add_filter( 'wp_nav_menu_items', array( $this, 'woocommerce_menu_item' ), 10, 3 );
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'woocommerce_add_to_cart_fragments' ) );
		}
	}

	/**
	 * @param $items
	 * @param $args
	 * @param bool $ajax
	 *
	 * @return string
	 */
	public function woocommerce_menu_item( $items, $args, $ajax = false ) {
		// Top Navigation Area Only
		if ( ( isset( $ajax ) && $ajax ) || ( property_exists( $args, 'theme_location' ) && $args->theme_location === 'top_menu' ) ) {
			// WooCommerce
			if ( lisner_helper::is_plugin_active( 'woocommerce' ) ) {
				$css_class = 'menu-item menu-item-type-cart woocommerce-cart';
				// Is this the cart page?
				if ( is_cart() ) {
					$css_class .= ' current-menu-item';
				}
				$items .= '<li class="' . esc_attr( $css_class ) . '">';
				$items .= '<a class="cart-contents" href="' . esc_url( wc_get_cart_url() ) . '">';
				$items .= wp_kses_data( WC()->cart->get_cart_total() ) . '<span class="count"><i class="material-icons mf">' . esc_attr( 'shopping_basket' ) . '</i><span class="count-number">' . wp_kses_data( WC()->cart->get_cart_contents_count() ) . '</span></span>';
				$items .= '</a>';
				$items .= '</li>';
			}
		}

		return apply_filters( 'lisner_menu_link_shop', $items );
	}

	public function woocommerce_add_to_cart_fragments( $fragments ) {
		// Add our fragment
		$fragments['li.woocommerce-cart'] = $this->woocommerce_menu_item( '', new stdClass(), true );

		return $fragments;
	}

	/**
	 * Add Sign Up to menu items
	 *
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public static function menu_item_auth( $items, $args ) {
		$modal_auth = lisner_helper::is_plugin_active( 'pebas-report-listings' ) && is_singular( 'job_listing' ) ? 'report' : 'auth';
		$auth       = '<li class="menu-item menu-item-auth"><a href="javascript:" class="nav-link" data-toggle="modal" data-target="#modal-' . esc_attr( $modal_auth ) . '">';
		$auth       .= '<i class="nav-icon material-icons">' . esc_html( 'person_outline' ) . '</i>';
		$auth       .= esc_html__( 'Sign Up', 'lisner-core' );
		$auth       .= '</a></li>';

		if ( ! is_user_logged_in() ) {
			$items = $items . $auth;
		}

		return $items;
	}

	/**
	 * Add User menu options to navigation
	 * when user is logged in
	 *
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public static function menu_item_authorized( $items, $args ) {
		// get user avatar
		$avatar = get_avatar( get_current_user_id(), '34', '', '', array( 'class' => 'rounded-circle' ) );

		// submenu pages permalinks
		$my_account   = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		$my_account   = rtrim( $my_account, '/' );
		$all_listings = trailingslashit( $my_account ) . wp_basename( get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) ) );

		$submenu = '<div class="sub-menu sub-menu-alternative"><ul class="sub-menu-wrapper">';
		$submenu .= '<li class="menu-item"><i class="material-icons mf">' . esc_html( 'person_outline' ) . '</i><a href="' . esc_url( $my_account ) . '" class="nav-link">' . esc_html__( 'My Account', 'lisner-core' ) . '</a></li>';
		$submenu .= '<li class="menu-item"><i class="material-icons mf">' . esc_html( 'format_list_bulleted' ) . '</i><a href="' . esc_url( $all_listings ) . '" class="nav-link">' . esc_html__( 'My Listings', 'lisner-core' ) . '</a></li>';
		$submenu .= '<li class="menu-item menu-item-warning menu-item-logout"><a href="' . wp_logout_url( home_url() ) . '" class="nav-link">' . esc_html__( 'Logout', 'lisner-core' ) . '<i class="material-icons mf">' . esc_html( 'exit_to_app' ) . '</i></a></li>';
		$submenu .= '</ul></div>';

		$auth = '<li class="menu-item menu-item-auth dropdown">';
		$auth .= '<a href="' . esc_url( $my_account ) . '" class="nav-link">';
		$auth .= $avatar;
		$auth .= '</a>';
		$auth .= $submenu;
		$auth .= '</li>';

		if ( is_user_logged_in() ) {
			$items = $items . $auth;
		}

		return $items;
	}

	/**
	 * add Add Listing Button to menu item
	 *
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public static function menu_item_add_listing_button( $items, $args ) {
		$option                           = get_option( 'pbs_option' );
		$requires_account                 = get_option( 'job_manager_user_requires_account' );
		$create_account_during_submission = get_option( 'job_manager_enable_registration' );
		$add_listing_link                 = get_permalink( get_option( 'job_manager_submit_job_form_page_id' ) );
		if ( ! $requires_account || $create_account_during_submission || is_user_logged_in() ) {
			$link = "href='{$add_listing_link}'";
		} else {
			$link = 'href="javascript:" data-toggle="modal" data-target="#modal-auth"';
		}
		$add_listing = '<li class="menu-item menu-item-add-listing">';
		$add_listing .= '<a class="nav-link btn btn-primary" ' . $link . '>';
		$add_listing .= '<i class="nav-icon material-icons">' . esc_html( 'add_circle_outline' ) . '</i>';
		$add_listing .= esc_html__( 'Add Listing', 'lisner-core' );
		$add_listing .= '</a></li>';

		$disable_add_listing_button = isset( $option['general-disable-add-listing-button'] ) ? $option['general-disable-add-listing-button'] : false;
		if ( ! $disable_add_listing_button ) {
			$items = $items . $add_listing;
		}

		return $items;
	}

	/**
	 * Add menu authorization modal
	 *
	 * @param array $args
	 */
	public static function menu_auth_modal( $args = array() ) {
		$requires_account = get_option( 'job_manager_user_requires_account' );
		$add_listing_link = get_option( 'job_manager_submit_job_form_page_id' );
		if ( lisner_helper::is_plugin_active( 'woocommerce' ) ) {
			$add_listing_link = wc_get_page_id( 'myaccount' );
		}
		if ( $requires_account ) {
			$args = array( 'redirect' => $add_listing_link );
			if ( lisner_helper::is_plugin_active( 'pebas-claim-listings' ) ) {
				$claim_page = get_option( 'job_manager_claim_listing_page_id' );
				if ( is_page( $claim_page ) ) {
					$args['redirect'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

				}
			}
		}
		if ( ! is_user_logged_in() ) {
			if ( is_singular( 'job_listing' ) && lisner_helper::is_plugin_active( 'pebas-report-listings' ) ) {
			} else {
				include lisner_helper::get_template_part( 'modal-login', 'modals/', $args );
			}
			include lisner_helper::get_template_part( 'modal-register', 'modals/', $args );
			include lisner_helper::get_template_part( 'modal-lost-password', 'modals/', $args );
		}
	}

}

/**
 * Instantiate the class
 *
 * @return lisner_menu_actions|null
 */
function lisner_menu_actions() {
	return lisner_menu_actions::instance();
}
