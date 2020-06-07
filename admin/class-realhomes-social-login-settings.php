<?php
/**
 * RealHomes Social Login Settings.
 *
 * This class is used to add the settings page of this plugin.
 *
 * @since      1.0.0
 * @package    realhomes-social-login
 * @subpackage realhomes-social-login/admin
 */

if ( ! class_exists( 'Realhomes_Social_Login_Settings' ) ) {
	/**
	 * Realhomes_Social_Login_Settings
	 *
	 * Class for RealHomes Social Login Settings. It is
	 * responsible for handling the settings page of the
	 * plugin.
	 *
	 * @since 1.0.0
	 */
	class Realhomes_Social_Login_Settings {

		/**
		 * Hook the required settings functions when the class is initiated.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_menu', array( $this, 'settings_page_menu' ) );
		}

		/**
		 * Add plugin settings page menu to the dashboard settings menu.
		 *
		 * @since  1.0.0
		 */
		public function settings_page_menu() {

			add_submenu_page(
				'easy-real-estate',
				esc_html__( 'Social Login Settings', 'realhomes-social-login' ),
				esc_html__( 'Social Login Settings', 'realhomes-social-login' ),
				'manage_options',
				'realhomes-social-login',
				array( $this, 'render_settings_page' ),
				11
			);

		}

		/**
		 * Render settings on the settings page.
		 *
		 * @since  1.0.0
		 */
		public function render_settings_page() {

			$rsl_settings = get_option( 'rsl_settings' );

			?>
			<div class="wrap">
				<h2><?php esc_html_e( 'RealHomes Social Login Settings', 'realhomes-social-login' ); ?></h2>
				<form method="post" action="options.php">

					<?php settings_fields( 'rsl_settings_group' ); ?>
					<table class="form-table">
						<tbody>

							<tr>
								<th>
									<h3><?php esc_html_e( 'Facebook', 'realhomes-social-login' ); ?></h3>
								</th>
							</tr>

							<!-- Enable Facebook -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Enable Facebook', 'realhomes-social-login' ); ?>
								</th>
								<td>
									<?php
										$enable_social_login_facebook = ! empty( $rsl_settings['enable_social_login_facebook'] ) ? $rsl_settings['enable_social_login_facebook'] : '';
									?>
									<input id="rsl_settings[enable_social_login_facebook]" name="rsl_settings[enable_social_login_facebook]" type="checkbox" value="1" <?php checked( 1, $enable_social_login_facebook ); ?> />
									<label class="description" for="rsl_settings[enable_social_login_facebook]"><?php esc_html_e( 'Enable login/register with facebook on login forms.', 'realhomes-social-login' ); ?></label>
								</td>
							</tr>

							<!-- Facebook App ID -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'App ID*', 'realhomes-currency-switcher' ); ?>
								</th>
								<td>
									<input id="rsl_settings[facebook_app_id]" name="rsl_settings[facebook_app_id]" type="text" class="regular-text" value="<?php echo esc_attr( $rsl_settings['facebook_app_id'] ); ?>"/>
									<p class="description"><label for="rsl_settings[facebook_app_id]"><?php echo sprintf( esc_html__( 'You can get Facebook APP ID and Secret from %s.', 'realhomes-currency-switcher' ), '<a href="https://developers.facebook.com/docs/apps/" target="_blank">here</a>' ); ?></label></p>
								</td>
							</tr>

							<!-- Facebook App Secret -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'App Secret*', 'realhomes-currency-switcher' ); ?>
								</th>
								<td>
									<input id="rsl_settings[facebook_app_secret]" name="rsl_settings[facebook_app_secret]" type="text" class="regular-text" value="<?php echo esc_attr( $rsl_settings['facebook_app_secret'] ); ?>"/>
								</td>
							</tr>

							<tr>
								<th>
									<h3><?php esc_html_e( 'Google', 'realhomes-social-login' ); ?></h3>
								</th>
							</tr>

							<!-- Enable Google -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Enable Google', 'realhomes-social-login' ); ?>
								</th>
								<td>
									<?php
										$enable_social_login_google = ! empty( $rsl_settings['enable_social_login_google'] ) ? $rsl_settings['enable_social_login_google'] : '';
									?>
									<input id="rsl_settings[enable_social_login_google]" name="rsl_settings[enable_social_login_google]" type="checkbox" value="1" <?php checked( 1, $enable_social_login_google ); ?> />
									<label class="description" for="rsl_settings[enable_social_login_google]"><?php esc_html_e( 'Enable login/register with google on login forms.', 'realhomes-social-login' ); ?></label>
								</td>
							</tr>

							<!-- Google API Key -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'API Key*', 'realhomes-currency-switcher' ); ?>
								</th>
								<td>
									<input id="rsl_settings[google_app_api_key]" name="rsl_settings[google_app_api_key]" type="text" class="regular-text" value="<?php echo esc_attr( $rsl_settings['google_app_api_key'] ); ?>"/>
									<p class="description"><label for="rsl_settings[google_app_api_key]"><?php echo sprintf( esc_html__( 'You can get Google API Key, Client ID and Client Secret from %s.', 'realhomes-currency-switcher' ), '<a href="https://developers.google.com/identity/sign-in/web/sign-in#create_authorization_credentials" target="_blank">here</a>' ); ?></label></p>
								</td>
							</tr>

							<!-- Google Client ID -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Client ID*', 'realhomes-currency-switcher' ); ?>
								</th>
								<td>
									<input id="rsl_settings[google_app_client_id]" name="rsl_settings[google_app_client_id]" type="text" class="regular-text" value="<?php echo esc_attr( $rsl_settings['google_app_client_id'] ); ?>"/>
								</td>
							</tr>

							<!-- Google Client Secret -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Client Secret*', 'realhomes-currency-switcher' ); ?>
								</th>
								<td>
									<input id="rsl_settings[google_app_client_secret]" name="rsl_settings[google_app_client_secret]" type="text" class="regular-text" value="<?php echo esc_attr( $rsl_settings['google_app_client_secret'] ); ?>"/>
								</td>
							</tr>

							<tr>
								<th>
									<h3><?php esc_html_e( 'Twitter', 'realhomes-social-login' ); ?></h3>
								</th>
							</tr>

							<!-- Enable Twitter -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Enable Twitter', 'realhomes-social-login' ); ?>
								</th>
								<td>
									<?php
										$enable_social_login_twitter = ! empty( $rsl_settings['enable_social_login_twitter'] ) ? $rsl_settings['enable_social_login_twitter'] : '';
									?>
									<input id="rsl_settings[enable_social_login_twitter]" name="rsl_settings[enable_social_login_twitter]" type="checkbox" value="1" <?php checked( 1, $enable_social_login_twitter ); ?> />
									<label class="description" for="rsl_settings[enable_social_login_twitter]"><?php esc_html_e( 'Enable login/register with twitter on login forms.', 'realhomes-social-login' ); ?></label>
								</td>
							</tr>

							<!-- Twitter App Consumer Key -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Consumer Key*', 'realhomes-currency-switcher' ); ?>
								</th>
								<td>
									<input id="rsl_settings[twitter_app_consumer_key]" name="rsl_settings[twitter_app_consumer_key]" type="text" class="regular-text" value="<?php echo esc_attr( $rsl_settings['twitter_app_consumer_key'] ); ?>"/>
									<p class="description"><label for="rsl_settings[twitter_app_consumer_key]"><?php echo sprintf( esc_html__( 'You can create Twitter App and get its Consumer Key + Consumer Secret from %s.', 'realhomes-currency-switcher' ), '<a href="https://developer.twitter.com/en/apps" target="_blank">here</a>' ); ?></label></p>
								</td>
							</tr>

							<!-- Twitter App Consumer Secret -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Consumer Secret*', 'realhomes-currency-switcher' ); ?>
								</th>
								<td>
									<input id="rsl_settings[twitter_app_consumer_secret]" name="rsl_settings[twitter_app_consumer_secret]" type="text" class="regular-text" value="<?php echo esc_attr( $rsl_settings['twitter_app_consumer_secret'] ); ?>"/>
								</td>
							</tr>

						</tbody>
					</table>

					<p class="submit">
						<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Options', 'realhomes-social-login' ); ?>"/>
					</p>

				</form>
			</div>
			<?php
		}

		/**
		 * Register settings for the plugin.
		 *
		 * @since  1.0.0
		 */
		public function register_settings() {
			register_setting( 'rsl_settings_group', 'rsl_settings' );
		}

	}

	// Initiate the social login settings class.
	new Realhomes_Social_Login_Settings();
}
