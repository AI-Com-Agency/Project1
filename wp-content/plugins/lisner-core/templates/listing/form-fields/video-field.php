<?php
/**
 * Shows the `video` form field on listing forms.
 *
 * @author      pebas
 * @package     Lisner
 * @category    Template
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$classes = array( 'input-text' );
$post_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : '';
$video   = get_post_meta( $post_id, '_listing_video', true );
?>
<div class="lisner-video-ajax input-group">
    <input type="url" name="listing_video" class="form-control" id="video-ajax"
           placeholder="<?php echo esc_html( $field['placeholder'] ) ?>"
           value="<?php echo esc_attr( $video ) ?>">
    <span class="input-group-append ajax-icon">
            <i class="material-icons"><?php echo esc_html( 'videocam' ); ?></i>
    </span>
    <div class="video-preview <?php echo ! empty( $video ) ? esc_attr( 'video-preview-loaded' ) : ''; ?>">
		<?php $embed = wp_oembed_get( isset( $video ) ? $video : '' ); ?>
		<?php echo $embed ? $embed : ( empty( $embed ) ? '' : esc_html__( 'Embed not available', 'lisner-core' ) ); ?>
    </div>
</div>

