<?php
/**
 * Shortcode Listing / Listing Preview Modal
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/listing/partials
 */
?>

<div class="lisner-listing-preview-modal">
	<div class="lisner-listing-preview-container">
		<span class="listing-preview-modal-close material-icons"><?php echo esc_attr( 'close' ); ?></span>
		<div class="lisner-listing-preview-wrapper">

			<?php $atts['listing_preview'] = true; ?>
			<!-- Listing / Preview Modal -->
			<?php include lisner_helper::get_template_part( 'listing', 'shortcodes/listing/partials', $atts ); ?>

			<!-- Listing / Preview Map -->
			<div id="map-preview" class="map-preview map-preview-loading"></div>
		</div>
	</div>
</div>
