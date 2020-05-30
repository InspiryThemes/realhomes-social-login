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
			$login_url   = $helper->getLoginUrl( get_home_url( null, '/', 'https' ), $permissions );

			echo wp_json_encode(
				array(
					'success'   => true,
					'oauth_url' => $login_url,
					'message'   => esc_html__( 'Redirecting you to facebook for the authentication...', 'realhomes-social-login' ),
				)
			);
		} else {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => 'Hello World!',
				)
			);
		}

		wp_die();
	}
}

add_action( 'wp_ajax_nopriv_rsl_facebook_oauth_url', 'rsl_facebook_oauth_url' );
add_action( 'wp_ajax_rsl_facebook_oauth_url', 'rsl_facebook_oauth_url' );
