<?php

include 'rapid-addon.php';

$lisner_addon = new RapidAddon( 'Lisner WP Add-On', 'lisner-core' );

$lisner_addon->disable_default_images();

$lisner_addon->add_field(
	'_job_location',
	'Location',
	'radio',
	array(
		'search_by_address'     => array(
			'Search by Address',
			$lisner_addon->add_options(
				$lisner_addon->add_field(
					'job_address',
					'Listing Address',
					'text'
				),
				'Google Geocode API Settings',
				array(
					$lisner_addon->add_field(
						'address_geocode',
						'Request Method',
						'radio',
						array(
							'address_no_key'            => array(
								'No API Key',
								'Limited number of requests.'
							),
							'address_google_developers' => array(
								'Google Maps Standard API Key - <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key">Get free API key</a>',
								$lisner_addon->add_field(
									'address_google_developers_api_key',
									'API Key',
									'text'
								),
								'Up to 2500 requests per day and 5 requests per second.'
							),
							'address_google_for_work'   => array(
								'Google Maps Premium Client ID & Digital Signature - <a href="https://developers.google.com/maps/premium/">Sign up for Google Maps Premium Plan</a>',
								$lisner_addon->add_field(
									'address_google_for_work_client_id',
									'Google Maps Premium Client ID',
									'text'
								),
								$lisner_addon->add_field(
									'address_google_for_work_digital_signature',
									'Google Maps Premium Digital Signature',
									'text'
								),
								'Up to 100,000 requests per day and 10 requests per second'
							)
						) // end Request Method options array
					), // end Request Method nested radio field

				) // end Google Geocode API Settings fields
			) // end Google Gecode API Settings options panel
		), // end Search by Address radio field
		'search_by_coordinates' => array(
			'Search by Coordinates',
			$lisner_addon->add_field(
				'job_lat',
				'Latitude',
				'text',
				null,
				'Example: 34.0194543'
			),
			$lisner_addon->add_options(
				$lisner_addon->add_field(
					'job_lng',
					'Longitude',
					'text',
					null,
					'Example: -118.4911912'
				),
				'Google Geocode API Settings',
				array(
					$lisner_addon->add_field(
						'coord_geocode',
						'Request Method',
						'radio',
						array(
							'coord_no_key'            => array(
								'No API Key',
								'Limited number of requests.'
							),
							'coord_google_developers' => array(
								'Google Maps Standard API Key - <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key">Get free API key</a>',
								$lisner_addon->add_field(
									'coord_google_developers_api_key',
									'API Key',
									'text'
								),
								'Up to 2500 requests per day and 5 requests per second.'
							),
							'coord_google_for_work'   => array(
								'Google Maps Premium Client ID & Digital Signature - <a href="https://developers.google.com/maps/premium/">Sign up for Google Maps Premium Plan</a>',
								$lisner_addon->add_field(
									'coord_google_for_work_client_id',
									'Google Maps Premium Client ID',
									'text'
								),
								$lisner_addon->add_field(
									'coord_google_for_work_digital_signature',
									'Google Maps Premium Digital Signature',
									'text'
								),
								'Up to 100,000 requests per day and 10 requests per second'
							)
						) // end Geocode API options array
					), // end Geocode nested radio field

				) // end Geocode settings
			) // end coordinates Option panel
		) // end Search by Coordinates radio field
	) // end Job Location radio field
);

