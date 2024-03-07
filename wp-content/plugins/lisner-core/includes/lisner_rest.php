<?php

/**
 * Class lisner_rest
 *
 * @author includes
 * @version 1.0.0
 */

class lisner_rest extends WP_REST_Controller {

	protected static $_instance = null;

	public $namespace = 'lisner/v2';

	/**
	 * @return null|lisner_rest
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * tbm_rest constructor.
	 */
	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		add_action( 'lisner_ajax_get_keyword', array( $this, 'get_keyword' ) );
		add_action( 'lisner_ajax_nopriv_get_keyword', array( $this, 'get_keyword' ) );
		add_action( 'lisner_ajax_get_listing_preview', array( $this, 'get_listing_preview' ) );
		add_action( 'lisner_ajax_nopriv_get_listing_preview', array( $this, 'get_listing_preview' ) );
		add_action( 'lisner_ajax_update_listing_likes', array( $this, 'update_listing_likes' ) );
		add_action( 'lisner_ajax_nopriv_update_listing_likes', array( $this, 'update_listing_likes' ) );
		add_action( 'lisner_ajax_contact_listing', array( $this, 'contact_listing' ) );
		add_action( 'lisner_ajax_nopriv_contact_listing', array( $this, 'contact_listing' ) );
		add_action( 'lisner_ajax_nopriv_newsletter_ajax', array( $this, 'newsletter_ajax_render' ) );
		add_action( 'lisner_ajax_newsletter_ajax', array( $this, 'newsletter_ajax_render' ) );
		//add_action( 'wp_mail_failed', array( $this, 'mail_error' ), 10, 1 );

