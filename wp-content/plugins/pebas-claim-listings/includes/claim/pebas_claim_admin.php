<?php
/**
 * Class pebas_claim_admin
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_claim_admin
 */
class pebas_claim_admin {

	protected static $_instance = null;


	/**
	 * @return null|pebas_claim_admin
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_claim_admin constructor.
	 */
	public function __construct() {
		$claim_type = pebas_claim_install()->pebas_claim_type_name;

		// add actions
		add_action( 'admin_menu', array( $this, 'claim_admin_menu' ) );

		add_filter( 'parent_file', array( $this, 'admin_menu_parent_file' ) );
		add_filter( 'submenu_file', array( $this, 'admin_menu_submenu_file' ) );
		// admin columns
		add_filter( "manage_edit-{$claim_type}_columns", array( $this, 'manage_columns' ) );
		add_action( "manage_{$claim_type}_posts_custom_column", array( $this, 'manage_custom_column' ), 10, 2 );

		// admin columns filter
		add_action( 'restrict_manage_posts', array( $this, 'manage_column_filter_status_dropdown' ) );
		add_action( 'pre_get_posts', array( $this, 'manage_column_status_filter' ) );

		// claim updated message
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

		// verify && redirect claim
		add_action( 'admin_init', array( $this, 'check_new_claim' ) );
	}

	/**
	 * Add Claim Listings admin menu
	 */
	public function claim_admin_menu() {
		$claim     = pebas_claim_install()->pebas_claim_type_name;
		$claim_obj = get_post_type_object( $claim );

		// add submenu page under listings
		add_submenu_page(
			$parent_slug = 'edit.php?post_type=job_listing',
			$page_title = $claim_obj->labels->name,
			$menu_title = $claim_obj->labels->menu_name,
			$capability = 'manage_job_listings',
			$menu_slug = "edit.php?post_type={$claim}"
		);
	}

	function admin_menu_parent_file( $parent_file ) {
		global $current_screen;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_claim_install()->pebas_claim_type_name == $current_screen->post_type ) {
			$parent_file = 'edit.php?post_type=job_listing';
		}

