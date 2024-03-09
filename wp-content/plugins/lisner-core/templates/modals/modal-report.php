<?php
/**
 * Modals / Report modal template
 *
 * @author pebas
 * @package templates/modals
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Modal -->
<div class="modal" id="modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-report" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<span data-dismiss="modal" class="modal-close material-icons"><?php echo esc_attr( 'close' ); ?></span>
			<div class="modal-header">
				<?php if ( is_user_logged_in() ) : ?>
					<h3 class="modal-title"><?php esc_html_e( 'Report Listing', 'lisner-core' ); ?></h3>
					<button type="button" class="btn btn-label" data-dismiss="modal">
						<i class="btn-icon btn-icon-before material-icons mf"><?php echo esc_html( 'subdirectory_arrow_left' ); ?></i>
						<?php esc_html_e( 'Cancel', 'lisner-core' ) ?>
					</button>
				<?php else: ?>
					<h3 class="modal-title"
					    id="exampleModalCenterTitle"><?php esc_html_e( 'Sign In', 'lisner-core' ); ?></h3>
					<button type="button" class="btn btn-label" data-dismiss="modal" data-toggle="modal"
					        data-target="#modal-register">
						<?php esc_html_e( 'Not a member', 'lisner-core' ) ?>
						<i class="btn-icon material-icons"><?php echo esc_html( 'subdirectory_arrow_right' ); ?></i>
					</button>
				<?php endif; ?>
			</div>
			<div class="modal-body">
				<?php if ( is_user_logged_in() ) : ?>
					<!-- Form / Login form -->
					<form class="ajax-report" method="post">
						<!-- Field / Username -->
						<div class="form-group">
							<label for="report_reason"><?php esc_html_e( 'Report Reason', 'lisner-core' ); ?>
								<span
										class="required"><?php echo esc_html( '*' ); ?></span></label>
							<textarea name="report_reason" id="report_reason" cols="30" rows="10"
							          class="form-control report-control"
							          placeholder="<?php esc_html_e( 'Explain report reasons as clear as possible', 'lisner-core' ); ?>"
							          required></textarea>
						</div>
						<!-- Field / Submit-->
						<div class="btn-submit-group">
							<?php wp_nonce_field( 'submit_report', 'submit-report-nonce' ); ?>
							<button type="submit" name="submit_report" id="submit_report" class="btn btn-primary btn-lg"
							        value="<?php esc_attr_e( 'Report Listing', 'lisner-core' ); ?>"><?php esc_html_e( 'Report Listing', 'lisner-core' ); ?></button>
							<input type="hidden" name="report_user_id"
							       value="<?php echo esc_attr( get_current_user_id() ); ?>">
							<input type="hidden" name="report_listing_id"
							       value="<?php echo esc_attr( get_the_ID() ); ?>">
							<input type="hidden" name="report_user_ip"
							       value="<?php echo esc_attr( lisner_helper()->get_client_ip() ); ?>">
						</div>
					</form>
				<?php elseif ( ! is_user_logged_in() && is_singular( 'job_listing' ) ): ?>
					<?php $args['redirect'] = get_the_ID(); ?>
					<?php include lisner_helper::get_template_part( 'form-login', 'forms/', $args ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>