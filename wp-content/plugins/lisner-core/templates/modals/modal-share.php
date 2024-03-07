<?php
/**
 * Modals / Share modal template
 *
 * @author pebas
 * @package templates/modals
 * @version 1.0.0
 *
 * @param $args
 */

$option = get_option( 'pbs_option' );
?>
<?php if ( isset( $option['share-posts'] ) && ! empty( $option['share-posts'] ) ) : ?>
    <!-- Modal -->
    <div class="modal" id="modal-share" tabindex="-1" role="dialog" aria-labelledby="modal-share" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php esc_html_e( 'Share Listing', 'lisner-core' ); ?></h3>
                </div>
                <div class="modal-body">
					<?php include lisner_helper::get_template_part( 'share', 'partials' ); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>