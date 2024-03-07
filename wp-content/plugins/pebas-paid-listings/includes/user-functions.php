<?php
/**
 * Get a users packages from the DB. By default this will only return packages
 * that are not used up. If the $all parameter is `true`, all packages will be
 * returned.
 *
 * @param int $user_id
 * @param string|array $package_type
 * @param bool $all
 *
 * @return array of objects
 */
function pebas_pl_get_user_packages( $user_id, $package_type = '', $all = false ) {
	global $wpdb;
	$table = pebas_paid_listings_install::$pebas_paid_listings_table;

	if ( empty( $package_type ) ) {
		$package_type = array( 'job_listing' );
	} else {
		$package_type = array( $package_type );
	}

	$query = "SELECT * FROM {$wpdb->prefix}{$table} WHERE user_id = %d AND package_type IN ( '" . implode( "','", $package_type ) . "' )";

	if ( ! $all ) {
		$query .= ' AND ( package_count < package_limit OR package_limit = 0 )';
	}

	$packages = $wpdb->get_results( $wpdb->prepare( $query, $user_id ), OBJECT_K );

	return $packages;
}

/**
 * Get a package
 *
 * @param  int $package_id
 *
 * @return pebas_pl_package
 */
function pebas_pl_get_user_package( $package_id ) {
	global $wpdb;
	$table = pebas_paid_listings_install::$pebas_paid_listings_table;

	$package = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$table} WHERE id = %d;", $package_id ) );

	return pebas_pl_get_package( $package );
}

/**
 * Give a user a package
 *
 * @param  int $user_id
 * @param  int $product_id
 * @param  int $order_id
 *
 * @return int|bool false
 */
function pebas_pl_give_user_package( $user_id, $product_id, $order_id = 0 ) {
	global $wpdb;
	$table = pebas_paid_listings_install::$pebas_paid_listings_table;

	$package = wc_get_product( $product_id );

	if ( ! $package->is_type( 'job_package' ) && ! $package->is_type( 'job_package_subscription' ) ) {
		return false;
	}

	$is_featured = false;
	if ( $package instanceof WC_Product_Job_Package || $package instanceof WC_Product_Job_Package_Subscription ) {
		$is_featured = $package->is_job_listing_featured();
	}

	$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}{$table} WHERE
		user_id = %d
		AND product_id = %d
		AND order_id = %d
		AND package_duration = %d
		AND package_limit = %d
		AND package_featured = %d
		AND package_type = %d",
		$user_id,
		$product_id,
		$order_id,
		$package->get_duration(),
		$package->get_limit(),
		$is_featured ? 1 : 0,
		'job_listing' ) );

	if ( $id ) {
		return $id;
	}

	$wpdb->insert(
		"{$wpdb->prefix}{$table}",
		array(
			'user_id'          => $user_id,
			'product_id'       => $product_id,
			'order_id'         => $order_id,
			'package_count'    => 0,
			'package_duration' => $package->get_duration(),
			'package_limit'    => $package->get_limit(),
			'package_featured' => $is_featured ? 1 : 0,
			'package_type'     => 'job_listing',
		)
	);

	return $wpdb->insert_id;
}

/**
 * Get customer ID from Order
 *
 * @param WC_Order $order
 *
 * @return int
 */
function pebas_pl_get_order_customer_id( $order ) {

	return $order->get_customer_id();
}

/**
 * Get customer ID from Order
 *
 * @param WC_Order $order
 *
 * @return int
 */
function pebas_pl_get_order_id( $order ) {

	return $order->get_id();
}

/**
 * @deprecated
 */
function get_user_job_packages( $user_id ) {
	return pebas_pl_get_user_packages( $user_id, 'job_listing' );
}

/**
 * @deprecated
 */
function get_user_job_package( $package_id ) {
	return pebas_pl_get_user_package( $package_id );
}

/**
 * @deprecated
 */
function give_user_job_package( $user_id, $product_id ) {
	return pebas_pl_give_user_package( $user_id, $product_id );
}

/**
 * @deprecated
 */
function user_job_package_is_valid( $user_id, $package_id ) {
	return pebas_pl_package_is_valid( $user_id, $package_id );
}

/**
 * @deprecated
 */
function increase_job_package_job_count( $user_id, $package_id ) {
	pebas_pl_increase_package_count( $user_id, $package_id );
}

/**
 * Get listing IDs for a user package
 *
 * @return array
 */
function pebas_pl_get_listings_for_package( $user_package_id ) {
	global $wpdb;

	return $wpdb->get_col( $wpdb->prepare(
		"SELECT post_id FROM {$wpdb->postmeta} " .
		"LEFT JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID " .
		"WHERE meta_key = '_user_package_id' " .
		'AND meta_value = %s;'
		, $user_package_id ) );
}

/**
 * Get all listings by a single package
 *
 * @param $package
 *
 * @return array
 */
function pebas_get_listings_by_package( $package ) {
	global $wpdb;
	$table = pebas_paid_listings_install::$pebas_paid_listings_table;

	$posts = array();
	$ids   = $wpdb->get_col(
		"SELECT id FROM {$wpdb->prefix}{$table} " .
		"WHERE product_id IN (" . ( esc_sql( $package ) ) . ") "
	);
	foreach ( $ids as $id ) {
		$post_ids = $wpdb->get_col( $wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} " .
			"WHERE meta_key='_user_package_id' " .
			"AND meta_value=%s "
			, $id ) );
		$posts[]  = $post_ids;
	}
	$posts_array = array();
	foreach ( $posts as $post ) {
		$posts_array = array_merge( $posts_array, $post );
	}

	return $posts_array;
}
