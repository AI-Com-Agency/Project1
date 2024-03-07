<?php
/**
 * Class pebas_coupons_admin
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_coupons_admin
 */
class pebas_coupons_admin {

	protected static $_instance = null;


	/**
	 * @return null|pebas_coupons_admin
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_coupons_admin constructor.
	 */
	public function __construct() {
		$report_type = pebas_listing_coupons_install::$post_type_name;

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
	 * Add Bookmark Listings admin menu
	 */
	public function type_admin_menu() {
		$type       = pebas_listing_coupons_install::$post_type_name;
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
			) ) && pebas_listing_coupons_install::$post_type_name == $current_screen->post_type ) {
			$parent_file = 'edit.php?post_type=job_listing';
		}

		return $parent_file;
	}

	function admin_menu_submenu_file( $submenu_file ) {
		global $current_screen;
		$post_type = pebas_listing_coupons_install::$post_type_name;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_listing_coupons_install::$post_type_name == $current_screen->post_type ) {
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
			'title'   => __( 'Coupon', 'pebas-listing-coupons' ),
			'listing' => __( 'Listing', 'pebas-listing-coupons' ),
			'type'    => __( 'Type', 'pebas-listing-coupons' ),
			'expires' => __( 'Expires', 'pebas-listing-coupons' ),
			'status'  => __( 'Status', 'pebas-listing-coupons' ),
		);

		return $columns;
	}

	/**
	 * Manage Custom Bookmark Listings columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function manage_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'listing':
				$listing = get_post_meta( $post_id, '_coupon_listing', true );
				$listing_link = admin_url() . "/post.php?post={$listing}&action=edit";
				?>
				<strong><a class="row-title"
				           href="<?php echo esc_url( $listing_link ); ?>"><?php echo esc_html( get_the_title( $listing ) ); ?></a></strong>
				<?php
				break;
			case 'type':
				$type = get_post_meta( $post_id, '_coupon_type', true );
				?>
				<div class="coupon-type"><?php echo esc_html( ucfirst( $type ) ); ?></div>
				<?php
				break;
			case 'expires':
				$end = rwmb_meta( '_coupon_end' );
				?>
				<?php if ( isset( $end ) && ! empty( $end ) ) : ?>
				<div class="coupon-type"><strong><?php echo esc_html( ucfirst( $end ) ); ?></strong></div>
			<?php else: ?>
				<div class="coupon-type"><strong><?php esc_html_e( 'Unlimited', 'pebas-listing-coupons' ); ?></strong>
				</div>
			<?php endif; ?>
				<?php
				break;
			case 'status':
				$status = get_post_meta( $post_id, '_coupon_status', true );
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
		$post_type = pebas_listing_coupons_install::$post_type_name;

		$messages[ $post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Bookmark updated.', 'pebas-listing-coupons' ),
			2  => __( 'Custom field updated.', 'pebas-listing-coupons' ),
			3  => __( 'Custom field deleted.', 'pebas-listing-coupons' ),
			4  => __( 'Bookmark updated.', 'pebas-listing-coupons' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Bookmark restored to revision from %s', 'pebas-listing-coupons' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Bookmark published.', 'pebas-listing-coupons' ),
			7  => __( 'Bookmark saved.', 'pebas-listing-coupons' ),
			8  => __( 'Bookmark submitted.', 'pebas-listing-coupons' ),
			9  => sprintf(
				__( 'Bookmark scheduled for: <strong>%1$s</strong>.', 'pebas-listing-coupons' ),
				date_i18n( __( 'M j, Y @ G:i', 'pebas-listing-coupons' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Bookmark draft updated.', 'pebas-listing-coupons' ),
		);

		$return_to_reports_link = sprintf( ' <a href="%s">%s</a>', esc_url( admin_url( "edit.php?post_type={$post_type}" ) ), __( 'Return to reports', 'pebas-listing-coupons' ) );

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
 * @return null|pebas_coupons_admin
 */
function pebas_coupons_admin() {
	return pebas_coupons_admin::instance();
}
