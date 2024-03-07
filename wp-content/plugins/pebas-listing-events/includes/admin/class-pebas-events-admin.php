<?php
/**
 * Class pebas_events_admin
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_events_admin
 */
class pebas_events_admin {

	protected static $_instance = null;


	/**
	 * @return null|pebas_events_admin
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_events_admin constructor.
	 */
	public function __construct() {
		$report_type = pebas_listing_events_install::$post_type_name;

		// add actions
		add_action( 'admin_menu', array( $this, 'type_admin_menu' ) );

		add_filter( 'parent_file', array( $this, 'admin_menu_parent_file' ) );
		add_filter( 'submenu_file', array( $this, 'admin_menu_submenu_file' ) );
		// admin columns
		add_filter( "manage_edit-{$report_type}_columns", array( $this, 'manage_columns' ) );
		add_action( "manage_{$report_type}_posts_custom_column", array( $this, 'manage_custom_column' ), 10, 2 );

		// report update messages
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
	}

	/**
	 * Add Event Listings admin menu
	 */
	public function type_admin_menu() {
		$type       = pebas_listing_events_install::$post_type_name;
		$report_obj = get_post_type_object( $type );

		// add submenu page under listings
		add_submenu_page(
			$parent_slug = 'edit.php?post_type=job_listing',
			$page_title = $report_obj->labels->name,
			$menu_title = $report_obj->labels->menu_name,
			$capability = 'manage_job_listings',
			$menu_slug = "edit.php?post_type={$type}"
		);
	}

	function admin_menu_parent_file( $parent_file ) {
		global $current_screen;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_listing_events_install::$post_type_name == $current_screen->post_type ) {
			$parent_file = 'edit.php?post_type=job_listing';
		}

		return $parent_file;
	}

	function admin_menu_submenu_file( $submenu_file ) {
		global $current_screen;
		$post_type = pebas_listing_events_install::$post_type_name;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_listing_events_install::$post_type_name == $current_screen->post_type ) {
			$submenu_file = "edit.php?post_type={$post_type}";
		}

		return $submenu_file;
	}

	/*
	 * Add Bookmarked Listing column statuses
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_columns( $columns ) {

		$old_columns = $columns;
		$columns     = array(
			'cb'      => $old_columns['cb'],
			'title'   => __( 'Event', 'pebas-listing-events' ),
			'listing' => __( 'Listing', 'pebas-listing-events' ),
			'start'   => __( 'Expires', 'pebas-listing-events' ),
			'status'  => __( 'Status', 'pebas-listing-events' ),
		);

		return $columns;
	}

	/**
	 * Manage Custom Event Listings columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function manage_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'listing':
				$listing = get_post_meta( $post_id, '_event_listing', true );
				$listing_link = admin_url() . "/post.php?post={$listing}&action=edit";
				?>
				<strong><a class="row-title"
				           href="<?php echo esc_url( $listing_link ); ?>"><?php echo esc_html( get_the_title( $listing ) ); ?></a></strong>
				<?php
				break;
			case 'start':
				$start = rwmb_meta( '_event_start' );
				?>
				<div class="coupon-type"><strong><?php echo esc_html( ucfirst( $start ) ); ?></strong></div>
				<?php
				break;
			case 'status':
				$status = get_post_meta( $post_id, '_event_status', true );
				?>
				<div class="coupon-type"><?php echo esc_html( ucfirst( $status ) ); ?></div>
				<?php
				break;
			default :
				break;
		}

		return $column;
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
		$post_type = pebas_listing_events_install::$post_type_name;

		$messages[ $post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Event updated.', 'pebas-listing-events' ),
			2  => __( 'Custom field updated.', 'pebas-listing-events' ),
			3  => __( 'Custom field deleted.', 'pebas-listing-events' ),
			4  => __( 'Event updated.', 'pebas-listing-events' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Event restored to revision from %s', 'pebas-listing-events' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Event published.', 'pebas-listing-events' ),
			7  => __( 'Event saved.', 'pebas-listing-events' ),
			8  => __( 'Event submitted.', 'pebas-listing-events' ),
			9  => sprintf(
				__( 'Event scheduled for: <strong>%1$s</strong>.', 'pebas-listing-events' ),
				date_i18n( __( 'M j, Y @ G:i', 'pebas-listing-events' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Event draft updated.', 'pebas-listing-events' ),
		);

		$return_to_reports_link = sprintf( ' <a href="%s">%s</a>', esc_url( admin_url( "edit.php?post_type={$post_type}" ) ), __( 'Return to reports', 'pebas-listing-events' ) );

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
 * @return null|pebas_events_admin
 */
function pebas_events_admin() {
	return pebas_events_admin::instance();
}
