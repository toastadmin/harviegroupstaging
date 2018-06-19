<?php
/**
 * Install Functions
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add default settings to EPL - Location Profiles upon activation
 *
 * @since	2.1.0
 */
function epl_lp_install() {

	global $wpdb, $epl_options;

	$epl_settings = epl_settings();

	$new_fields_defaults = array(
		'epl_lp_post_type_name'		=>	__( 'Suburb' , 'epl-location-profiles' ),
		'epl_lp_single_tab_orientation'	=>	1,
		'epl_lp_single_author_box'	=>	1,
		'epl_lp_single_gallery'		=>	1,
		'epl_lp_single_tabbed_info'	=>	1,
		'epl_lp_single_map'		=>	1,

		'epl_lp_label_tab_list_name'	=>	__( 'Fast Facts' , 'epl-location-profiles' ),
		/*
		'epl_lp_label_list_item_1'	=>	__( 'Number of properties' , 'epl-location-profiles' ),
		'epl_lp_label_list_item_2'	=>	__( 'Growth Rate' , 'epl-location-profiles' ),
		'epl_lp_label_list_item_3'	=>	__( 'Number of Schools' , 'epl-location-profiles' ),
		'epl_lp_label_list_item_4'	=>	__( 'Population' , 'epl-location-profiles' ),
		'epl_lp_label_list_item_5'	=>	__( 'Investor type' , 'epl-location-profiles' ),

		'epl_lp_label_tab_0'		=>	__( 'Shopping' , 'epl-location-profiles' ),
		'epl_lp_label_tab_1'		=>	__( 'Property Facts' , 'epl-location-profiles' ),
		'epl_lp_label_tab_2'		=>	__( 'Education' , 'epl-location-profiles' ),
		'epl_lp_label_tab_3'		=>	__( 'Facilities' , 'epl-location-profiles' ),
		'epl_lp_label_tab_4'		=>	__( 'History' , 'epl-location-profiles' ),
		'epl_lp_label_tab_5'		=>	__( 'Our Service' , 'epl-location-profiles' ),
		*/

	);

	foreach($new_fields_defaults as $key	=>	$value) {
		if(!isset($epl_settings[$key])) {
			$epl_settings[$key] = $value;
		}
	}
	update_option( 'epl_settings', $epl_settings );
}
register_activation_hook( EPL_LP_PLUGIN_FILE, 'epl_lp_install' );

/**
 * Delete plugin settings
 *
 * @since	2.1.0
 */
function epl_lp_uninstall() {

	global $wpdb, $epl_options;

	$epl_settings = epl_settings();

	$new_fields_defaults = array(
		'epl_lp_post_type_name'		=>	__( 'Suburb' , 'epl-location-profiles' ),
		'epl_lp_single_tab_orientation'	=>	1,
		'epl_lp_single_author_box'	=>	1,
		'epl_lp_single_gallery'		=>	1,
		'epl_lp_single_tabbed_info'	=>	1,
		'epl_lp_single_map'		=>	1,

		'epl_lp_label_tab_list_name'	=>	__( 'Fast Facts' , 'epl-location-profiles' ),
		/*
		'epl_lp_label_list_item_1'	=>	__( 'Number of properties' , 'epl-location-profiles' ),
		'epl_lp_label_list_item_2'	=>	__( 'Growth Rate' , 'epl-location-profiles' ),
		'epl_lp_label_list_item_3'	=>	__( 'Number of Schools' , 'epl-location-profiles' ),
		'epl_lp_label_list_item_4'	=>	__( 'Population' , 'epl-location-profiles' ),
		'epl_lp_label_list_item_5'	=>	__( 'Investor type' , 'epl-location-profiles' ),

		'epl_lp_label_tab_0'		=>	__( 'Shopping' , 'epl-location-profiles' ),
		'epl_lp_label_tab_1'		=>	__( 'Property Facts' , 'epl-location-profiles' ),
		'epl_lp_label_tab_2'		=>	__( 'Education' , 'epl-location-profiles' ),
		'epl_lp_label_tab_3'		=>	__( 'Facilities' , 'epl-location-profiles' ),
		'epl_lp_label_tab_4'		=>	__( 'History' , 'epl-location-profiles' ),
		'epl_lp_label_tab_5'		=>	__( 'Our Service' , 'epl-location-profiles' ),
		*/
	);

	foreach($new_fields_defaults as $key	=>	&$value) {
		if(isset($epl_settings[$key])) {
			unset($epl_settings[$key]);
		}
	}
	update_option( 'epl_settings', $epl_settings );
}
if( function_exists( 'epl_get_option' ) && epl_get_option( 'uninstall_on_delete' ) == 1 ) {
	register_deactivation_hook( EPL_LP_PLUGIN_FILE, 'epl_lp_uninstall' );
}