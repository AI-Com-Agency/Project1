<?php

/**
 * Class lisner_listings
 *
 * @author pebas
 * @ver 1.0.0
 */

class lisner_listings {

	protected static $_instance = null;

	public $strings, $text_domains, $theme;

	/**
	 * @return null|lisner_listings
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		//todo there's an issue with wp job manager causing this template to be called twice.
		//todo $pbs_edit_job_count is used to prevent that.
		$pbs_edit_job_count = 0;
		$this->theme        = get_translations_for_domain( 'lisner-core' );
		$this->strings      = $this->get_strings();
		$this->text_domains = array_keys( $this->strings );
		// actions
		add_action( 'init', array( $this, 'register_new_fields' ) );
		add_action( 'init', array( $this, 'adjust_default_taxonomies' ), 0 );
		add_action( 'admin_init', array(
			$this,
			'unregister_listing_types_taxonomies_and_options'
		) ); // remove job type taxonomy
		add_action( 'do_meta_boxes', array( $this, 'remove_job_meta_box' ) ); // remove default job meta boxes
		add_action( 'after_setup_theme', array( $this, 'change_wp_job_slug' ) ); // change job slug
		// Update WP Job Manager Data
		add_action( 'rwmb_after_save_post', array( $this, 'save_listing' ) );
		add_action( 'rwmb_page_add_listing_after_save_field', array( $this, 'set_add_listing_page_template' ), 10, 5 );
		add_filter( 'template_include', array( $this, 'load_add_listing_page_template' ) );
		add_post_type_support( 'job_listing', 'post_tag' );
		// Call ajax video preview
		add_action( 'lisner_ajax_lisner_get_embed', array( $this, 'ajax_get_embed' ) );
		add_action( 'lisner_ajax_nopriv_lisner_get_embed', array( $this, 'ajax_get_embed' ) );
		add_filter( 'oembed_result', array( $this, 'oembed_result' ), 10, 3 );

		// Edit Listing
		add_action( 'job_manager_update_job_data', array( $this, 'update_listing' ), 10, 2 );

		// filters
		add_filter( 'submit_job_form_fields', array( $this, 'frontend_fields' ) );
		add_filter( 'job_manager_job_listing_data_fields', array( $this, 'admin_fields' ) );
		// Update WP Job Manager template override folder.
		add_filter( 'job_manager_locate_template', array( $this, 'job_manager_locate_template' ), 10, 3 );
		add_filter( 'woocommerce_locate_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );
		// Update WP Job Manager settings
		add_filter( 'job_manager_settings', array( $this, 'configure_settings' ) );
		add_filter( 'job_manager_upload_dir', function () {
			global $job_manager_uploading_file;

			return '';
		} );
		add_filter( 'register_post_type_job_listing', function ( $args ) {
			$args['show_in_rest'] = true;

			return $args;
		} );
		add_filter( 'job_manager_show_addons_page', '__return_false' );
		add_filter( 'body_class', function ( $classes ) {
			if ( is_page( get_option( 'job_manager_submit_job_form_page_id' ) ) ) {
				$classes[] = 'page-lisner-add-listing';
			}

			return $classes;
		}, 10, 1 );
		add_theme_support( 'job-manager-templates' );

		// call single page modals
		add_action( 'pbs_footer_after', array( $this, 'share_modal' ) );

		// upgrade geocoded address
		add_filter( 'job_manager_geolocation_get_location_data', array( $this, 'update_geocoded_address' ), 10, 2 );

	}

	/**
	 * Register new fields
	 */
	public function register_new_fields() {
		add_action( 'job_manager_input_working-hours', function ( $key, $field ) {
			require trailingslashit( LISNER_DIR ) . 'templates/listing/form-fields/working-hours.php';
		} );
		add_action( 'job_manager_input_map', function ( $key, $field ) {
			require trailingslashit( LISNER_DIR ) . 'templates/listing/form-fields/map.php';
		} );
	}

	/**
	 * Add default taxonomies options to show in rest
	 * ----------------------------------------------
	 */
	public function adjust_default_taxonomies() {
		add_filter( 'register_taxonomy_job_listing_category_args', function ( $args ) {
			$args['show_in_rest'] = true;

			return $args;
		} );
	}

	/**
	 * Translate default WP Job Manager post type strings
	 *
	 * @param $text
	 *
	 * @return string
	 */
	private function translate( $text ) {
		if ( is_array( $text ) ) {
			return vsprintf( $this->theme->translate[ $text[0] ], $text[1] );
		}

		return $this->theme->translate( $text ) ?: $text;
	}

