<?php
//mimic the actual admin-ajax
define( 'DOING_AJAX', true );

if ( ! isset( $_REQUEST['action'] ) ) {
	die( '-1' );
}

//make sure you update this line
//to the relative location of the wp-load.php
$full_path = getcwd();
$ar = explode("wp-", $full_path);
if ( isset( $ar[0] ) && ! empty( $ar[0] ) ) {
	include( $ar[0] . 'wp-load.php' );
} else {
	include( $_SERVER['DOCUMENT_ROOT'] . 'wp-load.php' );
}

//Typical headers
header( 'Content-Type: text/html' );
send_nosniff_header();

//Disable caching
header( 'Cache-Control: no-cache' );
header( 'Pragma: no-cache' );


$action = esc_attr( trim( $_REQUEST['action'] ) );

if ( has_action( "lisner_ajax_{$action}" ) || has_action( "lisner_ajax_nopriv_{$action}" ) ) {
	if ( is_user_logged_in() ) {
		do_action( "lisner_ajax_{$action}" );
	} elseif ( ! is_user_logged_in() ) {
		do_action( "lisner_ajax_nopriv_{$action}" );
	} else {
		die( '-1' );
	}
} else {
	die( '-1' );
}