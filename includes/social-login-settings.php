<?php
/**
 * RealHomes Social Login Settings.
 *
 * This class is used to initialize the settings page of this plugin.
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

		public function __construct() {
			
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
							<!-- Social Login enable disable -->
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Social Login', 'realhomes-social-login' ); ?>
								</th>
								<td>
									<?php
										$enable_social_login = ! empty( $rsl_settings['enable_social_login'] ) ? $rsl_settings['enable_social_login'] : '';
									?>
									<input id="rsl_settings[enable_social_login]" name="rsl_settings[enable_social_login]" type="checkbox" value="1" <?php checked( 1, $enable_social_login ); ?> />
									<label class="description" for="rsl_settings[enable_social_login]"><?php esc_html_e( 'Enable Social Login on Login forms.', 'realhomes-social-login' ); ?></label>
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
