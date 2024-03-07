<?php
/**
 * Modals / Notification modal template
 *
 * @author pebas
 * @package templates/modals
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Modal -->
<div class="modal" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-"
     aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<span data-dismiss="modal" class="modal-close material-icons"><?php echo esc_attr( 'close' ); ?></span>
			<div class="modal-header">
				<h3 class="modal-title"><?php esc_html_e( 'Reset Password Notice', 'lisner-core' ); ?></h3>
			</div>
			<div class="modal-body">
				<?php if ( $args['notice'] ) : ?>
					<h5><?php echo esc_html( $args['notice'] ); ?></h5>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>