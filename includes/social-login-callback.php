<?php

if ( ( isset( $_GET['code'] ) && isset( $_GET['state'] ) ) ) {
	rsl_facebook_login( $_GET );
} elseif ( isset( $_GET['code'] ) ) {
	rsl_google_login();
} else {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( home_url() );
	}
}

function rsl_facebook_login() {
	if ( session_status() === PHP_SESSION_NONE ) {
		session_start();
	}
	// TODO: ensure FB class is already loaded and no need to load here.
	require_once __DIR__ . '/../facebook/autoload.php';

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
		$response = $fb->get( '/me?fields=id,email,name,first_name,last_name,picture' );
	} catch ( Facebook\Exception\ResponseException $e ) {
		echo 'Graph returned an error: ' . esc_html( $e->getMessage() );
		exit;
	} catch ( Facebook\Exception\SDKException $e ) {
		echo 'Facebook SDK returned an error: ' . esc_html( $e->getMessage() );
		exit;
	}

	$user = $response->getGraphUser();
}