		return $parent_file;
	}

	function admin_menu_submenu_file( $submenu_file ) {
		global $current_screen;
		$post_type = pebas_claim_install()->pebas_claim_type_name;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_claim_install()->pebas_claim_type_name == $current_screen->post_type ) {
			$submenu_file = "edit.php?post_type={$post_type}";
		}

		return $submenu_file;
	}

	/**
	 * Add Listing Claim column statuses
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_columns( $columns ) {

		$old_columns = $columns;
		$columns     = array(
			'cb'     => $old_columns['cb'],
			'status' => __( 'Status', 'pebas-claim-listings' ),
			'title'  => __( 'Claimed Listing', 'pebas-claim-listings' ),
			'author' => __( 'Claimer', 'pebas-claim-listings' ),
			'date'   => $old_columns['date'],
		);

		return $columns;
	}

	/**
	 * Manage Custom Listing Claim columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function manage_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'status' :
				$status = get_post_meta( $post_id, '_status', true );
				$statuses = pebas_claim()->get_listing_claim_statuses();
				?>
                <span class='status status-<?php echo sanitize_html_class( strtolower( $status ) ); ?>'>
					<?php echo isset( $statuses[ $status ] ) ? $statuses[ $status ] : __( 'Unknown', 'pebas-claim-listings' ); ?>
				</span>
				<?php
				break;
			default :
				break;
		}

		return $column;
	}

	/**
	 * Add Listing Claim statuses in dropdown
	 *
	 * @param $post_type
	 */
	public function manage_column_filter_status_dropdown( $post_type ) {

		/* Bail early if not claim post type */
		if ( pebas_claim_install()->pebas_claim_type_name !== $post_type ) {
			return;
		}

		/* Vars */
		$statuses = pebas_claim()->get_listing_claim_statuses();
		$request  = stripslashes_deep( $_GET );
		?>
        <select name='claim_status' id='dropdown_claim_status'>
            <option value=''><?php _e( 'All claim statuses', 'pebas-claim-listings' ); ?></option>

			<?php foreach ( $statuses as $key => $status ) { ?>

                <option value='<?php echo esc_attr( $key ); ?>' <?php selected( isset( $request['claim_status'] ) ? $request['claim_status'] : '', $key ); ?>><?php echo esc_html( $status ); ?></option>

			<?php } ?>

        </select><!-- #dropdown_claim_status -->
		<?php
	}

	/**
	 * Allow sorting Listing Claim columns by status
	 *
	 * @param $query
	 */
	public function manage_column_status_filter( $query ) {

		/* Vars */
		global $hook_suffix, $post_type;
		$request  = stripslashes_deep( $_GET );
		$statuses = pebas_claim()->get_listing_claim_statuses();

		/* Only in Admin Edit Column Screen */
		if ( is_admin() && 'edit.php' == $hook_suffix && pebas_claim_install()->pebas_claim_type_name == $post_type && $query->is_main_query() && isset( $request['claim_status'] ) && array_key_exists( $request['claim_status'], $statuses ) ) {

			/* Set simple meta query */
			$query->query_vars['meta_key']   = '_status';
			$query->query_vars['meta_value'] = esc_attr( $request['claim_status'] );
		}
	}

	/**
	 * Listing Claim updated messages
	 *
	 * @param $messages
	 *
	 * @return mixed
	 */
	public function post_updated_messages( $messages ) {
		$post      = get_post();
		$post_type = pebas_claim_install()->pebas_claim_type_name;

		$messages[ $post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Claim updated.', 'pebas-claim-listings' ),
			2  => __( 'Custom field updated.', 'pebas-claim-listings' ),
			3  => __( 'Custom field deleted.', 'pebas-claim-listings' ),
			4  => __( 'Claim updated.', 'pebas-claim-listings' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Claim restored to revision from %s', 'pebas-claim-listings' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Claim published.', 'pebas-claim-listings' ),
			7  => __( 'Claim saved.', 'pebas-claim-listings' ),
			8  => __( 'Claim submitted.', 'pebas-claim-listings' ),
			9  => sprintf(
				__( 'Claim scheduled for: <strong>%1$s</strong>.', 'pebas-claim-listings' ),
				date_i18n( __( 'M j, Y @ G:i', 'pebas-claim-listings' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Claim draft updated.', 'pebas-claim-listings' ),
		);

		$return_to_claims_link = sprintf( ' <a href="%s">%s</a>', esc_url( admin_url( "edit.php?post_type={$post_type}" ) ), __( 'Return to claims', 'pebas-claim-listings' ) );

		$messages[ $post_type ][1]  .= $return_to_claims_link;
		$messages[ $post_type ][6]  .= $return_to_claims_link;
		$messages[ $post_type ][9]  .= $return_to_claims_link;
		$messages[ $post_type ][8]  .= $return_to_claims_link;
		$messages[ $post_type ][10] .= $return_to_claims_link;

		return $messages;
	}

	/**
	 * Check new claim
	 */
	public function check_new_claim() {
		global $pagenow;
		$claim_archive = add_query_arg( 'post_type', pebas_claim_install()->pebas_claim_type_name, admin_url( 'edit.php' ) );
		if ( 'post-new.php' == $pagenow && isset( $_GET['post_type'] ) && pebas_claim_install()->pebas_claim_type_name == $_GET['post_type'] ) {
			if ( isset( $_GET['listing_id'] ) ) {
				$claimed = get_post_meta( $_GET['listing_id'], '_claimed', true );
				if ( $claimed ) {
					wp_redirect( esc_url_raw( $claim_archive ) );
					exit;
				}
			} else {
				wp_redirect( esc_url_raw( $claim_archive ) );
				exit;
			}
		}
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_claim_admin
 */
function pebas_claim_admin() {
	return pebas_claim_admin::instance();
}
