<?php

/*
 | ----------------------------------------------------------------------------
 | PowerThemes Helper Functions
 | ----------------------------------------------------------------------------
 |
 | PowerThemes helper functions used throughout the theme.
 |
 */

class pbs_helpers {

	protected static $_instance = null;

	/**
	 * @return null|pbs_helpers
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Get page permalink
	 *
	 * @param $page
	 *
	 * @return mixed|void
	 */
	public function get_page_permalink( $page ) {
		$page_id   = $this->get_page_id( $page );
		$permalink = 0 < $page_id ? get_permalink( $page_id ) : get_home_url();

		return apply_filters( 'pbs_get_' . $page . '_page_permalink', $permalink );
	}

	/**
	 * Get id of the given page
	 *
	 * @param $page
	 *
	 * @return int
	 */
	public function get_page_id( $page ) {
		$page = get_page_by_path( $page );
		$page = $page ? $page->ID : '';
		$page = apply_filters( 'pbs_get_' . $page . '_page_id', $page );

		return $page ? absint( $page ) : - 1;
	}

	/**
	 * Return page templates from specific folder. All templates are based in views folder.
	 *
	 * @param $tpl_dir
	 *
	 * @return array|mixed
	 */
	static function get_page_templates( $tpl_dir = '' ) {
		$templates = scandir( PBS_THEME . 'views/' . $tpl_dir . '/' );
		$tpl_array = array_slice( $templates, 2 );
		$tpl_array = preg_replace( "/(.+)\.php$/", "$1", $tpl_array );

		return $tpl_array;
	}

	/**
	 * Get template part from views folder
	 *
	 * @param $view
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function get_view( $view, $atts = array() ) {
		return get_parent_theme_file_path( "/views/{$view}.php" );
	}

	/**
	 * Check whether page contains page builder shortcode
	 *
	 * @return bool
	 */
	public static function has_vc_shortcode() {
		global $post;
		if ( ! $post ) {
			return false;
		}
		if ( class_exists( 'Vc_Manager' ) ) {
			if ( strpos( $post->post_content, '[vc_' ) !== false ) {
				return true;
			}

		}

		return false;
	}

	/**
	 * Get array list of all available pages
	 *
	 * @return array
	 */
	public static function get_all_pages() {
		$pages           = get_pages();
		$pages_list      = array();
		$pages_list[' '] = ' ';
		foreach ( $pages as $page ) {
			$pages_list[ $page->post_name ] = $page->post_title;
		}

		return $pages_list;
	}

	/**
	 * Convert hex color to rgba
	 *
	 * @param $color
	 * @param bool $opacity
	 *
	 * @return string
	 */
	public static function hex2rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if ( empty( $color ) ) {
			return $default;
		}

		//Sanitize $color if "#" is provided
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $color;
		}

		//Convert hexadec to rgb
		$rgb = array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ",", $rgb ) . ')';
		}

		//Return rgb(a) color string
		return $output;
	}

	public static function get_permalink_by_tpl( $template_name ) {
		$page = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template_name . '.php'
		) );
		if ( ! empty( $page ) ) {
			return get_permalink( $page[0]->ID );
		} else {
			return "javascript:;";
		}
	}

}

/**
 * Instantiate pbs_helpers() class
 * @return null|pbs_helpers
 */
function pbs_helpers() {
	return pbs_helpers::instance();
}