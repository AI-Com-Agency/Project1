<?php
/**
 * Class pebas_bookmark_admin
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_bookmark_admin
 */
class pebas_bookmark_admin {

	protected static $_instance = null;


	/**
	 * @return null|pebas_bookmark_admin
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_bookmark_admin constructor.
	 */
	public function __construct() {
		$report_type = pebas_bookmark_listings_install::$pebas_bookmark_type_name;

		// add actions
		add_action( 'admin_menu', array( $this, 'report_admin_menu' ) );
		add_action( 'lisner_user_bookmarks', array( $this, 'my_bookmarks' ) );

		add_filter( 'parent_file', array( $this, 'admin_menu_parent_file' ) );
		add_filter( 'submenu_file', array( $this, 'admin_menu_submenu_file' ) );
		// admin columns
		add_filter( "manage_edit-{$report_type}_columns", array( $this, 'manage_columns' ) );
		add_action( "manage_{$report_type}_posts_custom_column", array( $this, 'manage_custom_column' ), 10, 2 );

		// report update messages
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
	}

	/**
	 * Show my packages
	 */
	public function my_bookmarks() {
		$bookmark_posts = get_posts( array(
			'post_type'  => 'listing_bookmark',
			'meta_query' => array(
				array(
					'key'     => '_bookmark_users',
					'value'   => get_current_user_id(),
					'compare' => 'IN'
				)
			)
		) );
		wc_get_template( 'my-bookmarks.php', array(
			'bookmark_posts' => $bookmark_posts,
			'type'           => 'job_listing',
			'template'       => 'listing_bookmarks'
		), '', PEBAS_BM_DIR . '/templates/' );
	}

	/**
	 * Add Bookmark Listings admin menu
	 */
	public function report_admin_menu() {
		$report     = pebas_bookmark_listings_install::$pebas_bookmark_type_name;
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
			) ) && pebas_bookmark_listings_install::$pebas_bookmark_type_name == $current_screen->post_type ) {
			$parent_file = 'edit.php?post_type=job_listing';
		}

		return $parent_file;
	}

	function admin_menu_submenu_file( $submenu_file ) {
		global $current_screen;
		$post_type = pebas_bookmark_listings_install::$pebas_bookmark_type_name;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_bookmark_listings_install::$pebas_bookmark_type_name == $current_screen->post_type ) {
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
			'title'   => __( 'Bookmark', 'pebas-bookmark-listings' ),
			'listing'   => __( 'Listing', 'pebas-bookmark-listings' ),
			'count'   => __( 'Bookmarked By', 'pebas-bookmark-listings' ),
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
				$listing = get_post_meta( $post_id, '_bookmark_listing', true );
				$listing_link = admin_url() . "/post.php?post={$listing}&action=edit";
				?>
				<strong><a class="row-title"
				           href="<?php echo esc_url( $listing_link ); ?>"><?php echo esc_html( get_the_title( $listing ) ); ?></a></strong>
				<?php
				break;
			case 'count':
				$users = get_post_meta( $post_id, '_bookmark_users' );
				$count = count( $users );
				?>
				<div class="bookmark-count"><?php printf( esc_html__( '%d Users', 'pebas-bookmark-listings' ), $count ); ?></div>
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
		$post_type = pebas_bookmark_listings_install::$pebas_bookmark_type_name;

		$messages[ $post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Bookmark updated.', 'pebas-bookmark-listings' ),
			2  => __( 'Custom field updated.', 'pebas-bookmark-listings' ),
			3  => __( 'Custom field deleted.', 'pebas-bookmark-listings' ),
			4  => __( 'Bookmark updated.', 'pebas-bookmark-listings' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Bookmark restored to revision from %s', 'pebas-bookmark-listings' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Bookmark published.', 'pebas-bookmark-listings' ),
			7  => __( 'Bookmark saved.', 'pebas-bookmark-listings' ),
			8  => __( 'Bookmark submitted.', 'pebas-bookmark-listings' ),
			9  => sprintf(
				__( 'Bookmark scheduled for: <strong>%1$s</strong>.', 'pebas-bookmark-listings' ),
				date_i18n( __( 'M j, Y @ G:i', 'pebas-bookmark-listings' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Bookmark draft updated.', 'pebas-bookmark-listings' ),
		);

		$return_to_reports_link = sprintf( ' <a href="%s">%s</a>', esc_url( admin_url( "edit.php?post_type={$post_type}" ) ), __( 'Return to reports', 'pebas-bookmark-listings' ) );

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
 * @return null|pebas_bookmark_admin
 */
function pebas_bookmark_admin() {
	return pebas_bookmark_admin::instance();
}
