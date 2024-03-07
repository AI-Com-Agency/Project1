<?php
/**
 * Home / Hero section heading template
 *
 * @author pebas
 * @package field/field-heading
 * @version 1.0.0
 *
 * @param $args
 */
?>
<?php if ( ! empty( $args['heading'] ) ) : ?>
	<div class="hero-title <?php echo ! in_array( $args['hero_template'], array(
		2,
		6,
		7,
		8,
		9,
		10,
	) ) ? esc_attr( 'text-center' ) : ''; ?>">
		<?php $heading = str_replace( '[city-locate]', '<span class="city-name-geo">' . esc_html__( 'your city', 'lisner-core' ) . '</span>', $args['heading'] ); ?>
		<?php echo wp_kses_post( $heading ); ?>
	</div>
<?php endif; ?>
