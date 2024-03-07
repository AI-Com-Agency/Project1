<?php
/**
 * Modals / Register modal template
 *
 * @author pebas
 * @package templates/modals
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Modal -->
<div class="modal modal-register" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="modal-register"
     aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<span data-dismiss="modal" class="modal-close material-icons"><?php echo esc_attr( 'close' ); ?></span>
			<div class="modal-header">
				<h3 class="modal-title"><?php esc_html_e( 'Sign Up', 'lisner-core' ); ?></h3>
				<?php if ( lisner_helper::is_plugin_active( 'pebas-report-listings' ) && is_singular( 'job_listing' ) ) : ?>
					<button type="button" class="btn btn-label" data-dismiss="modal" data-toggle="modal"
					        data-target="#modal-report">
						<?php esc_html_e( 'Member already?', 'lisner-core' ) ?>
						<i class="btn-icon material-icons"><?php echo esc_html( 'subdirectory_arrow_right' ); ?></i>
					</button>
				<?php else: ?>
					<button type="button" class="btn btn-label" data-dismiss="modal" data-toggle="modal"
					        data-target="#modal-auth">
						<?php esc_html_e( 'Member already?', 'lisner-core' ) ?>
						<i class="btn-icon material-icons"><?php echo esc_html( 'subdirectory_arrow_right' ); ?></i>
					</button>
				<?php endif; ?>
			</div>
			<div class="modal-body">
				<?php include lisner_helper::get_template_part( 'form-register', 'forms/', $args ); ?>
			</div>
		</div>
	</div>
</div>