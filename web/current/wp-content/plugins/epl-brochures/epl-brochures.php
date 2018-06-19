<?php
/*
Plugin Name: EPL - Brochures
Plugin URL: https://easypropertylistings.com.au/extension/brochures/
Description: Adds a printable brochure button to your properties in Easy Property Listings. Also you can print out brochure stock lists.
Version: 1.3.1
Author: Merv Barrett
Author URI: http://realestateconnected.com.au/
Contributors: mervb
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'EPL_Brochures' ) ) :
	/*
	 * Main EPL_Brochures Class
	 *
	 * @since 1.0
	 */
	final class EPL_Brochures {

		/*
		 * @var EPL_Brochures The one true EPL_Brochures
		 * @since 1.0
		 */
		private static $instance;

		/*
		 * Main EPL_Brochures Instance
		 *
		 * Insures that only one instance of EPL_Brochures exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses EPL_Brochures::includes() Include the required files
		 * @see EPL_BR()
		 * @return The one true EPL_Brochures
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_Brochures ) ) {
				self::$instance = new EPL_Brochures;
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
			add_action('epl_buttons_single_property', array( $this, 'epl_button_brochure') );
			add_action('epl_buttons_loop_property', array( $this, 'epl_button_brochure') );
			add_action('wp', array( $this, 'generate_brochure') );
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
				_e( 'Please activate <b>Easy Property Listings</b> to enable all functions of EPL - Brochures', 'epl-br' );
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
			// Plugin version
			if ( ! defined( 'EPL_BR_VER' ) ) {
				define( 'EPL_BR_VER', '1.3.1' );
			}

			// Extension name on API server
			if ( ! defined( 'EPL_BR_PRODUCT_NAME' ) ) {
				define( 'EPL_BR_PRODUCT_NAME', 'Brochures' );
			}

			// API URL
			if ( ! defined( 'EPL_TEMPLATES' ) ) {
				define( 'EPL_TEMPLATES', 'http://easypropertylistings.com.au' );
			}

			// Plugin File
			if ( ! defined( 'EPL_BR_PLUGIN_FILE' ) ) {
				define( 'EPL_BR_PLUGIN_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'EPL_BR_PLUGIN_URL' ) ) {
				define( 'EPL_BR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'EPL_BR_PLUGIN_PATH' ) ) {
				define( 'EPL_BR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'EPL_BR_PLUGIN_PATH_INCLUDES' ) ) {
				define( 'EPL_BR_PLUGIN_PATH_INCLUDES', EPL_BR_PLUGIN_PATH . 'includes/' );
			}

			// Plugin shortcode Paths
			if ( ! defined( 'EPL_BR_PLUGIN_PATH_SHORTCODES' ) ) {
				define( 'EPL_BR_PLUGIN_PATH_SHORTCODES', EPL_BR_PLUGIN_PATH . 'shortcodes/' );
			}

			// Plugin templates Paths
			if ( ! defined( 'EPL_BR_PLUGIN_PATH_TEMPLATES' ) ) {
				define( 'EPL_BR_PLUGIN_PATH_TEMPLATES', EPL_BR_PLUGIN_PATH . 'templates/' );
			}

			// CSS
			if ( ! defined( 'EPL_BR_CSS' ) ) {
				define( 'EPL_BR_CSS', EPL_BR_PLUGIN_URL . 'assets/css/' );
			}

			// CSS from active theme
			if ( ! defined( 'EPL_BR_THEME_CSS' ) ) {
				define( 'EPL_BR_THEME_CSS', get_stylesheet_directory_uri() . '/style.css' );
			}

			// Custom CSS from child theme
			if ( ! defined( 'EPL_BR_CUSTOM_CSS' ) ) {
				define( 'EPL_BR_CUSTOM_CSS', get_stylesheet_directory() . '/easypropertylistings/css/' );
			}

			// Custom CSS URL from child theme
			if ( ! defined( 'EPL_BR_CUSTOM_CSS_URL' ) ) {
				define( 'EPL_BR_CUSTOM_CSS_URL', get_stylesheet_directory_uri() . '/easypropertylistings/css/' );
			}

			// CSS Version
			if ( ! defined( 'EPL_BR_CSS_VERSION' ) ) {
				define( 'EPL_BR_CSS_VERSION', '?version=' . EPL_BR_VER );
			}

			// CSS EPL Core Version
			if ( ! defined( 'EPL_BR_CSS_VERSION_CORE' ) ) {
				define( 'EPL_BR_CSS_VERSION_CORE', '?version=' . EPL_BR_VER );
			}

			// Core CSS fallback
			if ( ! defined( 'EPL_BR_CSS_STRUCTURE' ) ) {
				define( 'EPL_BR_CSS_STRUCTURE', EPL_BR_CSS );
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
				$eplbr_license = new EPL_License( __FILE__, EPL_BR_PRODUCT_NAME, EPL_BR_VER , 'Merv Barrett' );
				require_once EPL_BR_PLUGIN_PATH_INCLUDES . 'hooks.php';
				require_once EPL_BR_PLUGIN_PATH_INCLUDES . 'install.php';

			} else {
				require_once( EPL_BR_PLUGIN_PATH_INCLUDES . 'brochure-functions.php' );
				require_once( EPL_BR_PLUGIN_PATH_SHORTCODES . 'shortcode-brochure.php' );
				require_once( EPL_BR_PLUGIN_PATH_SHORTCODES . 'shortcode-brochures-list.php' );
			}
		}

		function epl_button_brochure() {
			global $property, $epl_settings;
			$label =  isset($epl_settings['epl_br_button_label']) ? $epl_settings['epl_br_button_label'] : __('Brochure', 'epl-br');
		?>

			 <div class="epl-button button-br">
				<a href="<?php echo get_bloginfo('url').'?epl_br_action=generate&id='.$property->post->ID ?>" id="EPL_Brochures_button" target="_blank" rel="nofollow">
					<?php echo $label; ?>
				</a>
			</div> <?php
		}

		function generate_brochure() {
			global $post;

			/** generate brochures on single listings **/
			if(isset($_REQUEST['epl_br_action']) && $_REQUEST['epl_br_action'] == 'generate' && isset($_REQUEST['id']) && intval($_REQUEST['id']) != 0) {
				global $epl_settings;
				$banner = isset($epl_settings['epl_br_header_logo']) ? $epl_settings['epl_br_header_logo'] : '';
				$id = intval($_REQUEST['id']);
				ob_start();
				include(EPL_BR_PLUGIN_PATH_TEMPLATES.'view.php');
				echo ob_get_clean();
				die;
			}

			/** generate brochures based on shortcode config **/
			if( isset($_REQUEST['epl_br_action']) && $_REQUEST['epl_br_action'] == 'generate_list' ) {
				global $epl_settings;

				// Remove Default EPL Icons for Printing
				remove_action( 'epl_property_icons' , 'epl_property_icons' );
				add_action('epl_property_icons','epl_br_property_icons');

				$banner = isset($epl_settings['epl_br_header_logo']) ? $epl_settings['epl_br_header_logo'] : '';
				ob_start();
				include(EPL_BR_PLUGIN_PATH_TEMPLATES.'view-list.php');
				echo ob_get_clean();
				die;
			}
		}

	}
endif; // End if class_exists check
/*
 * The main function responsible for returning the one true EPL_Brochures
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $epl = EPL_BR(); ?>
 *
 * @since 1.0
 * @return object The one true EPL_Brochures Instance
 */
function EPL_BR() {
	return EPL_Brochures::instance();
}
// Get EPL_BR Running
EPL_BR();
