<?php
/**
 * Form / Lost password form template
 *
 * @author pebas
 * @package templates/forms
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Form / Lost password form -->
<form class="ajax-auth" method="post">
    <!-- Field / Username -->
    <div class="form-group">
        <label for="user_login"><?php esc_html_e( 'Username or Email Address', 'lisner-core' ); ?><span
                    class="required"><?php echo esc_html( '*' ); ?></span></label>
        <input type="text" name="user_login" id="user_login" class="form-control"
               value="<?php echo ( ! empty( $_POST['user_login'] ) ) ? esc_attr( $_POST['user_login'] ) : ''; ?>"
               size="20"/>
    </div>
    <!-- Field / Submit-->
    <div class="btn-submit-group">
		<?php wp_nonce_field( 'lost_password', 'lisner-lost-password-nonce' ); ?>
        <button type="submit" name="lost_password" id="lost_password" class="btn btn-primary btn-lg"
                value="<?php esc_attr_e( 'Reset Password', 'lisner-core' ); ?>"><?php esc_html_e( 'Reset Password', 'lisner-core' ); ?></button>
        <input type="hidden" name="lisner_reset_password" value="true" />
	    <?php $redirect = home_url( '/' ); ?>
        <input type="hidden" name="redirect_to"
               value="<?php echo esc_url( $redirect ); ?>" />
    </div>
</form>
