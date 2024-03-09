<?php
/**
 * File Name: pebas WooCommerce Overrides Class
 * Description: Main class for WooCommerce Overrides
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_woocommerce_functions' ) ) {
	class pbs_woocommerce_functions {

		protected static $_instance = null;

		/**
		 * @return null|pbs_woocommerce_functions
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct() {
			// shop page layout
			add_action( 'init', array( $this, 'remove_woocommerce_actions' ), 10 );
			add_action( 'init', array( $this, 'woocommerce_add_image_sizes' ), 20 );
			add_filter( 'body_class', array( $this, 'woocommerce_body_classes' ) );
			add_filter( 'single_product_archive_thumbnail_size', array(
				$this,
				'single_product_archive_thumbnail_size'
			) );
			add_action( 'woocommerce_before_main_content', array( $this, 'woocommerce_before_main_content' ), 1 );
			add_action( 'woocommerce_after_main_content', array( $this, 'woocommerce_after_main_content' ), 1 );
			add_action( 'woocommerce_sidebar', array( $this, 'get_shop_sidebar' ), 10 );

			// change related products limit
			add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_limit' ) );


			// add menu cart

			$this->woocommerce_register_shop_sidebar(); // load shop sidebar

		}

		/**
		 * Set custom related products limit
		 * ---------------------------------
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		public function related_products_limit( $args ) {
			$args['posts_per_page'] = 2;

			return $args;
		}

		/**
		 * Remove unnecessary WooCommerce hooks
		 */
		public function remove_woocommerce_actions() {
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			add_action( 'lisner_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
			add_action( 'lisner_woocommerce_after_shop_loop_item_title', array(
				$this,
				'lisner_woocommerce_template_loop_category'
			), 10 );
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		}

		/**
		 * Get a single product category
		 */
		public function lisner_woocommerce_template_loop_category() {
			$ids = wc_get_product_cat_ids( get_the_ID() );
			shuffle( $ids );
			$category = get_term_by( 'term_id', $ids[0], 'product_cat' );
			$html     = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
			echo wp_kses_post( $html );
		}

		/**
		 * Function to add to woocommerce_before_main_content
		 *
		 * @removed woocommerce_breadcrumb - 20
		 */
		public function woocommerce_before_main_content() {
			if ( ! is_product() ) { // remove breadcrumbs only on shop page
				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			}
			$this->pbs_woocommerce_before_main_content_main_tag();
		}

		/**
		 * Add theme wrappers to woocommerce shop page
		 */
		public function pbs_woocommerce_before_main_content_main_tag() {
			?>
			<div class="single-post main-single-listing lisner-shop">
			<div class="container">
			<div class="row justify-content-center">
			<div class="col-xl-9 col-sm-9-cst">
			<div class="shop-margin-bottom">
			<?php
		}

		/**
		 * Hook end wrappers to woocommerce_after_main_content hook
		 */
		public function woocommerce_after_main_content() {
			$this->pbs_woocommerce_after_main_content_main_tag();
		}

		/**
		 * Add theme end wrappers to woocommerce shop page
		 */
		public function pbs_woocommerce_after_main_content_main_tag() {
			?>
			<?php if ( ! is_active_sidebar( 'sidebar-shop' ) ) : ?>
				</div>
				</div>
				</div>
				</div>
				</div>
			<?php endif; ?>
			<?php
		}

		/**
		 * Add custom body classes to woocommerce shop page
		 *
		 * @param $classes
		 *
		 * @return array
		 */
		public function woocommerce_body_classes( $classes ) {

			if ( is_shop() || is_product_taxonomy() ) {
				$classes[] = esc_attr( 'woocommerce-page-lisner' );
			}
			if ( is_product() ) {
				$classes[] = esc_attr( 'woocommerce-single-lisner' );
			}
			if ( is_cart() ) {
				$classes[] = esc_attr( 'woocommerce-cart-lisner' );
			}

			return $classes;
		}

		public function single_product_archive_thumbnail_size() {
			return 'lisner_wc_thumbnail';
		}

		public function woocommerce_register_shop_sidebar() {
			register_sidebar( array(
				'name'          => esc_html__( 'Shop Sidebar', 'lisner' ),
				'id'            => 'sidebar-shop',
				'before_widget' => '<aside class="single-listing-sidebar shop-sidebar"><section class="listing-widget widget %2$s">',
				'after_widget'  => '</section></aside>',
				'before_title'  => '<h4 class="single-listing-section-title">',
				'after_title'   => '</h4>',
				'description'   => esc_html__( 'Sidebar that appears on shop page', 'lisner' )
			) );
		}

		public function woocommerce_add_image_sizes() {
			add_image_size( 'wc_lisner_thumbnail', 270, 9999, true );
			add_image_size( 'wc_page_hero', '1920', '445', true );
		}

		public function get_shop_sidebar() {
			?>
			<?php if ( is_active_sidebar( 'sidebar-shop' ) ): ?>
				</div>
				</div>
				<!-- Sidebar -->
				<div class="col-sm">
					<div class="sidebar">
						<?php dynamic_sidebar( 'sidebar-shop' ); ?>
					</div>
				</div>

				</div>
				</div>
				</div>
			<?php endif; ?>
			<?php
		}

	}

	/**
	 * instance of pbs_woocommerce_functions class
	 *
	 * @return null|pbs_woocommerce_functions
	 */
	function pbs_woocommerce_functions() {
		return pbs_woocommerce_functions::instance();
	}
}
pbs_woocommerce_functions();
