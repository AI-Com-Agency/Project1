<?php
/**
 * Index page layout
 *
 * @author   pebas
 * @version  1.0.0
 */


if ( ! class_exists( 'pbs_index' ) ) {
	class pbs_index extends pbs_page_template {

		/**
		 * Additional classes for main wrapper
		 *
		 * @var array
		 */
		public $classes = array( 'main-blog' );

		/**
		 * Additional classes for main container
		 *
		 * @var array
		 */
		public $container_classes = array( 'container-search-full' );

		public function header() {
			$page_id = get_queried_object_id();
			if ( class_exists( 'Lisner_Core' ) ) {
				include lisner_helper::get_template_part( 'header-media', 'pages/header' );
			} else {
				if ( ! is_home() && get_the_ID() != get_option( 'page_on_front' ) ) {
					?>
					<header class="header-hero header-hero-unit">
						<!-- Page Title -->
						<div class="header-hero-inner container">
							<div class="row">
								<div class="col-sm-12">
									<div class="header-hero-title text-left">
										<?php if ( is_archive() ) : ?>
											<h1 class="page-hero-title"><?php the_archive_title(); ?></h1>
										<?php else: ?>
											<h1 class="page-hero-title"><?php echo get_the_title( $page_id ); ?></h1>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</header>
					<?php
				}
			}
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			$has_sidebar = is_active_sidebar( 'sidebar-blog' );
			$count       = 0;
			$row_number  = $has_sidebar ? 3 : 4;
			?>
			<?php if ( class_exists( 'Lisner_Core' ) && ! is_front_page() && ! is_archive() ) : ?>
				<div class="col-sm-12">
					<div class="posts-category-filter">
						<?php pbs_post_functions::get_posts_filter(); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-lg-<?php echo esc_html( $has_sidebar ) ? esc_attr( '9' ) : esc_attr( '12' ); ?>">
				<div class="row blog-posts-row <?php echo ( is_home() && is_front_page() ) || is_archive() ? esc_attr( 'blog-masonry' ) : ''; ?>">
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : ?>
							<?php the_post(); ?>
							<?php $class = $has_sidebar ? esc_attr( '4' ) : esc_attr( '3' ); ?>
							<div class="col-xl-<?php echo esc_attr( $class ); ?> col-lg-4 col-sm-6">
								<?php get_template_part( 'views/posts/loop/loop' ); ?>
							</div>
							<?php wp_reset_postdata(); ?>
						<?php endwhile; ?>
					<?php else: ?>
						<!-- Error Header -->
						<div class="posts no-posts">
							<?php $query = null !== get_query_var( 's' ) ? get_query_var( 's' ) : ''; ?>
							<h5><?php echo sprintf( __( 'It looks like no posts were found for: %s, please try something else.', 'lisner' ), '<strong>' . esc_html( $query ) . '</strong>' ); ?></h5>
						</div>
					<?php endif; ?>
				</div>

				<?php get_template_part( 'views/partials/pagination' ); // get post pagination ?>

			</div>

			<?php if ( $has_sidebar && is_active_sidebar( 'sidebar-blog' ) ) : ?>
				<!-- Sidebar -->
				<div class="col-lg-3 sidebar-col">
					<div class="sidebar sidebar-default">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php
		}
	}

	$index = new pbs_index();
}
