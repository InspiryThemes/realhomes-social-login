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
} elseif ( isset( $_GET['oauth_token'] ) && isset( $_GET['oauth_verifier'] ) ) {
	add_action( 'init', 'rsl_twitter_oauth_login' );
}


if ( ! function_exists( 'rsl_twitter_oauth_login' ) ) {
	/**
	 * Twitter oauth login.
	 */
	function rsl_twitter_oauth_login() {

			$consumer_key    = get_option( 'rsl_twitter_app_consumer_key' );
			$consumer_secret = get_option( 'rsl_twitter_app_consumer_secret' );

			$connection    = new Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret );
			$request_token = $connection->oauth( 'oauth/access_token', array( 'oauth_consumer_key' => $consumer_key, 'oauth_token' => $_GET['oauth_token'], 'oauth_verifier' => $_GET['oauth_verifier'] ) );

			$connection = new Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret, $request_token['oauth_token'], $request_token['oauth_token_secret'] );
			$user       = (array) $connection->get( 'account/verify_credentials', array( 'include_email' => 'true' ) );

			$register_cred['user_email']    = $user['email'];
			$register_cred['user_login']    = explode( '@', $user['email'] );
			$register_cred['user_login']    = $register_cred['user_login'][0];
			$register_cred['display_name']  = $user['name'];
			$register_cred['first_name']    = explode( ' ', $user['name'] );
			$register_cred['first_name']    = $register_cred['first_name'][0];
			$register_cred['last_name']     = isset( $user['first_name'][1] ) ? $user['first_name'][1] : '';
			$register_cred['profile_image'] = str_replace( '_normal', '_400x400', $user['profile_image_url_https'] );
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

if ( ! function_exists( 'rsl_google_oauth_login' ) ) {
	/**
	 * Google oauth login.
	 */
	function rsl_google_oauth_login() {

		$allowed_html = array(); // TODO: remove this line of code if not needed.

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
		$code            = sanitize_text_field( wp_unslash( $_GET['code'] ) );
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
		$user_id = wp_insert_user( $register_cred );

		if ( ! is_wp_error( $user_id ) ) {

			$profile_image_id = rsl_insert_image( $register_cred['profile_image'] );
			update_user_meta( $user_id, 'profile_image_id', $profile_image_id );

			// User notification function exists in plugin.
			if ( class_exists( 'Easy_Real_Estate' ) ) {
				// Send email notification to newly registered user and admin.
				ere_new_user_notification( $user_id, $register_cred['user_pass'] );
			}

			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'rsl_insert_image' ) ) {
	/**
	 * Insert an image to the WordPress library from given image url.
	 *
	 * @param  string $image_url URL of the image that needs to be inserted.
	 * @return int    $attached_id ID of the image that has been inserted.
	 */
	function rsl_insert_image( $image_url ) {

		$upload_dir = wp_upload_dir();
		$image_data = file_get_contents( $image_url );
		$filename   = basename( $image_url );

		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		file_put_contents( $file, $image_data );

		$wp_filetype = wp_check_filetype( $filename, null );

		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $file );
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		return $attach_id;
	}
}
