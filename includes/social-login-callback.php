<?php
/**
 * Social login callback functions.
 *
 * @package    realhomes-social-login
 * @subpackage realhomes-social-login/includes
 */

if ( ( isset( $_GET['code'] ) && isset( $_GET['state'] ) ) ) {
	add_action( 'init', 'rsl_facebook_oauth_login' );
} elseif ( isset( $_GET['code'] ) ) {
	add_action( 'init', 'rsl_google_oauth_login' );
}

if ( ! function_exists( 'rsl_google_oauth_login' ) ) {
	/**
	 * Google oauth login.
	 */
	function rsl_google_oauth_login() {

		$allowed_html = array();

		$google_app_creds     = rsl_google_app_creds();
		$google_client_id     = $google_app_creds['client_id'];
		$google_client_secret = $google_app_creds['client_secret'];
		$google_developer_key = $google_app_creds['developer_key'];
		$google_redirect_url  = home_url();

		$google_client = new Google_Client();
		$google_client->setApplicationName( 'Login to' . get_bloginfo( 'name' ) );
		$google_client->setClientId( $google_client_id );
		$google_client->setClientSecret( $google_client_secret );
		$google_client->setDeveloperKey( $google_developer_key );
		$google_client->setRedirectUri( $google_redirect_url );
		$google_client->setScopes( array( 'email', 'profile' ) );

		$google_oauth_v2 = new Google_Oauth2Service( $google_client );
		$code = sanitize_text_field( wp_unslash( $_GET['code'] ) );
		$google_client->authenticate( $code );

		if ( $google_client->getAccessToken() ) {

			$user = $google_oauth_v2->userinfo->get();

			$register_cred['user_email']    = $user['email'];
			$register_cred['user_login']    = explode( '@', $user['email'] );
			$register_cred['user_login']    = $register_cred['user_login'][0];
			$register_cred['display_name']  = $user['name'];
			$register_cred['first_name']    = isset( $user['given_name'] ) ? $user['given_name'] : '';
			$register_cred['last_name']     = isset( $user['family_name'] ) ? $user['family_name'] : '';
			$register_cred['profile_image'] = $user['picture'];
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
	}
}

if ( ! function_exists( 'rsl_facebook_oauth_login' ) ) {
	/**
	 * Facebook profile login.
	 */
	function rsl_facebook_oauth_login() {

		$fb_app_keys = rsl_facebook_app_keys();

		if ( null === $fb_app_keys ) {
			return;
		}

		$fb_args = array(
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
}

if ( ! function_exists( 'rsl_social_login' ) ) {
	/**
	 * Logging in with the social profile credentials.
	 *
	 * @param array $login_creds Login credentials.
	 */
	function rsl_social_login( $login_creds ) {

		$user_signon = wp_signon( $login_creds, false );

		if ( is_wp_error( $user_signon ) ) {
			wp_safe_redirect( home_url() );
		} else {
			$edit_profile_page_url = inspiry_get_edit_profile_url();
			if ( $edit_profile_page_url ) {
				wp_safe_redirect( $edit_profile_page_url );
			} else {
				wp_safe_redirect( home_url() );
			}
		}
		exit;
	}
}

if ( ! function_exists( 'rsl_social_register' ) ) {
	/**
	 * User registeration with social profile information.
	 *
	 * @param array $register_cred User registeration credentials.
	 * @return bool
	 */
	function rsl_social_register( $register_cred ) {

		// Register the user.
		$user_register = wp_insert_user( $register_cred );

		if ( ! is_wp_error( $user_register ) ) {

			// User notification function exists in plugin.
			if ( class_exists( 'Easy_Real_Estate' ) ) {
				// Send email notification to newly registered user and admin.
				ere_new_user_notification( $user_register, $register_cred['user_pass'] );
			}

			return true;
		}

		return false;
	}
}
