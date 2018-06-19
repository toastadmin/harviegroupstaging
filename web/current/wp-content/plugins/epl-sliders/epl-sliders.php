<?php
/*
 * Plugin Name: EPL - Sliders
 * Plugin URL: https://easypropertylistings.com.au/extension/sliders/
 * Description: Adds a customisable mobile responsive Sliders image galleries to your listings single and list views.
 * Version: 2.0.1
 * Author: Merv Barrett
 * Author URI: http://realestateconnected.com.au/
 * Contributors: mervb
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EPL_Slider' ) ) :
	/*
	 * Main EPL_Slider Class
	 *
	 * @since 1.0
	 */
	final class EPL_Slider {

		/*
		 * @var EPL_Slider The one true EPL_Slider
		 * @since 1.0
		 */
		private static $instance;

		/*
		 * Main EPL_Slider Instance
		 *
		 * Insures that only one instance of EPL_Slider exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses EPL_Slider::includes() Include the required files
		 * @see EPL_SLIDER()
		 * @return The one true EPL_Slider
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_Slider ) ) {
				self::$instance = new EPL_Slider;
				self::$instance->hooks();
				if ( defined('EPL_RUNNING') ) {
					self::$instance->setup_constants();
					self::$instance->includes();
				}
			}
			return self::$instance;
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function hooks() {
			// activation
			add_action( 'admin_init', array( $this, 'activation' ) );
		}

		/**
		 * Activation function fires when the plugin is activated.
		 * @since 1.0
		 * @access public
		 *
		 * @return void
		 */
		public function activation() {
			if ( ! defined('EPL_RUNNING') ) {
				// is this plugin active?
				if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			 		// unset activation notice
			 		unset( $_GET[ 'activate' ] );
			 		// display notice
			 		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
				}
			}
		}

		/**
		 * Admin notices
		 *
		 * @since 1.0
		*/
		public function admin_notices() {

			if ( ! defined('EPL_RUNNING') ) {
				echo '<div class="error"><p>';
				_e( 'Please activate <b>Easy Property Listings</b> to enable all functions of EPL - Sliders', 'epl-sliders' );
				echo '</p></div>';
			}
		}
		/*
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function setup_constants() {
			// API URL
			if ( ! defined( 'EPL_TEMPLATES' ) ) {
				define( 'EPL_TEMPLATES', 'https://easypropertylistings.com.au' );
			}

			// Extension name on API server
			if ( ! defined( 'EPL_SLIDER_VERSION' ) ) {
				define( 'EPL_SLIDER_VERSION', '2.0.1' );
			}

			// Extension name on API server
			if ( ! defined( 'EPL_SLIDER_PRODUCT_NAME' ) ) {
				define( 'EPL_SLIDER_PRODUCT_NAME', 'Sliders' );
			}

			// Plugin File
			if ( ! defined( 'EPL_SLIDER_PLUGIN_FILE' ) ) {
				define( 'EPL_SLIDER_PLUGIN_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'EPL_SLIDER_PLUGIN_URL' ) ) {
				define( 'EPL_SLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'EPL_SLIDER_PLUGIN_PATH' ) ) {
				define( 'EPL_SLIDER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'EPL_SLIDER_PLUGIN_PATH_INCLUDES' ) ) {
				define( 'EPL_SLIDER_PLUGIN_PATH_INCLUDES', EPL_SLIDER_PLUGIN_PATH . 'includes/' );
			}


		}
		/*
		 * Include required files
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function includes() {

			if ( is_admin() ) {
				$eplsliders_license = new EPL_License( __FILE__, EPL_SLIDER_PRODUCT_NAME, EPL_SLIDER_VERSION, 'Merv Barrett' );
				require_once EPL_SLIDER_PLUGIN_PATH_INCLUDES . 'admin-hooks.php';
				require_once EPL_SLIDER_PLUGIN_PATH_INCLUDES . 'install.php';
			}

			require_once EPL_SLIDER_PLUGIN_PATH_INCLUDES . 'css.php';
			require_once EPL_SLIDER_PLUGIN_PATH_INCLUDES . 'front-hooks.php';
		}

	}
endif; // End if class_exists check
/*
 * The main function responsible for returning the one true EPL_Slider
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $epl = EPL_SLIDER(); ?>
 *
 * @since 1.0
 * @return object The one true EPL_Slider Instance
 */
function EPL_SLIDER() {
	return EPL_Slider::instance();
}
// Get EPL_SLIDER Running
EPL_SLIDER();
