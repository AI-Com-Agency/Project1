<?php
/**
 * Template Name: Listing Single Phone
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $phone_args
 */
global $post;
$option = get_option( 'pbs_option' );
?>
<?php if ( $phone_args['has_title'] ) : ?>
	<?php $title = $phone_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<div class="phone d-flex mb-0">
    <div class="d-flex align-items-center mb-0">
        <div class="d-flex align-items-center">
            <div class="widget-label d-flex align-items-center">
                <i class="material-icons mf"><?php echo esc_attr( 'phone' ); ?></i>
                <span><?php esc_html_e( 'Phone:', 'lisner-core' ) ?></span>
            </div>
        </div>
    </div>
    <div class="phone-number ml-auto">
		<?php $phone = lisner_listings::hide_phone_number( $post->_listing_phone ); ?>
		<?php if ( ! isset( $option['listings-appearance-hide-phone'] ) || 'hide' == $option['listings-appearance-hide-phone'] ) : ?>
            <a href="tel:<?php echo esc_attr( $post->_listing_phone ); ?>"
               data-number="<?php echo esc_attr( $phone['hidden'] ); ?>"
               class="phone-link"><?php echo esc_html( $phone['number'] ); ?></a>
		<?php else: ?>
            <a href="tel:<?php echo esc_attr( $post->_listing_phone ); ?>"
               class="phone-nolink"><?php echo esc_html( $phone['number'] ); ?></a>
		<?php endif; ?>
		<?php $whatsapp = isset( $option['listings-appearance-whatsapp'] ) ? $option['listings-appearance-whatsapp'] : ''; ?>
		<?php $viber = isset( $option['listings-appearance-viber'] ) ? $option['listings-appearance-viber'] : ''; ?>
		<?php if ( ! empty( $whatsapp ) || ! empty( $viber ) ) : ?>
            <span class="phone-or"><?php esc_html_e( 'or', 'lisner-core' ); ?></span>
			<?php if ( ! empty( $whatsapp ) ) : ?>
                <a class="whatsapp"
                   href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $post->_listing_phone ); ?>"><i
                            class="fab fa-whatsapp fa-fw"></i></a>
			<?php endif; ?>
			<?php if ( ! empty( $viber ) ) : ?>
                <a class="viber" href="viber://chat?number=<?php echo esc_attr( $post->_listing_phone ); ?>"><i
                            class="fab fa-viber fa-fw"></i></a>
			<?php endif; ?>
		<?php endif; ?>
    </div>
</div>
