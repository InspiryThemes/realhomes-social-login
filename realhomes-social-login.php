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
	/**
	 * Main class of plugin that manage all assets and plugin instance.
	 */
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
			$this->load_assets();

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );
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

			// Plugin directory path.
			if ( ! defined( 'RSL_PLUGIN_URL' ) ) {
				define( 'RSL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
		}

		/**
		 * Load assets of the plugin.
		 */
		public function load_assets() {
			require_once RSL_PLUGIN_DIR . 'includes/realhomes-social-login-helper-functions.php';
			require_once RSL_PLUGIN_DIR . 'public/realhomes-social-login-handler.php';
			require_once RSL_PLUGIN_DIR . 'admin/realhomes-social-login-callbacks.php';
			require_once RSL_PLUGIN_DIR . 'admin/class-realhomes-social-login-settings.php';
		}

		/**
		 * Enqueue public scripts.
		 */
		public function enqueue_public_scripts() {

			wp_register_script(
				'realhomes_social_login',
				RSL_PLUGIN_URL . 'js/frontend.js',
				array( 'jquery' ),
				$this->version,
				true
			);

			wp_localize_script( 'realhomes_social_login', 'rsl_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

			wp_enqueue_script( 'realhomes_social_login' );
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
