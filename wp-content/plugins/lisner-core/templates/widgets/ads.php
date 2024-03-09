<?php
/**
 * Widget Ads Template
 *
 * @author pebas
 * @version 1.0.0
 * @package templates/widgets/
 *
 * @var $instance
 */
?>

<div class="widget-ads">
	<?php if ( 'google_ad' == $instance['ad_type'] ) : ?>
		<?php echo $instance['google_code']; ?>
	<?php else: ?>
        <a href="<?php echo esc_url( $instance['custom_ad_link'] ); ?>" target="_blank">
            <img src="<?php echo esc_url( $instance['custom_ad_media'] ); ?>" alt="ad_image">
        </a>
	<?php endif; ?>
</div>
