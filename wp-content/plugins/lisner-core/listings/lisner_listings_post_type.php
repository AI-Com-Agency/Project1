<?php

/**
 * Class lisner_listings_post_type
 *
 * @author pebas
 * @ver 1.0.0
 */

class lisner_listings_post_type {

	protected static $_instance = null;

	public $strings, $text_domains, $theme;

	/**
	 * @return null|lisner_listings_post_type
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_listings_post_type constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
	}

	/**
	 * Retrieves permalink settings.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.0.8/includes/wc-core-functions.php#L1573
	 * @since 1.28.0
	 * @return array
	 */
	public static function get_permalink_structure() {
		// Switch to the site's default locale, bypassing the active user's locale.
		if ( function_exists( 'switch_to_locale' ) && did_action( 'admin_init' ) ) {
			switch_to_locale( get_locale() );
		}

		$permalinks = wp_parse_args(
			(array) get_option( 'lisner_permalinks', array() ),
			array(
				'location_base'   => '',
				'lisner_tag_base' => '',
				'amenity_base'    => '',
			)
		);

		// Ensure rewrite slugs are set.
		$permalinks['location_rewrite_slug']   = untrailingslashit( empty( $permalinks['location_base'] ) ? _x( 'location', 'Location permalink - resave permalinks after changing this', 'lisner-core' ) : $permalinks['location_base'] );
		$permalinks['amenity_rewrite_slug']    = untrailingslashit( empty( $permalinks['amenity_base'] ) ? _x( 'amenity', 'Amenity permalink - resave permalinks after changing this', 'lisner-core' ) : $permalinks['amenity_base'] );
		$permalinks['lisner_tag_rewrite_slug'] = untrailingslashit( empty( $permalinks['lisner_tag_base'] ) ? _x( 'listing-tag', 'Tag permalink - resave permalinks after changing this', 'lisner-core' ) : $permalinks['lisner_tag_base'] );

		// Restore the original locale.
		if ( function_exists( 'restore_current_locale' ) && did_action( 'admin_init' ) ) {
			restore_current_locale();
		}

		return $permalinks;
	}

