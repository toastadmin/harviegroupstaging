<?php
/*
 * Plugin Name: EPL - Staff Directory
 * Plugin URL: https://easypropertylistings.com.au/extension/staff-directory/
 * Description: Adds Staff and Agent management directory post type, widgets and shortcodes to Easy Property Listings
 * Version: 2.3
 * Author: Merv Barrett
 * Author URI: http://www.realestateconnected.com.au
 * Contributors: Merv Barrett
 * Text Domain: epl-staff-directory
 * Domain Path: /languages
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EPL_Staff_Directory' ) ) :
	/*
	 * Main EPL_Staff_Directory Class
	 *
	 * @since 1.0
	 */
	final class EPL_Staff_Directory {

		/*
		 * @var EPL_Staff_Directory The one true EPL_Staff_Directory
		 * @since 1.0
		 */
		private static $instance;

		/*
		 * Main EPL_Staff_Directory Instance
		 *
		 * Insures that only one instance of EPL_Staff_Directory exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses EPL_Staff_Directory::includes() Include the required files
		 * @see EPL()
		 * @return The one true EPL_Staff_Directory
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_Staff_Directory ) ) {
				self::$instance = new EPL_Staff_Directory;
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

			if ( ! 	defined('EPL_RUNNING') ) {
				echo '<div class="error"><p>';
				_e( 'Please activate <b>Easy Property Listings</b> to enable all functions of EPL - Staff Directory', 'epl-staff-directory' );
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
				define( 'EPL_TEMPLATES', 'http://easypropertylistings.com.au' );
			}

			// Extension name on API server
			if ( ! defined( 'EPL_SD_PRODUCT_NAME' ) ) {
				define( 'EPL_SD_PRODUCT_NAME', 'Staff Directory' );
			}
			// Extension version
			if ( ! defined( 'EPL_SD_VER' ) ) {
				define( 'EPL_SD_VER', '2.3' );
			}
			// Plugin File
			if ( ! defined( 'EPL_SD_PLUGIN_FILE' ) ) {
				define( 'EPL_SD_PLUGIN_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'EPL_SD_PLUGIN_URL' ) ) {
				define( 'EPL_SD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'EPL_SD_PLUGIN_PATH' ) ) {
				define( 'EPL_SD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'EPL_SD_PATH_INCLUDES' ) ) {
				define( 'EPL_SD_PATH_INCLUDES', EPL_SD_PLUGIN_PATH . 'includes/' );
			}

			if ( ! defined( 'EPL_SD_PATH_LANGUAGES' ) ) {
				define( 'EPL_SD_PATH_LANGUAGES', EPL_SD_PLUGIN_PATH . 'languages/' );
			}

			if ( ! defined( 'EPL_SD_PATH_TEMPLATES' ) ) {
				define( 'EPL_SD_PATH_TEMPLATES', EPL_SD_PLUGIN_PATH . 'templates/' );
			}

			if ( ! defined( 'EPL_SD_PATH_TEMPLATES_CONTENT' ) ) {
				define( 'EPL_SD_PATH_TEMPLATES_CONTENT', EPL_SD_PATH_TEMPLATES . 'content/' );
			}

			if ( ! defined( 'EPL_SD_CSS_URL' ) ) {
				define( 'EPL_SD_CSS_URL', EPL_SD_PLUGIN_URL . 'includes/css/' );
			}
			if ( ! defined( 'EPL_SD_IMAGES_URL' ) ) {
				define( 'EPL_SD_IMAGES_URL', EPL_SD_PLUGIN_URL . 'includes/images/' );
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

			require_once( EPL_SD_PATH_INCLUDES . 'install.php' );
			require_once( EPL_SD_PATH_INCLUDES . 'post-type-directory.php' );
			require_once( EPL_SD_PATH_INCLUDES . 'tax-department.php' );

			if( class_exists( 'Easy_Property_Listings' ) ) {
				require_once( EPL_SD_PATH_INCLUDES . 'widget-advanced-author.php' );
				require_once( EPL_SD_PATH_INCLUDES . 'widget-search-agent-hook.php' );
			}

			if ( is_admin() ) {
				$eplsd_license = new EPL_License( __FILE__, EPL_SD_PRODUCT_NAME, EPL_SD_VER, 'Merv Barrett' );
				require_once( EPL_SD_PATH_INCLUDES . 'admin-functions.php' );
				require_once( EPL_SD_PATH_INCLUDES . 'meta-boxes-directory.php' );
			} else {
				require_once( EPL_SD_PATH_INCLUDES . 'template-functions.php' );
				require_once( EPL_SD_PATH_INCLUDES . 'vcard.php' );
				require_once( EPL_SD_PATH_INCLUDES . 'shortcodes-directory.php' );
			}
		}

		/**
		* Add a flag that will allow to flush the rewrite rules when needed.
		*/
		function reset_permalinks() {
			if ( ! get_option( 'epl_sd_reset_permalinks' ) ) {
				add_option( 'epl_sd_reset_permalinks', true );
			}
		}

		/**
		* Flush rewrite rules if the previously added flag exists,
		* and then remove the flag.
		*/
		function reset_permalinks_maybe() {
			if ( get_option( 'epl_sd_reset_permalinks' ) ) {
				flush_rewrite_rules();
				delete_option( 'epl_sd_reset_permalinks' );
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
			$epl_lang_dir = EPL_SD_PATH_LANGUAGES;
			$epl_lang_dir = apply_filters( 'epl_sd_languages_directory', $epl_lang_dir );

			// Traditional WordPress plugin locale filter
			$locale        = apply_filters( 'plugin_locale',  get_locale(), 'epl-staff-directory' );
			$mofile        = sprintf( '%1$s-%2$s.mo', 'epl-staff-directory', $locale );

			// Setup paths to current locale file
			$mofile_local  = $epl_lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/epl-staff-directory/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/epl folder
				load_textdomain( 'epl-staff-directory', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/easy-property-listings/languages/ folder
				load_textdomain( 'epl-staff-directory', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'epl-staff-directory', false, $epl_lang_dir );
			}
		}
	}
endif; // End if class_exists check
/*
 * The main function responsible for returning the one true EPL_Staff_Directory
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $epl = EPL_SD(); ?>
 *
 * @since 1.0
 * @return object The one true EPL_Staff_Directory Instance
 */
function EPL_SD() {
	return EPL_Staff_Directory::instance();
}
// Get EPL Running
EPL_SD();
