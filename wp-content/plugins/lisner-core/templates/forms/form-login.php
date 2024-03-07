<?php
/**
 * Form / Login form template
 *
 * @author pebas
 * @package templates/forms
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Form / Login form -->
<form class="ajax-auth" method="post">
	<?php if ( pbs_is_demo() ) : ?>
		<div class="demo-description">
			<span><?php esc_html_e( 'For demo purposes use below credentials:', 'lisner-core' ) ?></span>
			<div class="credentials">
				<span><?php _e( 'username: <strong>demo</strong>', 'lisner-core' ); ?></span>
				<span><?php _e( 'password: <strong>demo</strong>', 'lisner-core' ); ?></span>
			</div>
		</div>
		<hr>
	<?php endif; ?>
	<!-- Field / Username -->
	<div class="form-group" data-error-username>
		<label for="username"><?php esc_html_e( 'Username or Email Address', 'lisner-core' ); ?><span
					class="required"><?php echo esc_html( '*' ); ?></span></label>
		<input type="text" name="username" id="username" class="form-control"
		       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>"
		       size="20" />
	</div>
	<!-- Field / Password -->
	<div class="form-group" data-error-password>
		<label for="password"><?php esc_html_e( 'Password', 'lisner-core' ); ?><span
					class="required"><?php echo esc_html( '*' ); ?></span></label>
		<input type="password" name="password" id="password" class="form-control" value="" size="20" />
	</div>
	<div class="form-actions">
		<!-- Field / Remember Me -->
		<div class="custom-control custom-checkbox">
			<input id="rememberme" name="rememberme" type="checkbox" class="custom-control-input">
			<label class="custom-control-label"
			       for="rememberme"><?php esc_html_e( 'Remember Me', 'lisner-core' ); ?></label>
		</div>
		<a href="javascript:" class="link link-error" data-dismiss="modal" data-toggle="modal"
		   data-target="#modal-lost-password"><?php esc_html_e( 'Lost your password?', 'lisner-core' ); ?></a>
	</div>
	<!-- Field / Submit-->
	<div class="btn-submit-group">
		<?php wp_nonce_field( 'login', 'lisner-login-nonce' ); ?>
		<button type="submit" name="login" id="login" class="btn btn-primary btn-lg"
		        value="<?php esc_attr_e( 'Sign In', 'lisner-core' ); ?>"><?php esc_html_e( 'Sign In', 'lisner-core' ); ?></button>
		<?php $redirect = isset( $args['redirect'] ) && is_numeric( $args['redirect'] ) ? get_permalink( $args['redirect'] ) : $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
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
