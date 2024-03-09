<?php

/**
 * Class lisner_search
 *
 * @author pebas
 * @ver 1.0.0
 */

class lisner_search {

	protected static $_instance = null;

	public $data = array();

	/**
	 * Instance
	 *
	 * @return null|lisner_search
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor lisner_search constructor.
	 */
	public function __construct() {

		// add actions.
		add_action( 'init', array( __CLASS__, 'add_endpoint' ) );
		add_action( 'template_redirect', array( __CLASS__, 'do_lisner_ajax' ), 0 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'rwmb_page_search_after_save_field', array( $this, 'set_search_page_template' ), 10, 5 );

		add_action( 'get_job_listings_init', array( $this, 'add_custom_query_args' ) );

//		add_action( 'lisner_ajax_nopriv_get_listings', array( $this, 'get_listings' ) );
//		add_action( 'lisner_ajax_get_listings', array( $this, 'get_listings' ) );
		add_action( 'wp_ajax_nopriv_get_listings', array( $this, 'get_listings' ) );
		add_action( 'wp_ajax_get_listings', array( $this, 'get_listings' ) );

		add_action( 'pbs_nav', array( $this, 'add_main_search' ) );

		// add filters.
		//add_filter( 'job_manager_settings', array( $this, 'exclude_jobs_page_setting' ), 10, 1 );
		add_filter( 'template_include', array( $this, 'load_search_page_template' ) );
		add_filter( 'job_manager_get_listings', array( $this, 'listing_query_args' ), 10, 2 );
		add_filter( 'job_listing_searchable_meta_keys', function ( $meta_keys ) {
			array_push( $meta_keys, 'listing_location' );
			array_push( $meta_keys, 'listing_tag' );
			array_push( $meta_keys, 'listing_amenity' );

			return $meta_keys;
		} );

		// add image sizes.
		add_image_size( 'listing_box_search', 372, 222, true );
	}

	/**
	 * Enqueue necessary scripts
	 */
	public function enqueue_scripts() {
	}

	/**
	 * Adds endpoint for frontend Ajax requests.
	 */
	public static function add_endpoint() {
		add_rewrite_tag( '%lisner-ajax%', '([^/]*)' );
		add_rewrite_rule( 'lisner-ajax/([^/]*)/?', 'index.php?lisner-ajax=$matches[1]', 'top' );
		add_rewrite_rule( 'index.php/lisner-ajax/([^/]*)/?', 'index.php?lisner-ajax=$matches[1]', 'top' );
	}

	/**
	 * Gets Job Manager's Ajax Endpoint.
	 *
	 * @param string $request Optional.
	 * @param string $ssl (Unused) Optional.
	 *
	 * @return string
	 */
	public static function get_endpoint( $request = '%%endpoint%%', $ssl = null ) {
		if ( strstr( get_option( 'permalink_structure' ), '/index.php/' ) ) {
			$endpoint = trailingslashit( home_url( '/index.php/lisner-ajax/' . $request . '/', 'relative' ) );
		} elseif ( get_option( 'permalink_structure' ) ) {
			$endpoint = trailingslashit( home_url( '/lisner-ajax/' . $request . '/', 'relative' ) );
		} else {
			$endpoint = add_query_arg( 'lisner-ajax', $request, trailingslashit( home_url( '', 'relative' ) ) );
		}

		return esc_url_raw( $endpoint );
	}

	/**
	 * Performs Job Manager's Ajax actions.
	 */
	public static function do_lisner_ajax() {
		global $wp_query;
		if ( ! empty( $_GET['lisner-ajax'] ) ) {
			$wp_query->set( 'lisner-ajax', sanitize_text_field( $_GET['lisner-ajax'] ) );
		}
		$action = $wp_query->get( 'lisner-ajax' );
		if ( $action ) {
			if ( ! defined( 'DOING_AJAX' ) ) {
				define( 'DOING_AJAX', true );
			}
			// Not home - this is an ajax endpoint.
			$wp_query->is_home = false;
			/**
			 * Performs an Ajax action.
			 * The dynamic part of the action, $action, is the predefined Ajax action to be performed.
			 *
			 * @since 1.23.0
			 */
			do_action( 'lisner_ajax_' . sanitize_text_field( $action ) );
			wp_die();
		}
	}

