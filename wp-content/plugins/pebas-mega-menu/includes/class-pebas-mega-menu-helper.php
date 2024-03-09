<?php

/**
 * Class pebas_mega_menu_helper
 */

class pebas_mega_menu_helper {

	protected static $_instance = null;

	/**
	 * @return null|pebas_mega_menu_helper
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Check if plugin has been activated
	 *
	 * @param $plugin_dir
	 * @param string $plugin_filename - Leave empty if plugin filename is the same as directory it's it
	 *
	 * @return bool
	 */
	public static function is_plugin_active( $plugin_dir, $plugin_filename = '' ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$plugin = $plugin_dir . '/' . ( ! empty( $plugin_filename ) ? $plugin_filename : $plugin_dir ) . '.php';
		if ( is_plugin_active( $plugin ) ) {
			return true;
		}

		return false;
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

		return apply_filters( 'pebas_get_' . $page . '_page_permalink', $permalink );
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
		$page = apply_filters( 'pebas_get_' . $page . '_page_id', $page );

		return $page ? absint( $page ) : - 1;
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
		return PEBAS_MM_DIR . "/views/{$view}.php";
	}

	/**
	 * Get the post time and date
	 *
	 * @param string $post_id
	 */
	public static function post_date( $post_id = '' ) {
		$post_id = ! empty( $post_id ) ? $post_id : get_the_ID();
		if ( ! is_new_day() ) {
			echo get_the_time( get_option( 'time_format' ), $post_id );
		} else {
			$post_from_date = human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) );
			echo sprintf( esc_html__( '%s ago', 'pebas-mega-menu' ), $post_from_date );
		}
	}

}

function pebas_mega_menu_helper() {
	return pebas_mega_menu_helper::instance();
}
