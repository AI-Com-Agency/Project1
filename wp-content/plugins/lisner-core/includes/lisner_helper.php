<?php

/**
 * Class lisner_helper
 */

class lisner_helper {

	protected static $_instance = null;

	/**
	 * @return null|lisner_helper
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
	 * @return mixed
	 */
	public function get_page_permalink( $page ) {
		$page_id   = $this->get_page_id( $page );
		$permalink = 0 < $page_id ? get_permalink( $page_id ) : get_home_url();

		return apply_filters( 'lisner_get_' . $page . '_page_permalink', $permalink );
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
		$page = apply_filters( 'lisner_get_' . $page . '_page_id', $page );

		return $page ? absint( $page ) : - 1;
	}

	/**
	 * Get all created pages that have certain template assigned to them
	 *
	 * @param string $template
	 * @param array $args
	 *
	 * @return array|bool|false
	 */
	public function get_pages_by_template( $template = '', $args = array() ) {
		if ( empty( $template ) ) {
			return false;
		}
		if ( strpos( $template, '.php' ) ) {
			$template = str_replace( '.php', '', $template );
		}
		$args['meta_key']   = '_wp_page_template';
		$args['meta_value'] = $template;
		$pages              = get_pages( $args );
		$page_id            = ! empty( $pages ) ? $pages[0]->ID : '';

		return $page_id;
	}

	/**
	 * Get template
	 *
	 * @param $template
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function get_template( $template, $atts = array() ) {
		return LISNER_DIR . "templates/{$template}.php";
	}

	/**
	 * Get template part
	 *
	 * @param $template
	 * @param string $folder
	 * @param array $args
	 *
	 * @return string
	 */
	public static function get_template_part( $template, $folder = '', $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		if ( empty( $folder ) ) {
			$dir = LISNER_DIR . "templates/{$template}.php";
		} else {
			$dir = LISNER_DIR . "templates/{$folder}/{$template}.php";
		}

		return $dir;
	}

	/**
	 * Helper function to check if the listing is open now
	 *
	 * @param $open
	 * @param $close
	 *
	 * @return bool
	 */
	public function is_open_now( $open, $close ) {
		$current_time = current_time( 'H:i' );
		$open = date( 'H:i', strtotime( $open ) );
		$close = date( 'H:i', strtotime( $close ) );

		if ( $current_time > $open && $current_time < $close ) {
			return true;
		}

		return false;
	}

	/**
	 * Render is open now
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function is_open_now_render( $post_id ) {
		$day           = strtolower( current_time( 'l' ) );
		$is_open       = false;
		$open_settings = get_post_meta( $post_id, "_listing_{$day}_hours_radio", true );
		$open_hours    = get_post_meta( $post_id, "_listing_{$day}_hours_open" );
		$close_hours   = get_post_meta( $post_id, "_listing_{$day}_hours_close" );
		$hours         = $open_hours && $close_hours ? array_combine( array_shift( $open_hours ),
			array_shift( $close_hours ) ) : '';
		if ( 'custom' == $open_settings ) {
			foreach ( $hours as $open => $close ) {
				if ( lisner_helper()->is_open_now( $open, $close ) ) {
					$is_open = true;
				}
			}
		} elseif ( 'open' == $open_settings ) {
			$is_open = true;
		}

		return $is_open;
	}

	/**
	 * Rendering listing pricing range with icons
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public static function pricing_range_render( $post_id ) {
		$price_range = get_post_meta( $post_id, '_listing_pricing_range', true );
		$range       = '<span class="lisner-listing-price-range lisner-listing-price-range-' . $price_range . '">
						<i class="material-icons mf">' . esc_html( 'attach_money' ) . '</i>
						<i class="material-icons mf">' . esc_html( 'attach_money' ) . '</i>
						<i class="material-icons mf">' . esc_html( 'attach_money' ) . '</i>
						<i class="material-icons mf">' . esc_html( 'attach_money' ) . '</i>
						</span>';

		return $range;
	}

	/**
	 * Get shuffled category for a single listing
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function get_single_category_id( $post_id ) {
		$categories   = get_the_terms( $post_id, 'job_listing_category' );
		$category_ids = array();
		if ( $categories ) :
			$category_ids = array();
			foreach ( $categories as $category ) :
				$category_ids[] = $category->term_id;
			endforeach;
			shuffle( $category_ids );
			$category_ids = array_shift( $category_ids );
		endif;

		return $category_ids;
	}

	/**
	 * Get the ip address of the client
	 *
	 * @return bool|mixed|string|void
	 */
	public function get_client_ip() {
		$ip = '';

		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$ip_list = explode( ',', $ip );

		if ( get_option( 'pebas-detect-has_reverse_proxy', 0 ) && isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
			$ip_list = explode( ',', @$_SERVER["HTTP_X_FORWARDED_FOR"] );
			$ip_list = array_map( 'detect_normalize_ip', $ip_list );

			$trusted_proxies = explode( ',', get_option( 'pebas-detect-trusted_proxy_ips' ) );

			// Always trust localhost
			$trusted_proxies[] = '';
			$trusted_proxies[] = '::1';
			$trusted_proxies[] = '127.0.0.1';

			$trusted_proxies = array_map( 'detect_normalize_ip', $trusted_proxies );
			$ip_list[]       = $ip;

			$ip_list = array_diff( $ip_list, $trusted_proxies );
		}
		// Fallback IP
		array_unshift( $ip_list, '::1' );

		// Each Proxy server append their information at the end, so the last IP is most trustworthy.
		$ip = end( $ip_list );
		$ip = self::detect_normalize_ip( $ip );

		if ( ! $ip ) {
			$ip = '::1';
		} // By default, use localhost

		$ip = apply_filters( 'detect_client_ip', $ip, $ip_list );

		return $ip;
	}

