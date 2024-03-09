<?php
/**
 * Single job listing.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing.php.
 *
 * @author      pebas
 * @package     templates/listing
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
$option       = get_option( 'pbs_option' );
$hide_expired = get_option( 'job_manager_hide_expired_content', 0 );
?>
<?php $page_template = $post->_listing_template ? : ( isset( $option['listings-template'] ) ? $option['listings-template'] : 1 ); ?>

<?php if ( $hide_expired && 'expired' !== $post->post_status && 2 == $page_template ) : ?>
	<!-- Single Listing Head -->
	<section class="single-listing-head">
		<div class="container">
			<div class="row align-items-end">
				<div class="col-sm-9">
					<?php include lisner_helper::get_template_part( 'single-title', 'listing/single/content' ); ?>
					<?php include lisner_helper::get_template_part( 'single-meta', 'listing/single/content' ); ?>
				</div>
				<div class="col-sm-3">
					<?php include lisner_helper::get_template_part( 'single-meta-actions', 'listing/single/content' ); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- Single Listing / Main Content -->
<div class="col-lg-8 col-crp">
	<article id="post-print" class="single-listing">
		<?php if ( $hide_expired && 'expired' === $post->post_status ) : ?>
			<section>
				<div class="job-manager-info"><?php _e( 'This listing has expired.', 'listing-core' ); ?></div>
			</section>
		<?php else : ?>

			<?php include lisner_helper::get_template_part( "single-template-{$page_template}", 'listing/single/templates' ); ?>

			<?php if ( lisner_helper::is_plugin_active( 'pebas-review-listings' ) ) : ?>
				<?php if ( comments_open() ) : ?>
					<!-- Show more on mobiles -->
					<section class="listing-show-more">
						<a href="javascript:" class="listing-show-more-call"
						   data-show-more="<?php esc_attr_e( 'Show Reviews', 'lisner-core' ); ?>"
						   data-show-less="<?php esc_attr_e( 'Hide Reviews', 'lisner-core' ); ?>"><span><?php esc_html_e( 'Show Reviews', 'lisner-core' ); ?></span><i
									class="material-icons mf"><?php echo esc_html( 'keyboard_arrow_down' ); ?></i></a>
					</section>
				<?php endif; ?>
			<?php endif; ?>

			<?php
			/**
			 * single_job_listing_end hook
			 */
			do_action( 'single_job_listing_end' );
			?>
		<?php endif; ?>
	</article>
</div>
<?php if ( isset( $hide_expired ) && 'expired' !== $post->post_status ) : ?>
	<?php $sidebar_template = $post->_listing_sidebar_template ? : ( isset( $option['listings-sidebar-template'] ) ? $option['listings-sidebar-template'] : 1 ); ?>
	<?php $args = array(
		'sidebar_template' => $sidebar_template
	); ?>
	<!-- Single Listing / Sidebar -->
	<div class="col-lg-4 col-clp single-listing-sidebar-template-<?php echo esc_attr( $sidebar_template ); ?>">
		<aside class="single-listing-sidebar single-listing-sidebar-lisner">
			<?php include lisner_helper::get_template_part( "single-sidebar-template-{$sidebar_template}", 'listing/single/sidebar', $args ); ?>
		</aside>
		<?php if ( lisner_helper::is_plugin_active( 'pebas-claim-listings' ) ) : ?>
			<?php $claim_args = array(
				'has_title' => false
			); ?>
			<?php $is_claimed = $post->_claimed; ?>
			<?php if ( ! $is_claimed ) : ?>
				<aside class="single-listing-sidebar single-listing-claim">
					<div class="single-listing-sidebar">
						<?php include lisner_helper::get_template_part( 'single-claim', 'listing/single/sidebar', $claim_args ); ?>
					</div>
				</aside>
			<?php endif; ?>
		<?php endif; ?>
		<?php dynamic_sidebar( 'sidebar-listing-single' ); ?>
	</div>
<?php endif; ?>
