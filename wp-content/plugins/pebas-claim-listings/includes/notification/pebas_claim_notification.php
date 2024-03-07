<?php
/**
 * Class pebas_claim_notification
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_claim_notification
 */
class pebas_claim_notification {

	protected static $_instance = null;

	/**
	 * @return null|pebas_claim_notification
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_meta constructor.
	 */
	function __construct() {
		/* Send Email Notification On New Claim */
		add_action( 'pebas_claim_listings_create_new_claim', array( $this, 'mail_claimer_new_claim' ), 10, 2 );
		add_action( 'pebas_claim_listings_create_new_claim', array( $this, 'mail_admin_new_claim' ), 10, 2 );

		/* Send Email Notification On Claim Status Update */
		add_action( 'pebas_claim_listings_claim_status_updated', array(
			$this,
			'mail_claimer_claim_status_updated'
		), 10, 3 );
		add_action( 'pebas_claim_listings_claim_status_updated', array(
			$this,
			'mail_admin_claim_status_updated'
		), 10, 3 );

		// sanitize settings
		add_filter( 'sanitize_option_pebas_paid_listings_email_message_new_claim_claimer', array(
			'pebas_claim_notification',
			'sanitize_email'
		) );
		add_filter( 'sanitize_option_pebas_paid_listings_email_message_new_claim_admin', array(
			'pebas_claim_notification',
			'sanitize_email'
		) );
		add_filter( 'sanitize_option_pebas_paid_listings_email_message_status_update_claimer', array(
			'pebas_claim_notification',
			'sanitize_email'
		) );
		add_filter( 'sanitize_option_pebas_paid_listings_email_message_status_update_claimer', array(
			'pebas_claim_notification',
			'sanitize_email'
		) );

	}

	function mail_claimer_new_claim( $claim_id, $context ) {
		if ( 'front' != $context ) {
			return false; // only send on front end submission.
		}

		/* Mail Args */
		$args = array(
			'to'      => '%claimer_email%',
			'subject' => __( 'Your Claim Information', 'pebas-claim-listings' ),
			'message' => get_option( 'email_message_new_claim_claimer', self::default_email_message_new_claim_claimer() ),
		);
		$args = apply_filters( 'pebas_claim_listings_notification_mail_claimer_new_claim_args', $args );
		if ( ! $args ) {
			return false;
		}

		/* Add data to message */
		$data = pebas_claim()->get_data( $claim_id );
		if ( ! $data ) {
			return false;
		}
		foreach ( $data as $key => $val ) {
			$args['to']      = str_replace( "%{$key}%", $val, $args['to'] );
			$args['subject'] = str_replace( "%{$key}%", $val, $args['subject'] );
			$args['message'] = str_replace( "%{$key}%", $val, $args['message'] );
		}

		/* Send Mail */
		self::send_mail( $args );
	}


	/**
	 * New Claim Notification
	 *
	 * @param $claim_id
	 * @param $context
	 *
	 * @return bool
	 */
	function mail_admin_new_claim( $claim_id, $context ) {
		if ( 'front' != $context ) {
			return false; // only send on front end submission.
		}

		/* Mail Args */
		$args = array(
			'to'      => get_bloginfo( 'admin_email' ),
			'subject' => __( '[WP Job Man] New Claim Submitted', 'pebas-claim-listings' ),
			'message' => get_option( 'email_message_new_claim_admin', self::default_email_message_new_claim_admin() ),
		);

		$args = apply_filters( 'pebas_claim_listings_notification_mail_admin_new_claim_args', $args );
		if ( ! $args ) {
			return false;
		}

		/* Add data to message */
		$data = pebas_claim()->get_data( $claim_id );
		if ( ! $data ) {
			return false;
		}
		foreach ( $data as $key => $val ) {
			$args['to']      = str_replace( "%{$key}%", $val, $args['to'] );
			$args['subject'] = str_replace( "%{$key}%", $val, $args['subject'] );
			$args['message'] = str_replace( "%{$key}%", $val, $args['message'] );
		}

		/* Send Mail */
		self::send_mail( $args );
	}