	/**
	 * Normalize detected IP address
	 *
	 * @param $ip
	 *
	 * @return bool|string
	 */
	public function detect_normalize_ip( $ip ) {
		$ip     = trim( $ip );
		$binary = @inet_pton( $ip );
		if ( empty( $binary ) ) {
			return $ip;
		}

		$ip = inet_ntop( $binary );

		return $ip;
	}

	/**
	 * Check whether current user has liked the listing
	 *
	 * @param $listing_id
	 *
	 * @return bool
	 */
	public static function has_user_liked_listing( $listing_id ) {
		$likes = get_post_meta( $listing_id, 'listing_likes_ip' );
		$ips   = array_unique( $likes );
		$ips   = implode( PHP_EOL, $ips );
		$ips   = explode( PHP_EOL, $ips );
		$ip    = lisner_helper()->get_client_ip();
		if ( in_array( $ip, $ips ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get current post id
	 *
	 * @return bool|int
	 */
	public static function get_current_post_id() {
		$post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : false );

		return is_numeric( $post_id ) ? absint( $post_id ) : false;
	}

	/**
	 * Convert string to array
	 *
	 * @param $string
	 *
	 * @return array
	 */
	public static function csv_to_array( $string ) {
		return is_array( $string ) ? $string : array_filter( array_map( 'trim', explode( ',', $string . ',' ) ) );
	}

	/**
	 * Check whether a post template has been assigned
	 *
	 * @param $templates
	 * @param string $post_id
	 *
	 * @return bool
	 */
	public static function check_template( $templates, $post_id = '' ) {
		$post_id  = ! empty( $post_id ) ? $post_id : self::get_current_post_id();
		$template = get_post_meta( $post_id, '_wp_page_template', true );

		return in_array( $template, self::csv_to_array( $templates ) );
	}

	/**
	 * Check whether it we're on search page
	 *
	 * @return bool
	 */
	public static function is_search_page() {
		$search_tpl = lisner_search()->get_search_page_template();
		$tpl        = lisner_helper::check_template( 'page-search', get_the_ID() );
		$taxonomies = array( 'listing_location', 'listing_amenity', 'listing_tag', 'job_listing_category' );
		if ( $tpl || $search_tpl && $search_tpl == lisner_helper::get_current_post_id() || is_tax( $taxonomies ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check whether it we're on account page
	 *
	 * @return bool
	 */
	public static function is_account_page() {
		$account_tpl = lisner_dashboard()->get_dashboard_page_template();
		$tpl         = lisner_helper::check_template( 'page-my-account', get_the_ID() );
		if ( $tpl || $account_tpl == lisner_helper::get_current_post_id() ) {
			return true;
		}

		return false;
	}

	/**
	 * Get hero image overlay styl
	 *
	 * @param $page_id
	 * @param $key
	 * @param $setting
	 * @param $option
	 *
	 * @return string
	 */
	public static function get_hero_image_overlay_style( $page_id, $key, $setting, $option ) {
		$bg_overlay    = get_post_meta( $page_id, "{$key}_bg_overlay_show", true ) ? true : false;
		$meta_value    = get_post_meta( $page_id, "{$key}_bg_{$setting}", true ) ? : '';
		$overlay_style = '';
		if ( $bg_overlay && ! empty( $meta_value ) ) {
			$overlay_style = $option . ':' . $meta_value . ';';
		}

		return $overlay_style;

	}

	/**
	 * Get last word from text
	 *
	 * @param $text
	 *
	 * @return mixed
	 */
	public function get_last_word( $text ) {
		$text      = explode( ' ', $text );
		$last_word = end( $text );

		return $last_word;
	}

	/**
	 * Check if an role exists
	 * --------------------------------
	 *
	 *
	 * @param $role
	 *
	 * @return bool
	 */
	public static function role_exists( $role ) {

		if ( ! empty( $role ) ) {
			return $GLOBALS['wp_roles']->is_role( $role );
		}

		return false;
	}

}

function lisner_helper() {
	return lisner_helper::instance();
}
