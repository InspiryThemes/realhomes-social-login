<?php
/**
 * Plugin helper functions.
 *
 * @since      1.0.0
 * @package    realhomes-social-login
 * @subpackage realhomes-social-login/includes
 */

if ( ! function_exists( 'realhomes_social_login_buttons' ) ) {
	/**
	 * Display the markup of the social login buttons.
	 */
	function realhomes_social_login_buttons() {
		$rsl_settings = get_option( 'rsl_settings' );
		if ( isset( $rsl_settings['enable_social_login_facebook'] ) || isset( $rsl_settings['enable_social_login_google'] ) || isset( $rsl_settings['enable_social_login_twitter'] ) ) {
			?>
			<div class="realhomes-social-login">
				<div class="realhomes-social-login-widget">
					<div class="rsl-connect-with"><?php esc_html_e( 'Connect with:', 'realhomes-social-login' ); ?></div>
					<div class="rsl-provider-list">
						<?php
						// Login with facebook button.
						if ( isset( $rsl_settings['enable_social_login_facebook'] ) ) {
							?>
							<a rel="nofollow" data-provider="facebook" class="rsl-provider rsl-provider-facebook">
								<span><i class="fa fa-facebook"></i> <?php esc_html_e( 'Facebook', 'realhomes-social-login' ); ?></span>
							</a>
							<?php
						}

						// Login with google button.
						if ( isset( $rsl_settings['enable_social_login_google'] ) ) {
							?>
							<a rel="nofollow" data-provider="google" class="rsl-provider rsl-provider-google">
								<span><i class="fa fa-google"></i> <?php esc_html_e( 'Google', 'realhomes-social-login' ); ?></span>
							</a>
							<?php
						}

						// Login with twitter button.
						if ( isset( $rsl_settings['enable_social_login_twitter'] ) ) {
							?>
							<a rel="nofollow" data-provider="twitter" class="rsl-provider rsl-provider-twitter">
								<span><i class="fa fa-twitter"></i> <?php esc_html_e( 'Twitter', 'realhomes-social-login' ); ?></span>
							</a>
							<?php
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}
	}

	add_action( 'realhomes_social_login', 'realhomes_social_login_buttons' );
}

if ( ! function_exists( 'rsl_app_keys' ) ) {
	/**
	 * Return App keys based on given social network name.
	 *
	 * @param  string $social_network Name of social network.
	 * @return null|array
	 */
	function rsl_app_keys( $social_network = '' ) {

		if ( empty( $social_network ) ) {
			return null;
		}

		$rsl_settings = get_option( 'rsl_settings' );

		if ( 'facebook' === $social_network ) {
			if ( ! empty( $rsl_settings['facebook_app_id'] ) && ! empty( $rsl_settings['facebook_app_secret'] ) ) {
				return array(
					'app_id'     => $rsl_settings['facebook_app_id'],
					'app_secret' => $rsl_settings['facebook_app_secret'],
				);
			}
		} elseif ( 'google' === $social_network ) {
			if ( ! empty( $rsl_settings['google_app_api_key'] ) && ! empty( $rsl_settings['google_app_client_id'] ) && ! empty( $rsl_settings['google_app_client_secret'] ) ) {
				return array(
					'api_key'       => $rsl_settings['google_app_api_key'],
					'client_id'     => $rsl_settings['google_app_client_id'],
					'client_secret' => $rsl_settings['google_app_client_secret'],
				);
			}
		} elseif ( 'twitter' === $social_network ) {
			if ( ! empty( $rsl_settings['twitter_app_consumer_key'] ) && ! empty( $rsl_settings['twitter_app_consumer_secret'] ) ) {
				return array(
					'consumer_key'    => $rsl_settings['twitter_app_consumer_key'],
					'consumer_secret' => $rsl_settings['twitter_app_consumer_secret'],
				);
			}
		}

		return null;
	}
}

if ( ! function_exists( 'rsl_is_enabled' ) ) {
	/**
	 * Check if a given social network is enabled or not.
	 *
	 * @param string $network Name of the network.
	 */
	function rsl_is_enabled( $network = '' ) {

		if ( empty( $network ) ) {
			return false;
		}

		$rsl_settings = get_option( 'rsl_settings' );

		if ( 'facebook' === $network && isset( $rsl_settings['enable_social_login_facebook'] ) ||
			'google' === $network && isset( $rsl_settings['enable_social_login_google'] ) ||
			'twitter' === $network && isset( $rsl_settings['enable_social_login_twitter'] ) ) {
			return true;
		}
		return false;
	}
}
