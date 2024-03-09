<?php
/**
 * Template Name: Default Narrow
 *
 * @package Templates
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_page_narrow' ) ) {
	class pbs_page_narrow extends pbs_page_template_narrow {

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
			$container_override = get_post_meta( get_the_ID(), 'page_container_custom', true );
			if( isset( $container_override ) && 1 == $container_override ) {
					$this->container_classes = array( 'container-custom' );
					do_action( 'pbs_custom_container_width', get_the_ID() );
			}
			if ( class_exists( 'Lisner_Core' ) ) {
				include lisner_helper::get_template_part( 'header-media', 'pages/header' );
			} else {
				$this->classes = array( 'page-default page-default-unit' );
			}
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			global $post;
			the_post();
			?>
			<div class="col-sm-12">

				<!-- Page / Main Content -->
				<section class="main-content main-content-page-default">

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

					<!-- Page / Pagination -->
					<?php get_template_part( 'views/partials/pagination' ); // get post pagination ?>

					<?php if ( 0 < $post->comment_count ) : ?>
						<div class="col-sm-12">
							<!-- Page Comments -->
							<div class="mt-5">
								<?php get_template_part( 'views/posts/single/single', 'comments' ); // get page comments ?>
							</div>
						</div>
					<?php endif; ?>

				</section>

			</div>

			<?php
		}
	}

	$page = new pbs_page_narrow();
}
