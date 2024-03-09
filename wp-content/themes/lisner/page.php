<?php
/**
 * Index page layout
 *
 * @author  pebas
 * @version  1.0.0
 */


if ( ! class_exists( 'pbs_page' ) ) {
	class pbs_page extends pbs_page_template {

		/**
		 * Additional classes for main wrapper
		 *
		 * @var array
		 */
		public $classes = array( 'page-default' );

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
				$this->classes = array( 'page-default page-default-unit' );
				get_template_part( 'views/partials/header', 'media' );
			}
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			global $post;
			$has_sidebar = is_active_sidebar( 'sidebar-page' );
			the_post();
			?>
			<div class="col-sm-<?php echo esc_html( $has_sidebar ) && ! class_exists( 'Vc_Manager' ) ? esc_attr( '9' ) : esc_attr( '12' ); ?>">

				<!-- Page / Main Content -->
				<section class="main-content main-content-page-default clearfix">

					<?php if ( ! class_exists( 'Vc_Manager' ) ) : ?>
						<div class="row page-row">
							<div class="col-sm-12">
								<!-- Page / Title -->
								<h1 class="title-page"><?php the_title(); ?></h1>
							</div>
						</div>
					<?php endif; ?>

					<!-- Page / Content -->
					<?php the_content(); ?>
					<?php wp_reset_postdata(); ?>

					<div class="col-sm-12">
						<!-- Page Comments -->
						<div class="mt-5">
							<?php get_template_part( 'views/posts/single/single', 'comments' ); // get page comments ?>
						</div>
					</div>

					<!-- Page / Pagination -->
					<?php get_template_part( 'views/partials/pagination' ); // get post pagination ?>

				</section>

			</div>

			<?php if ( ( $has_sidebar ) && ! class_exists( 'Vc_Manager' ) ) : ?>
				<!-- Sidebar -->
				<div class="col-sm-3">
					<div class="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php
		}
	}

	$page = new pbs_page();
}
