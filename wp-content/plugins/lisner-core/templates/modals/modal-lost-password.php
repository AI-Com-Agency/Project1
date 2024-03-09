<?php
/**
 * Modals / Lost password modal template
 *
 * @author pebas
 * @package templates/modals
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Modal -->
<div class="modal" id="modal-lost-password" tabindex="-1" role="dialog" aria-labelledby="modal-lost-password"
     aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<span data-dismiss="modal" class="modal-close material-icons"><?php echo esc_attr( 'close' ); ?></span>
			<div class="modal-header">
				<h3 class="modal-title"><?php esc_html_e( 'Lost Password?', 'lisner-core' ); ?></h3>
				<button type="button" class="btn btn-label" data-dismiss="modal" data-toggle="modal"
				        data-target="#modal-auth">
					<?php esc_html_e( 'Cancel', 'lisner-core' ) ?>
					<i class="btn-icon material-icons"><?php echo esc_html( 'subdirectory_arrow_right' ); ?></i>
				</button>
			</div>
			<div class="modal-body">
				<?php include lisner_helper::get_template_part( 'form-lost-password', 'forms/', $args ); ?>
			</div>
		</div>
	</div>
</div>