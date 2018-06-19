<?php
/*
 * Plugin Name: EPL - Location Profiles
 * Plugin URL: https://easypropertylistings.com.au/extension/location-profiles/
 * Description: Adds location post type, widgets and shortcodes to Easy Property Listings
 * Version: 2.3
 * Author: Merv Barrett
 * Author URI: http://www.realestateconnected.com.au
 * Contributors: mervb1
 * Text Domain: epl-location-profiles
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EPL_Location_Profiles' ) ) :
	/*
	 * Main EPL_Location_Profiles Class
	 *
	 * @since 1.0
	 */
	final class EPL_Location_Profiles {

		/*
		 * @var EPL_Location_Profiles The one true EPL_Location_Profiles
		 * @since 1.0
		 */
		private static $instance;

		/*
		 * Main EPL_Location_Profiles Instance
		 *
		 * Insures that only one instance of EPL_Location_Profiles exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses EPL_Location_Profiles::includes() Include the required files
		 * @see EPL()
		 * @return The one true EPL_Location_Profiles
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_Location_Profiles ) ) {
				self::$instance = new EPL_Location_Profiles;
				self::$instance->hooks();
				if ( defined('EPL_RUNNING') ) {
					self::$instance->setup_constants();
					self::$instance->includes();
					self::$instance->load_textdomain();
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
			register_activation_hook( __FILE__, array($this,'reset_permalinks') );
			add_action( 'init', array( $this, 'reset_permalinks_maybe' ) );
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
				_e( 'Please activate <b>Easy Property Listings</b> to enable all functions of EPL - Location Profiles', 'epl-location-profiles' );
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
			if ( ! defined( 'EPL_LP_PRODUCT_NAME' ) ) {
				define( 'EPL_LP_PRODUCT_NAME', 'Location Profiles' );
			}

			// Version
			if ( ! defined( 'EPL_LP_VER' ) ) {
				define( 'EPL_LP_VER', '2.3' );
			}

			// Plugin File
			if ( ! defined( 'EPL_LP_PLUGIN_FILE' ) ) {
				define( 'EPL_LP_PLUGIN_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'EPL_LP_PLUGIN_URL' ) ) {
				define( 'EPL_LP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'EPL_LP_PLUGIN_PATH' ) ) {
				define( 'EPL_LP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}
			if ( ! defined( 'EPL_LP_PLUGIN_DIR' ) ) {
				define( 'EPL_LP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'EPL_LP_PATH_INCLUDES' ) ) {
				define( 'EPL_LP_PATH_INCLUDES', EPL_LP_PLUGIN_PATH . 'includes/' );
			}

			if ( ! defined( 'EPL_LP_PATH_LANGUAGES' ) ) {
				define( 'EPL_LP_PATH_LANGUAGES', EPL_LP_PLUGIN_PATH . 'languages/' );
			}

			if ( ! defined( 'EPL_LP_CSS_URL' ) ) {
				define( 'EPL_LP_CSS_URL', EPL_LP_PLUGIN_URL . 'css/' );
			}

			if ( ! defined( 'EPL_LP_PATH_TEMPLATES' ) ) {
				define( 'EPL_LP_PATH_TEMPLATES', EPL_LP_PLUGIN_PATH . 'templates/' );
			}

			if ( ! defined( 'EPL_LP_PATH_TEMPLATES_CONTENT' ) ) {
				define( 'EPL_LP_PATH_TEMPLATES_CONTENT', EPL_LP_PATH_TEMPLATES . 'content/' );
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

			require_once( EPL_LP_PATH_INCLUDES . 'install.php');

			require_once( EPL_LP_PATH_INCLUDES . 'functions.php' );
			require_once( EPL_LP_PATH_INCLUDES . 'post-type-location-profiles.php' );
			require_once( EPL_LP_PATH_INCLUDES . 'widget-location-profiles.php' );

			if ( is_admin() ) {
				$eplsp_license = new EPL_License( __FILE__, EPL_LP_PRODUCT_NAME, EPL_LP_VER, 'Merv Barrett' );

				require_once( EPL_LP_PATH_INCLUDES . 'admin-functions.php' );
				require_once( EPL_LP_PATH_INCLUDES . 'meta-boxes-location-profiles.php' );

			} else {
				require_once( EPL_LP_PATH_INCLUDES . 'template-functions.php' );
				require_once( EPL_LP_PATH_INCLUDES . 'shortcodes-location-profiles.php' );
			}
		}

		/**
		* Add a flag that will allow to flush the rewrite rules when needed.
		*/
		function reset_permalinks() {
			if ( ! get_option( 'epl_lp_reset_permalinks' ) ) {
				add_option( 'epl_lp_reset_permalinks', true );
			}
		}

		/**
		* Flush rewrite rules if the previously added flag exists,
		* and then remove the flag.
		*/
		function reset_permalinks_maybe() {
			if ( get_option( 'epl_lp_reset_permalinks' ) ) {
				flush_rewrite_rules();
				delete_option( 'epl_lp_reset_permalinks' );
			}
		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 2.3
		 * @return void
		 */
		public function load_textdomain() {

			// Set filter for plugin's languages directory
			$epl_lang_dir = EPL_LP_PATH_LANGUAGES;
			$epl_lang_dir = apply_filters( 'epl_lp_languages_directory', $epl_lang_dir );

			// Traditional WordPress plugin locale filter
			$locale        = apply_filters( 'plugin_locale',  get_locale(), 'epl-location-profiles' );
			$mofile        = sprintf( '%1$s-%2$s.mo', 'epl-location-profiles', $locale );

			// Setup paths to current locale file
			$mofile_local  = $epl_lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/epl-location-profiles/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/epl folder
				load_textdomain( 'epl-location-profiles', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/easy-property-listings/languages/ folder
				load_textdomain( 'epl-location-profiles', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'epl-location-profiles', false, $epl_lang_dir );
			}
		}
	}
endif; // End if class_exists check
/*
 * The main function responsible for returning the one true EPL_Location_Profiles
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $epl = EPL_LP(); ?>
 *
 * @since 1.0
 * @return object The one true EPL_Location_Profiles Instance
 */
function EPL_LP() {
	return EPL_Location_Profiles::instance();
}
// Get EPL Running
EPL_LP();
