<?php

/**
 * Post functions
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_post_functions' ) ) {
	class pbs_post_functions {

		protected static $_instance = null;

		/**
		 * @return null|pbs_post_functions
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * pbs_post_functions constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'post_page_rewrite' ), 10, 0 );
			add_action( 'pre_get_posts', array( $this, 'modify_main_query' ) );
		}

		/**
		 * Rewrite post archive url to enable pretty permalinks for ti
		 */
		public function post_page_rewrite() {
			if ( class_exists( 'Lisner_Core' ) ) {
				$page_id = get_option( 'page_for_posts' );
				$page_id = isset( $page_id ) && ! empty ( $page_id ) ? $page_id : get_option( 'page_on_front' );
				$post    = get_post( $page_id );
				if ( $post ) {
					add_rewrite_rule( '^' . $post->post_name . '/page/([0-9]{1,})/?', 'index.php?page_id=' . $page_id . '&paged=$matches[1]', 'top' );
					add_rewrite_rule( '^' . $post->post_name . '/([^/]*)/page/([0-9]{1,})/?', 'index.php?page_id=' . $page_id . '&category=$matches[1]&paged=$matches[2]', 'top' );
					add_rewrite_rule( '^' . $post->post_name . '/([^/]*)/?', 'index.php?page_id=' . $page_id . '&category=$matches[1]', 'top' );
				}
			}
		}

		/**
		 * Modify main query on posts archive page
		 *
		 * @param $query
		 */
		public function modify_main_query( $query ) {
			global $wp;
			$category = wp_basename( $wp->request );
			$cat      = get_category_by_slug( $category );
			if ( $query->is_main_query() ) {
				if ( $cat ) {
					$query->set( 'cat', $cat->term_id );
				}
			}

			remove_all_actions( '__after_loop' );
		}

		/**
		 * Get posts archive filter breadrcumbs
		 */
		public static function get_posts_filter() {
			global $wp;
			$categories      = get_categories( array( 'hide_empty' => false, 'parent' => 0 ) );
			$posts_page_link = get_permalink( get_option( 'page_for_posts' ) );
			$page            = preg_replace( '/page\/(\d+)/i', '', $wp->request );
			?>
			<!-- Post / Category Filter -->
			<nav aria-label="breadcrumb" class="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item home-cat <?php echo wp_basename( $page ) == wp_basename( $posts_page_link ) ? esc_attr( 'active' ) : ''; ?>">
						<a href="<?php echo esc_url( $posts_page_link ); ?>"><?php esc_html_e( 'All', 'lisner' ); ?></a>
					</li>
					<?php if ( $categories ) : ?>
						<?php foreach ( $categories as $category ) : ?>
							<li class="breadcrumb-item <?php echo wp_basename( $page ) == $category->slug ? esc_attr( 'active' ) : ''; ?>">
								<a href="<?php echo esc_url( $posts_page_link . $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></a>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ol>
			</nav>
			<?php
		}

		/**
		 * Remove last div tag from comments
		 *
		 * @return string
		 */
		public static function remove_last_div_from_comments() {
			return '';
		}

		public static function post_date() {
			if ( ! is_new_day() ) {
				the_time( get_option( 'time_format' ) );
			} else {
				$post_from_date = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
				echo sprintf( esc_html__( '%s ago', 'lisner' ), $post_from_date );
			}
		}

		/**
		 * Check whether post has any media attached
		 *
		 * @param string $post_id
		 *
		 * @return bool
		 */
		public static function has_media( $post_id = '' ) {
			$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
			$content = apply_filters( 'the_content', get_the_content() );
			$images  = get_attached_media( 'image' );
			// Only get video from the content if a playlist isn't present.
			if ( false === strpos( $content, 'wp-playlist-script' ) ) {
				$media = get_media_embedded_in_content( $content, array(
					'video',
					'object',
					'embed',
					'iframe',
					'audio'
				) );
			}
			if ( has_post_thumbnail() || ! empty( $media ) || $images ) {
				return true;
			}

			return false;
		}

		/**
		 * Get category page breadcrumbs
		 */
		public static function get_category_page_breadcrumbs() {
			?>
			<!-- Page Breadcrumbs -->
			<div class="page-breadcrumbs">
				<?php
				$category_slug    = get_query_var( 'category_name' );
				$category         = get_category_by_slug( $category_slug );
				$category_parents = get_category_parents( $category->term_id, false, ',', 'slug' );
				$category_parents = explode( ',', $category_parents );
				unset( $category_parents[ array_pop( $category_parents ) ] );
				?>
				<!-- Main Breadcrumbs -->
				<nav class="page-breadcrumbs-breadcrumb">
					<ul class="list-unstyled list-inline">
						<?php $category_parents_count = count( $category_parents ); ?>
						<?php if ( 1 == $category_parents_count ) : ?>
							<?php $cat = get_category_by_slug( $category_parents[0] ); ?>
							<li class="list-inline-item only-cat"><?php echo esc_html( $cat->name ); ?></li>
						<?php else: ?>
							<?php foreach ( $category_parents as $category_parent ) : ?>
								<?php if ( $category_parent ) : ?>
									<?php $cat = get_category_by_slug( $category_parent ); ?>
									<?php if ( 0 == $cat->parent ) : ?>
										<li class="list-inline-item first-cat"><a
													href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
										</li>
									<?php elseif ( $cat->slug == $category_slug ) : ?>
										<li class="list-inline-item"><?php echo esc_html( $cat->name ); ?></a></li>
									<?php else: ?>
										<li class="list-inline-item"><a
													href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
										</li>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</nav>
				<?php $category_parent_id = get_category_by_slug( end( $category_parents ) ); ?>
				<?php $category_by_parent = get_categories( array( 'parent' => $category_parent_id->term_id ) ); ?>
				<?php if ( $category_by_parent ): ?>
					<!-- Alternate Breadcrumbs -->
					<nav class="page-breadcrumbs-breadcrumb alternate-breadcrumb">
						<ul class="list-unstyled list-inline">
							<?php foreach ( $category_by_parent as $category ) : ?>
								<?php if ( $category ) : ?>
									<li class="list-inline-item"><a
												href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Get search page breadcrumbs
		 *
		 * @param $term string
		 */
		public static function get_search_page_breadcrumbs( $term = '' ) {
			?>
			<!-- Page Breadcrumbs -->
			<div class="page-breadcrumbs">
				<?php
				$term        = empty( $term ) ? 's' : $term;
				$search_term = get_query_var( $term );
				?>
				<!-- Main Breadcrumbs -->
				<nav class="page-breadcrumbs-breadcrumb">
					<ul class="list-unstyled list-inline">
						<li class="list-inline-item only-cat"><?php echo esc_html( $search_term ); ?></li>
					</ul>
				</nav>
			</div>
			<?php
		}

		/**
		 * Load more new button for ajax posts loading
		 *
		 * @param $query array
		 */
		public static function load_more_posts_button( $query ) {
			?>
			<div class="load-more-wrapper d-flex justify-content-center mt-3">
				<a href="javascript:"
				   class="load-more-news btn btn-default"><?php esc_html_e( 'Load More', 'lisner' ); ?>
					<span class="news-query-args hidden"
					      data-posts_per_page="<?php echo isset( $query['posts_per_page'] ) ? esc_attr( $query['posts_per_page'] ) : ''; ?>"
					      data-author="<?php echo isset( $query['author'] ) ? esc_attr( $query['author'] ) : ''; ?>"
					      data-cat="<?php echo isset( $query['cat'] ) ? esc_attr( $query['cat'] ) : ''; ?>"
					      data-post__in="<?php echo isset( $query['post__in'] ) ? esc_attr( $query['post__in'] ) : ''; ?>"
					      data-order_by="<?php echo isset( $query['order_by'] ) ? esc_attr( $query['order_by'] ) : ''; ?>"
					      data-order="<?php echo isset( $query['order'] ) ? esc_attr( $query['order'] ) : ''; ?>">
                </span>
				</a>
			</div>
			<?php
		}

		public static function get_author_single() {
			?>
			<div class="row">
				<div class="col-sm-12">
					<div class="single-author">
						<div class="row">
							<?php $author_desc = get_the_author_meta( 'description' ); ?>
							<div class="col-lg-<?php echo ! empty( $author_desc ) ? esc_attr( '5' ) : esc_attr( '12' ); ?> d-flex">
								<?php $author = get_the_author(); ?>
								<?php $author_id = get_the_author_meta( 'ID' ); ?>
								<?php $avatar = get_avatar( $author ); ?>
								<?php if ( ! empty( $avatar ) ) : ?>
								<div class="single-author-image">
									<?php echo get_avatar( $author_id, '105', '', '', array( 'class' => 'rounded-circle' ) ); ?>
								</div>
								<div class="single-author-desc">
									<h4><?php echo esc_html( $author ); ?></h4>
									<?php $author_site = get_the_author_meta( 'url' ); ?>
									<?php if ( ! empty( $author_site ) ): ?>
										<a href="<?php echo esc_url( $author_site ); ?>"
										   class="website"><?php echo esc_html( $author_site ); ?></a>
									<?php endif; ?>
								</div>
							</div>
							<?php if ( ! empty( $author_desc ) ): ?>
								<div class="col-lg-7">
									<p class="single-author-description"><?php echo esc_html( $author_desc ); ?></p>
								</div>
							<?php endif; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}

	}
}

