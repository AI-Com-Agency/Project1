<?php

/**
 * Demo configuration file
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_demo' ) ) {
	class pbs_demo {

		protected static $_instance = null;

		/**
		 * @return null|pbs_demo
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct() {
			if ( class_exists( 'Lisner_Core' ) ) {
				// filters
				add_filter( 'pt-ocdi/import_files', array( $this, 'import_files' ) );
				add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );


				// actions
				add_action( 'pt-ocdi/before_content_import', array( $this, 'before_import_setup' ) );
				add_action( 'pt-ocdi/before_content_import_execution', array( $this, 'before_import_setup' ) );
				add_action( 'pt-ocdi/after_import', array( $this, 'after_import_setup' ) );
				add_action( 'admin_init', array( $this, 'mark_demo_as_imported' ) );

			}
		}

		/**
		 * Import demo files
		 *
		 * @return array
		 */
		public function import_files() {
			return array(
				array(
					'import_file_name'         => 'Complete Demo Import',
					'categories'               => array(),
					'local_import_file'        => trailingslashit( get_parent_theme_file_path() ) . 'includes/demo/demo-files/default/content.xml',
					'local_import_widget_file' => trailingslashit( get_parent_theme_file_path() ) . 'includes/demo/demo-files/default/widgets.json',
					'import_preview_image_url' => 'http://pebas.rs/lisner/demo_complete_install.jpg',
					'import_notice'            => esc_html__( 'This is complete demo import and after it is done your site will look 100% like the demo. If you are getting error 500 issue you need to increase `max_execution_time` on your server or try with small import ', 'lisner' ),
					'preview_url'              => '',
				),
				array(
					'import_file_name'         => 'Basic Demo Install',
					'categories'               => array(),
					'local_import_file'        => trailingslashit( get_parent_theme_file_path() ) . 'includes/demo/demo-files/small/content.xml',
					'local_import_widget_file' => trailingslashit( get_parent_theme_file_path() ) . 'includes/demo/demo-files/small/widgets.json',
					'import_preview_image_url' => 'http://pebas.rs/lisner/demo_basic_install.jpg',
					'import_notice'            => esc_html__( 'This is small import that will get you started with most basic things already set up.', 'lisner' ),
					'preview_url'              => '',
				),
			);
		}

		public function before_import_setup() {
			$categories = get_option( 'job_manager_enable_categories' );
			if ( isset( $categories ) ) {
				update_option( 'job_manager_enable_categories', true );
				update_option( 'job_manager_enable_categories', 1 );
			} else {
				add_option( 'job_manager_enable_categories', 1 );
			}
		}

		/**
		 * Do after demo import is done
		 */
		public function after_import_setup() {
			$this->import_settings(); // import theme settings
			$this->delete_wp_sample(); // delete default wp page & post
			$this->assign_menus(); // assign demo menus to proper menu locations
			$this->assign_demo_pages(); // assign demo pages
			$this->update_page_featured_categories(); // update featured categories on homepage hero section
			$this->set_permalinks_after_import(); // set permalinks to postname after demo has been imported
			$this->update_listing_expiring_dates(); // update listing expiring dates for 30 days more

			$this->mark_demo_as_imported();
		}

		public function update_page_featured_categories() {
			$cat_1      = get_term_by( 'name', 'Beauty & Spa', 'job_listing_category' );
			$cat_2      = get_term_by( 'name', 'Hotel', 'job_listing_category' );
			$cat_3      = get_term_by( 'name', 'Nightlife', 'job_listing_category' );
			$cat_4      = get_term_by( 'name', 'Restaurant', 'job_listing_category' );
			$taxonomies = array( $cat_1->term_id, $cat_2->term_id, $cat_3->term_id, $cat_4->term_id );

			$args  = array(
				'post_type'  => 'page',
				'fields'     => 'ids',
				'nopaging'   => true,
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'templates/tpl-home.php'
			);
			$pages = get_posts( $args );
			foreach ( $pages as $page ) {
				// update featured taxonomies so they are not empty
				update_post_meta( $page, 'home_search_featured_taxonomies', implode( ',', $taxonomies ) );
			}
		}

		/**
		 * Import demo content theme options
		 *
		 * @return false|int
		 */
		public function import_settings() {
			global $wpdb;
			ob_start();
			require get_parent_theme_file_path( 'includes/demo/demo-files/default/theme_options.json' );
			$options = ob_get_clean();
			$options = json_decode( $options );
			$data    = $wpdb->insert( $wpdb->options, array(
					'option_name'  => 'pbs_option',
					'option_value' => array_shift( $options )
				)
			);

			return $data;
		}

		/**
		 * Assign demo theme menus
		 */
		public function assign_menus() {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

			set_theme_mod( 'nav_menu_locations', array(
				'top_menu' => $main_menu->term_id,
			) );
		}

		/**
		 * Assign demo theme pages
		 */
		public function assign_demo_pages() {
			$option          = get_option( 'pbs_option' );
			$front_page_id   = get_page_by_title( 'Home' );
			$blog_page       = get_page_by_title( 'News' );
			$contact_page_id = get_page_by_title( 'Contact' );
			$shop_page       = get_page_by_title( 'Shop' );
			$checkout        = get_page_by_title( 'Checkout' );
			$cart            = get_page_by_title( 'Cart' );
			$account         = get_page_by_title( 'My Account' );
			$search          = get_page_by_title( 'Explore' );
			$claim           = get_page_by_title( 'Claim' );
			$add_listing     = get_page_by_title( 'Add Listing' );
			$all_listings    = get_page_by_title( 'All Listings' );
			$terms           = get_page_by_title( 'Terms & Conditions' );

			// set default pages
			update_option( 'job_manager_submit_job_form_page_id', $add_listing->ID );
			update_option( 'job_manager_job_dashboard_page_id', $all_listings->ID );
			update_option( 'job_manager_jobs_page_id', $search->ID );
			update_option( $option['page-search'], $search->ID );
			update_option( 'job_manager_claim_listing_page_id', $claim->ID );
			update_option( 'woocommerce_shop_page_id', $shop_page->ID );
			update_option( 'woocommerce_cart_page_id', $cart->ID );
			update_option( 'woocommerce_checkout_page_id', $checkout->ID );
			update_option( 'woocommerce_myaccount_page_id', $account->ID );
			update_option( 'woocommerce_terms_page_id', $terms->ID );

			// set homepage
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page_id->ID );
			update_option( 'page_for_posts', $blog_page->ID );

			update_option( 'users_can_register', true );

			// do proper contact form 7 shortcode
			if ( class_exists( 'WPCF7' ) ) {
				update_post_meta( $contact_page_id->ID, 'contact_form', '[contact-form-7 id="88" title="Contact form 1"]' );
			}
		}

		/**
		 * Delete WordPress default page and post
		 */
		public function delete_wp_sample() {
			$defaultPage = get_page_by_title( 'Sample Page' );
			wp_delete_post( $defaultPage->ID, $bypass_trash = true );

			// Find and delete the WP default 'Hello world!' post
			$defaultPost = get_posts( array( 'title' => 'Hello World!' ) );
			wp_delete_post( $defaultPost[0]->ID, $bypass_trash = true );
		}

		/**
		 * Mark demo as imported
		 */
		public function mark_demo_as_imported() {
			$url = get_site_url();
			if ( strstr( $url, 'themes.php?page=pt-one-click-demo-import' ) ) {
				update_option( 'pbs_demo_imported', 'yes' ); // mark demo as imported
			}
		}

		/**
		 * Set theme permalinks to post name
		 */
		public function set_permalinks_after_import() {
			global $wp_rewrite;
			//Write the rule
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
			//Set the option
			update_option( "rewrite_rules", false );
			//Flush the rules and tell it to write htaccess
			$wp_rewrite->flush_rules( true );
		}

		/**
		 * Update listings expiring dates for 30 days more
		 * after demo import
		 */
		public function update_listing_expiring_dates() {
			$new_date = date( 'Y-m-d', strtotime( '+30 days', current_time( 'timestamp' ) ) );
			$listings = get_posts( array(
				'post_type' => 'job_listing',
				'posts_per_page' => -1
			) );

			if ( ! empty( $listings ) && 0 != count( $listings ) ) {
				foreach ( $listings as $listing ) {
					update_post_meta( $listing->ID, '_job_expires', $new_date );
				}
			}
		}

	}
}

function pbs_demo() {
	return pbs_demo::instance();
}

// instantiate class
pbs_demo();
