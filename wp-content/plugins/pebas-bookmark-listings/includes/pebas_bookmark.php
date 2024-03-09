<?php
/**
 * Class pebas_bookmark
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_bookmark
 */
class pebas_bookmark {

	protected static $_instance = null;


	/**
	 * @return null|pebas_bookmark
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_bookmark constructor.
	 */
	public function __construct() {
		add_action( 'lisner_ajax_bookmark', array( $this, 'bookmark' ) );
		add_action( 'lisner_ajax_bookmark_delete', array( $this, 'bookmark_delete' ) );
	}

	/**
	 * Add bookmark
	 */
	public function bookmark() {
		$result = array();
		if ( ! empty( $_POST['action'] ) && 'bookmark' == $_POST['action'] && wp_verify_nonce( $_REQUEST['bookmark_nonce'], 'bookmark' ) ) {
			$user_id    = isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) ? $_POST['user_id'] : null;
			$listing_id = isset( $_POST['listing_id'] ) && ! empty( $_POST['listing_id'] ) ? $_POST['listing_id'] : null;
			if ( isset( $user_id ) && isset( $listing_id ) ) {
				$bookmark_post = get_posts( array(
					'post_type'  => 'listing_bookmark',
					'meta_key'   => '_bookmark_listing',
					'meta_value' => $listing_id
				) );
			} else {
				$message         = esc_html__( 'User id or listing id is not set, please contact site administrator', 'pebas-bookmark-listings' );
				$result['error'] = $message;
			}
			if ( ! isset( $bookmark_post ) || 0 == count( $bookmark_post ) ) {
				$listing = get_post( $listing_id );
				/* Create Bookmark */
				$post_data   = array(
					'post_title'  => sprintf( esc_html__( '%s Bookmarks', 'pebas-bookmark-listings' ), $listing->post_title ),
					'post_type'   => pebas_bookmark_listings_install::$pebas_bookmark_type_name,
					'post_status' => 'publish',
				);
				$bookmark_id = wp_insert_post( $post_data );
				update_post_meta( $bookmark_id, '_bookmark_listing', $listing_id );
				add_post_meta( $bookmark_id, '_bookmark_users', $user_id );
				$message           = esc_html__( 'Bookmarked!', 'pebas-bookmark-listings' );
				$result['success'] = $message;
			} else {
				if ( 1 != count( $bookmark_post ) ) {
					$message         = esc_html__( 'Multiple bookmark post types attached to the same user, please contact site administrator.', 'pebas-bookmark-listings' );
					$result['error'] = $message;
				} else {
					/* Get bookmark post object */
					$bookmark_obj = get_post( array_shift( $bookmark_post ) );
					$bookmark_id  = $bookmark_obj->ID;
				}
			}
			if ( ! is_wp_error( $bookmark_id ) ) {

				/* Update Status */
				$current_users = get_post_meta( $bookmark_id, '_bookmark_users', false );
				if ( ! in_array( $user_id, $current_users ) ) {
					add_post_meta( $bookmark_id, '_bookmark_users', $user_id );
					$message           = esc_html__( 'Bookmarked!', 'pebas-bookmark-listings' );
					$result['success'] = $message;
					$result['icon']    = esc_html( 'bookmark' );
				} else {
					delete_post_meta( $bookmark_id, '_bookmark_users', $user_id );
					$message           = esc_html__( 'Removed from bookmarks.', 'pebas-bookmark-listings' );
					$result['success'] = $message;
					$result['icon']    = esc_html( 'bookmark_border' );
				}

			}

		} else {
			$message         = esc_html__( 'Something is not initialized properly, contact site administrator.', 'pebas-bookmark-listings' );
			$result['error'] = $message;
		}

		wp_send_json( $result );
	}

	/**
	 * Delete bookmark
	 */
	public function bookmark_delete() {
		$result = array();
		if ( ! empty( $_POST['action'] ) && 'bookmark_delete' == $_POST['action'] && wp_verify_nonce( $_REQUEST['bookmark_nonce'], 'bookmark_delete' ) ) {
			$user_id    = get_current_user_id();
			$listing_id = isset( $_POST['listing_id'] ) && ! empty( $_POST['listing_id'] ) ? $_POST['listing_id'] : null;
			if ( isset( $user_id ) && isset( $listing_id ) ) {
				$bookmark_post = get_posts( array(
					'post_type'  => 'listing_bookmark',
					'meta_key'   => '_bookmark_listing',
					'meta_value' => $listing_id
				) );
				/* Get bookmark post object */
				$bookmark_obj = get_post( array_shift( $bookmark_post ) );
				$bookmark_id  = $bookmark_obj->ID;
				if ( ! is_wp_error( $bookmark_id ) ) {
					/* Update Status */
					delete_post_meta( $bookmark_id, '_bookmark_users', $user_id );
					$message           = esc_html__( 'Removed from bookmarks.', 'pebas-bookmark-listings' );
					$result['success'] = $message;
				}
			} else {
				$message         = esc_html__( 'User id or listing id is not set', 'pebas-bookmark-listings' );
				$result['error'] = $message;
			}

		} else {
			$message         = esc_html__( 'Something is not initialized properly, contact site administrator.', 'pebas-bookmark-listings' );
			$result['error'] = $message;
		}

		wp_send_json( $result );
	}

	/**
	 * Has user bookmarked the post
	 *
	 * @param $listing_id
	 *
	 * @return bool
	 */
	public static function is_listing_bookmarked( $listing_id ) {
		$bookmark_post = get_posts( array(
			'post_type'  => 'listing_bookmark',
			'meta_key'   => '_bookmark_listing',
			'meta_value' => $listing_id
		) );

		if ( 0 == count( $bookmark_post ) ) {
			return false;
		}
		$bookmark_post = array_shift( $bookmark_post );
		$bookmarks = get_post_meta( $bookmark_post->ID, '_bookmark_users' );
		if ( in_array( get_current_user_id(), $bookmarks ) ) {
			return true;
		}

		return false;
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_bookmark
 */
function pebas_bookmark() {
	return pebas_bookmark::instance();
}
