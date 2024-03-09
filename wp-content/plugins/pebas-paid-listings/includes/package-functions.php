<?php
/**
 * Get a package
 *
 * @param  stdClass $package
 *
 * @return pebas_pl_package
 */
function pebas_pl_get_package( $package ) {
	return new pebas_pl_package( $package );
}

/**
 * Approve a listing
 *
 * @param  int $listing_id
 * @param  int $user_id
 * @param  int $user_package_id
 *
 * @return void
 */
function pebas_pl_approve_listing_with_package( $listing_id, $user_id, $user_package_id ) {
	if ( pebas_pl_package_is_valid( $user_id, $user_package_id ) ) {
		$resumed_post_status = get_post_meta( $listing_id, '_post_status_before_package_pause', true );
		if ( ! empty( $resumed_post_status ) ) {
			$listing = array(
				'ID'          => $listing_id,
				'post_status' => $resumed_post_status,
			);
			delete_post_meta( $listing_id, '_post_status_before_package_pause' );
		} else {
			$listing = array(
				'ID'            => $listing_id,
				'post_date'     => current_time( 'mysql' ),
				'post_date_gmt' => current_time( 'mysql', 1 ),
			);

			switch ( get_post_type( $listing_id ) ) {
				case 'job_listing' :
					delete_post_meta( $listing_id, '_job_expires' );
					$listing['post_status'] = get_option( 'job_manager_submission_requires_approval' ) ? 'pending' : 'publish';
					break;
			}
		}

		// Do update
		wp_update_post( $listing );
		update_post_meta( $listing_id, '_user_package_id', $user_package_id );

		/**
		 * Checks to see whether or not a particular job listing affects the package count.
		 *
		 * @since 2.7.3
		 *
		 * @param bool $job_listing_affects_package_count True if it affects package count.
		 * @param int $listing_id Post ID.
		 */
		if ( apply_filters( 'job_manager_job_listing_affects_package_count', true, $listing_id ) ) {
			pebas_pl_increase_package_count( $user_id, $user_package_id );
		}
	}
}

/**
 * Approve a job listing
 *
 * @param  int $job_id
 * @param  int $user_id
 * @param  int $user_package_id
 *
 * @return void
 */
function pebas_pl_approve_job_listing_with_package( $job_id, $user_id, $user_package_id ) {
	pebas_pl_approve_listing_with_package( $job_id, $user_id, $user_package_id );
}

/**
 * Approve a resume
 *
 * @param  int $resume_id
 * @param  int $user_id
 * @param  int $user_package_id
 *
 * @return void
 */
function pebas_pl_approve_resume_with_package( $resume_id, $user_id, $user_package_id ) {
	pebas_pl_approve_listing_with_package( $resume_id, $user_id, $user_package_id );
}

/**
 * See if a package is valid for use
 *
 * @param int $user_id
 * @param int $package_id
 *
 * @return bool
 */
function pebas_pl_package_is_valid( $user_id, $package_id ) {
	global $wpdb;
	$table = pebas_paid_listings_install::$pebas_paid_listings_table;

	$package = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$table} WHERE user_id = %d AND id = %d;", $user_id, $package_id ) );

	if ( ! $package ) {
		return false;
	}

	if ( $package->package_count >= $package->package_limit && $package->package_limit != 0 ) {
		return false;
	}

	return true;
}

/**
 * Increase job count for package
 *
 * @param  int $user_id
 * @param  int $package_id
 *
 * @return int affected rows
 */
function pebas_pl_increase_package_count( $user_id, $package_id ) {
	global $wpdb;
	$table = pebas_paid_listings_install::$pebas_paid_listings_table;

	$packages = pebas_pl_get_user_packages( $user_id, '', true );

	if ( isset( $packages[ $package_id ] ) ) {
		$new_count = $packages[ $package_id ]->package_count + 1;
	} else {
		$new_count = 1;
	}

	return $wpdb->update(
		"{$wpdb->prefix}{$table}",
		array(
			'package_count' => $new_count,
		),
		array(
			'user_id' => $user_id,
			'id'      => $package_id,
		),
		array( '%d' ),
		array( '%d', '%d' )
	);
}

/**
 * Decrease job count for package
 *
 * @param  int $user_id
 * @param  int $package_id
 *
 * @return int affected rows
 */
function pebas_pl_decrease_package_count( $user_id, $package_id ) {
	global $wpdb;
	$table = pebas_paid_listings_install::$pebas_paid_listings_table;

	$packages = pebas_pl_get_user_packages( $user_id, '', true );

	if ( isset( $packages[ $package_id ] ) ) {
		$new_count = $packages[ $package_id ]->package_count - 1;
	} else {
		$new_count = 0;
	}

	return $wpdb->update(
		"{$wpdb->prefix}{$table}",
		array(
			'package_count' => max( 0, $new_count ),
		),
		array(
			'user_id' => $user_id,
			'id'      => $package_id,
		),
		array( '%d' ),
		array( '%d', '%d' )
	);
}

if ( ! function_exists( 'pebas_paid_listings_get_template_part' ) ) {
	/**
	 * Get template part
	 *
	 * @param $template
	 * @param string $folder
	 * @param array $args
	 *
	 * @return string
	 */
	function pebas_paid_listings_get_template_part( $template, $folder = '', $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		if ( empty( $folder ) ) {
			$dir = PEBAS_PL_DIR . "templates/{$template}.php";
		} else {
			$dir = PEBAS_PL_DIR . "templates/{$folder}/{$template}.php";
		}

		return $dir;
	}
}


add_filter( 'woocommerce_product_query_tax_query', 'pebas_exclude_packages_from_query' );
if ( ! function_exists( 'pebas_exclude_packages_from_query' ) ) {
	/**
	 * Exclude packages from WooCommerce
	 * products query
	 * ----------------------------------
	 *
	 * @param $tax_query
	 *
	 * @return mixed
	 */
	function pebas_exclude_packages_from_query( $tax_query ) {
		if ( is_shop() ) {
			$tax_query['tax_query'][] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => array( 'job_package', 'job_package_subscription', 'booking' ),//todo move to paid listings && bookings extensions plugins
				'operator' => 'NOT IN',
			);
		}

		return $tax_query;
	}
}

add_filter( 'woocommerce_related_products', 'pebas_exclude_packages_from_related' );
if ( ! function_exists( 'pebas_exclude_packages_from_related' ) ) {
	/**
	 * Exclude packages from WooCommerce
	 * related products query
	 * ---------------------------------
	 *
	 * @param $exclude_ids
	 *
	 * @return array
	 */
	function pebas_exclude_packages_from_related( $exclude_ids ) {
		$posts       = get_posts( array(
			'post_type' => 'product',
			'tax_query' => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => array( 'job_package', 'job_package_subscription', 'booking' ),
					'operator' => 'NOT IN',
				)
			)
		) );
		$exclude_ids = wp_list_pluck( $posts, 'ID' );

		return $exclude_ids;
	}

}
