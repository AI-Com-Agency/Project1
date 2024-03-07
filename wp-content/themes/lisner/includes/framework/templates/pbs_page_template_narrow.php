<?php

/**
 * Created by Pebas
 *
 */
if ( ! class_exists( 'pbs_page_template_narrow' ) ) {
	abstract class pbs_page_template_narrow {

		/**
		 * [$classes array of additional classes for main container]
		 * @var array
		 */
		public $classes = array();

		/**
		 * [$container_classes array of additional classes for bootstrap container]
		 *
		 * @var array
		 */
		public $container_classes = array();


		public function __construct() {
			get_header();

			$this->header();

			$this->render( $this->classes, $this->container_classes );

			$this->footer();

			get_footer();
		}

		/**
		 * Header rendering
		 *
		 * @return string
		 */
		public function header() {
			return '';
		}

		/**
		 * Page rendering
		 *
		 * @param $classes
		 * @param $container_classes
		 */
		public function render( $classes, $container_classes ) {
			$classes           = ! empty( $classes ) ? implode( ' ', $classes ) : array();
			$container_classes = ! empty( $container_classes ) && is_array( $container_classes ) ? implode( ' ', $container_classes ) : '';
			?>
			<?php $this->before_main_content(); ?>
			<main class="main<?php echo esc_attr( ' ' . $classes ); ?>">
				<div class="container<?php echo esc_attr( ' ' . $container_classes ); ?>">
					<div class="row row-wrapper">
						<?php $this->main_content(); ?>
					</div>
				</div>
			</main>
			<?php $this->after_main_content(); ?>
			<?php
		}

		/**
		 * Before main content
		 *
		 * @return string
		 */
		public function before_main_content() {
			return '';
		}

		/**
		 * Before main content
		 *
		 * @return string
		 */
		public function after_main_content() {
			return '';
		}

		/**
		 * Render page main content
		 *
		 * @return string
		 */
		public function main_content() {
			return '';
		}

		/**
		 * Footer rendering
		 *
		 * @return string
		 */
		public function footer() {
			return '';
		}

	}

}
