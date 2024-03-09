<?php
/**
 * 404 page layout
 *
 * @author  pebas
 * @version  1.0.0
 */


if ( ! class_exists( 'pbs_404' ) ) {
	class pbs_404 extends pbs_page_template {

		/**
		 * Additional classes for main wrapper
		 *
		 * @var array
		 */
		public $classes = array( 'main main-contact page-default' );

		/**
		 * Additional classes for main container
		 *
		 * @var array
		 */
		public $container_classes = array( 'container-wrapped' );

		public function header() {
			if ( class_exists( 'Lisner_Core' ) ) {
				include lisner_helper::get_template_part( 'header-media', 'pages/header' );
			} else {
				?>
				<header class="header-hero header-hero-unit"
				        style="background-image: url(<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/bg_pattern.jpg' ); ?>)">
					<!-- Page Title -->
					<div class="header-hero-inner container">
						<div class="row">
							<div class="col-sm-12">
							</div>
						</div>
					</div>
				</header>
				<?php
			}
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			?>
			<div class="col-sm-12">

				<!-- Page / Main Content -->
				<section class="main-content main-content-page-default">

					<div class="error404-breadcrumb">
						<?php get_template_part( 'views/partials/breadcrumb' ); // get post media ?>
					</div>

					<div class="error404-heading">
						<h1><?php esc_html_e( '404 Error', 'lisner' ); ?></h1>
					</div>

					<div class="error404-content text-center">
						<div class="error404-content-heading">
							<h2 class="error404-content-heading-title"><?php esc_html_e( 'Sorry but page does not exists!', 'lisner' ); ?></h2>
							<h3><?php esc_html_e( 'Try searching something else:', 'lisner' ); ?></h3>
						</div>
						<div class="error404-content-form">
							<?php get_search_form() ?>
						</div>
					</div>

				</section>

			</div>

			<?php
		}
	}

	$pbs_404 = new pbs_404();
}
