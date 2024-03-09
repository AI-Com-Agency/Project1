<?php

/**
 * Template Name: Home Page Template
 *
 * @package Templates
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_page_template_home' ) ) {
	class pbs_page_template_home extends pbs_page_template {

		/**
		 * Additional classes for main wrapper
		 *
		 * @var array
		 */
		public $classes = array( 'main main-home' );

		public function header() {
			do_action( 'pbs_home_header_before' );
			do_action( 'pbs_home_header' );
			do_action( 'pbs_home_header_after' );
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			the_post();

			if ( get_the_content() ) {
				the_content();
			}

		}

	}
}

new pbs_page_template_home();
