<?php
/**
 * Template Name: Listing Single Title
 * Description: Partial content for single listing content
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/content
 */

global $post;
?>

<!-- Single Listing / Title -->
<div class="single-listing-main-title">
	<?php $claimed = $post->_claimed; ?>
	<?php $claimed_render = ''; ?>
	<h1 class="single-listing-title"><?php wpjm_the_job_title(); ?>
		<?php if ( $claimed ) : ?>
			<span class="lisner-listing-claimed material-icons color-success" data-toggle="tooltip"
			      data-title="<?php echo esc_attr__( 'Claimed', 'lisner-core' ) ?>"><?php echo esc_html( 'check_circle' ) ?></span>
		<?php endif; ?>
	</h1>
</div>

