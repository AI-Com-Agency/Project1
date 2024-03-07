<?php
/**
 * In job listing creation flow, this template shows above the job creation form.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/account-signin.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<h2 class="form-title"><?php esc_html_e( 'Submit your listing', 'lisner-core' ); ?></h2>
<?php if ( is_user_logged_in() ) : ?>

    <fieldset class="fieldset-logged_in">
        <div class="field account-sign-in">
			<?php
			$user = wp_get_current_user();
			printf( wp_kses_post( __( 'You are currently signed in as <strong class="theme-color">%s</strong>.',
				'lisner-core' ) ), esc_html( $user->user_login ) );
			?>

            <a class="link-auth"
               href="<?php echo esc_url( apply_filters( 'submit_job_form_logout_url',
				   wp_logout_url( get_permalink() ) ) ); ?>"><?php esc_html_e( 'Sign out', 'lisner-core' ); ?></a>
        </div>
    </fieldset>

<?php else :
	$account_required = job_manager_user_requires_account();
	$registration_enabled = job_manager_enable_registration();
	$registration_fields = wpjm_get_registration_fields();
	$use_standard_password_email = wpjm_use_standard_password_setup_email();
	?>
    <fieldset class="fieldset-login_required">
        <div class="field account-sign-in">
			<?php $sign_in = '<a href="javascript:" class="link-auth" data-toggle="modal"
               data-target="#modal-auth">' . esc_html__( 'sign in', 'lisner-core' ) . '</a>'; ?>

			<?php if ( $registration_enabled ) : ?>

				<?php if ( pbs_is_demo() ) : ?>
					<?php printf( __( 'Account creation is disabled for the demo purposes so you will have to %s first using provided demo account in order to check out listing submission process. Thank you!',
						'lisner-core' ), $sign_in ); ?>
				<?php else: ?>
					<?php printf( __( 'You are still not signed in: %s, or if you don\'t have an account you can %screate one below by entering your email address/username.',
						'lisner-core' ), $sign_in,
						$account_required ? '' : esc_html__( 'optionally', 'lisner-core' ) . ' ' ); ?>
					<?php if ( $use_standard_password_email ) : ?>
						<?php printf( esc_html__( 'Your account details will be confirmed via email.', 'lisner-core' ) ); ?>
					<?php endif; ?>
				<?php endif; ?>

			<?php elseif ( $account_required ) : ?>

				<?php echo wp_kses_post( apply_filters( 'submit_job_form_login_required_message',
					__( 'You must sign in to create a new listing.', 'lisner-core' ) ) ); ?>

			<?php endif; ?>
        </div>
    </fieldset>
	<?php
	if ( ! empty( $registration_fields ) ) :
		foreach ( $registration_fields as $key => $field ) :
			$icon = 'mail_outline';
			switch ( $key ) :
				case 'create_account_password':
				case 'create_account_password_verify':
					$icon = 'lock_outline';
					break;
				case 'create_account_username':
					$icon = 'person';
					break;
			endswitch;
			?>
			<?php if ( ! pbs_is_demo() ) : ?>
            <fieldset class="fieldset-<?php echo esc_attr( $key ); ?> fieldset-label-icon mb-0">
                <div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
                    <i class="material-icons mf"><?php echo esc_html( $icon ); ?></i>
                    <label
                            for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] . ':' ) . wp_kses_post( apply_filters( 'submit_job_form_required_label',
								$field['required'] ? '' : ' <small>' . __( '(optional)', 'lisner-core' ) . '</small>',
								$field ) ); ?></label>
					<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array(
						'key'   => $key,
						'field' => $field
					) ); ?>
                </div>
            </fieldset>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php do_action( 'job_manager_register_form' ); ?>
	<?php endif; ?>
<?php endif; ?>
