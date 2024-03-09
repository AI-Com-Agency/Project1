<?php
/**
 * Class pebas_listing_claim
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_claim_listings_claim
 */
class pebas_listing_claim {

	protected static $_instance = null;


	/**
	 * @return null|pebas_listing_claim
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_claim_listings_claim constructor.
	 */
	public function __construct() {
		// add claim link
		add_action( 'single_job_listing_start', array( $this, 'add_claim_link' ) );

		add_filter( 'pebas_claim_listings_submit_claim_link', array( $this, 'add_user_notice' ), 10, 3 );
		add_filter( 'pebas_claim_listings_submit_claim_link', array( $this, 'add_claimed_badge' ), 99, 3 );

		// set claimed listing status
		add_action( 'pebas_claim_listings_claim_status_updated', array( $this, 'set_listing_claim_status' ), 10, 3 );

		// add post class to claimed listings
		add_filter( 'post_class', array( $this, 'add_post_class' ), 10, 3 );
	}

	/**
	 * Add claim link
	 */
	public function add_claim_link() {
		$listing_id = get_the_ID();
		echo self::submit_claim_link( $listing_id );
	}

	/**
	 * Submit claim link
	 *
	 * @param $listing_id
	 *
	 * @return mixed
	 */
	public function submit_claim_link( $listing_id ) {

		/* Var */
		$link = '';

		/* Get Submit Claim URL */
		$submit_claim_url = self::submit_claim_url( $listing_id );

		/* Link HTML */
		if ( $submit_claim_url ) {
			$link = '<a href="' . esc_url( $submit_claim_url ) . '" class="claim-listing"><span>' . __( 'Claim this listing', 'pebas-claim-listings' ) . '</span></a>';
		}

		/* Filter The output. */

		return apply_filters( 'pebas_submit_claim_link', $link, $listing_id, $submit_claim_url );
	}

	public function submit_claim_url( $listing_id ) {

		/* Claim Listing Page URL */
		$submit_claim_page_url = job_manager_get_permalink( 'claim_listing' );
		if ( ! $submit_claim_page_url ) {
			return false; // page not set.
		}

		/* Job Listing Check */
		if ( ! self::is_claimable( $listing_id ) ) {
			return false;
		}

		/* Check if it's the author of the listing */
		if ( is_user_logged_in() ) {
			$post_obj              = get_post( $listing_id );
			$curr_user_id          = get_current_user_id();
			$can_claim_own_listing = get_option( 'claim_own_listing', false );
			if ( ! $can_claim_own_listing && $curr_user_id == $post_obj->post_author ) {
				return false;
			}
		}

		/* Build URL */
		$url = add_query_arg( array(
			'listing_id' => $listing_id,
		), $submit_claim_page_url );

		return esc_url( $url );
	}

	/**
	 * Check whether the listing is claimable
	 *
	 * @param $listing_id
	 *
	 * @return mixed
	 */
	public function is_claimable( $listing_id ) {
		$claimable = true;

		/* Check if listing entry exist */
		$post_obj = get_post( $listing_id );

		if ( ! $post_obj ) {
			$claimable = false;
		}

		/* Check post type */
		if ( 'job_listing' !== $post_obj->post_type ) {
			$claimable = false;
		}

		/* Check status. */
		if ( 'preview' === $post_obj->post_status ) {
			$claimable = false;
		}

		/* Check if it's already claimed/verified. */
		$claimed = get_post_meta( $listing_id, '_claimed', true );

		if ( 1 == $claimed ) {
			$claimable = false;
		}

		return apply_filters( 'pebas_listing_is_claimable', $claimable, $post_obj );
	}

	/**
	 * Replace claim link with notice if user already submit claim to listing.
	 *
	 * @param $link
	 * @param $listing_id
	 * @param $url
	 *
	 * @return string
	 */
	public function add_user_notice( $link, $listing_id, $url ) {
		if ( $link && is_user_logged_in() ) {
			$curr_user_id = get_current_user_id();

			/* Get claims matched with listing ID and current user */
			$claims = get_posts( array(
				'post_type'      => pebas_claim_install()->pebas_claim_type_name,
				'posts_per_page' => 1, // only one.
				'author'         => $curr_user_id,
				'meta_key'       => '_listing_id',
				'meta_value'     => $listing_id,
			) );

			/* Match found. */
			if ( $claims && isset( $claims[0]->ID ) ) {
				$claim_status = pebas_claim()->get_claim_status_label( $claims[0]->ID );

				$link = '<span class="claim-user-notice">';
				$link .= ' <a href="' . $url . '">' . sprintf( __( 'View %s Claim', 'pebas-claim-listings' ), $claim_status ) . '</a>';
				$link .= '</span>';
			}
		}

		return $link;
	}

