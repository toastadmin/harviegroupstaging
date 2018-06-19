<?php
/*
Plugin Name: EPL - Inspect Real Estate
Plugin URL: http://easypropertylistings.com.au/extension/inspect-real-estate
Description: Adds a Inspect Real Estate button to Easy Property Listings
Version: 2.0.1
Author: Merv Barrett
Author URI: http://realestateconnected.com.au/
Contributors: mervb
*/
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'EPL_Inspect_Real_Estate' ) ) :
	/*
	 * Main EPL_Inspect_Real_Estate Class
	 *
	 * @since 1.0
	 */
	final class EPL_Inspect_Real_Estate {
		
		/*
		 * @var EPL_Inspect_Real_Estate The one true EPL_Inspect_Real_Estate
		 * @since 1.0
		 */
		private static $instance;
		
		/*
		 * Main EPL_Inspect_Real_Estate Instance
		 *
		 * Insures that only one instance of EPL_Inspect_Real_Estate exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses EPL_Inspect_Real_Estate::includes() Include the required files
		 * @see EPL_TM()
		 * @return The one true EPL_Inspect_Real_Estate
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_Inspect_Real_Estate ) ) {
				self::$instance = new EPL_Inspect_Real_Estate;
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
			add_action('epl_buttons_single_property', array( $this, 'epl_button_inspect_real_estate') );
			add_action('epl_buttons_loop_property', array( $this, 'epl_button_inspect_real_estate') );
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
				_e( 'Please activate <b>Easy Property Listings</b> to enable all functions of EPL - Inspect Real Estate', 'epl' );
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
			if ( ! defined( 'EPL_IRE_PRODUCT_NAME' ) ) {
				define( 'EPL_IRE_PRODUCT_NAME', 'Inspect Real Estate' );
			}
			
			// Plugin File
			if ( ! defined( 'EPL_IRE_PLUGIN_FILE' ) ) {
				define( 'EPL_IRE_PLUGIN_FILE', __FILE__ );
			}
			
			// Plugin Folder URL
			if ( ! defined( 'EPL_IRE_PLUGIN_URL' ) ) {
				define( 'EPL_IRE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
			
			// Plugin Folder Path
			if ( ! defined( 'EPL_IRE_PLUGIN_PATH' ) ) {
				define( 'EPL_IRE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}
			
			// Plugin Sub-Directory Paths
			if ( ! defined( 'EPL_IRE_PLUGIN_PATH_INCLUDES' ) ) {
				define( 'EPL_IRE_PLUGIN_PATH_INCLUDES', EPL_IRE_PLUGIN_PATH . 'includes/' );
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
				$eplir_license = new EPL_License( __FILE__, EPL_IRE_PRODUCT_NAME, '2.0.1', 'Merv Barrett' );
				require_once EPL_IRE_PLUGIN_PATH_INCLUDES . 'hooks.php';
			}
		}
		
		function epl_button_inspect_real_estate() {

			global $property,$epl_settings;
			
			$status = get_property_meta('property_status');
			
			// Status Removal Do Not Display Withdrawn or OffMarket listings
			if ( $status == 'sold' || $status == 'leased' ) {
				// Do Not Display on Sold or Leased listings
			} else {
				
				// Agent Account Name
				$agent_account 		= isset($epl_settings['epl_ire_account'])?$epl_settings['epl_ire_account']:'';
				$agent_account_id 	= isset($epl_settings['epl_ire_account_id'])?$epl_settings['epl_ire_account_id']:'';
				
				$full_street 		= urlencode(epl_property_get_the_full_address());
				$thumbnail_src 		= wp_get_attachment_image_src( get_post_thumbnail_id( $property->post->ID ), 'thumbnail' );
				
				$property_id		= $property->get_property_meta('property_unique_id'); 
				
				// Set Post Type
				if ( 'rental' == get_post_type() ) {
					$inspect_type = 'rental'; 
				} else {
					$inspect_type = 'sale'; 
				}

				if ( $agent_account_id != '' ) {
			
				/**
				*	Inspect Real Estate Fields
				*
				*	agentID 		= REAXML unique usually prefixed with provider key. "agent Point" = AP12345
				*	address 		= url encoded address
				*	uniqueID		= REAXML Property unique ID
				*	AgentAccountName	= Agency login name. johnsmith
				*	type			= Listing type. rental, sale
				*	imgURL			= URL encoded link to image
				*
				**/ 
				?>
					<div class="epl-button button-inspect-re">
						<form action="http://www.inspectrealestate.com.au/RegisterOnline/Register.aspx?agentID=<?php echo $agent_account_id; ?>&uniqueID=<?php echo $property_id; ?>&imgURL=<?php echo urlencode($thumbnail_src[0]); ?>" method="post" target="_blank">
							<input type="submit" value="Book Inspection" />
						</form>
					</div>
				<?php
				
				} elseif( $agent_account != '' ) {

					?>
					<div class="epl-button button-inspect-re">
						<form action="http://www.inspectrealestate.com.au/RegisterOnline/Register.aspx?AgentAccountName=<?php echo $agent_account; ?>&address=<?php echo $full_street; ?>&type=<?php echo $inspect_type; ?>&imgURL=<?php echo urlencode($thumbnail_src[0]); ?>" method="post" target="_blank">
							<input type="submit" value="Book Inspection" />
						</form>
					</div>
					<?php
						
						
				} else {
					// if account id not set do not display button
				}
			} 
		}
	}
endif; // End if class_exists check
/*
 * The main function responsible for returning the one true EPL_Inspect_Real_Estate
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $epl = EPL_IRE(); ?>
 *
 * @since 1.0
 * @return object The one true EPL_Inspect_Real_Estate Instance
 */
function EPL_IRE() {
	return EPL_Inspect_Real_Estate::instance();
}
// Get EPL_IRE Running
EPL_IRE();
