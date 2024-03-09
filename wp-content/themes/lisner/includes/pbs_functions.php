<?php
/**
 * General Theme Functions File
 *
 * @author includes
 * @version 1.0.0
 */

// hook for theme api
do_action( 'pbs_global_before' );

// theme files
require_once get_parent_theme_file_path( 'includes/pbs_global.php' );

/**
 * Class pbs_functions
 */
if ( ! class_exists( 'pbs_functions' ) ) {
	class pbs_functions {

		protected static $_instance = null;

		/**
		 * @return null| pbs_functions
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct() {
			/**
			 * Add theme support features
			 */
			$content_width = 1370;

			set_post_thumbnail_size( 848, 477, true );

			add_editor_style();

			$this->add_theme_support();

			// load theme setup actions
			add_action( 'init', array( $this, 'set_direction' ) );
			add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_scripts' ) );
			if ( is_admin() ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
			}
			add_action( 'widgets_init', array( $this, 'initialize' ) );
			add_image_size( 'post_box', 430, 240, true );
		}

		/**
		 * Add default theme support
		 */
		public function add_theme_support() {
			$post_formats_default = array( 'standard', 'video', 'gallery' );
			add_theme_support( 'post-thumbnails' );
			$post_formats = array_merge( $post_formats_default, array(
				'aside',
				'gallery',
				'link',
				'image',
				'quote',
				'status',
				'video',
				'audio',
				'chat'
			) );
			add_theme_support( 'post-formats', $post_formats );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'custom-logo' );
			add_theme_support( 'custom-header' );
			add_theme_support( 'custom-background' );
			add_theme_support( 'html5', array( 'comment-list', 'search-form', 'gallery', 'caption' ) );
			if ( pbs_global::$is_woocommerce_installed ) {
				$this->woocommerce_support();
			}
		}

		// load theme domain
		public function load_textdomain() {
			load_theme_textdomain( 'lisner', get_template_directory() . '/locales' );
		}

		// declare WooCommerce support
		public function woocommerce_support() {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
		}

		/**
		 * Load Frontend Scripts
		 */
		public function load_frontend_scripts() {
			// load styles
			wp_enqueue_style( 'pbs-theme-fonts', 'https://fonts.googleapis.com/css?family=Assistant:100,200,300,400,500,600,700,800', '', PBS_THEME_VERSION, 'all' );
			wp_enqueue_style( 'pbs-theme-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', '', null, 'all' );
			wp_enqueue_style( 'pbs-theme-font-awesome', PBS_THEME_URL . 'assets/styles/fontawesome.min.css', '', '5.2', 'all' );
			wp_enqueue_style( 'pbs-theme-font-awesome-brands', PBS_THEME_URL . 'assets/styles/brands.min.css', '', '5.2', 'all' );
			wp_enqueue_style( 'pbs-theme-font-awesome-regular', PBS_THEME_URL . 'assets/styles/solid.min.css', '', '5.2', 'all' );
			wp_enqueue_style( 'pbs-theme-bootstrap', PBS_THEME_URL . 'assets/styles/bootstrap.min.css', '', '4.0', 'all' );
			wp_enqueue_style( 'pbs-theme', PBS_THEME_URL . 'style.min.css', '', PBS_THEME_VERSION, 'all' );

			// load rtl if necessary
			$option    = get_option( 'pbs_option' );
			$direction = isset( $option['site-direction'] ) ? $option['site-direction'] : '';
			if ( 'rtl' == $direction ) {
				wp_style_add_data( 'pbs-theme', 'rtl', 'replace' );
				wp_enqueue_style( 'pbs-rtl-additional', PBS_THEME_URL . 'assets/styles/rtl.css' );
			}

			// load scripts
			if ( is_singular() ) {
				wp_enqueue_script( 'comment-reply' );
			}
			wp_enqueue_script( 'masonry' );
			wp_enqueue_script( 'pbs-bootstrap', PBS_THEME_URL . 'assets/scripts/bootstrap.bundle.min.js', array(
				'jquery',
			), '', true );
			wp_enqueue_script( 'lisner-theme-chosen', PBS_THEME_URL . 'assets/scripts/chosen.jquery.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'pbs-theme', PBS_THEME_URL . 'assets/scripts/theme.js', array( 'jquery' ), PBS_THEME_VERSION, true );
			pbs_theme_functions::localize_vars();
		}

		/**
		 * Load admin scripts
		 */
		public function load_admin_scripts() {
			/* -load admin js files*/
			pbs_theme_functions::localize_vars();
		}

		/**
		 * Register Theme Sidebars & Navigation Menus
		 */
		public function initialize() {
			// register theme sidebars
			register_sidebar( array(
				'name'          => esc_html__( 'Blog Sidebar', 'lisner' ),
				'id'            => 'sidebar-blog',
				'before_widget' => '<aside class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
				'description'   => esc_html__( 'Sidebar that appears on blog listing page', 'lisner' )
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Footer Sidebar / Column 1', 'lisner' ),
				'id'            => 'sidebar-footer',
				'before_widget' => '<aside class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h6 class="widget-title">',
				'after_title'   => '</h6>',
				'description'   => esc_html__( 'Sidebar that appears in footer in first column', 'lisner' )
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Footer Sidebar / Column 2', 'lisner' ),
				'id'            => 'sidebar-footer-2',
				'before_widget' => '<aside class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h6 class="widget-title">',
				'after_title'   => '</h6>',
				'description'   => esc_html__( 'Sidebar that appears in footer in second column', 'lisner' )
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Footer Sidebar / Column 3', 'lisner' ),
				'id'            => 'sidebar-footer-3',
				'before_widget' => '<aside class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h6 class="widget-title">',
				'after_title'   => '</h6>',
				'description'   => esc_html__( 'Sidebar that appears in footer third column', 'lisner' )
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Footer Sidebar / Column 4', 'lisner' ),
				'id'            => 'sidebar-footer-4',
				'before_widget' => '<aside class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h6 class="widget-title">',
				'after_title'   => '</h6>',
				'description'   => esc_html__( 'Sidebar that appears in footer fourth column', 'lisner' )
			) );

			// register theme menus
			register_nav_menus( array(
				'top_menu' => __( 'Top Menu', 'lisner' ),
			) );

			// add image sizes

		}

		/**
		 * Set theme direction
		 */
		public function set_direction() {
			global $wp_locale, $wp_styles;
			$option = get_option( 'pbs_option' );

			$_user_id  = get_current_user_id();
			$direction = isset( $option['site-direction'] ) ? $option['site-direction'] : '';
			if ( 'rtl' != $direction ) {
				$direction = 'ltr';
			}

			if ( 'rtl' == $direction ) {
				update_user_meta( $_user_id, 'rtladminbar', $direction );
			} else {
				$direction = get_user_meta( $_user_id, 'rtladminbar', true );
				if ( $direction ) {
					$direction = isset( $wp_locale->text_direction ) ? $wp_locale->text_direction : 'ltr';
				}
			}

			$wp_locale->text_direction = $direction;
			if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
				$wp_styles = new WP_Styles();
			}
			$wp_styles->text_direction = $direction;
		}

	}

}

// instantiate class
function pbs_functions() {
	return pbs_functions::instance();
}

pbs_functions();

// load framework
require_once get_parent_theme_file_path( 'includes/framework/menu/pbs_nav.php' );
require_once get_parent_theme_file_path( 'includes/framework/templates/pbs_page_template.php' );
require_once get_parent_theme_file_path( 'includes/framework/templates/pbs_page_template_alternate.php' );
require_once get_parent_theme_file_path( 'includes/framework/templates/pbs_page_template_narrow.php' );

// hook for theme api
do_action( 'pbs_global_after' );

// load demo
require_once get_parent_theme_file_path( 'includes/demo/pbs_demo.php' );

