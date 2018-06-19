<?php
/*
 * Plugin Name: EPL - Field Sliders
 * Plugin URL: http://easypropertylistings.com.au/extension/field-sliders/
 * Description: Jquery UI sliders for epl search fields
 * Version: 1.0.0
 * Author: Merv Barrett
 * Author URI: http://www.realestateconnected.com.au
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EPL_Field_Sliders' ) ) :
	/*
	 * Main EPL_Field_Sliders Class
	 *
	 * @since 1.0
	 */
	final class EPL_Field_Sliders {

		/*
		 * @var EPL_Field_Sliders The one true EPL_Field_Sliders
		 * @since 1.0
		 */
		private static $instance;

		/*
		 * Main EPL_Field_Sliders Instance
		 *
		 * Insures that only one instance of EPL_Field_Sliders exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses EPL_Field_Sliders::includes() Include the required files
		 * @see EPL()
		 * @return The one true EPL_Field_Sliders
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_Field_Sliders ) ) {
				self::$instance = new EPL_Field_Sliders;
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
				_e( 'Please activate <b>Easy Property Listings</b> to enable all functions of EPL - Field Sliders', 'epl-field-sliders' );
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
			if ( ! defined( 'EPL_FIELD_SLIDERS_PRODUCT_NAME' ) ) {
				define( 'EPL_FIELD_SLIDERS_PRODUCT_NAME', 'Field Sliders' );
			}

			// Plugin File
			if ( ! defined( 'EPL_FIELD_SLIDERS_PLUGIN_FILE' ) ) {
				define( 'EPL_FIELD_SLIDERS_PLUGIN_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'EPL_FIELD_SLIDERS_PLUGIN_URL' ) ) {
				define( 'EPL_FIELD_SLIDERS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'EPL_FIELD_SLIDERS_PLUGIN_PATH' ) ) {
				define( 'EPL_FIELD_SLIDERS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}
			if ( ! defined( 'EPL_FIELD_SLIDERS_PLUGIN_DIR' ) ) {
				define( 'EPL_FIELD_SLIDERS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'EPL_FIELD_SLIDERS_PATH_INCLUDES' ) ) {
				define( 'EPL_FIELD_SLIDERS_PATH_INCLUDES', EPL_FIELD_SLIDERS_PLUGIN_PATH . 'includes/' );
			}

			if ( ! defined( 'EPL_FIELD_SLIDERS_PATH_TEMPLATES' ) ) {
				define( 'EPL_FIELD_SLIDERS_PATH_TEMPLATES', EPL_FIELD_SLIDERS_PLUGIN_PATH . 'templates/' );
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

			require_once( EPL_FIELD_SLIDERS_PLUGIN_PATH . 'includes/functions.php' );
			require_once( EPL_FIELD_SLIDERS_PLUGIN_PATH . 'includes/hooks.php' );

			if ( is_admin() ) {
				$eplsp_license = new EPL_License( __FILE__, EPL_FIELD_SLIDERS_PRODUCT_NAME, '1.0.0', 'Merv Barrett' );
			}
		}

	}
endif; // End if class_exists check
/*
 * @since 1.0
 * @return object The one true EPL_Field_Sliders Instance
 */
function EPL_FIELD_SLIDERS() {
	return EPL_Field_Sliders::instance();
}
// Get EPL Running
EPL_FIELD_SLIDERS();
