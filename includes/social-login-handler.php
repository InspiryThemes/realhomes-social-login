<?php
/**
 * This file is responsible to handle all plugin ajax requests.
 *
 * @package realhomes-social-login
 */

if ( ! function_exists( 'rsl_facebook_oauth_url' ) ) {
	/**
	 * Return the facebook login authorization url.
	 */
	function rsl_facebook_oauth_url() {

		if ( class_exists( 'Facebook\Facebook' ) && null !== rsl_facebook_app_keys() ) {

			$fb_app_keys = rsl_facebook_app_keys();

			$fb = new Facebook\Facebook([
				'app_id'                => esc_html( $fb_app_keys['app_id'] ),
				'app_secret'            => esc_html( $fb_app_keys['app_secret'] ),
				'default_graph_version' => 'v2.10',
			]);

			$helper = $fb->getRedirectLoginHelper();

			$permissions = array( 'public_profile', 'email' ); // App permissions.
			$oauth_url   = $helper->getLoginUrl( get_home_url( null, '/', 'https' ), $permissions );

			echo wp_json_encode(
				array(
					'success'   => true,
					'oauth_url' => $oauth_url,
					'message'   => esc_html__( 'Redirecting you to facebook for the authentication...', 'realhomes-social-login' ),
				)
			);
		} else {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Facebook library is not loaded.', 'realhomes-social-login' ),
				)
			);
		}

		wp_die();
	}
}

add_action( 'wp_ajax_nopriv_rsl_facebook_oauth_url', 'rsl_facebook_oauth_url' );
add_action( 'wp_ajax_rsl_facebook_oauth_url', 'rsl_facebook_oauth_url' );

if ( ! function_exists( 'rsl_google_oauth_url' ) ) {
	/**
	 * Return the facebook login authorization url.
	 */
	function rsl_google_oauth_url() {

		if ( class_exists( 'Google_Client' ) && class_exists( 'Google_Oauth2Service' ) && null !== rsl_google_app_creds() ) {

			$google_app_creds     = rsl_google_app_creds();
			$google_client_id     = $google_app_creds['client_id'];
			$google_client_secret = $google_app_creds['client_secret'];
			$google_developer_key = $google_app_creds['developer_key'];
			// $google_redirect_url  = home_url();
			$google_redirect_url  = 'http://localhost:3000/'; // TODO: change to default home url.

			$client = new Google_Client();

			$client->setApplicationName( 'Login to' . get_bloginfo( 'name' ) );
			$client->setClientId( $google_client_id );
			$client->setClientSecret( $google_client_secret );
			$client->setDeveloperKey( $google_developer_key );
			$client->setRedirectUri( $google_redirect_url );
			$client->setScopes( array( 'email', 'profile' ) );

			// $google_oauthV2 = new Google_Oauth2Service($client); // TODO: remove this line of code after testing.
			$oauth_url = $client->createAuthUrl();

			echo wp_json_encode(
				array(
					'success'   => true,
					'oauth_url' => $oauth_url,
					'message'   => esc_html__( 'Redirecting you to google for the authentication...', 'realhomes-social-login' ),
				)
			);

		} else {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Google library is not loaded.', 'realhomes-social-login' ),
				)
			);
		}

		wp_die();
	}
}

add_action( 'wp_ajax_nopriv_rsl_google_oauth_url', 'rsl_google_oauth_url' );
add_action( 'wp_ajax_rsl_google_oauth_url', 'rsl_google_oauth_url' );
