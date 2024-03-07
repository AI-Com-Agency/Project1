<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles front admin page for Lisner Core.
 *
 * @package lisner-core
 * @since 1.1.7
 */
class lisner_listings_permalink_settings {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.27.0
	 */
	private static $_instance = null;

	/**
	 * Permalink settings.
	 *
	 * @var array
	 * @since 1.27.0
	 */
	private $permalinks = array();

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.1.7
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->setup_fields();
		$this->settings_save();
		$this->permalinks = lisner_listings_post_type::get_permalink_structure();
	}

	/**
	 * Add setting fields related to permalinks.
	 */
	public function setup_fields() {
		add_settings_field(
			'lisner_location_base_slug',
			__( 'Listing Location base', 'lisner-core' ),
			array( $this, 'location_base_slug_input' ),
			'permalink',
			'optional'
		);
		add_settings_field(
			'lisner_amenity_base_slug',
			__( 'Listing Amenity base', 'lisner-core' ),
			array( $this, 'amenity_base_slug_input' ),
			'permalink',
			'optional'
		);
		add_settings_field(
			'lisner_tag_base_slug',
			__( 'Listing Tag base', 'lisner-core' ),
			array( $this, 'tag_base_slug_input' ),
			'permalink',
			'optional'
		);
	}

	/**
	 * Show a slug input box for location taxonomy slug.
	 */
	public function location_base_slug_input() {
		?>
		<input name="lisner_location_base_slug" type="text" class="regular-text code"
		       value="<?php echo esc_attr( $this->permalinks['location_base'] ); ?>"
		       placeholder="<?php echo esc_attr_x( 'location', 'Location permalink - resave permalinks after changing this', 'lisner-core' ); ?>" />
		<?php
	}

	/**
	 * Show a slug input box for amenity taxonomy slug.
	 */
	public function amenity_base_slug_input() {
		?>
		<input name="lisner_amenity_base_slug" type="text" class="regular-text code"
		       value="<?php echo esc_attr( $this->permalinks['amenity_base'] ); ?>"
		       placeholder="<?php echo esc_attr_x( 'amenity', 'Amenity permalink - resave permalinks after changing this', 'lisner-core' ); ?>" />
		<?php
	}

	/**
	 * Show a slug input box for tag taxonomy slug.
	 */
	public function tag_base_slug_input() {
		?>
		<input name="lisner_tag_base_slug" type="text" class="regular-text code"
		       value="<?php echo esc_attr( $this->permalinks['lisner_tag_base'] ); ?>"
		       placeholder="<?php echo esc_attr_x( 'listing-tag', 'Tag permalink - resave permalinks after changing this', 'lisner-core' ); ?>" />
		<?php
	}

	/**
	 * Save the settings.
	 */
	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		if ( isset( $_POST['permalink_structure'] ) ) {
			if ( function_exists( 'switch_to_locale' ) ) {
				switch_to_locale( get_locale() );
			}

			$permalinks                    = (array) get_option( 'lisner_permalinks', array() );
			$permalinks['location_base']   = sanitize_title_with_dashes( $_POST['lisner_location_base_slug'] );
			$permalinks['amenity_base']    = sanitize_title_with_dashes( $_POST['lisner_amenity_base_slug'] );
			$permalinks['lisner_tag_base'] = sanitize_title_with_dashes( $_POST['lisner_tag_base_slug'] );

			update_option( 'lisner_permalinks', $permalinks );

			if ( function_exists( 'restore_current_locale' ) ) {
				restore_current_locale();
			}
		}
	}
}

lisner_listings_permalink_settings::instance();