	/**
	 * Register listing post type taxonomies
	 */
	public function register_taxonomies() {
		if ( post_type_exists( 'job_listing' ) ) {
			return;
		}

		$permalink_structure = self::get_permalink_structure();

		// Listing Taxonomy / Amenity
		$singular = esc_html__( 'Listing Amenity', 'lisner-core' );
		$plural   = esc_html__( 'Listing Amenities', 'lisner-core' );

		$rewrite = array(
			'slug'         => $permalink_structure['amenity_rewrite_slug'],
			'with_front'   => false,
			'hierarchical' => false
		);
		$public  = true;

		register_taxonomy( 'listing_amenity', apply_filters( 'register_taxonomy_listing_amenities_object_type', array( 'job_listing' ) ), apply_filters( 'register_taxonomy_listing_amenities_args', array(
			'hierarchical'          => true,
			'update_count_callback' => '_update_post_term_count',
			'label'                 => $plural,
			'labels'                => array(
				'name'              => $plural,
				'singular_name'     => $singular,
				'menu_name'         => ucwords( $plural ),
				'search_items'      => sprintf( esc_html__( 'Search %s', 'lisner-core' ), $plural ),
				'all_items'         => sprintf( esc_html__( 'All %s', 'lisner-core' ), $plural ),
				'parent_item'       => sprintf( esc_html__( 'Parent %s', 'lisner-core' ), $singular ),
				'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'lisner-core' ), $singular ),
				'edit_item'         => sprintf( esc_html__( 'Edit %s', 'lisner-core' ), $singular ),
				'update_item'       => sprintf( esc_html__( 'Update %s', 'lisner-core' ), $singular ),
				'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'lisner-core' ), $singular ),
				'new_item_name'     => sprintf( esc_html__( 'New %s Name', 'lisner-core' ), $singular )
			),
			'show_ui'               => true,
			'show_tagcloud'         => false,
			'show_in_rest'          => true,
			'public'                => $public,
			'rewrite'               => $rewrite,
		) ) );

		// Listing Taxonomy / Tags
		$singular = esc_html__( 'Listing Tag', 'lisner-core' );
		$plural   = esc_html__( 'Listing Tags', 'lisner-core' );

		$rewrite = array(
			'slug'         => $permalink_structure['lisner_tag_rewrite_slug'],
			'with_front'   => false,
			'hierarchical' => false
		);
		$public  = true;

		register_taxonomy( 'listing_tag', apply_filters( 'register_taxonomy_listing_tags_object_type', array( 'job_listing' ) ), apply_filters( 'register_taxonomy_listing_tag_args', array(
			'hierarchical'          => false,
			'update_count_callback' => '_update_post_term_count',
			'label'                 => $plural,
			'labels'                => array(
				'name'              => $plural,
				'singular_name'     => $singular,
				'menu_name'         => ucwords( $plural ),
				'search_items'      => sprintf( esc_html__( 'Search %s', 'lisner-core' ), $plural ),
				'all_items'         => sprintf( esc_html__( 'All %s', 'lisner-core' ), $plural ),
				'parent_item'       => sprintf( esc_html__( 'Parent %s', 'lisner-core' ), $singular ),
				'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'lisner-core' ), $singular ),
				'edit_item'         => sprintf( esc_html__( 'Edit %s', 'lisner-core' ), $singular ),
				'update_item'       => sprintf( esc_html__( 'Update %s', 'lisner-core' ), $singular ),
				'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'lisner-core' ), $singular ),
				'new_item_name'     => sprintf( esc_html__( 'New %s Name', 'lisner-core' ), $singular )
			),
			'show_ui'               => true,
			'show_tagcloud'         => true,
			'show_in_rest'          => true,
			'public'                => $public,
			'rewrite'               => $rewrite,
		) ) );


		// Listing Taxonomy / Location
		$singular = esc_html__( 'Listing Location', 'lisner-core' );
		$plural   = esc_html__( 'Listing Locations', 'lisner-core' );

		$rewrite = array(
			'slug'         => $permalink_structure['location_rewrite_slug'],
			'with_front'   => false,
			'hierarchical' => false
		);
		$public  = true;

		register_taxonomy( 'listing_location', apply_filters( 'register_taxonomy_listing_location_object_type', array( 'job_listing' ) ), apply_filters( 'register_taxonomy_listing_locations_args', array(
			'hierarchical'          => true,
			'update_count_callback' => '_update_post_term_count',
			'label'                 => $plural,
			'labels'                => array(
				'name'              => $plural,
				'singular_name'     => $singular,
				'menu_name'         => ucwords( $plural ),
				'search_items'      => sprintf( esc_html__( 'Search %s', 'lisner-core' ), $plural ),
				'all_items'         => sprintf( esc_html__( 'All %s', 'lisner-core' ), $plural ),
				'parent_item'       => sprintf( esc_html__( 'Parent %s', 'lisner-core' ), $singular ),
				'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'lisner-core' ), $singular ),
				'edit_item'         => sprintf( esc_html__( 'Edit %s', 'lisner-core' ), $singular ),
				'update_item'       => sprintf( esc_html__( 'Update %s', 'lisner-core' ), $singular ),
				'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'lisner-core' ), $singular ),
				'new_item_name'     => sprintf( esc_html__( 'New %s Name', 'lisner-core' ), $singular )
			),
			'show_ui'               => true,
			'show_tagcloud'         => false,
			'show_in_rest'          => true,
			'public'                => $public,
			'rewrite'               => $rewrite,
		) ) );
	}

}

/**
 * Instantiate class
 *
 * @return lisner_listings_post_type|null
 */
function lisner_listings_post_type() {
	return lisner_listings_post_type::instance();
}
