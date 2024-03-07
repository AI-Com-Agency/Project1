<?php
/**
 * Template Name: Listing Single / Price Range
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $pr_args
 */
global $post;
?>
<?php if ( $pr_args['has_title'] ) : ?>
	<?php $title = $pr_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<?php $price_value = $post->_listing_pricing_range; ?>
<?php
$price_value_i18n = $price_value;
switch ( $price_value ) {
	case 'cheap':
		$price_value_i18n = esc_html__( 'Cheap', 'lisner-core' );
		break;
	case 'moderate':
		$price_value_i18n = esc_html__( 'Moderate', 'lisner-core' );
		break;
	case 'expensive':
		$price_value_i18n = esc_html__( 'Expensive', 'lisner-core' );
		break;
	case 'ultra':
		$price_value_i18n = esc_html__( 'Ultra', 'lisner-core' );
		break;
}
?>
<?php if ( '2' == lisner_get_var( $args['page_template'], 1 ) ) : ?>
	<div class="widget-price-range d-flex">
		<div class="widget-price-range-data d-flex align-items-center">
			<?php $pricing_currency = isset( $post->_listing_pricing_currency ) ? $post->_listing_pricing_currency : get_woocommerce_currency_symbol(); ?>
			<?php $price_range = lisner_helper::pricing_range_render( get_the_ID() ); ?>
			<?php echo wp_kses( $price_range, array(
				'span' => array( 'class' => array() ),
				'i'    => array( 'class' => array() )
			) ); ?>
			<span class="price-value"><?php echo esc_html( ucfirst( $price_range ) ); ?></span>
		</div>
		<?php if ( $post->_listing_pricing_from && $post->_listing_pricing_to ) : ?>
			<div class="widget-price-range-price ml-auto">
				<span class="price-lowest"><?php echo esc_html( $pricing_currency ); ?><?php echo esc_html( $post->_listing_pricing_from ); ?></span>
				<span class="price-divider"><?php echo esc_html( '-' ); ?></span>
				<span class="price-highest"><?php echo esc_html( $pricing_currency ) ?><?php echo esc_html( $post->_listing_pricing_to ); ?></span>
			</div>
		<?php endif; ?>
	</div>
<?php else: ?>
	<div class="widget-price-range d-flex">
		<div class="widget-price-range-data d-flex align-items-center">
			<div class="widget-label d-flex align-items-center">
				<i class="material-icons mf"><?php echo esc_attr( 'attach_money' ); ?></i>
				<span><?php esc_html_e( 'Price:', 'lisner-core' ) ?></span>
			</div>
		</div>
		<div class="widget-price-range-price ml-auto d-flex align-items-center">
			<div class="widget-price-range-wrapper mr-2 d-flex">
				<?php $pricing_currency = isset( $post->_listing_pricing_currency ) ? $post->_listing_pricing_currency : get_woocommerce_currency_symbol(); ?>
				<?php $price_range = lisner_helper::pricing_range_render( get_the_ID() ); ?>
				<?php echo wp_kses( $price_range, array(
					'span' => array( 'class' => array() ),
					'i'    => array( 'class' => array() )
				) ); ?>
				<span class="price-value"><?php echo esc_html( ucfirst( $price_value_i18n) ); ?></span>
			</div>
			<?php if ( $post->_listing_pricing_from && $post->_listing_pricing_to ) : ?>
				<span class="price-lowest"><?php echo esc_html( $pricing_currency ); ?><?php echo esc_html( $post->_listing_pricing_from ); ?></span>
				<span class="price-divider"><?php echo esc_html( '-' ); ?></span>
				<span class="price-highest"><?php echo esc_html( $pricing_currency ) ?><?php echo esc_html( $post->_listing_pricing_to ); ?></span>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
