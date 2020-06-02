<?php

if ( ( isset( $_GET['code'] ) && isset( $_GET['state'] ) ) ) {
	add_action( 'init', 'rsl_facebook_login' );
} elseif ( isset( $_GET['code'] ) ) {
	rsl_google_login();
}

function rsl_facebook_login() {
	if ( session_status() === PHP_SESSION_NONE ) {
		session_start();
	}
	// TODO: ensure FB class is already loaded and no need to load here.
	// require_once __DIR__ . '/../facebook/autoload.php';

	$fb_app_keys = rsl_facebook_app_keys();
	$fb_args     = array(
		'app_id'                => $fb_app_keys['app_id'],
		'app_secret'            => $fb_app_keys['app_secret'],
		'default_graph_version' => 'v2.10',
	);

	$fb = new Facebook\Facebook( $fb_args );

	$helper = $fb->getRedirectLoginHelper();

	if ( isset( $_GET['state'] ) ) {
		$helper->getPersistentDataHandler()->set( 'state', $_GET['state'] );
	}

	try {
		$access_token_obj = $helper->getAccessToken();
	} catch ( Facebook\Exception\ResponseException $e ) {
		// When Graph returns an error.
		echo 'Graph returned an error: ' . esc_html( $e->getMessage() );
		exit;
	} catch ( Facebook\Exception\SDKException $e ) {
		// When validation fails or other local issues.
		echo 'Facebook SDK returned an error: ' . esc_html( $e->getMessage() );
		exit;
	}

	if ( ! isset( $access_token_obj ) ) {
		if ( $helper->getError() ) {
			header( 'HTTP/1.0 401 Unauthorized' );
			echo 'Error: ' . esc_html( $helper->getError() ) . '\n';
			echo 'Error Code: ' . esc_html( $helper->getErrorCode() ) . '\n';
			echo 'Error Reason: ' . esc_html( $helper->getErrorReason() ) . '\n';
			echo 'Error Description: ' . esc_html( $helper->getErrorDescription() ) . '\n';
		} else {
			header( 'HTTP/1.0 400 Bad Request' );
			echo 'Bad request';
		}
		exit;
	}

	$access_token = (string) $access_token_obj->getValue();

	$fb = new Facebook\Facebook(
		array(
			'app_id'                => esc_html( $fb_app_keys['app_id'] ),
			'app_secret'            => esc_html( $fb_app_keys['app_secret'] ),
			'default_graph_version' => 'v2.10',
			'default_access_token'  => $access_token,
		)
	);

	try {
		// Returns a `Facebook\Response` object.
		$response = $fb->get( '/me?fields=id,email,name,first_name,last_name' );
	} catch ( Facebook\Exception\ResponseException $e ) {
		echo 'Graph returned an error: ' . esc_html( $e->getMessage() );
		exit;
	} catch ( Facebook\Exception\SDKException $e ) {
		echo 'Facebook SDK returned an error: ' . esc_html( $e->getMessage() );
		exit;
	}

	$user = $response->getGraphUser();

	$register_cred['user_email']    = $user['email'];
	$register_cred['user_login']    = explode( '@', $user['email'] );
	$register_cred['user_login']    = $register_cred['user_login'][0];
	$register_cred['display_name']  = $user['name'];
	$register_cred['first_name']    = $user['first_name'];
	$register_cred['last_name']     = $user['last_name'];
	$register_cred['profile_image'] = 'https://graph.facebook.com/' . $user['id'] . '/picture?width=300&height=300';
	$register_cred['user_pass']     = $user['id'];

	$user_registered = rsl_social_register( $register_cred );

	if ( $user_registered ) {

		$login_creds                  = array();
		$login_creds['user_login']    = $register_cred['user_login'];
		$login_creds['user_password'] = $register_cred['user_pass'];
		$login_creds['remember']      = true;

		rsl_social_login( $login_creds );
	}

}

function rsl_social_login( $login_creds ) {

	$user_signon = wp_signon( $login_creds, false );

	if ( is_wp_error( $user_signon ) ) {
		wp_safe_redirect( home_url() );
	} else {
		// TODO: get the user members page dynamically.
		wp_safe_redirect( 'https://rh.o/edit-profile/' );
	}
	exit;
}

function rsl_social_register( $register_cred  ) {

	// Register the user.
	$user_register = wp_insert_user( $register_cred );

	if ( ! is_wp_error( $user_register ) ) {

		// User notification function exists in plugin.
		if ( class_exists( 'Easy_Real_Estate' ) ) {
			// Send email notification to newly registered user and admin.
			ere_new_user_notification( $user_register, $register_cred['user_pass'] );
		}

		// if ( inspiry_is_user_sync_enabled() ) {
		// 	$role       = $_POST['user_role'];
		// 	$user_roles = inspiry_user_sync_roles();

		// 	if ( array_key_exists( $role, $user_roles ) ) {

		// 		update_user_meta( $user_register, 'inspiry_user_role', $role );
		// 		inspiry_insert_role_post( $user_register, $role );
		// 	}
		// }

		return true;
	}

	return false;
}