	/**
	 * Update WP Job Manager `jobs_page_id` setting when search page is set through our custom settings
	 *
	 * @param $null
	 * @param $field
	 * @param $new
	 * @param $old
	 * @param $post_id
	 */
	public function set_search_page_template( $null, $field, $new, $old, $post_id ) {

		$wpjm_search_page = get_option( 'job_manager_jobs_page_id' );
		if ( $new != $old || $new != $wpjm_search_page ) {
			update_option( 'job_manager_jobs_page_id', $new ); // update wp job manager default listings page
		}
	}

	/**
	 * Remove not needed WP Job Manager default settings
	 *
	 * @param $settings
	 *
	 * @return mixed
	 */
	public function exclude_jobs_page_setting( $settings ) {
		$remove_settings = array(
			'job_manager_jobs_page_id',
		);

		if ( ! empty( $settings['job_pages'] ) ) {
			foreach ( $settings['job_pages'] as $setting_group_key => $setting_group ) {
				if ( ! is_array( $setting_group ) ) {
					continue;
				}

				foreach ( $setting_group as $setting_key => $setting ) {
					if ( ! is_array( $setting ) || empty( $setting['name'] ) ) {
						continue;
					}

					if ( in_array( $setting['name'], $remove_settings ) ) {
						unset( $settings['job_pages'][ $setting_group_key ][ $setting_key ] );
					}
				}
			}
		}

		return $settings;
	}

