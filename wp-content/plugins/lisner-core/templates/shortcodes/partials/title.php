<?php
/**
 * Shortcodes Partials / Title
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/partials/
 */
?>
<div class="lisner-taxonomy-title title-block">
	<?php if ( ! empty( $atts['title'] ) ) : ?>
		<?php if ( isset( $atts['title_custom'] ) ) : ?>
			<?php $title = str_replace( array( '-_', '_-' ), array( '<strong>', '</strong>' ), $atts['title'] ); ?>
			<h2 class="theme-title"><?php echo wp_kses_post( $title ); ?></h2>
		<?php else: ?>
			<?php $title = strpos( $atts['title'], ' ' ) ? explode( ' ', $atts['title'] ) : $atts['title']; ?>
			<h2 class="theme-title">
				<?php if ( is_array( $title ) ) : ?>
					<strong><?php echo esc_html( $title[0] ); ?></strong><?php echo esc_html( str_replace( $title[0], '', $atts['title'] ) ); ?>
				<?php else: ?>
					<strong><?php echo esc_html( $title ); ?></strong>
				<?php endif; ?>
			</h2>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( ! empty( $atts['subtitle'] ) ) : ?>
		<p class="theme-subtitle">
			<?php echo wp_kses_post( $atts['subtitle'] ); ?>
		</p>
	<?php endif; ?>
</div>
