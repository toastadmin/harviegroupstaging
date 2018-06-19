<?php
/*
 * Plugin Name: EPL - Frontend Submissions
 * Plugin URL: http://easypropertylistings.com.au/extension/frontend-submissions/
 * Description: Adds Frontend form submissions to Easy Property Listings
 * Version: 1.0.1
 * Author: Merv Barrett
 * Author URI: http://www.realestateconnected.com.au
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'EPL_Frontend_Submissions' ) ) :
	/*
	 * Main EPL_Frontend_Submissions Class
	 *
	 * @since 1.0
	 */
	final class EPL_Frontend_Submissions {
		
		/*
		 * @var EPL_Frontend_Submissions The one true EPL_Frontend_Submissions
		 * @since 1.0
		 */
		private static $instance;
	
		/*
		 * Main EPL_Frontend_Submissions Instance
		 *
		 * Insures that only one instance of EPL_Frontend_Submissions exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses EPL_Frontend_Submissions::includes() Include the required files
		 * @see EPL_FS()
		 * @return The one true EPL_Frontend_Submissions
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_Frontend_Submissions ) ) {
				self::$instance = new EPL_Frontend_Submissions;
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

			if ( ! 	defined('EPL_RUNNING') ) {
				echo '<div class="error"><p>';
				_e( 'Please activate <b>Easy Property Listings</b> to enable all functions of EPL - Frontend Submission', 'epl' );
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
			if ( ! defined( 'EPL_FS_PRODUCT_NAME' ) ) {
				define( 'EPL_FS_PRODUCT_NAME', 'Frontend Submissions' );
			}
			
			// Plugin File
			if ( ! defined( 'EPL_FS_PLUGIN_FILE' ) ) {
				define( 'EPL_FS_PLUGIN_FILE', __FILE__ );
			}
			
			// Plugin Folder URL
			if ( ! defined( 'EPL_FS_PLUGIN_URL' ) ) {
				define( 'EPL_FS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
			
			// Plugin Folder Path
			if ( ! defined( 'EPL_FS_PLUGIN_PATH' ) ) {
				define( 'EPL_FS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}
			
			// Plugin Sub-Directory Paths
			if ( ! defined( 'EPL_FS_PLUGIN_PATH_INCLUDES' ) ) {
				define( 'EPL_FS_PLUGIN_PATH_INCLUDES', EPL_FS_PLUGIN_PATH . 'includes/' );
			}
			
			// Assets Directory Path
			if ( ! defined( 'EPL_FS_PLUGIN_PATH_ASSETS' ) ) {
				define( 'EPL_FS_PLUGIN_PATH_ASSETS', EPL_FS_PLUGIN_PATH . 'assets/' );
			}
			
			// Assets Directory URL
			if ( ! defined( 'EPL_FS_PLUGIN_URL_ASSETS' ) ) {
				define( 'EPL_FS_PLUGIN_URL_ASSETS', EPL_FS_PLUGIN_URL . 'assets/' );
			}
			
			// Images Directory Paths
			if ( ! defined( 'EPL_FS_PLUGIN_URL_IMAGES' ) ) {
				define( 'EPL_FS_PLUGIN_URL_IMAGES', EPL_FS_PLUGIN_URL_ASSETS . 'images/' );
			}
			
			// CSS Directory Paths
			if ( ! defined( 'EPL_FS_PLUGIN_URL_CSS' ) ) {
				define( 'EPL_FS_PLUGIN_URL_CSS', EPL_FS_PLUGIN_URL_ASSETS . 'css/' );
			}
			
			// JS Directory Paths
			if ( ! defined( 'EPL_FS_PLUGIN_URL_JS' ) ) {
				define( 'EPL_FS_PLUGIN_URL_JS', EPL_FS_PLUGIN_URL_ASSETS . 'js/' );
			}
			
			global $wpdb;
			
			// Plugin DB Tables
			if ( ! defined( 'EPL_FS_LOGS_TABLE' ) ) {
				define( 'EPL_FS_LOGS_TABLE', $wpdb->prefix . 'EPL_FS_logs' );
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

			// require epl core meta box file so that we can render meta box on front end
			require_once EPL_PATH_LIB . 'meta-boxes/meta-boxes.php';
			include_once( EPL_FS_PLUGIN_PATH_INCLUDES . 'functions.php' );
			include_once( EPL_FS_PLUGIN_PATH_ASSETS . 'assets.php' );
			include_once( EPL_FS_PLUGIN_PATH_INCLUDES . 'shortcodes.php' );
			include_once( EPL_FS_PLUGIN_PATH_INCLUDES . 'hooks.php' );
			
			if ( is_admin() ) {
				$eplfront_license = new EPL_License( __FILE__, EPL_FS_PRODUCT_NAME, '1.0.1', 'Merv Barrett' );
			}
			
			include_once( EPL_FS_PLUGIN_PATH_INCLUDES . 'install.php' );
		}
	}
endif; // End if class_exists check

/*
 * The main function responsible for returning the one true EPL_Frontend_Submissions
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $epl = EPL_FS(); ?>
 *
 * @since 1.0
 * @return object The one true EPL_Frontend_Submissions Instance
 */
function EPL_FS() {
	@session_start();
	return EPL_Frontend_Submissions::instance();
}
// Get EPL_FS Running
EPL_FS();
