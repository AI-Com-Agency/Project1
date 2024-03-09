<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if ( ! empty( $breadcrumb ) ) : ?>
	<div class="single-listing-breadcrumbs">
		<nav aria-label="breadcrumb" class="single-listing-breadcrumb breadcrumb">
			<ol class="breadcrumb">
				<?php $count = 0; ?>
				<?php foreach ( $breadcrumb as $key => $crumb ) : ?>
					<?php if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) : ?>
						<li class="breadcrumb-item <?php echo 0 == $count ? esc_attr( 'home-cat' ) : ''; ?>">
							<?php if ( 0 == $count ) : ?>
								<i class="material-icons mf"><?php echo esc_html( 'home' ); ?></i>
							<?php endif; ?>
							<a href="<?php echo esc_url( $crumb[1] ) ?>"><?php echo esc_html( $crumb[0] ) ?></a>
						</li>
					<?php else: ?>
						<li class="breadcrumb-item"><span><?php echo esc_html( $crumb[0] ); ?></span></li>
					<?php endif; ?>
					<?php $count ++; ?>
				<?php endforeach; ?>
			</ol>
		</nav>
	</div>
<?php endif; ?>
