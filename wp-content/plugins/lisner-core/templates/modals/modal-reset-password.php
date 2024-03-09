<?php
/**
 * Modals / Reset password modal template
 *
 * @author pebas
 * @package templates/modals
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Modal -->
<div class="modal show" id="modal-reset-password" tabindex="-1" role="dialog" aria-labelledby="modal-reset-password"
     aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<span data-dismiss="modal" class="modal-close material-icons"><?php echo esc_attr( 'close' ); ?></span>
			<div class="modal-header">
				<h3 class="modal-title"><?php esc_html_e( 'Reset Password', 'lisner-core' ); ?></h3>
			</div>
			<div class="modal-body">
				<?php include lisner_helper::get_template_part( 'form-reset-password', 'forms/', $args ); ?>
			</div>
		</div>
	</div>
</div>