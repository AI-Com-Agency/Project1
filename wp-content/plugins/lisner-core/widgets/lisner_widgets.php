<?php

/**
 * Class lisner_widgets
 */
class lisner_widgets {

	protected static $_instance = null;

	/**
	 * @return null|lisner_widgets
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_widgets constructor.
	 */
	function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Get all shortcodes - class called lisner_widgets
		self::require_widgets();
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// add widget image sizes
		add_image_size( 'promo_image', 768, 266, true );
	}

	/**
	 * Enqueue admin scripts and styles
	 */
	public function enqueue_scripts() {
	}

	public static function register_sidebars() {
		register_sidebar( array(
			'name'          => esc_html__( 'Listing Single Sidebar', 'lisner-core' ),
			'id'            => 'sidebar-listing-single',
			'before_widget' => '<aside class="single-listing-sidebar"><section class="listing-widget %2s">',
			'after_widget'  => '</section></aside>',
			'before_title'  => '<h4 class="single-listing-section-title">',
			'after_title'   => '</h4>',
			'description'   => esc_html__( 'Sidebar that is being used on listing single page template', 'lisner-core' )
		) );
	}

	/**
	 * Get all widgets in array
	 *
	 * @param string $folder
	 *
	 * @return array
	 */
	public static function get_widgets( $folder = '' ) {
		if ( empty( $folder ) ) {
			$shortcodes = glob( LISNER_DIR . "widgets/blocks/*.php" );
		} else {
			$shortcodes = glob( LISNER_DIR . "{$folder}/*.php" );
		}

		return $shortcodes;
	}

	/**
	 * Get template for the widgets
	 *
	 * @param $view
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function get_widget_view( $view, $atts = array() ) {
		return LISNER_DIR . "widgets/views/{$view}.php";
	}

	/**
	 * Get news widgets
	 */
	public static function require_widgets() {
		$widgets = self::get_widgets();
		foreach ( $widgets as $widget ) {
			require_once( $widget );
		}
	}

	/**
	 * Register news widgets
	 */
	public static function register_widgets() {
		$widgets = self::get_widgets();
		foreach ( $widgets as $widget ) {
			$widget = str_replace( array( '-', '.php' ), array( '_', '' ), wp_basename( $widget ) );
			register_widget( $widget );
		}
	}

}

/** Instantiate class
 *
 * @return null|lisner_widgets
 */
function lisner_widgets() {
	return lisner_widgets::instance();
}