	/**
	 * Get search page template
	 *
	 * @return mixed|string
	 */
	public function get_search_page_template() {
		$option           = get_option( 'pbs_option' );
		$main_search      = isset( $option['page-search'] ) ? $option['page-search'] : '';
		$search_templates = lisner_helper()->get_pages_by_template( 'page-search' );
		$jm_search        = get_option( 'job_manager_jobs_page_id' );
		if ( ! empty( $main_search ) ) { // check whether theme option is set
			$template = $main_search;
		} else if ( ! empty( $search_templates ) ) { // check if there is template assigned to first page found
			$template = $search_templates;
		} else if ( isset( $jm_search ) && ! empty( $jm_search ) ) { // check whether wp job manager option is set
			$template = isset( $jm_search ) ? $jm_search : '';
		} else {
			$template = null;
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
	public function load_search_page_template( $template ) {
		$search_page_id = self::get_search_page_template();
		$taxonomies     = array( 'listing_location', 'listing_amenity', 'listing_tag', 'job_listing_category' );

		if ( ! empty( $search_page_id ) && $search_page_id == get_queried_object_id() || lisner_helper::is_search_page() || is_tax( $taxonomies ) ) {
			$new_template = lisner_listings()->job_manager_locate_template( '', 'pages/page-search.php', '' );

			return $new_template;
		}

		return $template;
	}

	/**
	 * Add main search to search page header
	 */
	public function add_main_search() {
		if ( lisner_helper::is_search_page() ) :
			?>
            <form id="search-form" class="search-page-form search-form" method="get">
				<?php include lisner_helper::get_template_part( 'field-taxonomy-search', 'fields/' ); // taxonomy search
				?>
				<?php include lisner_helper::get_template_part( 'field-location-search', 'fields/' ); // location search
				?>
            </form>
		<?php endif ?>
		<?php
	}

	/**
	 * Instantiate WordPress Query class
	 *
	 * @param $data
	 *
	 * @return WP_Query
	 */
	public function listing_query( $data ) {
		$query = new WP_Query( $data );

		return $query;
	}

	/**
	 * Get listings that are open now
	 *
	 * @return array|bool
	 */
	public function get_open_on_day_listings() {
		global $wpdb;
		$post_ids = array();
		$day      = current_time( 'l' );
		$results  = $wpdb->get_col(
			$wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key=%s AND ( meta_value='custom' OR meta_value='open')", '_listing_' . strtolower( $day ) . '_hours_radio' )
		);
		if ( $results ) {
			foreach ( $results as $result ) {
				$post_ids[] = $result;
			}
			$post_ids = array_unique( $post_ids );

			return $post_ids;
		}

		return false;
	}

	/**
	 * Display listings that are open now
	 *
	 * @return array|bool
	 */
	public function display_open_listings() {
		$ids = $this->get_open_on_day_listings();
		$day = strtolower( current_time( 'l' ) );
		foreach ( $ids as $key => $id ) {
			$is_open       = false;
			$open_settings = get_post_meta( $id, "_listing_{$day}_hours_radio", true );
			$open_hours    = get_post_meta( $id, "_listing_{$day}_hours_open" );
			$close_hours   = get_post_meta( $id, "_listing_{$day}_hours_close" );
			$open_hours    = is_array( $open_hours ) ? array_shift( $open_hours ) : [];
			$close_hours   = is_array( $close_hours ) ? array_shift( $close_hours ) : [];
			$hours         = is_array( $open_hours ) && is_array( $close_hours ) ? array_combine( $open_hours, $close_hours ) : '';
			if ( 'custom' == $open_settings && ! empty( $hours ) ) {
				foreach ( $hours as $open => $close ) {
					if ( lisner_helper()->is_open_now( $open, $close ) ) {
						$is_open = true;
					}
				}
			} else if ( 'open' == $open_settings ) {
				$is_open = true;
			}
			if ( ! $is_open ) {
				unset( $ids[ $key ] );
			}
		}

		return $ids;
	}

	/**
	 * Get listings withing certain radius
	 *
	 * @param $lat
	 * @param $lng
	 * @param int $radius
	 *
	 * @return array
	 */
	public function get_listings_within_radius( $lat, $lng, $radius = 10 ) {
		global $wpdb;
		$results   = $wpdb->get_results(
			$wpdb->prepare( "
				SELECT ID, (6371 * acos (cos ( radians( %s ) )
				* cos( radians( latitude.meta_value ) )
    			* cos( radians( longitude.meta_value ) - radians( %s) )
    			+ sin ( radians( %s ) )                       
    			* sin( radians( latitude.meta_value ) ) ) )
    			AS distance FROM $wpdb->posts INNER JOIN $wpdb->postmeta latitude
    			ON (ID = latitude.post_id AND latitude.meta_key = 'geolocation_lat' )
    			INNER JOIN $wpdb->postmeta longitude
    			ON (ID = longitude.post_id AND longitude.meta_key = 'geolocation_long' )
    			HAVING distance < %s
    			ORDER BY distance;", $lat, $lng, $lat, $radius
			)
		);
		$post_ids  = array();
		$distances = array();
		foreach ( $results as $result ) {
			$post_ids[]  = $result->ID;
			$distances[] = $result->distance;
		}
		$post_ids = array_unique( $post_ids );

		$all = array( $post_ids, $distances );

		return $all;
	}

	/**
	 * Add custom WP Job Manager search args
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function add_custom_query_args( $args ) {
		$args['price_range']      = '';
		$args['open_now']         = '';
		$args['nearby']           = '';
		$args['nearby_coords']    = '';
		$args['search_amenities'] = '';
		$args['search_tags']      = '';
		$args['search_orderby']   = '';

		return $args;
	}

	/**
	 * Update WP Job Manager search functionality with custom values
	 *
	 * @param $query_args
	 * @param $args
	 *
	 * @return mixed
	 */
	public function listing_query_args( $query_args, $args ) {
		global $page_id;
		$option        = get_option( 'pbs_option' );
		$location_slug = $_REQUEST['search_location'];
		//todo Rewrite this to find a way on how both of meta location and tax location can work together
		//todo location meta functionality is in below file
		//todo @see wp-job-manager/wp-job-manager-functions.php
		if ( ( $option['general-location-autocomplete-return'] == 'city' || $option['general-location-search'] == 'custom' ) ) {
			if ( isset( $option['general-taxonomy-page'] ) && 'default' == $option['general-taxonomy-page'] && isset( $location_slug ) && ! empty( $location_slug ) ) {
				$query_args['meta_query']  = [];
				$query_args['tax_query'][] = array(
					'taxonomy' => 'listing_location',
					'field'    => 'name',
					'terms'    => $location_slug,
				);
			}
		}

		if ( ! empty( $args['price_range'] ) && 'none' != $args['price_range'] ) {
			$query_args['meta_query'][] = array(
				'key'   => '_listing_pricing_range',
				'value' => $args['price_range'],
			);
		}

		if ( isset( $args['open_now'] ) && ! is_null( $args['open_now'] ) ) {
			$post_ids               = $this->display_open_listings();
			$query_args['post__in'] = ! empty( $post_ids ) ? $post_ids : array( 0 );
		}

		if ( isset( $args['nearby'] ) && ! is_null( $args['nearby'] ) ) {
			$coords   = explode( ',', $args['nearby_coords'] );
			$post_ids = $this->get_listings_within_radius( $coords[0], $coords[1] );
			$post_ids = array_shift( $post_ids );
			if ( ! is_null( $args['open_now'] ) ) {
				$post_ids = array_intersect( $this->display_open_listings(), $post_ids );
			}
			$query_args['post__in'] = ! empty( $post_ids ) ? $post_ids : array( 0 );
		}

		if ( ! empty( $args['search_amenities'] ) ) {
			$field                     = is_numeric( $args['search_amenities'][0] ) ? 'term_id' : 'slug';
			$operator                  = 'all' === get_option( 'job_manager_category_filter_type', 'all' ) && sizeof( $args['search_amenities'] ) > 1 ? 'AND' : 'IN';
			$query_args['tax_query'][] = array(
				'taxonomy'         => 'listing_amenity',
				'field'            => $field,
				'terms'            => array_values( $args['search_amenities'] ),
				'include_children' => $operator !== 'AND',
				'operator'         => $operator
			);
		}

		if ( ! empty( $args['search_tags'] ) ) {
			$field                     = is_numeric( $args['search_tags'][0] ) ? 'term_id' : 'slug';
			$operator                  = 'all' === get_option( 'job_manager_category_filter_type', 'all' ) && sizeof( $args['search_tags'] ) > 1 ? 'AND' : 'IN';
			$query_args['tax_query'][] = array(
				'taxonomy'         => 'listing_tag',
				'field'            => $field,
				'terms'            => array_values( $args['search_tags'] ),
				'include_children' => $operator !== 'AND',
				'operator'         => $operator
			);
		}

		if ( ! empty( $args['search_orderby'] ) ) {
			$orderby = explode( '_', $args['search_orderby'] );
			$sort    = $orderby[0];
			$order   = strtoupper( $orderby[1] );
			if ( 'price' == $sort ) {
				$query_args['orderby']  = 'meta_value';
				$query_args['order']    = $order;
				$query_args['meta_key'] = '_listing_pricing_range';
			} elseif ( 'distance' == $sort ) {
				if ( $args['nearby_coords'] ) {
					$coords   = explode( ',', $args['nearby_coords'] );
					$post_ids = $this->get_listings_within_radius( $coords[0], $coords[1], 30000 );
					$post_ids = array_shift( $post_ids );
					if ( ! is_null( $args['open_now'] ) ) {
						$post_ids = array_intersect( $this->display_open_listings(), $post_ids );
					}
					if ( 'ASC' == $order ) {
						$query_args['orderby']  = 'post__in';
						$query_args['post__in'] = ! empty( $post_ids ) ? $post_ids : '';
					}
				}
			} else {
				$query_args['orderby'] = 'date';
				$query_args['order']   = $order;
			}
		}

		return apply_filters( 'listing_query_args', $query_args, $args );
	}

	/**
	 * Get WP Query arguments of user input
	 *
	 * @return array
	 */
	public function get_updated_args() {
		$args              = array();
		$page_id           = isset( $_REQUEST['page_id'] ) ? sanitize_text_field( stripslashes( $_REQUEST['page_id'] ) ) : '';
		$search_location   = isset( $_REQUEST['search_location'] ) ? sanitize_text_field( stripslashes( $_REQUEST['search_location'] ) ) : '';
		$search_keywords   = isset( $_REQUEST['search_keywords'] ) ? sanitize_text_field( stripslashes( $_REQUEST['search_keywords'] ) ) : '';
		$search_categories = isset( $_REQUEST['search_categories'] ) ? $_REQUEST['search_categories'] : array();
		$search_amenities  = isset( $_REQUEST['search_amenities'] ) ? $_REQUEST['search_amenities'] : '';
		$search_tags       = isset( $_REQUEST['search_tags'] ) ? $_REQUEST['search_tags'] : '';
		$price_range       = isset( $_REQUEST['price_range'] ) ? $_REQUEST['price_range'] : '';
		$open_now          = isset( $_REQUEST['open_now'] ) ? true : null;
		$nearby            = isset( $_REQUEST['nearby'] ) ? true : null;
		$nearby_coords     = isset( $_REQUEST['nearby_coords'] ) ? $_REQUEST['nearby_coords'] : '';
		$orderby           = isset( $_REQUEST['search_orderby'] ) ? $_REQUEST['search_orderby'] : null;

		// filter / select categories
		foreach ( array( $search_categories, $search_amenities, $search_tags ) as $values ) {
			if ( is_array( $values ) ) {
				$values = array_filter( array_map( 'sanitize_text_field', array_map( 'stripslashes', $values ) ) );
			} else {
				$values = array_filter( array( sanitize_text_field( stripslashes( $values ) ) ) );
			}
		}

		$args = array(
			'page_id'          => $page_id,
			'search_keywords'  => $search_keywords,
			'search_location'  => $search_location,
			'search_amenities' => $search_amenities,
			'search_tags'      => $search_tags,
			'price_range'      => $price_range,
			'open_now'         => $open_now,
			'nearby'           => $nearby,
			'nearby_coords'    => $nearby_coords,
			'search_orderby'   => $orderby,
		);
		if ( isset( $search_categories ) && ! empty( $search_categories ) ) {
			$args['search_categories'] = $search_categories;
		}

		return $args;
	}

	/**
	 * Get listings results
	 *
	 * @param array $args
	 */
	public function get_listings( $args = array() ) {
		$result = array();
		$args   = $this->get_updated_args();

		$args = apply_filters( 'job_manager_get_listings_args', $args );

		$args['orderby']        = isset( $args['search_orderby'] ) ? $args['search_orderby'] : 'featured';
		$args['posts_per_page'] = get_option( 'job_manager_per_page' );
		$args['offset']         = ( absint( lisner_get_var( $_REQUEST['page'] ) ) - 1 ) * get_option( 'job_manager_per_page' );
		$listings               = get_job_listings( $args );
		$result                 = array(
			'found_listings' => $listings->have_posts(),
			'max_num_pages'  => $listings->max_num_pages,
			'data'           => $args
		);
		$pagination_args        = array(
			'max_num_pages' => $listings->max_num_pages,
			'current_page'  => absint( lisner_get_var( $_REQUEST['page'] ) )
		);
		ob_start();
		if ( $result['found_listings'] ) :
			include lisner_helper::get_template_part( 'listing-query', 'listing', $args );
		else:
			include lisner_helper::get_template_part( 'listing-query-no-content', 'listing', $args );
		endif;
		$result['pagination'] = include lisner_helper::get_template_part( 'pagination', 'partials', $pagination_args );
		$result['html']       = ob_get_clean();

		wp_send_json( $result, $args );
	}

}

/**
 * Instantiate class
 *
 * @return lisner_search|null
 */
function lisner_search() {
	return lisner_search::instance();
}