	/**
	 * Get default WP Job Manager post type strings
	 * @return array
	 */
	private function get_strings() {
		return array(
			'wp-job-manager' => array(
				'Job'                                                             => __( 'Listing', 'lisner-core' ),
				'Jobs'                                                            => __( 'Listings', 'lisner-core' ),
				'job'                                                             => __( 'listing', 'lisner-core' ),
				'jobs'                                                            => __( 'listings', 'lisner-core' ),
				'Job Listings'                                                    => __( 'Listings', 'lisner-core' ),
				'Job category'                                                    => __( 'Listing Category',
					'lisner-core' ),
				'Job categories'                                                  => __( 'Listing Categories',
					'lisner-core' ),
				'Job Categories'                                                  => __( 'Listing Categories',
					'lisner-core' ),
				'job-category'                                                    => __( 'listing-category',
					'lisner-core' ),
				'Job type'                                                        => __( 'Listing Type',
					'lisner-core' ),
				'Job types'                                                       => __( 'Listing Types',
					'lisner-core' ),
				'Job Types'                                                       => __( 'Listing Types',
					'lisner-core' ),
				'Job base'                                                        => __( 'Listing base',
					'lisner-core' ),
				'Job category base'                                               => __( 'Listing category base',
					'lisner-core' ),
				'Job type base'                                                   => __( 'Listing type base',
					'lisner-core' ),
				'job-type'                                                        => __( 'listing-type',
					'lisner-core' ),
				'Jobs will be shown if within ANY selected category'              => __( 'Listings will be shown if within ANY selected category',
					'lisner-core' ),
				'Jobs will be shown if within ALL selected categories'            => __( 'Listings will be shown if within ALL selected categories',
					'lisner-core' ),
				'Position filled?'                                                => __( 'Listing filled?',
					'lisner-core' ),
				'A video about your company'                                      => __( 'A video about your listing',
					'lisner-core' ),
				'Job Submission'                                                  => __( 'Listing Submission',
					'lisner-core' ),
				'Submit Job Form Page'                                            => __( 'Submit Listing Form Page',
					'lisner-core' ),
				'Job Dashboard Page'                                              => __( 'Listing Dashboard Page',
					'lisner-core' ),
				'Job Listings Page'                                               => __( 'Listings Page',
					'lisner-core' ),
				'Add a job via the back-end'                                      => __( 'Add a listing via the back-end',
					'lisner-core' ),
				'Add a job via the front-end'                                     => __( 'Add a listing via the front-end',
					'lisner-core' ),
				'Find out more about the front-end job submission form'           => __( 'Find out more about the front-end listing submission form',
					'lisner-core' ),
				'View submitted job listings'                                     => __( 'View submitted listings',
					'lisner-core' ),
				'Add the [jobs] shortcode to a page to list jobs'                 => __( 'Add the [jobs] shortcode to a page to list listings',
					'lisner-core' ),
				'View the job dashboard'                                          => __( 'View the listing dashboard',
					'lisner-core' ),
				'Find out more about the front-end job dashboard'                 => __( 'Find out more about the front-end listing dashboard',
					'lisner-core' ),
				'Job Title'                                                       => __( 'Listing Name',
					'lisner-core' ),
				'Apply for job'                                                   => __( 'Apply', 'lisner-core' ),
				'To apply for this job please visit the following URL:
<a href="%1$s" target="_blank">%1$s &rarr;</a>'                   => __( 'To apply please visit the following URL: <a href="%1$s" target="_blank">%1$s &rarr;</a>',
					'lisner-core' ),
				'To apply for this job <strong>email your details to</strong>
<a class="job_application_email" href="mailto:%1$s%2$s">%1$s</a>' => __( 'To apply <strong>email your details to</strong> <a class="job_application_email" href="mailto:%1$s%2$s">%1$s</a>',
					'lisner-core' ),
				'Anywhere'                                                        => __( '&mdash;', 'lisner-core' ),
			),

