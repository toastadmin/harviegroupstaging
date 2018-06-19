<?php
/*
 * Plugin Name: EPL - Listing Templates
 * Plugin URL: https://easypropertylistings.com.au/extension/listing-templates/
 * Description: Adds Listing templates to Easy Property Listings
 * Version: 2.2.4
 * Author: Merv Barrett
 * Author URI: http://www.realestateconnected.com.au/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EPL_Listing_Templates' ) ) :
	/*
	 * Main EPL_Listing_Templates Class
	 *
	 * @since 1.0
	 */
	final class EPL_Listing_Templates {

		/*
		 * @var EPL_Listing_Templates The one true EPL_Listing_Templates
		 * @since 1.0
		 */
		private static $instance;

		/*
		 * Main EPL_Listing_Templates Instance
		 *
		 * Insures that only one instance of EPL_Listing_Templates exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses EPL_Listing_Templates::includes() Include the required files
		 * @see EPL_TM()
		 * @return The one true EPL_Listing_Templates
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_Listing_Templates ) ) {
				self::$instance = new EPL_Listing_Templates;
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
				_e( 'Please activate <b>Easy Property Listings</b> to enable all functions of EPL - Listing Templates', 'epl-listing-templates' );
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
			// Version
			if ( ! defined( 'EPL_TEMPLATES_VERSION' ) ) {
				define( 'EPL_TEMPLATES_VERSION', '2.2.4' );
			}
			// Extension name on API server
			if ( ! defined( 'EPL_TEMPLATES_PRODUCT_NAME' ) ) {
				define( 'EPL_TEMPLATES_PRODUCT_NAME', 'Listing Templates' );
			}
			// Plugin Folder URL
			if ( ! defined( 'EPL_TEMPLATES_URL' ) ) {
				define( 'EPL_TEMPLATES_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder CSS
			if ( ! defined( 'EPL_TEMPLATES_CSS' ) ) {
				define( 'EPL_TEMPLATES_CSS', EPL_TEMPLATES_URL . 'includes/css/' );
			}
			// Plugin Folder JS
			if ( ! defined( 'EPL_TEMPLATES_JS' ) ) {
				define( 'EPL_TEMPLATES_JS', EPL_TEMPLATES_URL . 'includes/js/' );
			}
			// Plugin Folder IMAGE
			if ( ! defined( 'EPL_TEMPLATES_IMG' ) ) {
				define( 'EPL_TEMPLATES_IMG', EPL_TEMPLATES_URL . 'includes/img/' );
			}

			// Plugin Folder Path
			if ( ! defined( 'EPL_TEMPLATES_PATH' ) ) {
				define( 'EPL_TEMPLATES_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'EPL_TEMPLATES_PATH_INCLUDES' ) ) {
				define( 'EPL_TEMPLATES_PATH_INCLUDES', EPL_TEMPLATES_PATH . 'includes/' );
			}

			// Languages Path
			if ( ! defined( 'EPL_TEMPLATES_PATH_LANGUAGES' ) ) {
				define( 'EPL_TEMPLATES_PATH_LANGUAGES', EPL_TEMPLATES_PATH . 'languages/' );
			}

			// Plugin Sub-Directory Templates
			if ( ! defined( 'EPL_TEMPLATES_PATH_TEMPLATES' ) ) {
				define( 'EPL_TEMPLATES_PATH_TEMPLATES', EPL_TEMPLATES_PATH . 'templates/' );
			}

			if ( ! defined( 'EPL_TEMPLATES_PATH_CONTENT' ) ) {
				define( 'EPL_TEMPLATES_PATH_CONTENT', EPL_TEMPLATES_PATH_TEMPLATES . 'content/' );
			}

		}

		/**
		* Add a flag that will allow to flush the rewrite rules when needed.
		*/
		function reset_permalinks() {
			if ( ! get_option( 'epl_lt_reset_permalinks' ) ) {
				add_option( 'epl_lt_reset_permalinks', true );
			}
		}

		/**
		* Flush rewrite rules if the previously added flag exists,
		* and then remove the flag.
		*/
		function reset_permalinks_maybe() {
			if ( get_option( 'epl_lt_reset_permalinks' ) ) {
				flush_rewrite_rules();
				delete_option( 'epl_lt_reset_permalinks' );
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

			require_once EPL_TEMPLATES_PATH_INCLUDES . 'common-functions.php';
			if ( is_admin() ) {
				$epltempl_license = new EPL_License( __FILE__, EPL_TEMPLATES_PRODUCT_NAME, EPL_TEMPLATES_VERSION, 'Merv Barrett' );
				require_once EPL_TEMPLATES_PATH_INCLUDES . 'admin-functions.php';
			} else {
				require_once EPL_TEMPLATES_PATH_INCLUDES . 'template-functions.php';
			}
		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 2.2.1
		 * @return void
		 */
		public function load_textdomain() {

			// Set filter for plugin's languages directory
			$epl_lang_dir = EPL_TEMPLATES_PATH_LANGUAGES;
			$epl_lang_dir = apply_filters( 'epl_lt_languages_directory', $epl_lang_dir );

			// Traditional WordPress plugin locale filter
			$locale        = apply_filters( 'plugin_locale',  get_locale(), 'epl-listing-templates' );
			$mofile        = sprintf( '%1$s-%2$s.mo', 'epl-listing-templates', $locale );

			// Setup paths to current locale file
			$mofile_local  = $epl_lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/epl-listing-templates/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/epl folder
				load_textdomain( 'epl-listing-templates', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/easy-property-listings/languages/ folder
				load_textdomain( 'epl-listing-templates', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'epl-listing-templates', false, $epl_lang_dir );
			}
		}
	}
endif; // End if class_exists check
/*
 * The main function responsible for returning the one true EPL_Listing_Templates
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $epl = EPL_LT(); ?>
 *
 * @since 1.0
 * @return object The one true EPL_Listing_Templates Instance
 */
function EPL_LT() {
	return EPL_Listing_Templates::instance();
}
// Get EPL_LT Running
EPL_LT();
