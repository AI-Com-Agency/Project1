<?php

/**
 * Class lisner_auth
 */

class lisner_auth {

	protected static $_instance = null;

	/**
	 * @return null|lisner_auth
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_auth constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'redirect_reset_password_link' ) );

		// auth ajax calls when user is not logged in
		add_action( 'lisner_ajax_nopriv_login', array( $this, 'process_login' ) );
		add_action( 'lisner_ajax_nopriv_register', array( $this, 'process_registration' ) );
		add_action( 'lisner_ajax_nopriv_lost_password', array( $this, 'process_lost_password' ) );
		add_action( 'lisner_ajax_nopriv_reset_password', array( $this, 'process_reset_password' ) );
		// can happen when user is logged in too
		add_action( 'lisner_ajax_reset_password', array( $this, 'process_reset_password' ) );

		//add_action( 'lisner_created_user', array( $this, 'new_user_email' ) );
		add_action( 'wp_footer', array( $this, 'load_reset_password_template' ) );
	}

	/**
	 * Process the login form
	 */
	public function process_login() {

		$result = array();
		if ( ! empty( $_POST['action'] ) && wp_verify_nonce( $_REQUEST['lisner-login-nonce'], 'login' ) ) {

			try {
				$creds = array(
					'user_login'    => trim( $_POST['username'] ),
					'user_password' => $_POST['password'],
					'remember'      => isset( $_POST['rememberme'] ),
				);

				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'lisner_process_login_errors', $validation_error, $_POST['username'], $_POST['password'] );

				if ( $validation_error->get_error_code() ) {
					$message                          = $validation_error->get_error_message();
					$result['error']['data-error-wp'] = esc_html( $message );
					//throw new Exception( '<strong>' . __( 'Error:', 'lisner-core' ) . '</strong> ' . $validation_error->get_error_message() );
				}

				if ( empty( $creds['user_login'] ) ) {
					$result['error']['data-error-username'] = esc_html__( 'Username is required', 'lisner-core' );
					//throw new Exception( '<strong>' . __( 'Error:', 'lisner-core' ) . '</strong> ' . __( 'Username is required.', 'lisner-core' ) );
				}

				// On multisite, ensure user exists on current site, if not add them before allowing login.
				if ( is_multisite() ) {
					$user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );

					if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
						add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
					}
				}

				// Perform the login
				$user = wp_signon( apply_filters( 'pebas_login_credentials', $creds ), is_ssl() );

