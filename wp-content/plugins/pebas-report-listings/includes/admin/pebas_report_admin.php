<?php
/**
 * Class pebas_report_admin
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_report_admin
 */
class pebas_report_admin {

	protected static $_instance = null;


	/**
	 * @return null|pebas_report_admin
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_report_admin constructor.
	 */
	public function __construct() {
		$report_type = pebas_report_listings_install::$pebas_report_type_name;

		// add actions
		add_action( 'admin_menu', array( $this, 'report_admin_menu' ) );

		add_filter( 'parent_file', array( $this, 'admin_menu_parent_file' ) );
		add_filter( 'submenu_file', array( $this, 'admin_menu_submenu_file' ) );
		// admin columns
		add_filter( "manage_edit-{$report_type}_columns", array( $this, 'manage_columns' ) );
		add_action( "manage_{$report_type}_posts_custom_column", array( $this, 'manage_custom_column' ), 10, 2 );

		// admin columns filter
		add_action( 'restrict_manage_posts', array( $this, 'manage_column_filter_status_dropdown' ) );
		add_action( 'pre_get_posts', array( $this, 'manage_column_status_filter' ) );

		// report update messages
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
	}

	/**
	 * Add Report Listings admin menu
	 */
	public function report_admin_menu() {
		$report     = pebas_report_listings_install::$pebas_report_type_name;
		$report_obj = get_post_type_object( $report );

		// add submenu page under listings
		add_submenu_page(
			$parent_slug = 'edit.php?post_type=job_listing',
			$page_title = $report_obj->labels->name,
			$menu_title = $report_obj->labels->menu_name,
			$capability = 'manage_job_listings',
			$menu_slug = "edit.php?post_type={$report}"
		);
	}

	function admin_menu_parent_file( $parent_file ) {
		global $current_screen;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_report_listings_install::$pebas_report_type_name == $current_screen->post_type ) {
			$parent_file = 'edit.php?post_type=job_listing';
		}

		return $parent_file;
	}

	function admin_menu_submenu_file( $submenu_file ) {
		global $current_screen;
		$post_type = pebas_report_listings_install::$pebas_report_type_name;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_report_listings_install::$pebas_report_type_name == $current_screen->post_type ) {
			$submenu_file = "edit.php?post_type={$post_type}";
		}

		return $submenu_file;
	}

	/**
	 * Add Reported Listing column statuses
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_columns( $columns ) {

		$old_columns = $columns;
		$columns     = array(
			'cb'     => $old_columns['cb'],
			'status' => __( 'Status', 'pebas-report-listings' ),
			'title'  => __( 'Reported', 'pebas-report-listings' ),
			'listing'  => __( 'Listing', 'pebas-report-listings' ),
			'author' => __( 'Reported By', 'pebas-report-listings' ),
			'date'   => $old_columns['date'],
		);

		return $columns;
	}

	/**
	 * Manage Custom Report Listings columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function manage_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'status' :
				$status = get_post_meta( $post_id, '_report_status', true );
				?>
				<span class='status status-<?php echo sanitize_html_class( strtolower( $status ) ); ?>'>
					<?php echo isset( $status ) ? ucfirst( esc_html( $status ) ) : esc_html__( 'Unknown', 'pebas-report-listings' ); ?>
				</span>
				<?php
				break;
			case 'listing':
				$listing = get_post_meta( $post_id, '_report_listing_id', true );
				$listing_link = admin_url() . "/post.php?post={$listing}&action=edit";
				?>
				<strong><a class="row-title" href="<?php echo esc_url( $listing_link ); ?>"><?php echo esc_html( get_the_title( $listing ) ); ?></a></strong>
			<?php
				break;
			default :
				break;
		}

		return $column;
	}

	/**
	 * Add Report Listing statuses in dropdown
	 *
	 * @param $post_type
	 */
	public function manage_column_filter_status_dropdown( $post_type ) {

		/* Bail early if not claim post type */
		if ( pebas_report_listings_install::$pebas_report_type_name !== $post_type ) {
			return;
		}

		/* Vars */
		$statuses = array( 'pending', 'stashed' );
		$request  = stripslashes_deep( $_GET );
		?>
		<select name='report_status' id='dropdown_report_status'>
			<option value=''><?php _e( 'All report statuses', 'pebas-report-listings' ); ?></option>

			<?php foreach ( $statuses as $key => $status ) { ?>

				<option value='<?php echo esc_attr( $key ); ?>' <?php selected( isset( $request['report_status'] ) ? $request['report_status'] : '', $key ); ?>><?php echo esc_html( $status ); ?></option>

			<?php } ?>

		</select><!-- #dropdown_report_status -->
		<?php
	}

	/**
	 * Allow sorting Report Listing columns by status
	 *
	 * @param $query
	 */
	public function manage_column_status_filter( $query ) {

		/* Vars */
		global $hook_suffix, $post_type;
		$request  = stripslashes_deep( $_GET );
		$statuses = array( 'pending', 'stashed' );

		/* Only in Admin Edit Column Screen */
		if ( is_admin() && 'edit.php' == $hook_suffix && pebas_report_listings_install::$pebas_report_type_name == $post_type && $query->is_main_query() && isset( $request['report_status'] ) && array_key_exists( $request['report_status'], $statuses ) ) {

			/* Set simple meta query */
			$query->query_vars['meta_key']   = '_report_status';
			$query->query_vars['meta_value'] = esc_attr( $request['report_status'] );
		}
	}

	/**
	 * Listing report updated messages
	 *
	 * @param $messages
	 *
	 * @return mixed
	 */
	public function post_updated_messages( $messages ) {
		$post      = get_post();
		$post_type = pebas_report_listings_install::$pebas_report_type_name;

		$messages[ $post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Report updated.', 'pebas-report-listings' ),
			2  => __( 'Custom field updated.', 'pebas-report-listings' ),
			3  => __( 'Custom field deleted.', 'pebas-report-listings' ),
			4  => __( 'Report updated.', 'pebas-report-listings' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Report restored to revision from %s', 'pebas-report-listings' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Report published.', 'pebas-report-listings' ),
			7  => __( 'Report saved.', 'pebas-report-listings' ),
			8  => __( 'Report submitted.', 'pebas-report-listings' ),
			9  => sprintf(
				__( 'Report scheduled for: <strong>%1$s</strong>.', 'pebas-report-listings' ),
				date_i18n( __( 'M j, Y @ G:i', 'pebas-report-listings' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Report draft updated.', 'pebas-report-listings' ),
		);

		$return_to_reports_link = sprintf( ' <a href="%s">%s</a>', esc_url( admin_url( "edit.php?post_type={$post_type}" ) ), __( 'Return to reports', 'pebas-report-listings' ) );

		$messages[ $post_type ][1]  .= $return_to_reports_link;
		$messages[ $post_type ][6]  .= $return_to_reports_link;
		$messages[ $post_type ][9]  .= $return_to_reports_link;
		$messages[ $post_type ][8]  .= $return_to_reports_link;
		$messages[ $post_type ][10] .= $return_to_reports_link;

		return $messages;
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_report_admin
 */
function pebas_report_admin() {
	return pebas_report_admin::instance();
}
