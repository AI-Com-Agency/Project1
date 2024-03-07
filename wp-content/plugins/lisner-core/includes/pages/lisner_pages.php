<?php

/**
 * Class lisner_pages
 */

class lisner_pages {

	protected static $_instance = null;

	/**
	 * @return null|lisner_pages
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_pages constructor.
	 */
	public function __construct() {
		add_action( 'pbs_nav', array( $this, 'add_main_search_to_navigation' ) );
		add_image_size( 'lisner_hero', 1920, 350, true );
	}

	/**
	 * Add main search to search page header
	 */
	public function add_main_search_to_navigation() {
		$args                      = array();
		$args['use_icon']          = true;
		$args['is_multi_category'] = true;
		$search_page               = get_option( 'job_manager_jobs_page_id' );
		ob_start();
		?>
		<?php if ( ! lisner_helper::is_search_page() ) : ?>
			<form id="search-form" class="page-header-form search-form"
			      action="<?php echo esc_url( get_permalink( $search_page ) ); ?>"
			      method="get">
				<?php include lisner_helper::get_template_part( 'field-taxonomy-search', 'fields/', $args ); // taxonomy search
				?>
				<?php include lisner_helper::get_template_part( 'field-location-search', 'fields/', $args ); // location search
				?>
				<?php include lisner_helper::get_template_part( 'field-button', 'fields/', $args ); // taxonomy search
				?>
			</form>
		<?php endif; ?>
		<?php
		$search = ob_get_clean();
		echo $search;
	}

}

function lisner_pages() {
	return lisner_pages::instance();
}
