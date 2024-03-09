<?php
/**
 * Single page layout
 *
 * @author   pebas
 * @version  1.0.0
 */


if ( ! class_exists( 'pbs_single' ) ) {
	class pbs_single extends pbs_page_template {

		/**
		 * Additional classes for main wrapper
		 *
		 * @var array
		 */
		public $classes = array( 'single-post single-post-default main-single-listing wrapped' );

		public function header() {
			?>
			<!-- Post Single / Header -->
			<header class="single-listing-header text-center no-image">
				<!-- Post Single / Media -->
				<?php get_template_part( 'views/posts/single/single', 'media' ); // get post media ?>
			</header>
			<?php
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			$option      = get_option( 'pbs_option' );
			$has_sidebar = is_active_sidebar( 'sidebar-single' );
			the_post();
			?>
			<div class="col-sm-12">
				<div class="row row-wrapper justify-content-center">
					<div class="col-lg-8 col-xs-12">
						<article id="post-print"
						         class="single-post single-listing <?php echo ! has_post_thumbnail() ? esc_attr( 'no-image' ) : ''; ?>">

							<!-- Single Post / Breadcrumbs -->
							<section class="single-listing-meta">
								<div class="row justify-content-between">
									<div class="col-sm-<?php echo ! class_exists( 'Lisner_Core' ) ? esc_attr( '12' ) : esc_attr( '6' ); ?>">
										<?php get_template_part( 'views/posts/single/single', 'breadcrumbs' ); // get post category ?>
									</div>
									<?php do_action( 'pbs_single_post_share' ); ?>
								</div>
							</section>

							<!-- Single Post / Title -->
							<section class="single-post-main single-listing-main clearfix">
								<!-- Single Post / Prev/Next Posts -->
								<?php get_template_part( 'views/posts/single/single', 'prev_next' ); // get post previous & next ?>
								<?php get_template_part( 'views/posts/single/single', 'title' ); // get post title ?>
								<?php if ( ! class_exists( 'Lisner_Core' ) && has_post_thumbnail() ) : ?>
									<figure class="media justify-content-center">
										<?php the_post_thumbnail( 'full' ) ?>
									</figure>
								<?php elseif ( pbs_post_functions::has_media() && has_post_format() ): ?>
									<figure class="media">
										<?php get_template_part( 'views/posts/loop/loop', 'media' ); // get post media ?>
									</figure>
								<?php endif; ?>
								<?php get_template_part( 'views/posts/single/single', 'meta' ); // get post category ?>
								<?php get_template_part( 'views/posts/single/single', 'content' ); // get post content ?>
							</section>

							<!-- Post Tags -->
							<?php get_the_tag_list(); ?>
							<?php $tags = get_the_terms( get_the_ID(), 'post_tag' ); ?>
							<?php if ( $tags ) : ?>
								<section class="single-listing-taxonomies-wrapper post-tags">
									<h4 class="single-listing-section-title"><?php echo esc_html__( 'Tags', 'lisner' ); ?></h4>
									<?php get_template_part( 'views/posts/single/single', 'tags' ); // get post tags ?>
								</section>
							<?php endif; ?>

							<!-- Comments -->
							<?php get_template_part( 'views/posts/single/single', 'comments' ); // get post comments ?>

							<!-- Pagination -->
							<?php get_template_part( 'views/partials/pagination' ); // get post pagination ?>

						</article>
					</div>
				</div>
			</div>

			<?php
		}
	}

	$single = new pbs_single();
}
