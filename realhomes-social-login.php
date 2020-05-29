<?php
/**
 * Plugin Name:       RealHomes Social Login
 * Plugin URI:        https://themeforest.net/item/real-homes-wordpress-real-estate-theme/5373914
 * Description:       This plugin lets your visitors register and login to your site using their social profiles (Facebook, Google, Twitter, etc.).
 * Version:           1.0.0
 * Author:            InspiryThemes
 * Author URI:        https://inspirythemes.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realhomes-social-login
 * Domain Path:       /languages
 *
 * @since   1.0.0
 * @package realhomes-social-login
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Realhomes_Social_Login' ) ) {

	class Realhomes_Social_Login {

		/**
		 * Plugin's current version
		 *
		 * @var string
		 */
		public $version;

		/**
		 * Plugin Name
		 *
		 * @var string
		 */
		public $plugin_name;

		/**
		 * Constructor function.
		 */
		public function __construct() {

			$this->plugin_name = 'realhomes-social-login';
			$this->version     = '1.0.0';

			$this->define_constants();

			echo RSL_PLUGIN_DIR;

		}

		/**
		 * Define constants.
		 */
		protected function define_constants() {

			// Plugin version.
			if ( ! defined( 'RSL_VERSION' ) ) {
				define( 'RSL_VERSION', $this->version );
			}

			// Plugin directory path.
			if ( ! defined( 'RSL_PLUGIN_DIR' ) ) {
				define( 'RSL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		/**
		 * Social networks libraries.
		 */
		public function load_libraries() {
			require_once ERE_PLUGIN_DIR . 'includes/facebook/autoload.php';  // Facebook SDK.
		}

	}

} // End if class_exists check.


/**
 * Main instance of Realhomes_Social_Login.
 *
 * Returns the instance of Realhomes_Social_Login to prevent the need to use globals.
 *
 * @return Realhomes_Social_Login
 */
function realhomes_social_login() {
	return new Realhomes_Social_Login();
}

// Get RSL Running.
realhomes_social_login();
