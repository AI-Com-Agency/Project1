<?php
/**
 * Template Name: Listing Single Social
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $social_args
 */
global $post;
?>
<?php if ( $social_args['has_title'] ) : ?>
	<?php $title = $social_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<div class="listing-social d-flex mb-0">
	<div class="d-flex align-items-center mb-0">
		<div class="d-flex align-items-center">
			<div class="widget-label d-flex align-items-center">
				<i class="material-icons mf"><?php echo esc_attr( 'more_horiz' ); ?></i>
				<span><?php esc_html_e( 'Social:', 'lisner-core' ) ?></span>
			</div>
		</div>
	</div>
	<div class="listing-social-icons">
		<?php foreach ( $social_args['icons'] as $icon => $link ) : ?>
			<?php
			switch ( $icon ) :
				case 'google':
					$icon = 'google-plus-square';
					break;
				case 'twitter':
					$icon = 'twitter-square';
					break;
				case 'pinterest':
					$icon = 'pinterest-square';
					break;
				case 'youtube':
					$icon = 'youtube-square';
					break;
			endswitch;
			?>
			<?php if ( ! empty( $link ) ) : ?>
				<a title="<?php echo esc_attr( $icon ); ?>" class="<?php echo esc_attr( $icon ); ?>"
				   href="<?php echo esc_url( $link ) ?>" target="_blank"><i
							class="fab fa-<?php echo esc_attr( $icon ); ?> fa-fw"></i></a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