/*
 * Instantiate pbs_post_functions class
 */
function pbs_post_functions() {
	return pbs_post_functions::instance();
}

/**
 * Function used to display comment in custom formatted list
 *
 * @param $comment
 * @param $args
 * @param $depth
 */
function pbs_list_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$add_below          = '';
	?>
	<!-- Comment -->
	<li class="comment">
	<!-- Comment Meta -->
	<div class="comment-meta">
		<!-- Comment Author -->
		<div class="media <?php echo 'pingback' == $comment->comment_type ? esc_attr( 'media-pingback' ) : ''; ?>">
			<!-- Comment Reply -->
			<div class="comment-meta-reply">
				<?php if ( 'pingback' != $comment->comment_type ) : ?>
					<?php
					comment_reply_link( array_merge( $args, array(
						'reply_text' => esc_html__( 'Reply', 'lisner' ) . '<i class="material-icons mf">' . esc_html( 'reply' ) . '</i>',
						'add_below'  => $add_below,
						'depth'      => $depth,
						'max_depth'  => $args['max_depth']
					) ) );
					?>
				<?php endif; ?>
			</div>
			<?php if ( 'pingback' == $comment->comment_type ) : ?>
				<div class="media-info media-info-pingback">
					<div class="media-author">
						<span class="author"><?php esc_html_e( 'Pingback', 'lisner' ); ?></span>
					</div>
				</div>
			<?php else: ?>
				<div class="media-info">
					<a href="<?php echo esc_url( get_author_posts_url( $comment->user_id ) ); ?>">
						<?php echo get_avatar( $comment->user_id, '60', '', '', array( 'class' => 'rounded-circle' ) ); ?>
					</a>
					<?php $author_data = get_userdata( $comment->user_id ); ?>
					<div class="media-author">
						<span class="author"><?php echo isset( $author_data->display_name ) ? esc_html( $author_data->display_name ) : esc_html( get_comment_author() ); ?></span>
						<span class="time"><?php echo human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) . esc_html__( ' ago', 'lisner' ); ?></span>
					</div>
				</div>
			<?php endif; ?>
			<div class="comment-body media-body">
				<?php
				if ( $comment->comment_approved != '0' ) {
					?>
					<?php if ( 'pingback' == $comment->comment_type ) : ?>
						<a class="nav-link pingback-link"
						   href="<?php echo esc_url( $comment->comment_author_url ); ?>"><?php echo get_comment_text(); ?></a>
					<?php else : ?>
						<p><?php echo get_comment_text(); ?></p>
					<?php endif; ?>
					<?php
				} else { ?>
					<p><?php esc_html_e( 'Your comment is awaiting moderation.', 'lisner' ); ?></p>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
}

new pbs_post_functions();