$lisner_addon->add_field( '_listing_phone', 'Listing Phone', 'text' );
$lisner_addon->add_field( '_listing_email', 'Listing Email', 'text' );
$lisner_addon->add_field( '_listing_website', 'Listing Website', 'text' );
$lisner_addon->add_field( '_listing_pricing_range', 'Listing Pricing Range', 'radio',
	array(
		'none'      => esc_html__( 'Unknown', 'lisner-core' ),
		'cheap'     => esc_html__( 'Cheap', 'lisner-core' ),
		'moderate'  => esc_html__( 'Moderate', 'lisner-core' ),
		'expansive' => esc_html__( 'Expansive', 'lisner-core' ),
		'ultra'     => esc_html__( 'Ultra', 'lisner-core' ),
	)
);
$lisner_addon->add_field( '_listing_pricing_from', 'Listing Pricing From', 'text' );
$lisner_addon->add_field( '_listing_pricing_to', 'Listing Pricing To', 'text' );
$lisner_addon->add_field( '_listing_video', 'Listing Video', 'text' );
$lisner_addon->add_field( '_listing_likes', 'Listing Likes', 'text' );
$lisner_addon->add_field( '_listing_view', 'Listing Views', 'text' );
$lisner_addon->add_field( '_listing_social__facebook', 'Listing Facebook', 'text' );
$lisner_addon->add_field( '_listing_social__twitter', 'Listing Twitter', 'text' );
$lisner_addon->add_field( '_listing_social__google', 'Listing Google+', 'text' );
$lisner_addon->add_field( '_listing_social__instagram', 'Listing Instagram', 'text' );
$lisner_addon->add_field( '_listing_social__youtube', 'Listing YouTube', 'text' );
$lisner_addon->add_field( '_listing_social__linkedin', 'Listing Linkedin', 'text' );
$lisner_addon->add_field( '_listing_social__pinterest', 'Listing Pinterest', 'text' );
$lisner_addon->add_field( '_job_expires', 'Listing Expiry Date', 'text', null, 'Import date in any strtotime compatible format.' );

$lisner_addon->add_field( '_featured', 'Featured Listing', 'radio',
	array(
		'0' => 'No',
		'1' => 'Yes'
	),
	'Featured listings will be sticky during searches, and can be styled differently.'
);

$lisner_addon->set_import_function( 'lisner_addon_import' );

$lisner_addon->admin_notice(
	'Lisner Add-On requires WP All Import <a href="http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wpjm" target="_blank">Pro</a> or <a href="http://wordpress.org/plugins/wp-all-import" target="_blank">Free</a>, and the <a href="https://wordpress.org/plugins/wp-job-manager/">WP Job Manager</a> plugin.',
	array(
		"plugins" => array( "wp-job-manager/wp-job-manager.php", 'lisner-core/lisner-core.php' ),
	) );

$lisner_addon->run( array(
	"plugins"    => array( "wp-job-manager/wp-job-manager.php", 'lisner-core/lisner-core.php' ),
	'post_types' => array( 'job_listing' )
) );