	/**
	 * Mail Claimer that Claim Status Updated
	 *
	 * @since 3.0.0
	 */
	public function mail_claimer_claim_status_updated( $claim_id, $old_status, $request ) {
		/* Check! */
		if ( ! is_admin() ) {
			return false;
		}
		if ( ! isset( $request['_send_notification'] ) ) {
			return false;
		}
		if ( ! is_array( $request['_send_notification'] ) ) {
			return false;
		}
		if ( ! in_array( 'claimer', $request['_send_notification'] ) ) {
			return false;
		}

		/* Mail Args */
		$args = array(
			'to'      => '%claimer_email%',
			'subject' => __( 'Your Claim For "%listing_title%" is %claim_status%', 'pebas-claim-listings' ),
			'message' => get_option( 'email_message_status_update_claimer', self::default_email_message_status_update_claimer() ),
		);

		$args = apply_filters( 'pebas_claim_listings_notification_mail_claimer_claim_status_updated_args', $args );
		if ( ! $args ) {
			return false;
		}

		/* Add data to message */
		$data = pebas_claim()->get_data( $claim_id );
		if ( ! $data ) {
			return false;
		}
		foreach ( $data as $key => $val ) {
			$args['to']      = str_replace( "%{$key}%", $val, $args['to'] );
			$args['subject'] = str_replace( "%{$key}%", $val, $args['subject'] );
			$args['message'] = str_replace( "%{$key}%", $val, $args['message'] );
		}
		/* Old Status Label */
		$statuses         = pebas_claim()->get_listing_claim_statuses();
		$claim_old_status = array_key_exists( $old_status, $statuses ) ? $statuses[ $old_status ] : $old_status;
		$args['message']  = str_replace( '%claim_status_old%', $claim_old_status, $args['message'] );

		/* Send Mail */
		self::send_mail( $args );
	}


	/**
	 * Mail Admin that Claim Status has been updated
	 *
	 * @param $claim_id
	 * @param $old_status
	 * @param $request
	 *
	 * @return bool
	 */
	public function mail_admin_claim_status_updated( $claim_id, $old_status, $request ) {
		/* Check! */
		if ( ! is_admin() ) {
			return false;
		}
		if ( ! isset( $request['_send_notification'] ) ) {
			return false;
		}
		if ( ! is_array( $request['_send_notification'] ) ) {
			return false;
		}
		if ( ! in_array( 'admin', $request['_send_notification'] ) ) {
			return false;
		}

		/* Mail Args */
		$args = array(
			'to'      => get_option( 'admin_email' ),
			'subject' => __( '[WP Job Man] Claim for %listing_title% is updated to %claim_status%', 'pebas-claim-listings' ),
			'message' => get_option( 'email_message_status_update_admin', self::default_email_message_status_update_admin() ),
		);

		$args = apply_filters( 'pebas_claim_listings_notification_mail_admin_claim_status_updated_args', $args );
		if ( ! $args ) {
			return false;
		}

		/* Add data to message */
		$data = pebas_claim()->get_data( $claim_id );
		if ( ! $data ) {
			return false;
		}
		foreach ( $data as $key => $val ) {
			$args['to']      = str_replace( "%{$key}%", $val, $args['to'] );
			$args['subject'] = str_replace( "%{$key}%", $val, $args['subject'] );
			$args['message'] = str_replace( "%{$key}%", $val, $args['message'] );
		}
		/* Old Status Label */
		$statuses         = pebas_claim()->get_listing_claim_statuses();
		$claim_old_status = array_key_exists( $old_status, $statuses ) ? $statuses[ $old_status ] : $old_status;
		$args['message']  = str_replace( '%claim_status_old%', $claim_old_status, $args['message'] );

		/* Send Mail */
		self::send_mail( $args );
	}

	public static function site_name() {
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		return $sitename;
	}