				$message = '';
				if ( is_wp_error( $user ) ) {
					$code = $user->get_error_code();
					if ( 'invalid_username' == $code ) {
						$message                                = sprintf( __( '%s username is invalid', 'lisner-core' ), '<strong>' . esc_html( $creds['user_login'] ) . '</strong>' );
						$result['error']['data-error-username'] = $message;
					} else if ( 'invalid_password' ) {
						$message                                = esc_html__( 'Password is invalid', 'lisner-core' );
						$result['error']['data-error-password'] = $message;
					}
					//throw new Exception( $message );
				} else {
					if ( ! empty( $_POST['redirect'] ) ) {
						$result['redirect'] = wp_sanitize_redirect( $_POST['redirect'] );
					} elseif ( $this->get_raw_referer() ) {
						$result['redirect'] = $this->get_raw_referer();
					} else {
						$result['redirect'] = get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) );
					}
					$message                           = esc_html__( 'You have been successfully logged in!', 'lisner-core' );
					$result['success']['data-success'] = $message;
				}
				wp_send_json( $result );
			} catch ( Exception $e ) {
				$message                          = $e->getMessage();
				$message                          = str_replace( '<strong>ERROR</strong>', '', $message );
				$result['error']['data-error-wp'] = $message;
				do_action( 'pebas_login_failed' );
				wp_send_json( $result );
			}
		}
	}

	/**
	 * Process the registration form.
	 */
	public function process_registration() {
		$option = get_option( 'pbs_option' );
		$result = array();
		if ( ! empty( $_POST['action'] ) && wp_verify_nonce( $_REQUEST['lisner-register-nonce'], 'register' ) ) {

			$username   = 'no' === lisner_get_option( 'auth-generate-username' ) ? $_POST['username'] : '';
			$password   = 'no' === lisner_get_option( 'auth-generate-password' ) ? $_POST['password'] : '';
			$email      = $_POST['email'];
			$page_terms = lisner_get_option( 'page_terms' );
			$terms      = true;
			if ( ! empty( $page_terms ) ) {
				$terms = isset( $_POST['terms'] ) ? true : false;
			}

			try {
				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'lisner_process_registration_errors', $validation_error, $username, $password, $email, $terms );

				if ( $validation_error->get_error_code() ) {
					//throw new Exception( $validation_error->get_error_message() );
					$message                          = $validation_error->get_error_message();
					$result['error']['data-error-wp'] = esc_html( $message );
					$result['error']['data-error-wp'] = esc_html( $message );
				}

				$new_user = self::create_new_user( sanitize_email( $email ), $username, $password, $terms );

				if ( is_wp_error( $new_user ) ) {
					$message                          = $new_user->get_error_message();
					$result['error']['data-error-wp'] = $message;
					//throw new Exception( $new_user->get_error_message() );
				} else {
					if ( apply_filters( 'lisner_registration_auth_new_customer', true, $new_user ) ) {
						$this->set_user_auth_cookie( $new_user );
						$message                           = esc_html__( 'You have been successfully registered', 'lisner-core' );
						$result['success']['data-success'] = $message;
					}

				}

				if ( ! empty( $_POST['redirect_to'] ) ) {
					$result['redirect'] = wp_sanitize_redirect( $_POST['redirect_to'] );
				} elseif ( $this->get_raw_referer() ) {
					$result['redirect'] = $this->get_raw_referer();
				} else {
					$result['redirect'] = get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) );
				}

				wp_send_json( $result );

			} catch ( Exception $e ) {
				$message                          = $e->getMessage();
				$message                          = str_replace( '<strong>ERROR</strong>', '', $message );
				$result['error']['data-error-wp'] = $message;
				do_action( 'pebas_register_failed' );
				wp_send_json( $result );
			}
		}
	}

	public function create_new_user( $email, $username = '', $password = '', $terms = '' ) {
		$option = get_option( 'pbs_option' );

		// Handle username creation.
		if ( 'no' === $option['auth-generate-username'] || ! empty( $username ) ) {
			$username = sanitize_user( $username );

			if ( empty( $username ) || ! validate_username( $username ) ) {
				return new WP_Error( 'registration-error-invalid-username', __( 'Please enter a valid account username.', 'lisner-core' ) );
			}

			if ( username_exists( $username ) ) {
				return new WP_Error( 'registration-error-username-exists', __( 'An account is already registered with that username. Please choose another.', 'lisner-core' ) );
			}
		} else {
			$username = sanitize_user( current( explode( '@', $email ) ), true );

			// Ensure username is unique.
			$append     = 1;
			$o_username = $username;

			while ( username_exists( $username ) ) {
				$username = $o_username . $append;
				$append ++;
			}
		}

		// Check the email address.
		if ( empty( $email ) || ! is_email( $email ) ) {
			return new WP_Error( 'registration-error-invalid-email', __( 'Please provide a valid email address.', 'lisner-core' ) );
		}

		if ( email_exists( $email ) ) {
			return new WP_Error( 'registration-error-email-exists', apply_filters( 'lisner-core_registration_error_email_exists', __( 'An account is already registered with your email address. Please log in.', 'lisner-core' ), $email ) );
		}

		// Handle password creation.
		if ( 'yes' === $option['auth-generate-password'] && empty( $password ) ) {
			$password           = wp_generate_password();
			$password_generated = true;
		} elseif ( empty( $password ) ) {
			return new WP_Error( 'registration-error-missing-password', __( 'Please enter an account password.', 'lisner-core' ) );
		} else {
			$password_generated = false;
		}

		if ( ! $terms ) {
			return new WP_Error( 'registration-error-terms', esc_html__( 'You have not agreed to terms and conditions', 'lisner-core' ) );
		}

		// Use WP_Error to handle registration errors.
		$errors = new WP_Error();

		do_action( 'lisner_register_post', $username, $email, $errors );

		if ( $errors->get_error_code() ) {
			$result['error']['data-error-wp'] = $errors;
		}

		$new_user_data = apply_filters(
			'lisner_new_user_data', array(
				'user_login' => $username,
				'user_pass'  => $password,
				'user_email' => $email,
				'role'       => get_option( 'job_manager_registration_role' ),
			)
		);

		$user_id = wp_insert_user( $new_user_data );

		if ( is_wp_error( $user_id ) ) {
			return new WP_Error( 'registration-error', '<strong>' . __( 'Error:', 'lisner-core' ) . '</strong> ' . __( 'Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'lisner-core' ) );
		}

		//todo attach email here
		do_action( 'lisner_created_user', $user_id, $new_user_data, $password_generated );

		if ( ! is_wp_error( $user_id ) ) {
			//todo implement lisner-core email system
			$to      = $new_user_data['user_email'];
			$subject = sprintf( esc_html__( 'Welcome to %s', 'lisner-core' ), get_option( 'blogname' ) );
			$body    = sprintf( esc_html__( 'Thanks for creating am account on %1$s. Your username is: %2$s', 'lisner-core' ), get_option( 'blogname' ), $new_user_data['user_login'] );
			$body    .= sprintf( esc_html__( ' Your password is: %s', 'lisner-core' ), $new_user_data['user_pass'] );
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( $to, $subject, $body, $headers );
		}

		return $user_id;
	}

	public function set_user_auth_cookie( $user_id ) {
		global $current_user;

		$current_user = get_user_by( 'id', $user_id ); // WPCS: override ok.

		wp_set_auth_cookie( $user_id, true );
	}

	public function get_raw_referer() {
		if ( function_exists( 'wp_get_raw_referer' ) ) {
			return wp_get_raw_referer();
		}

		if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) { // WPCS: input var ok, CSRF ok.
			return wp_unslash( $_REQUEST['_wp_http_referer'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
		} elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) ) { // WPCS: input var ok, CSRF ok.
			return wp_unslash( $_SERVER['HTTP_REFERER'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
		}

		return false;
	}

	/**
	 * Handle lost password form.
	 */
	public function process_lost_password() {
		$result = array();
		if ( ! empty( $_POST['action'] ) && wp_verify_nonce( $_REQUEST['lisner-lost-password-nonce'], 'lost_password' ) ) {

			$success = self::retrieve_password();

			if ( is_wp_error( $success ) ) {
				$result = $success;
				// If successful, redirect to my account with query arg set.
			} else if ( $success ) {
				$result['redirect']                = isset( $_POST['redirect'] ) ? $_POST['redirect'] : home_url( '/' );
				$result['success']['data-success'] = esc_html__( 'Password has been emailed to you', 'lisner-core' );
			}
			wp_send_json( $result );
		} else {
			exit;
		}
	}

	public function retrieve_password() {
		$login = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ) ) : ''; // WPCS: input var ok, CSRF ok.

		if ( empty( $login ) ) {
			return new WP_Error( 'data-error', __( 'Enter a username or email address.', 'lisner-core' ), 'error' );
		} else {
			// Check on username first, as users can use emails as usernames.
			$user_data = get_user_by( 'login', $login );
		}

		// If no user found, check if it login is email and lookup user based on email.
		if ( ! $user_data && is_email( $login ) && apply_filters( 'lisner_get_username_from_email', true ) ) {
			$user_data = get_user_by( 'email', $login );
		}

		$errors = new WP_Error();

		if ( $errors->get_error_code() ) {
			return new WP_Error( 'wp-error', $errors->get_error_message(), 'error' );
		}

		if ( ! $user_data ) {
			return new WP_Error( 'wp-error', esc_html__( 'Invalid Username or Email', 'lisner-core' ), 'error' );
		}

		if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
			return new WP_Error( 'wp-error', esc_html__( 'Invalid Username or Email', 'lisner-core' ), 'error' );

		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;

		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( ! $allow ) {
			return new WP_Error( 'wp-error', esc_html__( 'Password reset is not allowed for this user', 'lisner-core' ) );
		} elseif ( is_wp_error( $allow ) ) {
			return new WP_Error( 'wp-error', esc_html( $errors->get_error_message() ), 'error' );
		}

		// Get password reset key (function introduced in WordPress 4.4).
		$key = get_password_reset_key( $user_data );

		// Send email notification.
		//WC()->mailer(); // Load email classes.
		//do_action( 'lisner_reset_password_notification', $user_login, $key );
		//todo implement real email system
		$permalink = add_query_arg( array(
			'key' => $key,
			'id'  => $user_data->ID
		), home_url( '/' ) );
		$link      = '<a class="link" href="' . esc_url( $permalink ) . '">' . __( 'Click here to reset your password', 'lisner-core' ) . '</a>';
		$to        = $user_data->user_email;
		$subject   = sprintf( esc_html__( 'You requested password reset for site: %s', 'lisner-core' ), get_option( 'blogname' ) );
		$body      = sprintf( esc_html__( 'Your new password is on link %s', 'lisner-core' ), $link );
		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $body, $headers );

		return true;
	}

	public function process_reset_password() {
		$posted_fields = array( 'lisner_reset_password', 'password_1', 'password_2', 'reset_key', 'reset_login' );
		$result        = array();
		$success       = true;

		foreach ( $posted_fields as $field ) {
			if ( ! isset( $_POST[ $field ] ) ) {
				return;
			}
			$posted_fields[ $field ] = $_POST[ $field ];
		}

		$nonce_value = lisner_get_var( $_REQUEST['lisner-reset-password-nonce'], lisner_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

		if ( ! wp_verify_nonce( $nonce_value, 'reset_password' ) ) {
			return;
		}

		$user = self::check_password_reset_key( $posted_fields['reset_key'], $posted_fields['reset_login'] );

		if ( $user instanceof WP_User ) {
			if ( empty( $posted_fields['password_1'] ) ) {
				$result['error']['data-error-password-1'] = esc_html__( 'Please enter your password', 'lisner-core' );

				$success = false;
			}

			if ( $posted_fields['password_1'] !== $posted_fields['password_2'] ) {
				$result['error']['data-error-password-1'] = esc_html__( 'Passwords do not match', 'lisner-core' );
				$result['error']['data-error-password-2'] = esc_html__( 'Passwords do not match', 'lisner-core' );

				$success = false;
			}

			if ( $success ) {
				self::reset_password( $user, $posted_fields['password_1'] );
				do_action( 'lisner_user_reset_password', $user );
				$result['redirect'] = isset( $_POST['redirect'] ) ? $_POST['redirect'] : home_url( '/' );
				if ( ! is_user_logged_in() ) {
					$result['success']['data-success'] = esc_html__( 'Password has been reset successfully. Please proceed to login.', 'lisner-core' );
				} else {
					$result['success']['data-success'] = esc_html__( 'Password has been reset successfully', 'lisner-core' );
				}
			}
			wp_send_json( $result );
		}
	}

	public function reset_password( $user, $new_pass ) {
		do_action( 'password_reset', $user, $new_pass );

		wp_set_password( $new_pass, $user->ID );
		self::set_reset_password_cookie();

		wp_password_change_notification( $user );
	}


	public function load_reset_password_template() {
		$args = array();
		if ( ! empty( $_GET['show-reset-form'] ) ) { // WPCS: input var ok, CSRF ok.
			if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {  // @codingStandardsIgnoreLine
				list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) ); // @codingStandardsIgnoreLine
				$user = self::check_password_reset_key( $rp_key, $rp_login );

				// Reset key / login is correct, display reset password form with hidden key / login values.
				if ( is_object( $user ) ) {
					$args = array(
						'key'   => $rp_key,
						'login' => $rp_login,
					);

					include lisner_helper::get_template_part( 'modal-reset-password', 'modals/', $args );
				} else {
					$args['notice'] = $user;
					include lisner_helper::get_template_part( 'modal-notification', 'modals/', $args );
				}

			}
		}
	}

	/**
	 * Remove key and user ID (or user login, as a fallback) from query string, set cookie, and redirect to account page to show the form.
	 */
	public function redirect_reset_password_link() {
		if ( isset( $_GET['key'] ) && ( isset( $_GET['id'] ) || isset( $_GET['login'] ) ) ) {

			// If available, get $user_login from query string parameter for fallback purposes.
			if ( isset( $_GET['login'] ) ) {
				$user_login = $_GET['login'];
			} else {
				$user       = get_user_by( 'id', absint( $_GET['id'] ) );
				$user_login = $user ? $user->user_login : '';
			}

			$value = sprintf( '%s:%s', wp_unslash( $user_login ), wp_unslash( $_GET['key'] ) );
			self::set_reset_password_cookie( $value );
			wp_safe_redirect( add_query_arg( 'show-reset-form', 'true', home_url() ) );
			exit;
		}
	}

	public function set_reset_password_cookie( $value = '' ) {
		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
		$rp_path   = isset( $_SERVER['REQUEST_URI'] ) ? current( explode( '?', wc_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; // WPCS: input var ok.

		if ( $value ) {
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		} else {
			setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		}
	}

	public function check_password_reset_key( $key, $login ) {
		// Check for the password reset key.
		// Get user data or an error message in case of invalid or expired key.
		$user = check_password_reset_key( $key, $login );

		if ( is_wp_error( $user ) ) {
			$message = esc_html__( 'This key is invalid or has already been used. Please reset your password again if needed.', 'lisner-core' );

			return $message;
		}

		return $user;
	}

}

/**
 * Instantiate the class
 *
 * @return lisner_auth|null
 */
function lisner_auth() {
	return lisner_auth::instance();
}