function lisner_addon_import( $post_id, $data, $import_options, $article ) {
	global $lisner_addon;

	// all fields except for slider and image fields
	$fields = array(
		'_job_title',
		'_job_description',
		'_listing_phone',
		'_listing_email',
		'_listing_website',
		'_listing_pricing_range',
		'_listing_pricing_from',
		'_listing_pricing_to',
		'_listing_video',
		'_listing_likes',
		'_listing_views',
		'_listing_social__facebook',
		'_listing_social__twitter',
		'_listing_social__google',
		'_listing_social__instagram',
		'_listing_social__youtube',
		'_listing_social__pinterest',
		'_listing_social__linkedin',
		'_featured',
	);

	// update everything in fields arrays
	foreach ( $fields as $field ) {

		if ( empty( $article['ID'] ) or $lisner_addon->can_update_meta( $field, $import_options ) ) {

			update_post_meta( $post_id, $field, $data[ $field ] );

		}
	}


	// update listing expiration date
	$field = '_job_expires';

	$date = $data[ $field ];

	$duration = get_option( "job_manager_submission_duration" );

	if ( empty( $article['ID'] ) or ( $lisner_addon->can_update_meta( $field, $import_options ) ) ) {

		if ( ! empty( $date ) ) {

			$date = strtotime( $date );

			$date = date( 'Y-m-d', $date );

			update_post_meta( $post_id, $field, $date );

		} elseif ( ! empty( $duration ) ) {

			$date = strtotime( "now + " . $duration . " day" );

			$date = date( 'Y-m-d', $date );

			update_post_meta( $post_id, $field, $date );

		} else {

			delete_post_meta( $post_id, $field );

		}

	}

	// update job location
	$field = 'job_address';

	$address = $data[ $field ];

	$lat = $data['job_lat'];

	$long = $data['job_lng'];

	//  build search query
	if ( $data['_job_location'] == 'search_by_address' ) {

		$search = ( ! empty( $address ) ? 'address=' . rawurlencode( $address ) : null );

	} else {

		$search = ( ! empty( $lat ) && ! empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );

	}

	// build api key
	if ( $data['_job_location'] == 'search_by_address' ) {

		if ( $data['address_geocode'] == 'address_google_developers' && ! empty( $data['address_google_developers_api_key'] ) ) {

			$api_key = '&key=' . $data['address_google_developers_api_key'];

		} elseif ( $data['address_geocode'] == 'address_google_for_work' && ! empty( $data['address_google_for_work_client_id'] ) && ! empty( $data['address_google_for_work_signature'] ) ) {

			$api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

		}

	} else {

		if ( $data['coord_geocode'] == 'coord_google_developers' && ! empty( $data['coord_google_developers_api_key'] ) ) {

			$api_key = '&key=' . $data['coord_google_developers_api_key'];

		} elseif ( $data['coord_geocode'] == 'coord_google_for_work' && ! empty( $data['coord_google_for_work_client_id'] ) && ! empty( $data['coord_google_for_work_signature'] ) ) {

			$api_key = '&client=' . $data['coord_google_for_work_client_id'] . '&signature=' . $data['coord_google_for_work_signature'];

		}

	}

	// Store _job_location value for later use

	if ( $data['_job_location'] == 'search_by_address' ) {

		$job_location = $address;

	} else {

		$job_location = $lat . ', ' . $long;

	}

	// if all fields are updateable and $search has a value
	if ( empty( $article['ID'] ) or ( $lisner_addon->can_update_meta( $field, $import_options ) && $lisner_addon->can_update_meta( '_job_location', $import_options ) && ! empty ( $search ) ) ) {

		// build $request_url for api call
		$request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;
		$curl        = curl_init();

		curl_setopt( $curl, CURLOPT_URL, $request_url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

		$lisner_addon->log( '- Getting location data from Geocoding API: ' . $request_url );

		$json = curl_exec( $curl );

		curl_close( $curl );

		// parse api response
		if ( ! empty( $json ) ) {

			$details = json_decode( $json, true );

			$address_data = array(
				'street_number'                          => '',
				'route'                                  => '',
				'locality'                               => '',
				'country_short_name'                     => '',
				'country_long_name'                      => '',
				'postal_code'                            => '',
				'administrative_area_level_1_short_name' => '',
				'administrative_area_level_1_long_name'  => ''
			);

			if ( ! empty( $details[ results ][0][ address_components ] ) ) {

				foreach ( $details[ results ][0][ address_components ] as $type ) {
					// Went for type_name here to try to make the if statement a bit shorter,
					// and hopefully clearer as well
					$type_name = $type[ types ][0];

					if ( $type_name == "administrative_area_level_1" || $type_name == "administrative_area_level_2" || $type_name == "country" ) {
						// short_name & long_name must be stored for these three field types, as
						// the short & long names are stored by WP Job Manager
						$address_data[ $type_name . "_short_name" ] = $type[ short_name ];
						$address_data[ $type_name . "_long_name" ]  = $type[ long_name ];
					} else {
						// The rest of the data from Google Maps can be returned in long format,
						// as the other fields only store data in that format
						$address_data[ $type_name ] = $type[ long_name ];
					}
				}
			}

			// It's a long list, but this is what WP Job Manager stores in the database
			$geo_status = ( $details[ status ] == "ZERO_RESULTS" ) ? 0 : 1;

			$latitude = $details[ results ][0][ geometry ][ location ][ lat ];

			$longitude = $details[ results ][0][ geometry ][ location ][ lng ];

			$formatted_address = $details[ results ][0][ formatted_address ];

			$street_number = $address_data[ street_number ];

			$street = $address_data[ route ];

			$city = $address_data[ locality ];

			$country_short = $address_data[ country_short_name ];

			$country_long = $address_data[ country_long_name ];

			$zip = $address_data[ postal_code ];

			// Important because the "geolocation_state_short" & "geolocation_state_long" fields
			// can get data from "administrative_area_level_1" or "administrative_area_level_2",
			// depending on the address that's provided
			$state_short = ! empty( $address_data[ administrative_area_level_1_short_name ] ) ? $address_data[ administrative_area_level_1_short_name ] : $address_data[ administrative_area_level_2_short_name ];

			$state_long = ! empty( $address_data[ administrative_area_level_1_long_name ] ) ? $address_data[ administrative_area_level_1_long_name ] : $address_data[ administrative_area_level_2_long_name ];

			// Checks for empty location elements

			if ( empty( $zip ) ) {

				$lisner_addon->log( '<b>WARNING:</b> Google Maps has not returned a Postal Code for this job location.' );

			}

			if ( empty( $country_short ) && empty( $country_long ) ) {

				$lisner_addon->log( '<b>WARNING:</b> Google Maps has not returned a Country for this job location.' );

			}

			if ( empty( $state_short ) && empty( $state_long ) ) {

				$lisner_addon->log( '<b>WARNING:</b> Google Maps has not returned a State for this job location.' );

			}

			if ( empty( $city ) ) {

				$lisner_addon->log( '<b>WARNING:</b> Google Maps has not returned a City for this job location.' );

			}

			if ( empty( $street_number ) ) {

				$lisner_addon->log( '<b>WARNING:</b> Google Maps has not returned a Street Number for this job location.' );

			}

			if ( empty( $street ) ) {

				$lisner_addon->log( '<b>WARNING:</b> Google Maps has not returned a Street Name for this job location.' );

			}

		} else {
			$lisner_addon->log( '<b>WARNING:</b> Could not retrieve response data from Google Maps API.' );
		}

	}

	// List of location fields to update
	$fields = array(
		'geolocation_lat'               => $latitude,
		'geolocation_long'              => $longitude,
		'geolocation_formatted_address' => $formatted_address,
		'geolocation_street_number'     => $street_number,
		'geolocation_street'            => $street,
		'geolocation_city'              => $city,
		'geolocation_state_short'       => $state_short,
		'geolocation_state_long'        => $state_long,
		'geolocation_postcode'          => $zip,
		'geolocation_country_short'     => $country_short,
		'geolocation_country_long'      => $country_long,
		'_job_location'                 => $job_location
	);

	$lisner_addon->log( '- Updating location data' );

	// Check if "geolocated" field should be created or deleted
	if ( $geo_status == "0" ) {
		delete_post_meta( $post_id, "geolocated" );
	} elseif ( $geo_status == "1" ) {
		update_post_meta( $post_id, "geolocated", $geo_status );
	} else {
		// Do nothing, it's possible that we didn't get a response from the Google Maps API
	}

	foreach ( $fields as $key => $value ) {

		if ( empty( $article['ID'] ) or $lisner_addon->can_update_meta( $key, $import_options ) && ! is_null( $value ) ) {
			// If the field can be updated, and the value isn't NULL, update the field
			update_post_meta( $post_id, $key, $value );
		} elseif ( empty( $article['ID'] ) or $lisner_addon->can_update_meta( $key, $import_options ) ) {
			// Else, if the value for the field returns NULL, delete the field
			delete_post_meta( $post_id, $key, $value );
		} else {
			// Else, do nothing
		}
	}
	$coords = implode( ',', array( $latitude, $longitude ) );
	update_post_meta( $post_id, '_job_location_map', $coords );

}

add_action( 'pmxi_before_post_import', 'lisner_ensure_location_data_is_imported', 10, 1 );

function lisner_ensure_location_data_is_imported( $import_id ) {

	$import        = new PMXI_Import_Record();
	$import_object = $import->getById( $import_id );
	$post_type     = $import_object->options['custom_type'];

	if ( $post_type == "job_listing" ) {
		remove_all_actions( 'job_manager_job_location_edited' );
	}

}