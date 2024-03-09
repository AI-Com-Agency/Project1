<?php
/**
 * Class pebas_listing_admin
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_listing_admin
 */
class pebas_listing_admin {

	protected static $_instance = null;


	/**
	 * @return null|pebas_listing_admin
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_listing_admin constructor.
	 */
	public function __construct() {

		add_filter( 'manage_edit-job_listing_columns', array( $this, 'columns' ), 20 );
		add_action( 'manage_job_listing_posts_custom_column', array( $this, 'custom_columns' ), 2 );

	}

	/**
	 * Add custom column
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	function columns( $columns ) {
		if ( ! is_array( $columns ) ) {
			$columns = array();
		}
		$columns['listing_claimed'] = '<span class="tips" data-tip="' . __( 'Claimed?', 'pebas-claim-listings' ) . '">' . __( 'Claimed?', 'wp-job-manager-claim-listing' ) . '</span>';

		return $columns;
	}

	/**
	 * Manage custom column
	 *
	 * @param $column
	 */
	function custom_columns( $column ) {
		global $post;
		$post_id = $post->ID;
		switch ( $column ) {
			case 'listing_claimed' :
				$claimed = get_post_meta( $post_id, '_claimed', true );
				if ( $claimed ) {
					echo '<span data-tip="' . __( 'Verified listing', 'pebas-claim-listings' ) . '" class="tips listing_claimed">' . __( 'Verified listing', 'wp-job-manager-claim-listing' ) . '</span>';
				} else {

					$action_url  = add_query_arg( array(
						'post_type'  => \pebas_claim_install()->pebas_claim_type_name,
						'listing_id' => $post_id,
					), admin_url( 'post-new.php' ) );
					$action      = 'add_claim';
					$action_name = 'Create Claim';
					printf( '<a class="button button-icon tips icon-%1$s" target="_blank" href="%2$s" data-tip="%3$s">%4$s</a>', $action, esc_url( $action_url ), esc_attr( $action_name ), esc_html( $action_name ) );
				}
				break;
		}
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_listing_admin
 */
function pebas_listing_admin() {
	return pebas_listing_admin::instance();
}