	/**
	 * Add claimed badge to claimed listing
	 *
	 * @param $link
	 * @param $listing_id
	 * @param $url
	 *
	 * @return mixed|string
	 */
	public function add_claimed_badge( $link, $listing_id, $url ) {
		$claimed = get_post_meta( $listing_id, '_claimed', true );
		if ( 1 == $claimed ) {
			$link = '<span class="claim-verified">' . __( 'Verified Listing', 'pebas-claim-listings' ) . '</span>';
			$link = apply_filters( 'pebas_listing_claimed_badge', $link );
		}

		return $link;
	}

	/**
	 * Set appropriate listing claim status
	 *
	 * @param $claim_id
	 * @param $old_status
	 * @param $request
	 *
	 * @return bool
	 */
	function set_listing_claim_status( $claim_id, $old_status, $request ) {

		/* Claim Data */
		$claim_obj    = get_post( $claim_id );
		$claimer_id   = $claim_obj->post_author;
		$claim_status = get_post_meta( $claim_id, '_status', true );

		/* Listing Data */
		$listing_id  = get_post_meta( $claim_id, '_listing_id', true );
		$listing_obj = get_post( $listing_id );
		if ( ! $listing_obj ) {
			return false;
		}
		$listing_claimed = get_post_meta( $listing_id, '_claimed', true );

		/* Status is approved */
		if ( ( 'approved' == $claim_status ) && ! $listing_claimed ) {

			/* Set to claimed */
			update_post_meta( $listing_id, '_claimed', 1 );

			/* Change Listing Author */
			$args = array(
				'ID'          => $listing_id,
				'post_author' => $claimer_id,
			);
			update_post_meta( $listing_id, '_job_author', $claimer_id );
			wp_update_post( $args );
		} else {
			delete_post_meta( $listing_id, '_claimed' );
		}
	}

	/**
	 * Add appropriate post class
	 *
	 * @param $classes
	 * @param $class
	 * @param $post_id
	 *
	 * @return array
	 */
	public function add_post_class( $classes, $class, $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( 'job_listing' == $post_type ) {
			$claimed   = get_post_meta( $post_id, '_claimed', true );
			$classes[] = $claimed ? 'claimed' : 'not-claimed';
		}

		return $classes;
	}

	/**
	 * Update listing when an claim is approved
	 *
	 * @param $claim_id
	 * @param $listing_id
	 */
	public function update_listing_on_claim_approval( $claim_id, $listing_id ) {

		/* Package ID */
		$package_id = get_post_meta( $claim_id, '_package_id', true );

		/* Check if package exist and WooCommerce Active */
		if ( $package_id && pebas_claim_listings::is_plugin_active( 'woocommerce' ) ) {

			/* Get WC Product */
			$package = wc_get_product( $package_id );

			/* Set Listing Data */
			update_post_meta( $listing_id, '_job_duration', $package->get_duration() );

			if ( 'job_package_subscription' === $package->get_type() && 'listing' === $package->get_package_subscription_type() ) {
				update_post_meta( $listing_id, '_job_expires', '' ); // Never expire automatically.
			} else {
				$expire_time = calculate_job_expiry( $listing_id );
				if ( $expire_time ) {
					update_post_meta( $listing_id, '_job_expires', $expire_time );
				}
			}

			// Paid listings.
			update_post_meta( $listing_id, '_featured', $package->is_featured() ? 1 : 0 );
			wp_update_post( array(
				'ID'         => $listing_id,
				'menu_order' => $package->is_featured() ? - 1 : 0,
			) );
		}
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_listing_claim
 */
function pebas_listing_claim() {
	return pebas_listing_claim::instance();
}
