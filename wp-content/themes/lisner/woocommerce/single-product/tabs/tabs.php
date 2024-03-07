<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

    <div class="woocommerce-tabs wc-tabs-wrapper">
        <div class="row">
            <div class="col-4">
                <div class="nav flex-column nav-pills" id="wc-pills-tab" aria-orientation="vertical" role="tablist">
					<?php $nav_count = 0; ?>
					<?php foreach ( $tabs as $key => $tab ) : ?>
                        <a class="nav-link <?php echo 0 == $nav_count ? esc_attr( 'active' ) : ''; ?>"
                           id="tab-title-<?php echo esc_attr( $key ); ?>"
                           role="tab"
                           aria-controls="tab-<?php echo esc_attr( $key ); ?>"
                           href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
						<?php $nav_count ++; ?>
					<?php endforeach; ?>
                </div>
            </div>
            <div class="col-8">
                <div class="tab-content" id="wc-pills-tab-content">
					<?php $count = 0; ?>
					<?php foreach ( $tabs as $key => $tab ) : ?>
                        <div class="tab-pane fade <?php echo 0 == $count ? esc_attr( 'show active' ) : ''; ?>"
                             id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
                             aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
							<?php if ( isset( $tab['callback'] ) ) {
								call_user_func( $tab['callback'], $key, $tab );
							} ?>
                        </div>
						<?php $count ++; ?>
					<?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
