<?php
/**
 * Template Name: Listing Single Meta Actions
 * Description: Partial content for single listing content
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/content
 */

$option = get_option( 'pbs_option' );
?>
<!-- Single Listing / Meta Actions -->
<div class="single-listing-meta-actions">
	<?php if ( class_exists( 'Pebas_Bookmark_Listings' ) && is_singular( 'job_listing' ) ): ?>
		<?php if ( is_user_logged_in() ) : ?>
			<?php $is_bookmarked = pebas_bookmark::is_listing_bookmarked( get_the_ID() ); ?>
			<!-- Single Listing / Meta Bookmarks -->
			<div class="single-listing-meta-action bookmark-action bookmark-call"
			     data-user-id="<?php echo esc_attr( get_current_user_id() ); ?>"
			     data-listing-id="<?php echo esc_attr( get_the_ID() ); ?>"
			     data-nonce="<?php echo wp_create_nonce( 'bookmark' ) ?>">
            <span class="meta-bookmarks">
	            <span class="meta-bookmarks-text"><?php esc_html_e( 'Bookmark', 'lisner-core' ); ?></span>
	            <?php if ( $is_bookmarked ) : ?>
		            <i class="material-icons mf"><?php echo esc_html( 'bookmark' ); ?></i>
	            <?php else: ?>
		            <i class="material-icons mf"><?php echo esc_html( 'bookmark_border' ); ?></i>
	            <?php endif; ?>
            </span>
			</div>
		<?php else: ?>
			<!-- Single Listing / Meta Bookmarks -->
			<div class="single-listing-meta-action bookmark-action"
			     data-toggle="modal" data-target="#modal-report">
            <span class="meta-bookmarks">
	            <?php esc_html_e( 'Bookmark', 'lisner-core' ); ?>
	            <i class="material-icons mf"><?php echo esc_html( 'bookmark_border' ); ?></i>
            </span>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<!-- Single Listing / Meta Views -->
	<div class="single-listing-meta-action">
        <span class="meta-views">
            <i class="material-icons mf"><?php echo esc_html( 'remove_red_eye' ); ?></i>
	        <?php echo esc_html( lisner_listings::get_listing_views_count( get_the_ID() ) ); ?>
        </span>
	</div>
	<!-- Single Listing / Meta Views -->
	<?php $liked = lisner_helper::has_user_liked_listing( get_the_ID() ); ?>
	<div class="single-listing-meta-action">
        <span class="meta-likes">
            <span class="listing-likes-call <?php echo $liked ? esc_attr( 'activated' ) : ''; ?> "
                  data-listing-id="<?php the_ID(); ?>">
	            <?php if ( $liked ) : ?>
		            <i class="material-icons mf"><?php echo esc_html( 'favorite' ); ?></i>
	            <?php else: ?>
		            <i class="material-icons mf"><?php echo esc_html( 'favorite_border' ); ?></i>
	            <?php endif; ?>
	            <?php $likes = get_post_meta( get_the_ID(), 'listing_likes', true ); ?>
	            <span class="listing-likes-count"><?php echo esc_html( lisner_get_var( $likes, 0 ) ); ?></span>
            </span>
        </span>
	</div>

	<?php if ( isset( $option['share-posts'] ) && ! empty( $option['share-posts'] ) ) : ?>
		<!-- Single Listing / Meta Share -->
		<a href="javascript:" class="single-listing-meta-action action-share" data-toggle="modal"
		   data-target="#modal-share">
        <span class="meta-share">
            <i class="material-icons mf"><?php echo esc_html( 'share' ); ?></i>
        </span>
		</a>
	<?php endif; ?>
	<?php if ( lisner_helper::is_plugin_active( 'pebas-report-listings' ) && is_singular( 'job_listing' ) ) : ?>
		<?php $report_args = array(); ?>
		<div class="btn-group dropup single-listing-meta-action report-listing">
			<button type="button" class="btn-dropup dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
			        aria-expanded="false">
				<?php echo esc_html( '...' ); ?>
			</button>
			<div class="dropdown-menu">
				<?php include lisner_helper::get_template_part( 'single-report', 'listing/single/sidebar', $report_args ); ?>
			</div>
		</div>
	<?php endif; ?>
</div>
