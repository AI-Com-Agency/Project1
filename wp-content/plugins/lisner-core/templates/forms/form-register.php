<?php
/**
 * Form / Register form template
 *
 * @author pebas
 * @package templates/forms
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Form / Login form -->
<form class="ajax-auth" method="post" novalidate="novalidate">
	<?php if ( 'no' == lisner_get_option( 'auth-generate-username' ) ) : ?>
		<!-- Field / Username -->
		<div class="form-group">
			<label for="reg_username"><?php esc_html_e( 'Username', 'lisner-core' ); ?><span
						class="required"><?php echo esc_html( '*' ); ?></span></label>
			<input type="text" name="username" id="reg_username" class="form-control"
			       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>"
			       size="20" required />
		</div>
	<?php endif; ?>
	<!-- Field / Email -->
	<div class="form-group">
		<label for="reg_email"><?php esc_html_e( 'Your email address', 'lisner-core' ); ?><span
					class="required"><?php echo esc_html( '*' ); ?></span></label>
		<input type="text" name="email" id="reg_email" class="form-control"
		       value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( $_POST['email'] ) : ''; ?>"
		       size="20" required />
	</div>
	<?php if ( 'no' == lisner_get_option( 'auth-generate-password' ) ) : ?>
		<!-- Field / Password -->
		<div class="form-group">
			<label for="reg_password"><?php esc_html_e( 'Password', 'lisner-core' ); ?><span
						class="required"><?php echo esc_html( '*' ); ?></span></label>
			<input type="password" name="password" id="reg_password" class="form-control" value="" size="20" required />
		</div>
	<?php endif; ?>
	<?php $terms_page = lisner_get_option( 'page_terms' ); ?>
	<?php if ( ! empty( $terms_page ) ) : ?>
		<div class="form-actions">
			<!-- Field / Remember Me -->
			<div class="custom-control custom-checkbox">
				<input id="terms" name="terms" type="checkbox" class="custom-control-input">
				<label class="custom-control-label"
				       for="terms"><?php esc_html_e( 'Terms & Conditions', 'lisner-core' ); ?></label>
			</div>
			<a href="<?php echo esc_url( get_permalink( $terms_page ) ); ?>"
			   class="link link-error"><?php echo esc_html( get_the_title( $terms_page ) ); ?></a>
		</div>
	<?php endif; ?>
	<!-- Field / Submit-->
	<div class="btn-submit-group">
		<?php wp_nonce_field( 'register', 'lisner-register-nonce' ); ?>
		<button type="submit" name="register" id="register" class="btn btn-primary btn-lg"
		        value="<?php esc_attr_e( 'Sign Up', 'lisner-core' ); ?>"><?php esc_html_e( 'Sign Up', 'lisner-core' ); ?></button>
		<?php $redirect = isset( $args['redirect'] ) ? get_permalink( $args['redirect'] ) : $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
		<input type="hidden" name="redirect_to"
		       value="<?php echo esc_url( $redirect ); ?>" />
	</div>
	<?php if ( lisner_helper::is_plugin_active( 'wordpress-social-login', 'wp-social-login' ) ) : ?>
		<!-- Form Login / Social Login -->
		<div class="lisner-social-login">
			<?php echo wsl_render_auth_widget(); ?>
		</div>
	<?php endif; ?>
</form>