	public static function send_mail( $args ) {
		$args_default = array(
			'to'           => '',
			'from'         => 'wordpress@' . self::site_name(),
			'from_name'    => get_bloginfo( 'name' ),
			'reply_to'     => get_bloginfo( 'admin_email' ),
			'subject'      => '',
			'message'      => '',
			'content_type' => 'text/html',
			'charset'      => get_bloginfo( 'charset' ),
		);
		$args         = wp_parse_args( $args, $args_default );
		$args         = apply_filters( 'pebas_claim_listings_notification_send_mail_args', $args );

		$headers = array(
			'From: "' . strip_tags( $args['from_name'] ) . '" <' . sanitize_email( $args['from'] ) . '>',
			'Reply-To: ' . $args['reply_to'],
			'Content-type: ' . $args['content_type'] . '; charset: ' . $args['charset'],
		);

		if ( $args['to'] && is_email( $args['to'] ) && $args['subject'] && $args['message'] ) {
			//todo enable sanitation for the message in the next version
			wp_mail( sanitize_email( $args['to'] ), esc_attr( $args['subject'] ), $args['message'], $headers );
		}
	}

	public static function sanitize_email_message( $input ) {

		/* allowed tags */
		$allowed_tags = array(
			'a'       => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
			),
			'abbr'    => array(
				'title' => array(),
			),
			'acronym' => array(
				'title' => array(),
			),
			'code'    => array(),
			'pre'     => array(),
			'em'      => array(),
			'strong'  => array(),
			'br'      => array(),
			'div'     => array(),
			'p'       => array(),
			'ul'      => array(),
			'ol'      => array(),
			'li'      => array(),
			'h1'      => array(),
			'h2'      => array(),
			'h3'      => array(),
			'h4'      => array(),
			'h5'      => array(),
			'h6'      => array(),
			'img'     => array(
				'src'   => array(),
				'class' => array(),
				'alt'   => array(),
			),
		);

		$allowed_tags = apply_filters( 'wpjmcl_notification_email_message_allowed_tags', $allowed_tags );

		return wp_kses( $input, $allowed_tags );
	}

	public static function default_email_message_new_claim_claimer() {
		$message = __(
			'Hi %claimer_name%,' . "\n" .
			"On %claim_date% you submitted a claim for a listing. Here's the details." . "\n\n" .
			'Listing URL: %listing_url%' . "\n" .
			'Claimed by: %claimer_name%' . "\n" .
			'Claim Status: %claim_status%' . "\n\n" .
			'You can also view your claim online: %claim_url%' . "\n\n" .
			'Thank you.' . "\n"
			, 'pebas-claim-listings' );

		return $message;
	}

	public static function default_email_message_new_claim_admin() {
		$message = __(
			'Hi Admin,' . "\n" .
			"New claim submitted, here's the details." . "\n\n" .
			'Listing URL: %listing_url%' . "\n" .
			'Claimed by: %claimer_name%' . "\n" .
			'Claim Status: %claim_status%' . "\n\n" .
			'Edit Claim: %claim_edit_url%' . "\n\n" .
			'Thank you.' . "\n"
			, 'pebas-claim-listings' );

		return $message;
	}

	public static function default_email_message_status_update_claimer() {
		$message = __(
			'Hi %claimer_name%,' . "\n" .
			"On %claim_date% you submitted a claim for a listing. Your claim status is updated. Here's the details." . "\n\n" .
			'Listing URL: %listing_url%' . "\n" .
			'Claimed by: %claimer_name%' . "\n\n" .
			'Previous Claim Status: %claim_status_old%' . "\n" .
			'New Claim Status: %claim_status%' . "\n\n" .
			'Thank you.' . "\n"
			, 'pebas-claim-listings' );

		return $message;
	}

	/**
	 * Claim Status Update Default Mail Message to Admin.
	 *
	 */
	public static function default_email_message_status_update_admin() {
		$message = __(
			'Hi Admin,' . "\n" .
			"Claim status for listing %listing_title% is updated, here's the details." . "\n\n" .
			'Listing URL: %listing_url%' . "\n" .
			'Claimed by: %claimer_name%' . "\n\n" .
			'Previous Claim Status: %claim_status_old%' . "\n" .
			'New Claim Status: %claim_status%' . "\n\n" .
			'You can edit this claim: %claim_edit_url%' . "\n\n" .
			'Thank you.' . "\n"
			, 'pebas-claim-listings' );

		return $message;
	}

}

/** Instantiate class
 *
 * @return null|pebas_claim_notification
 */
function pebas_claim_notification() {
	return pebas_claim_notification::instance();
}
