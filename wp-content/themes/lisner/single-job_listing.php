<?php
/**
 * Single Listing page layout
 *
 * @author   pebas
 * @version  1.0.0
 */


if ( ! class_exists( 'pbs_single_listing' ) ) {
	class pbs_single_listing extends pbs_page_template {

		/**
		 * Additional classes for main wrapper
		 *
		 * @var array
		 */
		public $classes = array( 'main-single-listing' );

		public function header() {
			global $post;
			$option        = get_option( 'pbs_option' );
			$page_template = $post->_listing_template ? : ( isset( $option['listings-template'] ) ? $option['listings-template'] : 1 );
			$this->classes = array( "main-single-listing single-listing-style-{$page_template}" );
			if ( class_exists( 'Lisner_Core' ) ) {
				$expired = get_option( 'job_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ? true : false;
				if ( ! isset( $_REQUEST['job_manager_form'] ) ) {
					lisner_listings::set_listing_views_count( get_the_ID() );
					lisner_statistics::add_listing_view( $post->post_author, get_the_ID() );
				}
				if ( ! $expired && 3 != $page_template ) {
					include lisner_helper::get_template_part( 'single-header', 'listing/single', $page_template );
				} else {
					$this->classes = array( 'main-single-listing', 'no-content' );
				}
			}
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			$option = get_option( 'pbs_option' );
			the_post();
			?>

			<?php the_content(); // load listing content ?>

			<?php
		}
	}

	$single_listing = new pbs_single_listing();
}
