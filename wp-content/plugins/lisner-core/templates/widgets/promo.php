<?php
/**
 * Widget Promo Template Default
 *
 * @author pebas
 * @version 1.0.0
 * @package templates/widgets/
 *
 * @var $instance
 * @var $query
 */
?>

<?php $order = isset( $instance['order'] ) ? $instance['order'] : ''; ?>
<?php if ( 'views' == $order ) : ?>
	<?php $query['order'] = $order; ?>
<?php endif; ?>
<?php require lisner_helper::get_template_part( 'promo', 'widgets/loop', $query ); ?>