		// add image size
		add_image_size( 'dropdown_thumbnail', 35, 35, true );
	}

	public function register_routes() {
		// search keywords in taxonomies
		register_rest_route( $this->namespace, '/listing_taxonomy' );
		register_rest_route( $this->namespace, '/listing_taxonomy/(?P<name>[w\W\s\S\d\D]+)', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'get_keyword_in_taxonomy' )
		) );
	}

	/**
	 * Get listing quick preview modal
	 */
	public function get_listing_preview() {
		$id                 = lisner_get_var( $_REQUEST['id'] );
		$lat                = esc_attr( get_post_meta( $id, 'geolocation_lat', true ) );
		$long               = esc_attr( get_post_meta( $id, 'geolocation_long', true ) );
		$result             = array();
		$atts['listing_id'] = $id;
		ob_start();
		include lisner_helper::get_template_part( 'listing-preview-modal', 'shortcodes/listing/partials', $atts );
		$template = ob_get_clean();

		$result['coords']['lat']  = $lat;
		$result['coords']['long'] = $long;
		$result['html']           = $template;

		wp_send_json( $result );
	}

	/**
	 * Contact listing functionality
	 */
	public function contact_listing() {
		$name    = lisner_get_var( sanitize_text_field( $_REQUEST['listing_name'] ) );
		$email   = lisner_get_var( sanitize_email( $_REQUEST['listing_email'] ) );
		$message = lisner_get_var( sanitize_textarea_field( $_REQUEST['listing_message'] ) );
		$id      = lisner_get_var( sanitize_text_field( $_REQUEST['listing_id'] ) );

		// get the right listing owner email address
		$owner       = get_post_meta( $id, '_job_author', true );
		$owner_data  = get_userdata( $owner );
		$owner_email = get_post_meta( $id, '_listing_email', true );
		$owner_email = lisner_get_var( $owner_email, $owner_data->user_email );
		$nonce_value = lisner_get_var( $_REQUEST['_contact_listing'],
			lisner_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

		if ( ! wp_verify_nonce( $nonce_value, 'contact_listing' ) ) {
			return;
		}
		$site = get_bloginfo( 'site_title' );

		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html;";
		$headers[] = "From: " . $name . "<" . $email . ">";
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
		$subject   = esc_html__( "[{$site}] New message from your listing page!", 'lisner-core' );

		if ( is_email( $email ) ) {
			wp_mail( $owner_email, $subject, $message, $headers );
			$result['success'] = esc_html__( 'Your email has been sent!', 'lisner-core' );
		} else {
			$result['error'] = esc_html__( 'Please enter real the email address', 'lisner-core' );
		}

		wp_send_json( $result );
	}

	/**
	 * Return email errors
	 *
	 * @param $wp_error
	 *
	 * @return mixed
	 */
	public function mail_error( $wp_error ) {
		return print_r( $wp_error );
	}


	/**
	 * Update listing likes count
	 */
	public function update_listing_likes() {
		$ip        = isset( $_REQUEST['ip'] ) ? $_REQUEST['ip'] : '';
		$id        = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';
		$ips       = get_post_meta( $id, 'listing_likes_ip' );
		$cur_likes = get_post_meta( $id, 'listing_likes', true );

		$ips = implode( PHP_EOL, $ips );
		$ips = explode( PHP_EOL, $ips );
		if ( ! in_array( $ip, $ips ) ) {
			$ips[] = $ip;
			$ips   = array( implode( PHP_EOL, $ips ) );
			update_post_meta( $id, 'listing_likes_ip', implode( ',', $ips ) );
			if ( empty( $cur_likes ) ) {
				update_post_meta( $id, 'listing_likes', 1 );
			} else {
				$cur_likes ++;
				update_post_meta( $id, 'listing_likes', $cur_likes );
			}
		} else {
			$cur_likes --;
			update_post_meta( $id, 'listing_likes', $cur_likes );
			unset( $ips[ array_search( $ip, $ips ) ] );
			$ips = array( implode( PHP_EOL, $ips ) );
			update_post_meta( $id, 'listing_likes_ip', implode( ',', $ips ) );
		}
		$result['likes_count'] = get_post_meta( $id, 'listing_likes', true );
		$result['error']       = false;
		$result['notice']      = esc_html__( 'Thanks for response!', 'lisner-core' );

		wp_send_json( $result );
	}

	/**
	 * Rest search for keyword
	 *
	 * @param WP_REST_Request $data
	 *
	 * @return array|null|object
	 */
	public function get_keyword_in_taxonomy( WP_REST_Request $data ) {
		global $wpdb;
		$key = $data['name'];

		$listings    = get_posts( array(
			'post_type'      => 'job_listing',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			's'              => $key,
		) );
		$listing_ids = array();
		foreach ( $listings as $listing ) {
			$listing_ids[] = $listing->ID;
		}
		$listings = implode( ',', $listing_ids );

		$conditional = ! empty( $listings ) ? " OR tr.object_id IN ( " . esc_sql( $listings ) . " ) )" : ')';
		$results     = $wpdb->get_results( $wpdb->prepare( "
				  SELECT DISTINCT terms.term_id, terms.name FROM {$wpdb->terms} AS terms
				  LEFT JOIN {$wpdb->term_taxonomy} AS tax
				  ON terms.term_id = tax.term_id
				  LEFT JOIN {$wpdb->term_relationships} AS tr
				  ON tr.term_taxonomy_id = tax.term_taxonomy_id
				  WHERE tax.taxonomy = 'job_listing_category'
				  AND ( terms.name LIKE %s" . esc_sql( $conditional ), '%$key%' ) );

		$terms = array();

		if ( ! $wpdb->last_error ) {
			if ( $results ) {
				foreach ( $results as $result ) {
					$taxonomy                              = get_term_by( 'id', $result->term_id,
						'job_listing_category' );
					$terms[ $result->term_id ]['term_id']  = $taxonomy->term_id;
					$terms[ $result->term_id ]['name']     = $taxonomy->name;
					$terms[ $result->term_id ]['taxonomy'] = $taxonomy->taxonomy;
					$terms[ $result->term_id ]['count']    = $taxonomy->count;
				}
			} else {
				$terms['no-results'] = esc_html__( 'No results matching your query', 'lisner-core' );
			}
		} else {
			$terms['wpdb-error'] = esc_html__( 'There has been an error', 'lisner-core' );
		}

		return $results;
	}

	/**
	 * Ajax search for keyword
	 */
	public function get_keyword() {
		global $wpdb;
		$option = get_option( 'pbs_option' );
		$key    = isset( $_REQUEST['search_keywords'] ) ? $_REQUEST['search_keywords'] : '';

		$limit = get_post_meta( get_option( 'page_on_front' ), 'home_search_limit_listings', true );
		$limit = isset( $limit ) && ! empty( $limit ) ? $limit : 8;

		$products = $this->get_products_by_keyword( $key, array( 'posts_per_page' => $limit ) );

		$listings       = $this->get_listings_by_keyword( $key, array( 'posts_per_page' => $limit ) );
		$listing_ids    = $this->get_listing_ids( $key ); // get listing ids by keyword
		$listing_titles = $this->get_listing_titles( $key ); // get listing titles by keyword
		$conditional    = ! empty( $listing_ids ) ? " OR tr.object_id IN ( " . esc_sql( $listing_ids ) . " ) )" : ')';

		$results = $wpdb->get_results( $wpdb->prepare( "
				  SELECT DISTINCT terms.term_id, terms.name FROM {$wpdb->terms} AS terms
				  LEFT JOIN {$wpdb->term_taxonomy} AS tax
				  ON terms.term_id = tax.term_id
				  LEFT JOIN {$wpdb->term_relationships} AS tr
				  ON tr.term_taxonomy_id = tax.term_taxonomy_id
				  WHERE tax.taxonomy = 'job_listing_category'
				  AND ( terms.name LIKE %s" . esc_sql( $conditional ), "%$key" ) );

		$terms          = array();
		$terms['limit'] = $limit;
		if ( $listing_ids ) {
			$terms['listing_ids'] = $listing_ids;
		}

		if ( ! $wpdb->last_error ) {
			if ( $results ) { // get available terms
				// add keyword to the list
				if ( ! empty( $key ) ) {
					if ( $listing_titles ) {
						$count = 0;
						foreach ( $listing_titles as $string ) {
							$terms["keyword_alt_{$count}"]['type']              = 'keyword_alt';
							$terms["keyword_alt_{$count}"]['suggestion']        = $string;
							$terms["keyword_alt_{$count}"]['custom_suggestion'] = '<span class="searched-terms-keyword" data-keyword="' . esc_html( $string ) . '">' . esc_html( $string ) . '</span>';
							$count ++;
						}
					}
					$terms['keyword']['type']        = 'keyword';
					$terms['keyword']['name']        = $key;
					$terms['keyword']['custom_name'] = '<span class="searched-terms-keyword" data-keyword="' . esc_html( $terms['keyword']['name'] ) . '">' . esc_html( $terms['keyword']['name'] ) . '</span>';
				}
				foreach ( $results as $result ) {
					$taxonomy                                 = get_term_by( 'id', $result->term_id,
						'job_listing_category' );
					$terms[ $result->term_id ]['type']        = 'taxonomy';
					$terms[ $result->term_id ]['term_id']     = $taxonomy->term_id;
					$terms[ $result->term_id ]['name']        = $taxonomy->name;
					$terms[ $result->term_id ]['taxonomy']    = $taxonomy->taxonomy;
					$terms[ $result->term_id ]['count']       = $taxonomy->count;
					$terms[ $result->term_id ]['custom_name'] = '<span class="searched-terms-keyword" data-keyword="' . esc_html( $key ) . '">' . esc_html( $terms['keyword']['name'] ) . '</span><span class="searched-terms-in">' . __( 'in',
							'lisner-core' ) . '</span><span class="searched-terms-title">' . $taxonomy->name . '</span>';
					$icon                                     = get_term_meta( $taxonomy->term_id, 'term_icon', true );
					$terms[ $result->term_id ]['icon']        = isset( $icon ) && ! empty( $icon ) ? $icon : '';
				}
			}
			if ( $listings ) { // get available listings
				foreach ( $listings as $listing ) {
					$city                           = get_post_meta( $listing->ID, 'geolocation_city', true );
					$city_alt                       = get_post_meta( $listing->ID, '_job_location', true );
					$city                           = isset( $city ) && ! empty( $city ) ? $city : ( isset( $city_alt ) && ! empty( $city_alt ) ? $city_alt : '' );
					$image                          = rwmb_meta( '_listing_gallery', array(
						'size'  => 'dropdown_thumbnail',
						'limit' => 1
					), $listing->ID );
					$image                          = ! empty( $image ) ? array_shift( $image ) : '';
					$terms[ $listing->ID ]['type']  = 'listing';
					$terms[ $listing->ID ]['ID']    = $listing->ID;
					$terms[ $listing->ID ]['name']  = wpjm_get_the_job_title( $listing->ID );
					$terms[ $listing->ID ]['guid']  = get_permalink( $listing->ID );
					$terms[ $listing->ID ]['city']  = $city;
					$terms[ $listing->ID ]['image'] = ! empty( $image ) ? $image['url'] : '';
				}
			}

			$include_products = isset( $option['general-products-search'] ) && ! empty( $option['general-products-search'] ) ? $option['general-products-search'] : 'no';
			if ( lisner_helper::is_plugin_active( 'woocommerce' ) && 'yes' == $include_products && $products ) { // get available products
				foreach ( $products as $product ) {
					$product                              = wc_get_product( $product->ID );
					$image                                = $product->get_image( 'dropdown_thumbnail' );
					$terms[ $product->get_id() ]['type']  = 'product';
					$terms[ $product->get_id() ]['ID']    = $product->get_id();
					$terms[ $product->get_id() ]['name']  = $product->get_title();
					$terms[ $product->get_id() ]['guid']  = $product->get_permalink();
					$terms[ $product->get_id() ]['price'] = $product->get_price_html();
					$terms[ $product->get_id() ]['image'] = $image;
				}
			}

			$all_results = $terms;
			unset( $all_results[ array_search( $all_results['limit'], $all_results ) ] );
			if ( empty( $all_results ) ) { // display error if no results
				$terms['no_results'] = sprintf( __( 'Sorry, but no results for %s keyword!', 'lisner-core' ),
					'<strong>' . $key . '</strong>' );
			}
		} else { // display database error
			$terms['wpdb_error'] = esc_html__( 'There has been an error', 'lisner-core' );
		}

		wp_send_json( $terms );
	}

	/**
	 * get WooCommerce products by given keyword
	 *
	 * @param $key
	 * @param $options
	 *
	 * @return array
	 */
	public function get_products_by_keyword( $key, $options = array() ) {
		$defaults = array(
			'post_type'   => 'product',
			'post_status' => 'publish',
			'numberposts' => - 1,
			's'           => $key,
		);
		$args     = array_merge( $defaults, $options );
		$products = get_posts( $args );

		return $products;

	}

	/**
	 * get listings by given keyword
	 *
	 * @param $key
	 * @param $options
	 *
	 * @return array
	 */
	public function get_listings_by_keyword( $key, $options = array() ) {
		$defaults = array(
			'post_type'   => 'job_listing',
			'post_status' => 'publish',
			'numberposts' => - 1,
			's'           => $key,
		);
		$args     = array_merge( $defaults, $options );
		$listings = get_posts( $args );

		return $listings;

	}

	/**
	 * Get array of listing ids by given keyword
	 *
	 * @param $key
	 *
	 * @return array|string
	 */
	public function get_listing_ids( $key ) {
		$listings    = $this->get_listings_by_keyword( $key );
		$listing_ids = array();
		foreach ( $listings as $listing ) {
			$listing_ids[] = $listing->ID;
		}
		$listings = implode( ',', $listing_ids );

		return $listings;
	}

	public function get_listing_titles( $key ) {
		$listings       = $this->get_listings_by_keyword( $key );
		$listing_titles = array();
		$titles         = array();
		foreach ( $listings as $listing ) {
			$listing_titles[] = $listing->post_title;
		}
		foreach ( $listing_titles as $title ) {
			preg_match( '/\b(' . $key . '\w+)\b/i', $title, $matches );
			if ( $matches ) {
				if ( ! in_array( $matches[1], $titles ) ) {
					$titles[] = $matches[1];
				}
			}
		}

		return $titles;
	}

	public function newsletter_ajax_render() {
		$email  = isset( $_REQUEST["email"] ) ? $_REQUEST["email"] : '';
		$result = array();
		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			require_once LISNER_DIR . 'includes/mailchimp/mailchimp.php';
			$chimp_api     = ! empty( $_REQUEST['mailchimp_api'] ) ? $_REQUEST['mailchimp_api'] : '';
			$chimp_list_id = ! empty( $_REQUEST['mailchimp_list_id'] ) ? $_REQUEST['mailchimp_list_id'] : '';
			if ( ! empty( $chimp_api ) && ! empty( $chimp_list_id ) ) {
				$mc           = new MailChimp( $chimp_api );
				$chimp_result = $mc->call( 'lists/subscribe', array(
					'id'    => $chimp_list_id,
					'email' => array( 'email' => $email )
				) );

				if ( $chimp_result === false ) {
					$result['title'] = esc_html__( 'API Error', 'lisner-core' );
					$result['text']  = esc_html__( 'There was an error contacting the API, please try again.',
						'lisner-core' );
					$result['type']  = esc_html( 'error' );
				} elseif ( $chimp_result['name'] === 'List_AlreadySubscribed' ) {
					$json_result     = wp_json_encode( $chimp_result['error'] );
					$result['title'] = esc_html__( 'Already Subscribed', 'lisner-core' );
					$result['text']  = esc_html( str_replace( array( '"', "\\" ), array( '', '' ), $json_result ) );
					$result['type']  = esc_html( 'info' );
				} elseif ( isset( $chimp_result['status'] ) && $chimp_result['status'] == 'error' ) {
					$json_result     = wp_json_encode( $chimp_result['error'] );
					$result['title'] = esc_html__( 'Error', 'lisner-core' );
					$result['text']  = esc_html( str_replace( array( '"', '\\' ), array( '', '' ), $json_result ) );
					$result['type']  = esc_html( 'error' );
				} else {
					$result['title'] = esc_html__( 'Subscribed!', 'lisner-core' );
					$result['text']  = esc_html__( 'You have successfully subscribed to the newsletter.',
						'lisner-core' );
					$result['type']  = esc_html( 'success' );
				}
			} else {
				$result['title'] = esc_html__( 'API Not Set!', 'lisner-core' );
				$result['text']  = esc_html__( 'API data has not been yet set.', 'lisner-core' );
				$result['type']  = esc_html( 'error' );
			}
		} else {
			$result['title'] = esc_html__( 'Email Error!', 'lisner-core' );
			$result['text']  = esc_html__( 'Email address you provided is either empty or invalid.', 'lisner-core' );
			$result['type']  = esc_html( 'error' );
		}
		wp_send_json( $result );
	}

}

/**
 * Instantiate class
 *
 * @return lisner_rest|null
 */
function lisner_rest() {
	return lisner_rest::instance();
}
