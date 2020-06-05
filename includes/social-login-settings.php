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

	}
}
