<?php

/**
 * Class lisner_templater
 *
 * @author pebas
 * @ver 1.0.0
 */

class lisner_templater {

	protected static $_instance = null;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	/**
	 * @return null|lisner_templater
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_templater constructor.
	 */
	private function __construct() {
		$this->templates = array();
		// Add a filter to the wp 4.7 version attributes metabox
		add_filter( 'theme_page_templates', array( $this, 'add_new_template' ) );
		// Add a filter to the save post to inject out template into the page cache
		add_filter( 'wp_insert_post_data', array( $this, 'register_project_templates' ) );
		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		add_filter( 'template_include', array( $this, 'view_project_template' ) );
		// Add your templates to this array.
		if ( is_admin() ) {
			$this->templates = $this->get_templates();
		}
		$templates = wp_get_theme()->get_page_templates();
		$templates = array_merge( $templates, $this->templates );
	}

	/**
	 * Get available page templates
	 *
	 * @return array
	 */
	public function get_templates() {
		$page_templates = array();
		$templates      = glob( LISNER_DIR . 'templates/pages/*.php' );
		foreach ( $templates as $template ) {
			$args                             = array(
				'Template Name' => 'Template Name',
				'Description'   => 'Description'
			);
			$page_template_header             = get_file_data( $template, $args );
			$template_slug                    = wp_basename( str_replace( '.php', '', $template ) );
			$page_templates[ $template_slug ] = esc_html( $page_template_header['Template Name'] );
		}

		return $page_templates;
	}

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 * @param $posts_templates
	 *
	 * @return array
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );

		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doesn't really exist.
	 *
	 * @param $atts
	 *
	 * @return mixed
	 */
	public function register_project_templates( $atts ) {
		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}
		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key, 'themes' );
		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );
		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;
	}

	/**
	 * Checks if the template is assigned to the page
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function view_project_template( $template ) {
		// Return the search template if we're searching (instead of the template for the first result)
		if ( is_search() ) {
			return $template;
		}
		// Get global post
		global $post;
		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}
		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[ get_post_meta(
				$post->ID, '_wp_page_template', true
			) ] ) ) {
			return $template;
		}
		// Allows filtering of file path
		$filepath = apply_filters( 'page_templater_plugin_dir_path', plugin_dir_path( __FILE__ ) );
		$file     = $filepath . get_post_meta(
				$post->ID, '_wp_page_template', true
			);
		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;
	}

}

/**
 * Instantiate class
 *
 * @return lisner_templater|null
 */
function lisner_templater() {
	return lisner_templater::instance();
}
