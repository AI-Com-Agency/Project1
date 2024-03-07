<?php
/**
 * Shortcodes Partials / Button
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/partials/
 */
?>
<?php if ( isset( $atts['display_button'] ) && $atts['display_button'] ) : ?>
	<?php $option = get_option( 'pbs_option' ); ?>
	<?php $color_primary = get_post_meta( get_the_ID(), 'color-primary', true ); ?>
	<?php $color_primary = isset( $color_primary ) ? $color_primary : $option['color-primary']; ?>
	<?php $color_primary_font = get_post_meta( get_the_ID(), 'color-primary-font', true ); ?>
	<?php $color_primary_font = isset( $color_primary_font ) ? $color_primary_font : $option['color-primary-font']; ?>

	<?php $center = isset( $atts['btn_center'] ) ? esc_attr( 'justify-content-center' ) : ''; ?>
	<?php $link = isset( $atts['button_link'] ) ? vc_build_link( $atts['button_link'] ) : ''; ?>
	<?php $size = ! isset( $atts['button_size'] ) ? 'default' : $atts['button_size']; ?>
	<?php $icon = ! isset( $atts['button_icon_material'] ) ? 'add_circle_outline' : $atts['button_icon_material']; ?>
	<?php $bg = ! empty( $atts['button_bg'] ) ? $atts['button_bg'] : ( ! empty( $color_primary ) ? $color_primary : '#fe015b' ); ?>
	<?php $bg_hover = ! empty( $atts['button_bg_hover'] ) ? $atts['button_bg_hover'] : ( ! empty( $color_primary ) ? $color_primary : '#fe015b' ); ?>
	<?php $btn_font = ! empty( $atts['button_font'] ) ? $atts['button_font'] : ( ! empty( $color_primary_font ) ? $color_primary_font : '#ffffff' ); ?>
	<?php $btn_font_hover = ! empty( $atts['button_font_hover'] ) ? $atts['button_font_hover'] : ( ! empty( $color_primary_font ) ? $color_primary_font : '#ffffff' ); ?>
	<?php $btn_class = 'btn-id-' . rand(); ?>
	<?php if ( ! empty( $link ) ) : ?>
		<div>
			<style type="text/css"><?php
				echo <<<CSS
			#{$btn_class} {background-color: {$bg};color: {$btn_font};}
			#{$btn_class}:hover {background-color: {$bg_hover};color: {$btn_font_hover};}
CSS;
				?></style>
		</div>
		<a id="<?php echo esc_attr( $btn_class ); ?>"
		   class="btn btn-primary btn-theme <?php echo 'big' == $size ? esc_attr( 'btn-theme-lg' ) : ( 'full' == $size ? esc_attr( 'btn-theme-full' ) : '' ); ?> <?php echo $atts['display_button_icon'] ? esc_attr( 'btn-with-icon' ) : ''; ?> <?php echo esc_attr( $center ); ?>"
		   href="<?php echo esc_url( $link['url'] ) ?>"
		   target="<?php echo esc_attr( isset( $link['target'] ) && ! empty( $link['target'] ) ? $link['target'] : '_self' ) ?>">
			<?php if ( $atts['display_button_icon'] ) : ?>
				<?php $icon = str_replace( 'vc-material vc-material-', '', $icon ); ?>
				<i class="btn-icon material-icons"><?php echo esc_html( $icon ); ?></i>
			<?php endif; ?>
			<?php echo esc_html( $link['title'] ); ?>
		</a>
	<?php endif; ?>
<?php endif; ?>
