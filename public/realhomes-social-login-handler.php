<?php
/**
 * This file is responsible to handle all plugin ajax requests.
 *
 * @since      1.0.0
 * @package    realhomes-social-login
 * @subpackage realhomes-social-login/public
 */

if ( ! function_exists( 'rsl_facebook_oauth_url' ) ) {
	/**
	 * Return the facebook login authorization url.
	 */
	function rsl_facebook_oauth_url() {

		// Facebook library.
		require_once RSL_PLUGIN_DIR . 'includes/libs/facebook/autoload.php';

		if ( class_exists( 'Facebook\Facebook' ) ) {

			if ( null === rsl_app_keys( 'facebook' ) ) {
				echo wp_json_encode(
					array(
						'success' => false,
						'message' => esc_html__( 'Facebook App keys are not set yet.', 'realhomes-social-login' ),
					)
				);

				wp_die();
			}

			$fb_app_keys = rsl_app_keys( 'facebook' );

			$fb = new Facebook\Facebook(
				array(
					'app_id'                => esc_html( $fb_app_keys['app_id'] ),
					'app_secret'            => esc_html( $fb_app_keys['app_secret'] ),
					'default_graph_version' => 'v2.10',
				)
			);

			$helper = $fb->getRedirectLoginHelper();

			$permissions = array( 'public_profile', 'email' ); // App permissions.
			$oauth_url   = $helper->getLoginUrl( get_home_url( null, '/', 'https' ), $permissions );

			echo wp_json_encode(
				array(
					'success'   => true,
					'oauth_url' => $oauth_url,
					'message'   => esc_html__( 'Redirecting you to the Facebook for the authentication...', 'realhomes-social-login' ),
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

	add_action( 'wp_ajax_nopriv_rsl_facebook_oauth_url', 'rsl_facebook_oauth_url' );
	add_action( 'wp_ajax_rsl_facebook_oauth_url', 'rsl_facebook_oauth_url' );
}

if ( ! function_exists( 'rsl_google_oauth_url' ) ) {
	/**
	 * Return the google login authorization url.
	 */
	function rsl_google_oauth_url() {

		// Google Client and Oauth libraries.
		require_once RSL_PLUGIN_DIR . 'includes/libs/google/Google_Client.php';
		require_once RSL_PLUGIN_DIR . 'includes/libs/google/contrib/Google_Oauth2Service.php';

		if ( class_exists( 'Google_Client' ) && class_exists( 'Google_Oauth2Service' ) ) {

			if ( null === rsl_app_keys( 'google' ) ) {
				echo wp_json_encode(
					array(
						'success' => false,
						'message' => esc_html__( 'Google related keys are not set yet.', 'realhomes-social-login' ),
					)
				);

				wp_die();
			}

			$google_app_creds     = rsl_app_keys( 'google' );
			$google_client_id     = $google_app_creds['client_id'];
			$google_client_secret = $google_app_creds['client_secret'];
			$google_developer_key = $google_app_creds['api_key'];
			$google_redirect_url  = home_url();

			$client = new Google_Client();

			$client->setApplicationName( esc_html__( 'Login to', 'realhomes-social-login' ) . get_bloginfo( 'name' ) );
			$client->setClientId( $google_client_id );
			$client->setClientSecret( $google_client_secret );
			$client->setDeveloperKey( $google_developer_key );
			$client->setRedirectUri( $google_redirect_url );
			$client->setScopes( array( 'email', 'profile' ) );

			$oauth_url = $client->createAuthUrl();

			echo wp_json_encode(
				array(
					'success'   => true,
					'oauth_url' => $oauth_url,
					'message'   => esc_html__( 'Redirecting you to the Google for the authentication...', 'realhomes-social-login' ),
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

	add_action( 'wp_ajax_nopriv_rsl_google_oauth_url', 'rsl_google_oauth_url' );
	add_action( 'wp_ajax_rsl_google_oauth_url', 'rsl_google_oauth_url' );
}

if ( ! function_exists( 'rsl_twitter_oauth_url' ) ) {
	/**
	 * Return the twitter login authorization url.
	 */
	function rsl_twitter_oauth_url() {

		// Twitter library.
		require_once RSL_PLUGIN_DIR . 'includes/libs/twitter/autoload.php';

		if ( class_exists( 'Abraham\TwitterOAuth\TwitterOAuth' ) ) {

			if ( null === rsl_app_keys( 'twitter' ) ) {
				echo wp_json_encode(
					array(
						'success' => false,
						'message' => esc_html__( 'Twitter App keys are not set yet.', 'realhomes-social-login' ),
					)
				);

				wp_die();
			}

			$twitter_app_keys = rsl_app_keys( 'twitter' );
			$consumer_key     = $twitter_app_keys['consumer_key'];
			$consumer_secret  = $twitter_app_keys['consumer_secret'];
			$callback_url     = home_url( '/' );

			try {

				$connection    = new Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret );
				$request_token = $connection->oauth( 'oauth/request_token', array( 'oauth_callback' => $callback_url ) );
				$oauth_url     = $connection->url( 'oauth/authorize', array( 'oauth_token' => $request_token['oauth_token'] ) );

				echo wp_json_encode(
					array(
						'success'   => true,
						'oauth_url' => $oauth_url,
						'message'   => esc_html__( 'Redirecting you to the Twitter for the authentication...', 'realhomes-social-login' ),
					)
				);

			} catch ( Exception $e ) {
				echo wp_json_encode(
					array(
						'success' => false,
						'message' => $e->getMessage(),
					)
				);

				wp_die();
			}
		} else {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Twitter library is not loaded.', 'realhomes-social-login' ),
				)
			);
		}

		wp_die();
	}

	add_action( 'wp_ajax_nopriv_rsl_twitter_oauth_url', 'rsl_twitter_oauth_url' );
	add_action( 'wp_ajax_rsl_twitter_oauth_url', 'rsl_twitter_oauth_url' );
}
