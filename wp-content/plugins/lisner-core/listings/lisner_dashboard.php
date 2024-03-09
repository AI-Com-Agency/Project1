<?php

/**
 * Class lisner_dashboard
 *
 * @author pebas
 * @ver 1.0.1
 */

class lisner_dashboard {

	protected static $_instance = null;

	public $pages = array();

	/**
	 * @return null|lisner_dashboard
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {

		// add actions
		add_action( 'wp', array( $this, 'shortcode_action_handler' ), 1 );
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_action( 'after_setup_theme', array( $this, 'insert_pages' ) );
		add_action( 'rwmb_page_dashboard_after_save_field', array( $this, 'set_dashboard_page_template' ), 10, 5 );

		// add filters
		add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
		add_filter( 'template_include', array( $this, 'load_dashboard_page_template' ) );

		add_filter( 'job_manager_job_dashboard_columns', function () {
			$new_columns = array(
				'thumbnail'     => __( 'Image', 'lisner-core' ),
				'listing_title' => __( 'Title', 'lisner-core' ),
				'date'          => __( 'Posted', 'lisner-core' ),
				'expires'       => __( 'Expires', 'lisner-core' ),
				//'featured'      => __( 'Featured', 'lisner-core' ),
			);

			return $new_columns;
		} );
		add_filter( 'job_manager_get_dashboard_jobs_args', function ( $args ) {
			global $wp;
			$args['offset'] = ( max( 1, wp_basename( $wp->request ) ) - 1 ) * $args['posts_per_page'];

			return $args;
		} );

		// additional functionality
		add_filter( 'ajax_query_attachments_args', array( $this, 'restrict_media_library' ) );
		add_action( 'init', array( $this, 'allow_file_uploads' ) );
		add_action( 'woocommerce_edit_account_form', array( $this, 'logo_field' ) );
		add_action( 'woocommerce_save_account_details', array( $this, 'logo_save' ), 12, 1 );

	}

	public function shortcode_action_handler() {
		global $post;
		$job_dashboard = new WP_Job_Manager_Shortcodes();

		if ( is_page() && ( get_option( 'woocommerce_myaccount_page_id' ) == $post->ID ) ) {
			$job_dashboard->job_dashboard_handler();
		}
	}

	/**
	 * Logo address field
	 * -------------------------------
	 *
	 */
	public function logo_field() {
		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );
		$logo    = get_user_meta( $user->ID, '_listing_logo', true );
		$logo    = isset( $logo ) ? $logo : '';
		?>
        <fieldset class="lisner-wc-fields">
            <div class="coupon-image-wrapper">
                <legend for="coupon_print-logo"><?php esc_html_e( 'Company Details', 'lisner-core' ); ?></legend>
                <label for="_listing_logo"><?php esc_html_e( 'Company Logo', 'lisner-core' ); ?></label>
                <div class="coupon-print-image <?php echo empty( $logo ) ? esc_attr( 'hidden' ) : ''; ?>">
					<?php if ( ! empty( $logo ) ) : ?>
						<?php $image = wp_get_attachment_image_src( $logo, 'full' ); ?>
						<?php if ( pbs_is_demo() ) : ?>
                            <span class="remove-style material-icons mf"><?php echo esc_html( 'delete' ); ?></span>
						<?php else: ?>
                            <span class="remove-image material-icons mf"><?php echo esc_html( 'delete' ); ?></span>
						<?php endif; ?>
                        <img src="<?php echo esc_url( $image[0] ); ?>">
					<?php endif; ?>
                </div>
				<?php if ( pbs_is_demo() ) : ?>
                    <a href="javascript:"
                       class="lisner-image"><?php esc_html_e( 'Upload Logo', 'lisner-core' ); ?></a>
				<?php else: ?>
                    <a href="javascript:"
                       class="lisner-image-uploader"><?php esc_html_e( 'Upload Logo', 'lisner-core' ); ?></a>
				<?php endif; ?>
                <input type="hidden" id="coupon_print-logo"
                       class="form-control lisner-image-uploader"
                       name="_listing_logo"
                       value="<?php echo esc_attr( get_user_meta( $user->ID, '_listing_logo', true ) ); ?>">
            </div>
        </fieldset>
		<?php
	}

	/*
	 * Logo saving
	 * ----------------------------
	 *
	 */
	public function logo_save( $user_id ) {
		if ( isset( $_POST['_listing_logo'] ) ) {
			update_user_meta( $user_id, '_listing_logo', sanitize_text_field( $_POST['_listing_logo'] ) );
		}
	}

	/**
	 * Allow file uploads for the given roles
	 */
	public function allow_file_uploads() {
		if ( ! class_exists( 'Pebas_Coupons' ) ) {
			if ( lisner_helper::role_exists( 'subscriber' ) ) {
				$contributor = get_role( 'subscriber' );
				$contributor->add_cap( 'upload_files' );
			}
			if ( lisner_helper::role_exists( 'author' ) ) {
				$author = get_role( 'author' );
				$author->add_cap( 'upload_files' );
			}

			if ( lisner_helper::role_exists( 'employer' ) ) {
				$subscriber = get_role( 'employer' );
				$subscriber->add_cap( 'upload_files' );
			}
		}
	}

	/**
	 * Restrict media library access to images that the
	 * current user has uploaded
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	function restrict_media_library( $query ) {
		if ( ! class_exists( 'Pebas_Coupons' ) ) {
			$user_id = get_current_user_id();
			if ( $user_id ) {
				$query['author'] = $user_id;
			}
		}

		return $query;
	}

	/**
	 * Add page endpoints
	 *
	 * @param $page
	 */
	public function add_page( $page ) {
		$this->pages[ $page['endpoint'] ] = $page;
	}

	/**
	 * Register new endpoint to use inside My Account page.
	 */
	public function add_endpoints() {
		foreach ( $this->pages as $page ) {
			add_rewrite_endpoint( $page['endpoint'], EP_ROOT | EP_PAGES );
			add_action( "woocommerce_account_{$page['endpoint']}_endpoint", function () use ( $page ) {
				require_once $page['template'];
			} );
		}
	}

	/**
	 * Add new query var.
	 */
	public function add_query_vars( $vars ) {
		return array_merge( $vars, array_column( $this->pages, 'endpoint', 'endpoint' ) );
	}

	/**
	 * Insert the new endpoint into the My Account menu.
	 */
	public function new_menu_items( $items ) {
		// Remove the logout menu item.
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

		// Insert custom endpoints.
		$items += array_column( array_filter( $this->pages, function ( $page ) {
			return $page['show_in_menu'];
		} ), 'title', 'endpoint' );

		// Insert back the logout item.
		$items['customer-logout'] = $logout;

		// Sort items.
		foreach ( $items as $item_key => $item ) {
			if ( in_array( $item_key, array_keys( $this->pages ) ) ) {
				$items[ $item_key ] = $this->pages[ $item_key ];
			}

			if ( $item_key == 'dashboard' ) {
				$items['dashboard'] = array(
					'title' => __( 'My Account', 'lisner-core' ),
					'order' => 1,
				);
			}
		}

		$items = $this->sortByProp( $items, 'order' );


		foreach ( $items as $item_key => $item ) {
			if ( is_array( $item ) && ! empty( $item['title'] ) ) {
				$items[ $item_key ] = $item['title'];
			}
		}

		return $items;
	}

	public function sortByProp( $array, $propName, $reverse = false ) {
		$sorted = array();
		foreach ( $array as $itemKey => $item ) {
			if ( ! is_array( $item ) ) {
				$item = array( 'title' => $item, 'order' => 25, 'endpoint' => $itemKey );
			}

			if ( ! isset( $item[ $propName ] ) ) {
				$item[ $propName ] = 25;
			}

			if ( ! isset( $item['endpoint'] ) ) {
				$item['endpoint'] = $itemKey;
			}

			$sorted[ $item[ $propName ] ][] = $item;
		}

		$reverse ? krsort( $sorted ) : ksort( $sorted );

		$result = array();
		foreach ( $sorted as $subArray ) {
			foreach ( $subArray as $item ) {
				$result[ $item['endpoint'] ] = $item;
			}
		}

		return $result;
	}

	/**
	 * Insert necessary pages
	 */
	public function insert_pages() {
		$this->add_page( array(
			'endpoint'     => 'all-listings',
			'title'        => __( 'My Listings', 'lisner-core' ),
			'template'     => LISNER_DIR . 'templates/dashboard/user-listings.php',
			'show_in_menu' => true,
			'order'        => 2,
		) );
		if ( lisner_helper::is_plugin_active( 'pebas-paid-listings' ) ) {
			$this->add_page( array(
				'endpoint'     => 'packages', //todo: make this optional in the theme options
				'title'        => __( 'Packages', 'lisner-core' ),
				'template'     => LISNER_DIR . 'templates/dashboard/user-packages.php',
				'show_in_menu' => true,
				'order'        => 3,
			) );
		}
		if ( lisner_helper::is_plugin_active( 'pebas-bookmark-listings' ) ) {
			$this->add_page( array(
				'endpoint'     => 'bookmarks', //todo: make this optional in the theme options
				'title'        => __( 'Bookmarks', 'lisner-core' ),
				'template'     => LISNER_DIR . 'templates/dashboard/user-bookmarks.php',
				'show_in_menu' => true,
				'order'        => 4,
			) );
		}
		if ( lisner_helper::is_plugin_active( 'woocommerce-bookings' ) ) {
			$this->add_page( array(
				'endpoint'     => 'booking-orders', //todo: make this optional in the theme options
				'title'        => __( 'Bookings Ordered', 'lisner-core' ),
				'template'     => LISNER_DIR . 'templates/dashboard/booking-orders.php',
				'show_in_menu' => true,
				'order'        => 100,
			) );
		}
	}

	/**
	 * Get Add Listing page template
	 *
	 * @return mixed|string
	 */
	public function get_dashboard_page_template() {
		$option                = get_option( 'pbs_option' );
		$main_add_listing      = isset( $option['page-dashboard'] ) ? $option['page-dashboard'] : '';
		$add_listing_templates = lisner_helper()->get_pages_by_template( 'page-my-account' );
		if ( ! empty( $main_add_listing ) ) { // check whether theme option is set
			$template = $main_add_listing;
		} else if ( ! empty( $add_listing_templates ) ) { // check if there is template assign to first page found
			$template = $add_listing_templates;
		} else { // check whether wp job manager option is set
			$template = get_option( 'woocommerce_myaccount_page_id' );
			$template = isset( $template ) ? $template : '';
		}

		return $template;
	}

	/**
	 * Load search page template
	 *
	 * @param $template
	 *
	 * @return mixed
	 */
	public function load_dashboard_page_template( $template ) {
		$add_listing_page_id = self::get_dashboard_page_template();

		if ( ! empty( $add_listing_page_id ) && $add_listing_page_id == get_queried_object_id() ) {
			$new_template = lisner_listings()->job_manager_locate_template( '', 'pages/page-my-account.php', '' );

			return $new_template;
		}

		return $template;
	}

}

/**
 * Instantiate class
 *
 * @return lisner_dashboard|null
 */
function lisner_dashboard() {
	return lisner_dashboard::instance();
}