			'pebas-paid-listings' => array(
				'Choose a package before entering job details'                         => __( 'Choose a package before entering listing details',
					'lisner-core' ),
				'Choose a package after entering job details'                          => __( 'Choose a package after entering listing details',
					'lisner-core' ),
				'Job Package'                                                          => __( 'Listing Package',
					'lisner-core' ),
				'Job Package Subscription'                                             => __( 'Listing Package Subscription',
					'lisner-core' ),
				'Job Listing'                                                          => __( 'Listing',
					'lisner-core' ),
				'Job listing limit'                                                    => __( 'Listing limit',
					'lisner-core' ),
				'Job listing duration'                                                 => __( 'Listing duration',
					'lisner-core' ),
				'The number of days that the job listing will be active.'              => __( 'The number of days that the listing will be active',
					'lisner-core' ),
				'Feature job listings?'                                                => __( 'Feature listings?',
					'lisner-core' ),
				'Feature this job listing - it will be styled differently and sticky.' => __( 'Feature this listing - it will be styled differently and sticky.',
					'lisner-core' ),
				'My Job Packages'                                                      => __( 'My Listing Packages',
					'lisner-core' ),
				'Jobs Remaining'                                                       => __( 'Listings Remaining',
					'lisner-core' ),
			),

		);
	}

	/**
	 * Change WP Job Manager slugs to match the theme
	 */
	public function change_wp_job_slug() {
		$this->theme        = get_translations_for_domain( 'lisner-core' );
		$this->strings      = $this->get_strings();
		$this->text_domains = array_keys( $this->strings );

		add_filter( 'gettext', function ( $translated, $text, $domain ) {
			if ( in_array( $domain, $this->text_domains ) && isset( $this->strings[ $domain ][ $text ] ) ) {
				return $this->translate( $this->strings[ $domain ][ $text ] );
			}

			return $translated;
		}, 0, 15 );

		add_filter( 'gettext_with_context', function ( $translated, $text, $context, $domain ) {
			if ( in_array( $domain, $this->text_domains ) && isset( $this->strings[ $domain ][ $text ] ) ) {
				return $this->translate( $this->strings[ $domain ][ $text ] );
			}

			return $translated;
		}, 0, 20 );

		// add image sizes
		add_image_size( 'listing_box', 385, 222, true );
		add_image_size( 'listing_preview_box', 450, 253, true );
		add_image_size( 'listing_single_image', 1920, 444, true );
		add_image_size( 'listing_single_gallery', 725, 444, true );
		add_image_size( 'listing_single_gallery_big', 725, 555, true );
		add_image_size( 'listing_single_popup', 1200, 9999, false );
	}


	/**
	 * Unregister default WP Job Manager type taxonomies and not needed options
	 */
	public function unregister_listing_types_taxonomies_and_options() {
		add_filter( 'pre_option_job_manager_enable_types', '__return_false' );
		add_filter( 'pre_option_job_manager_enable_categories', '__return_true' );
		update_option( 'job_manager_enable_categories', true );
		update_option( 'job_manager_enable_categories', 1 );
	}

	/**
	 * Configure WP Job Manager settings to match the theme
	 *
	 * @param $settings
	 *
	 * @return mixed
	 */
	public function configure_settings( $settings ) {
		$remove_settings = array(
			'job_manager_enable_types',
			'job_manager_multi_job_type',
		);

		if ( ! empty( $settings['job_listings'] ) ) {
			foreach ( $settings['job_listings'] as $setting_group_key => $setting_group ) {
				if ( ! is_array( $setting_group ) ) {
					continue;
				}

				foreach ( $setting_group as $setting_key => $setting ) {
					if ( ! is_array( $setting ) || empty( $setting['name'] ) ) {
						continue;
					}

					if ( in_array( $setting['name'], $remove_settings ) ) {
						unset( $settings['job_listings'][ $setting_group_key ][ $setting_key ] );
					}
				}
			}
		}

		return $settings;
	}

	/**
	 * Remove WP Job Manager default meta box
	 */
	public function remove_job_meta_box() {
		remove_meta_box( 'job_listing_data', 'job_listing', 'normal' );
		remove_meta_box( 'job_listing_type', 'job_listing', 'side' );
		remove_meta_box( 'job_listing_typediv', 'job_listing', 'side' );
	}

	/**
	 * Change WP Job Manager Template Paths to match the theme
	 *
	 * @param $template
	 * @param $template_name
	 * @param $template_path
	 *
	 * @return mixed
	 */
	public function job_manager_locate_template( $template, $template_name, $template_path ) {
		if ( file_exists( LISNER_DIR . "templates/{$template_name}" ) ) {
			$template = LISNER_DIR . "templates/{$template_name}";
		} elseif ( file_exists( LISNER_DIR . "templates/listing/{$template_name}" ) ) {
			$template = LISNER_DIR . "templates/listing/{$template_name}";
		}

		return apply_filters( 'lisner_job_manager_locate_template', $template, $template_name, $template_path );
	}


	/**
	 * Intercept WooCommerce templating
	 *
	 * @param $template
	 * @param $template_name
	 * @param $template_path
	 *
	 * @return string
	 */
	public function woocommerce_locate_template( $template, $template_name, $template_path ) {
		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path = LISNER_DIR . 'templates/woocommerce/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(

			array(
				$template_path . $template_name,
				$template_name
			)
		);

		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		// Use default template
		if ( ! $template ) {
			$template = $_template;
		}

		// Return what we found
		return $template;
	}

	/**
	 * Add admin fields if necessary ( Custom Meta Boxes are used instead )
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	public function admin_fields( $fields ) {
		$new_fields = array();

		return array_filter( $fields );
	}

	/**
	 * Add frontend fields to submit listing form
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	public function frontend_fields( $fields ) {
		$option = get_option( 'pbs_option' );

		foreach ( $fields as $key => $value ) {
			if ( in_array( $key, array( 'job', 'company' ) ) ) {
				unset( $fields[ $key ] );
			}
		}

		// default job fields
		$fields['job']['job_title'] = array(
			'label'       => __( 'Listing Title', 'lisner-core' ),
			'type'        => 'text',
			'required'    => true,
			'placeholder' => '',
			'priority'    => 1,
			'tooltip'     => esc_html__( 'Please enter the title of your listing', 'lisner-core' )
		);

		$fields['job']['job_description'] = array(
			'label'       => __( 'Listing Description', 'lisner-core' ),
			'type'        => 'wp-editor',
			'required'    => true,
			'placeholder' => '',
			'priority'    => 2,
			'tooltip'     => esc_html__( 'Please enter the description of your listing', 'lisner-core' )
		);

		if ( isset( $option['listing-fields-address'] ) && $option['listing-fields-address'] ) {
			$fields['job']['job_location'] = array(
				'label'       => __( 'Listing Address', 'lisner-core' ),
				'type'        => 'map',
				'required'    => isset( $option['listing-fields-address-required'] ) && $option['listing-fields-address-required'] ? true : false,
				'placeholder' => esc_html__( 'e.g. "London"', 'lisner-core' ),
				'priority'    => 3,
				'tooltip'     => esc_html__( 'Please enter the address of your listing or click on icon to geolocate yourself.',
					'lisner-core' )
			);
		}

		if ( isset( $option['listing-fields-categories'] ) && $option['listing-fields-categories'] ) {
			$fields['job']['job_category'] = array(
				'label'       => __( 'Listing Category', 'lisner-core' ),
				'type'        => 'term-multiselect',
				'required'    => isset( $option['listing-fields-categories-required'] ) && $option['listing-fields-categories-required'] ? true : false,
				'placeholder' => esc_html__( 'Select categories', 'lisner-core' ),
				'priority'    => 4,
				'taxonomy'    => 'job_listing_category',
				'tooltip'     => esc_html__( 'Please choose the categories of your listing', 'lisner-core' ),
				'label_name'  => esc_html__( 'Category:', 'lisner-core' ),
			);
		}

		// general listing fields
		if ( isset( $option['listing-fields-email'] ) && $option['listing-fields-email'] ) {
			$fields['listing']['listing_email'] = array(
				'label'       => __( 'Listing Email Address', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-email-required'] ) && $option['listing-fields-email-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 5,
				'tooltip'     => esc_html__( 'Please enter listing email address or leave empty to use your account email address',
					'lisner-core' )
			);
		}

		if ( isset( $option['listing-fields-website'] ) && $option['listing-fields-website'] ) {
			$fields['listing']['listing_website'] = array(
				'label'       => __( 'Listing Website', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-website-required'] ) && $option['listing-fields-website-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 6,
				'tooltip'     => esc_html__( 'Please enter website address of your listing', 'lisner-core' )
			);
		}

		if ( isset( $option['listing-fields-phone'] ) && $option['listing-fields-phone'] ) {
			$fields['listing']['listing_phone'] = array(
				'label'       => __( 'Listing Phone', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-phone-required'] ) && $option['listing-fields-phone-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 5,
				'tooltip'     => esc_html__( 'Please enter listing phone number.', 'lisner-core' )
			);
		}

		// listing specific fields
		if ( isset( $option['listing-fields-amenities'] ) && $option['listing-fields-amenities'] ) {
			$fields['listing_specific']['listing_amenities'] = array(
				'label'       => __( 'Listing Amenities', 'lisner-core' ),
				'type'        => 'term-multiselect',
				'required'    => isset( $option['listing-fields-amenities-required'] ) && $option['listing-fields-amenities-required'] ? true : false,
				'placeholder' => esc_html__( 'Amenities:', 'lisner-core' ),
				'priority'    => 7,
				'taxonomy'    => 'listing_amenity',
				'tooltip'     => esc_html__( 'Please choose your listing amenities', 'lisner-core' ),
				'label_name'  => esc_html__( 'Amenity:', 'lisner-core' ),
			);
		}

		if ( isset( $option['listing-fields-tags'] ) && $option['listing-fields-tags'] ) {
			$fields['listing_specific']['listing_tags'] = array(
				'label'       => __( 'Listing Tags', 'lisner-core' ),
				'type'        => 'term-multiselect',
				'required'    => isset( $option['listing-fields-tags-required'] ) && $option['listing-fields-tags-required'] ? true : false,
				'placeholder' => esc_html__( 'Tags:', 'lisner-core' ),
				'priority'    => 8,
				'taxonomy'    => 'listing_tag',
				'tooltip'     => esc_html__( 'Please choose your listing tags', 'lisner-core' ),
				'label_name'  => esc_html__( 'Tag:', 'lisner-core' ),
			);
		}

		if ( isset( $option['listing-fields-pricing'] ) && $option['listing-fields-pricing'] ) {
			$fields['listing_specific']['listing_pricing_range'] = array(
				'label'       => __( 'Listing Price Range', 'lisner-core' ),
				'type'        => 'select',
				'required'    => isset( $option['listing-fields-pricing-required'] ) && $option['listing-fields-pricing-required'] ? true : false,
				'options'     => array(
					'none'      => '',
					'cheap'     => esc_html__( '$ - Cheap', 'lisner-core' ),
					'moderate'  => esc_html__( '$$ - Moderate', 'lisner-core' ),
					'expensive' => esc_html__( '$$$ - Extensive', 'lisner-core' ),
					'ultra'     => esc_html__( '$$$$ - Ultra', 'lisner-core' ),
				),
				'placeholder' => esc_html__( 'Choose Listing Price Range', 'lisner-core' ),
				'priority'    => 9,
				'tooltip'     => esc_html__( 'Please select your listing pricing range', 'lisner-core' )
			);

			$fields['listing_specific']['listing_pricing_from'] = array(
				'label'       => __( 'Price From', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-pricing-required'] ) && $option['listing-fields-pricing-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 10,
				'tooltip'     => esc_html__( 'Please enter your listing lowest price', 'lisner-core' ),
				'before'      => '<div class="form-group-wrapper d-flex justify-content-between align-content-center">'
			);

			$fields['listing_specific']['listing_pricing_to'] = array(
				'label'       => __( 'Price To', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-pricing-required'] ) && $option['listing-fields-pricing-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 11,
				'tooltip'     => esc_html__( 'Please enter your listing highest price', 'lisner-core' )
			);

			$currency_symbol                                        = get_woocommerce_currency_symbol();
			$fields['listing_specific']['listing_pricing_currency'] = array(
				'label'       => __( 'Price Currency', 'lisner-core' ),
				'type'        => 'text',
				'required'    => false,
				'placeholder' => '',
				'default'     => isset( $currency_symbol ) ? $currency_symbol : '$',
				'priority'    => 12,
				'tooltip'     => esc_html__( 'Please set your listing main currency or leave empty to use site default',
					'lisner-core' ),
				'after'       => '</div>'
			);
		}

		if ( isset( $option['listing-fields-working-hours'] ) && $option['listing-fields-working-hours'] ) {
			$fields['listing_specific']['listing_working_hours'] = array(
				'label'       => __( 'Pick Working Days', 'lisner-core' ),
				'type'        => 'working-hours',
				'required'    => isset( $option['listing-fields-working-hours-required'] ) && $option['listing-fields-working-hours-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 13,
				'tooltip'     => esc_html__( 'Please set your listing working hours', 'lisner-core' )
			);
		}

		if ( isset( $option['listing-fields-logo'] ) && $option['listing-fields-logo'] ) {
			$fields['listing_media']['listing_logo'] = array(
				'label'              => __( 'Listing Logo', 'lisner-core' ),
				'type'               => 'file',
				'required'           => isset( $option['listing-fields-logo-required'] ) && $option['listing-fields-logo-required'] ? true : false,
				'placeholder'        => '',
				'priority'           => 7,
				'ajax'               => true,
				'allowed_mime_types' => array(
					'jpg'  => 'image/jpeg',
					'jpeg' => 'image/jpeg',
					'png'  => 'image/png'
				),
				'tooltip'            => esc_html__( 'Please set logo of your listing that will be shown in listing box used across the site',
					'lisner-core' )
			);
		}

		if ( isset( $option['listing-fields-cover'] ) && $option['listing-fields-cover'] ) {
			$fields['listing_media']['listing_cover'] = array(
				'label'              => __( 'Listing Featured Image', 'lisner-core' ),
				'type'               => 'file',
				'required'           => isset( $option['listing-fields-cover-required'] ) && $option['listing-fields-cover-required'] ? true : false,
				'placeholder'        => '',
				'priority'           => 7,
				'ajax'               => true,
				'allowed_mime_types' => array(
					'jpg'  => 'image/jpeg',
					'jpeg' => 'image/jpeg',
					'png'  => 'image/png'
				),
				'tooltip'            => esc_html__( 'Please set featured image of your listing that will be shown in listing box used across the site',
					'lisner-core' )
			);
		}

		if ( isset( $option['listing-fields-gallery'] ) && $option['listing-fields-gallery'] ) {
			$fields['listing_media']['listing_gallery'] = array(
				'label'              => __( 'Listing Gallery', 'lisner-core' ),
				'type'               => 'file',
				'required'           => isset( $option['listing-fields-gallery-required'] ) && $option['listing-fields-gallery-required'] ? true : false,
				'placeholder'        => '',
				'multiple'           => true,
				'priority'           => 8,
				'ajax'               => true,
				'allowed_mime_types' => array(
					'jpg'  => 'image/jpeg',
					'jpeg' => 'image/jpeg',
					'png'  => 'image/png'
				),
				'tooltip'            => esc_html__( 'Please upload your listing gallery images', 'lisner-core' )
			);
		}

		if ( isset( $option['listing-fields-video'] ) && $option['listing-fields-video'] ) {
			$fields['listing_media']['listing_video'] = array(
				'label'       => __( 'Listing Video', 'lisner-core' ),
				'type'        => 'video',
				'required'    => isset( $option['listing-fields-video-required'] ) && $option['listing-fields-video-required'] ? true : false,
				'placeholder' => esc_html__( 'https://www.youtube.com/watch?v=xDHpcAFSMr0', 'lisner-core' ),
				'priority'    => 9,
				'tooltip'     => esc_html__( 'Please enter link to your listing video. ( YouTube, Vimeo... ). Click on icon to preview the video.',
					'lisner-core' )
			);
		}

		if ( isset( $option['listing-fields-files'] ) && $option['listing-fields-files'] ) {
			$fields['listing_media']['listing_files'] = array(
				'label'              => __( 'Listing Additional Files', 'lisner-core' ),
				'type'               => 'file',
				'required'           => isset( $option['listing-fields-files-required'] ) && $option['listing-fields-files-required'] ? true : false,
				'placeholder'        => '',
				'multiple'           => true,
				'priority'           => 9,
				'ajax'               => true,
				'allowed_mime_types' => array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'gif'          => 'image/gif',
					'png'          => 'image/png',
					'pdf'          => 'application/pdf',
					'doc'          => 'application/msword',
					'docx'         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				),
				'tooltip'            => esc_html__( 'You can use this field to upload your additional files',
					'lisner-core' )
			);
		}

		// listing specific fields
		if ( isset( $option['listing-fields-social'] ) && $option['listing-fields-social'] ) {
			$fields['listing_social']['listing_social__facebook']  = array(
				'label'       => __( 'Listing Social / Facebook', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-social-required'] ) && $option['listing-fields-social-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 1,
				'tooltip'     => esc_html__( 'Please enter link to your facebook account', 'lisner-core' )
			);
			$fields['listing_social']['listing_social__twitter']   = array(
				'label'       => __( 'Listing Social / Twitter', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-social-required'] ) && $option['listing-fields-social-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 2,
				'tooltip'     => esc_html__( 'Please enter link to your twitter account', 'lisner-core' )
			);
			$fields['listing_social']['listing_social__google']    = array(
				'label'       => __( 'Listing Social / Google+', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-social-required'] ) && $option['listing-fields-social-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 3,
				'tooltip'     => esc_html__( 'Please enter link to your google+ account', 'lisner-core' )
			);
			$fields['listing_social']['listing_social__instagram'] = array(
				'label'       => __( 'Listing Social / Instagram', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-social-required'] ) && $option['listing-fields-social-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 4,
				'tooltip'     => esc_html__( 'Please enter link to your instagram account', 'lisner-core' )
			);
			$fields['listing_social']['listing_social__youtube']   = array(
				'label'       => __( 'Listing Social / YouTube', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-social-required'] ) && $option['listing-fields-social-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 5,
				'tooltip'     => esc_html__( 'Please enter link to your youtube account', 'lisner-core' )
			);
			$fields['listing_social']['listing_social__pinterest'] = array(
				'label'       => __( 'Listing Social / Pinterest', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-social-required'] ) && $option['listing-fields-social-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 6,
				'tooltip'     => esc_html__( 'Please enter link to your pinterest account', 'lisner-core' )
			);
			$fields['listing_social']['listing_social__linkedin']  = array(
				'label'       => __( 'Listing Social / Linkedin', 'lisner-core' ),
				'type'        => 'text',
				'required'    => isset( $option['listing-fields-social-required'] ) && $option['listing-fields-social-required'] ? true : false,
				'placeholder' => '',
				'priority'    => 7,
				'tooltip'     => esc_html__( 'Please enter link to your linkedin account', 'lisner-core' )
			);
		}

		return apply_filters( 'lisner__form_fields', array_filter( $fields ) );
	}

	/**
	 * Custom Save Listing and update WP Job Manager fields
	 *
	 * @param $post_id
	 */
	public function save_listing( $post_id ) {
		global $wpdb;
		$screen = get_current_screen();

		// Update listing location
		if ( ! empty( $_POST['_job_location'] ) ) {
			WP_Job_Manager_Geocode::generate_location_data( $post_id, sanitize_text_field( $_POST['_job_location'] ) );
			$lat = get_post_meta( $post_id, 'geolocation_lat', true );
			$lng = get_post_meta( $post_id, 'geolocation_long', true );
			if ( isset( $lat ) && ! empty( $lat ) && isset( $lng ) && ! empty( $lng ) ) {
				$coords = implode( ',', array( $lat, $lng ) );
				update_post_meta( $post_id, '_job_location_map', $coords );
			} else {
				$coords = get_post_meta( $post_id, '_job_location_map', true );
				$coords = explode( ',', $coords );
				update_post_meta( $post_id, 'geolocation_lat', $coords[0] );
				update_post_meta( $post_id, 'geolocation_long', $coords[1] );
			}
		}

		// Update listing author
		if ( ! empty( $_POST['_job_author'] ) ) {
			$wpdb->update( $wpdb->posts,
				array( 'post_author' => $_POST['_job_author'] > 0 ? absint( $_POST['_job_author'] ) : 1 ),
				array( 'ID' => $post_id ) );
		}

		if ( ! strstr( $screen->id, 'lisner-options' ) ) {
			// update listing expiration date
			update_post_meta( $post_id, '_job_expires', $_REQUEST['_job_duration'] );
			update_post_meta( $post_id, '_job_duration', $_REQUEST['_job_duration'] );
		}
	}

	/**
	 * Update WP Job Listing with custom values
	 *
	 * @param $post_id
	 * @param $values
	 */
	public function update_listing( $post_id, $values ) {
		global $wpdb;
		$option = get_option( 'pbs_option' );
		if ( ! is_admin() ) {

			// Update image fields
			$this->update_listing_images( $post_id, $values, 'listing_files' ); // update listing files
			$this->update_listing_images( $post_id, $values, 'listing_gallery' ); // update listing gallery
			$this->update_listing_images( $post_id, $values, 'listing_cover' ); // update listing cover
			$logo = $this->update_listing_images( $post_id, $values, 'listing_logo' ); // update listing logo
			set_post_thumbnail( $post_id, $logo );

			// Update author of the listing
			$listing_author = is_user_logged_in() ? get_current_user_id() : 1;
			$wpdb->update( $wpdb->posts,
				array( 'post_author' => $listing_author ),
				array( 'ID' => $post_id ) );
			update_post_meta( $post_id, '_job_author', $listing_author );

			// Update working hours
			$working_days = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
			foreach ( $working_days as $day ) {
				$day_option = isset( $_POST["_listing_{$day}_hours_radio"] ) ? $_POST["_listing_{$day}_hours_radio"] : array();
				update_post_meta( $post_id, "_listing_{$day}_hours_radio", $_POST["_listing_{$day}_hours_radio"] );
				if ( 'custom' != $day_option ) {
					update_post_meta( $post_id, "_listing_{$day}_hours_open", array() );
					update_post_meta( $post_id, "_listing_{$day}_hours_close", array() );
				} else {
					$hours_open  = isset( $_POST["_listing_{$day}_hours_open"] ) ? $_POST["_listing_{$day}_hours_open"] : array();
					$hours_close = isset( $_POST["_listing_{$day}_hours_close"] ) ? $_POST["_listing_{$day}_hours_close"] : array();
					update_post_meta( $post_id, "_listing_{$day}_hours_open", $hours_open );
					update_post_meta( $post_id, "_listing_{$day}_hours_close", $hours_close );
				}
			}

			// update geolocation before geolocating user in case geolocation is not working
			update_post_meta( $post_id, 'geolocation_lat',
				isset( $_POST['location_lat'] ) ? $_POST['location_lat'] : '' );
			update_post_meta( $post_id, 'geolocation_long',
				isset( $_POST['location_long'] ) ? $_POST['location_long'] : '' );

			// update location taxonomy
			$top_level = isset( $option['listings-location-type'] ) && ! empty( $option['listings-location-type'] ) ? $option['listings-location-type'] : 'state';
			$this->update_location_taxonomy( $post_id, $top_level, 'city', true );

		}
	}

	/**
	 * Update location taxonomy when updated from frontend
	 *
	 * @param $post_id
	 * @param $parent_location
	 * @param $child_location
	 * @param bool $hierarchy
	 */
	public function update_location_taxonomy( $post_id, $parent_location, $child_location, $hierarchy = true ) {
		$child_loc  = get_post_meta( $post_id, "geolocation_{$child_location}", true );
		$parent_loc = get_post_meta( $post_id, "geolocation_{$parent_location}_long", true );

		if ( $parent_loc ) {
			$country_obj = wp_set_object_terms( $post_id, $parent_loc, 'listing_location' );
		}

		if ( $hierarchy ) {
			$parent_id = term_exists( $parent_loc, 'listing_location' );
			$child_id  = term_exists( $child_loc, 'listing_location', $parent_id['term_id'] );
			if ( $child_loc ) {
				if ( $child_id ) {
					$state_obj = wp_set_object_terms( $post_id, (int) $child_id['term_id'], 'listing_location', true );
				} elseif ( $child_loc == $parent_loc ) {
					$child_id = wp_insert_term( $child_loc, 'listing_location',
						array( 'parent' => $parent_id['term_id'] ) );
					wp_set_object_terms( $post_id, (int) $child_id['term_id'], 'listing_location', true );
				} else {
					$state_obj = wp_set_object_terms( $post_id, $child_loc, 'listing_location', true );
					wp_update_term( $state_obj[0], 'listing_location', array( 'parent' => $parent_id['term_id'] ) );
				}
			}
		}
	}

	/**
	 * Update WP Job Manager geolocation results to include custom ones
	 *
	 * @param $address
	 * @param $geocoded_address
	 *
	 * @return mixed|void
	 */
	public function update_geocoded_address( $address, $geocoded_address ) {
		$city_name_short = $geocoded_address->results[0]->address_components[1]->short_name;
		if ( isset( $city_name_short ) && ! empty( $city_name_short ) ) {
			$address['city_name_short'] = $city_name_short;
		}

		return apply_filters( 'lisner_geolocation_get_location_data', $address, $geocoded_address );
	}

	/**
	 * Update WP Job Manager uploaded images
	 *
	 * @param $post_id
	 * @param $values
	 * @param $field
	 *
	 * @return int|mixed
	 */
	public function update_listing_images( $post_id, $values, $field ) {
		global $wpdb;
		$attachments = $values['listing_media'][ $field ];

		// Loop attachments already attached to the job
		if ( is_array( $attachments ) ) { // if multiple attachments are being uploaded
			$values = get_post_meta( $post_id, "_{$field}", true );
			$values = maybe_unserialize( $values );
			if ( $values ) {
				foreach ( $values as $value ) {
					if ( is_numeric( $value ) ) {
						add_post_meta( $post_id, "_{$field}", $value, false );
					} else {
						$value = $this->get_attachment_id( $value );
						add_post_meta( $post_id, "_{$field}", $value, false );
					}
					$wpdb->delete( $wpdb->postmeta, array(
						'meta_key'   => '_wp_attached_file',
						'meta_value' => $value
					) );
				}
				delete_post_meta( $post_id, "_{$field}", $values );
			}
		} else { // if single attachment is uploaded
			$value = get_post_meta( $post_id, "_{$field}", true );
			if ( is_numeric( $value ) ) {
				add_post_meta( $post_id, "_{$field}", $value, false );
			} else {
				$value = $this->get_attachment_id( $value );
				add_post_meta( $post_id, "_{$field}", $value, false );
			}
			if ( $value ) {
				delete_post_meta( $post_id, "_{$field}", $value );
				add_post_meta( $post_id, "_{$field}", $value, false );
				$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_wp_attached_file', 'meta_value' => $value ) );
			}

			return $value;
		}

	}

	/**
	 * Get the id of the uploaded attachment
	 *
	 * @param $url
	 *
	 * @return int
	 */
	public function get_attachment_id( $url ) {
		$attachment_id = 0;
		$dir           = wp_upload_dir();
		if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
			$file       = basename( $url );
			$query_args = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata',
					),
				)
			);
			$query      = new WP_Query( $query_args );
			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post_id ) {
					$meta                = wp_get_attachment_metadata( $post_id );
					$original_file       = basename( $meta['file'] );
					$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
					if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
						$attachment_id = $post_id;
						break;
					}
				}
			}
		}

		return $attachment_id;
	}

	/**
	 * Update WP Job Manager `job_manager_submit_job_form_page_id` setting when search page is set through our custom settings
	 *
	 * @param $null
	 * @param $field
	 * @param $new
	 * @param $old
	 * @param $post_id
	 */
	public function set_add_listing_page_template( $null, $field, $new, $old, $post_id ) {

		$wpjm_add_listing_page_id = get_option( 'job_manager_submit_job_form_page_id' );
		if ( $new != $old || $new != $wpjm_add_listing_page_id ) {
			update_option( 'job_manager_submit_job_form_page_id', $new ); // update wp job manager default listings page
		}
	}

	/**
	 * Get Add Listing page template
	 *
	 * @return mixed|string
	 */
	public function get_add_listing_page_template() {
		$option                = get_option( 'pbs_option' );
		$main_add_listing      = isset( $option['page-add-listing'] ) ? $option['page-add-listing'] : '';
		$add_listing_templates = lisner_helper()->get_pages_by_template( 'page-add-listing' );
		if ( ! empty( $main_add_listing ) ) { // check whether theme option is set
			$template = $main_add_listing;
		} elseif ( ! empty( $add_listing_templates ) ) { // check if there is template assign to first page found
			$template = $add_listing_templates;
		} else { // check whether wp job manager option is set
			$template = get_option( 'job_manager_submit_job_form_page_id' );
			$template = isset( $template ) ? $template : '';
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
	public function load_add_listing_page_template( $template ) {
		$add_listing_page_id = self::get_add_listing_page_template();

		if ( ! empty( $add_listing_page_id ) && $add_listing_page_id == get_queried_object_id() ) {
			$new_template = lisner_listings()->job_manager_locate_template( '', 'pages/page-add-listing.php', '' );

			return $new_template;
		}

		return $template;
	}

	/**
	 * Get embedded ajax video
	 */
	public function ajax_get_embed() {
		$url = (string) filter_input( INPUT_POST, 'url', FILTER_SANITIZE_URL );
		wp_send_json_success( wp_oembed_get( $url, array(
			'disablekb' => '0',
			'loop'      => '0',
			'rel'       => '0',
			'showinfo'  => '0',
			'autoplay'  => '1'
		) ) );
	}

	/**
	 * Modify oembed results
	 *
	 * @param $html
	 * @param $url
	 * @param $args
	 *
	 * @return mixed
	 */
	function oembed_result( $html, $url, $args ) {

		if ( $args ) {
			$html = str_replace( 'oembed', 'oembed&' . http_build_query( $args ), $html );
			$html = str_replace( '>', ' class="embed-responsive-item">', $html );
		}

		return $html;
	}

	/**
	 * Get listing taxonomy
	 *
	 * @param $taxonomy
	 * @param array $args
	 *
	 * @return array|int|WP_Error
	 */
	public function get_listing_taxonomy( $taxonomy, $args = array() ) {
		$terms = get_terms( $taxonomy, $args );
		if ( ! $terms ) {
			return false;
		}

		return $terms;
	}

	/**
	 * Get listing views count
	 *
	 * @param $post_id
	 *
	 * @return int
	 */
	public static function get_listing_views_count( $post_id ) {
		$views = get_post_meta( $post_id, 'listing_views', true );
		$views = lisner_get_var( $views, 0 );

		return $views;
	}

	/**
	 * Increase listing views count
	 *
	 * @param $post_id
	 *
	 * @return int|mixed
	 */
	public static function set_listing_views_count( $post_id ) {
		$views = self::get_listing_views_count( $post_id );

		update_post_meta( $post_id, 'listing_views', $views += 1 );
	}

	/**
	 * Load share modal
	 */
	public function share_modal() {
		if ( is_singular( 'job_listing' ) || is_single() ) {
			include lisner_helper::get_template_part( 'modal-share', 'modals' );
		}
	}

	/**
	 * Get link of the given taxonomy
	 *
	 * @param $id
	 * @param string $search_taxonomy
	 *
	 * @return string|WP_Error
	 */
	public function get_taxonomy_link( $id, $search_taxonomy = 'job_listing_category' ) {
		$search_tax          = '';
		$permalinks          = lisner_listings_post_type::get_permalink_structure();
		$permalink_structure = WP_Job_Manager_Post_Types::get_permalink_structure();
		switch ( $search_taxonomy ) {
			case 'job_listing_category':
				$search_tax = 'search_categories[]';
				break;
			case 'listing_amenity':
				$search_tax = 'search_amenities[]';
				break;
			case 'listing_tag':
				$search_tax = 'search_tags[]';
				break;
			case 'listing_location':
				$search_tax = 'search_location';
		}
		$page_option = lisner_get_option( 'general-taxonomy-page', 'search' );
		if ( 'default' != $page_option ) {
			$term        = get_term_by( 'term_id', $id, $search_taxonomy );
			$search_page = get_permalink( lisner_search()->get_search_page_template() );
			if ( 'listing_location' == $search_taxonomy ) {
				$link = add_query_arg( "{$search_tax}", $term->name, $search_page );
			} else {
				$link = add_query_arg( "{$search_tax}", $id, $search_page );
			}
		} else {
			switch ( $search_taxonomy ) {
				case 'job_listing_category':
					$search_tax = $permalink_structure['category_rewrite_slug'];
					break;
				case 'listing_amenity':
					$search_tax = $permalinks['amenity_rewrite_slug'];
					break;
				case 'listing_tag':
					$search_tax = $permalinks['lisner_tag_rewrite_slug'];
					break;
				case 'listing_location':
					$search_tax = $permalinks['location_rewrite_slug'];
			}
			$term = get_term_by( 'term_id', $id, $search_taxonomy );
			$link = home_url( $search_tax . '/' . $term->slug );
		}

		return $link;
	}

	/**
	 * Hide specified amount of phone number digits
	 *
	 * @param $phone_number
	 * @param int $number
	 *
	 * @return array|string
	 */
	public static function hide_phone_number( $phone_number, $number = 3 ) {
		$option      = get_option( 'pbs_option' );
		$phone_array = array();
		if ( isset( $phone_number ) ) {
			$chars = 0;
			if ( ! isset( $option['listings-appearance-hide-phone'] ) || 'hide' == $option['listings-appearance-hide-phone'] ) {
				$chars        = substr( $phone_number, - $number );
				$phone_number = substr( $phone_number, 0, - $number ) . 'XXX';
			}
			$phone_array['hidden'] = $chars;
			$phone_array['number'] = $phone_number;

			return $phone_array;
		}

		return $phone_number;
	}

}

/**
 * Instantiate class
 *
 * @return lisner_listings|null
 */
function lisner_listings() {
	return lisner_listings::instance();
}
