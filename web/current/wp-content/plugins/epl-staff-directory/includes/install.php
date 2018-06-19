<?php
/**
 * Install and Uninstall Functions
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add default settings to Staff Directory upon activation
 *
 * @since 2.2
 */
function epl_sd_install() {

	global $wpdb, $epl_options;

	$epl_settings = epl_settings();

	$new_fields_defaults = array(
		'epl_sd_post_type_slug'			=>	__('Directory','epl-staff-directory'),
		'epl_sd_staff_author_box_type'		=>	0,
		'epl_sd_staff_read_more'		=>	__('Read More','epl-staff-directory'),
		'epl_sd_staff_image_type'		=>	1,
		'epl_sd_staff_image_size_box'		=>	'thumbnail',
		'epl_sd_link_to'			=>	1,
		'epl_sd_recent_listings_tab_count'	=>	4,
		'epl_sd_staff_single_style'		=>	0,
		'epl_sd_single_listing_count'		=>	4,

		'epl_sd_listing_template'		=>	'blog',
		'epl_sd_show_sold_leased'		=>	'yes',
		'epl_sd_single_leased_listing_count'	=>	4,
		'epl_sd_single_recent_posts_display'	=>	'yes',
		'epl_sd_single_recent_posts_count'	=>	4,

		'epl_sd_archive_per_page'		=>	20,
		'epl_sd_archive_style'			=>	'list',
		'epl_sd_grid_archive_cols'		=>	3,
		'epl_sd_staff_image_size_loop'		=>	'thumbnail',
		'epl_sd_archive_excerpt'		=>	1,
		'epl_sd_archive_show_position'		=>	1,
		'epl_sd_archive_show_mobile'		=>	1,
		'epl_sd_archive_show_icons'		=>	1,
		'epl_sd_archive_show_vcard'		=>	1,
	);

	foreach($new_fields_defaults as $key	=>	$value) {
		if(!isset($epl_settings[$key])) {
			$epl_settings[$key] = $value;
		}
	}
	update_option( 'epl_settings', $epl_settings );
}
register_activation_hook( EPL_SD_PLUGIN_FILE, 'epl_sd_install' );

/**
 * Delete plugin settings
 *
 * @since 2.2
 */
function epl_sd_uninstall() {

	global $wpdb, $epl_options;

	$epl_settings = epl_settings();

	$new_fields_defaults = array(
		'epl_sd_post_type_slug'			=>	__('Directory','epl-staff-directory'),
		'epl_sd_staff_author_box_type'		=>	0,
		'epl_sd_staff_read_more'		=>	__('Read More','epl-staff-directory'),
		'epl_sd_staff_image_type'		=>	1,
		'epl_sd_staff_image_size_box'		=>	'thumbnail',
		'epl_sd_link_to'			=>	1,
		'epl_sd_recent_listings_tab_count'	=>	4,
		'epl_sd_staff_single_style'		=>	0,
		'epl_sd_single_listing_count'		=>	4,

		'epl_sd_listing_template'		=>	'blog',
		'epl_sd_show_sold_leased'		=>	'yes',
		'epl_sd_single_leased_listing_count'	=>	4,
		'epl_sd_single_recent_posts_display'	=>	'yes',
		'epl_sd_single_recent_posts_count'	=>	4,

		'epl_sd_archive_per_page'		=>	20,
		'epl_sd_archive_style'			=>	'list',
		'epl_sd_grid_archive_cols'		=>	3,
		'epl_sd_staff_image_size_loop'		=>	'thumbnail',
		'epl_sd_archive_excerpt'		=>	1,
		'epl_sd_archive_show_position'		=>	1,
		'epl_sd_archive_show_mobile'		=>	1,
		'epl_sd_archive_show_icons'		=>	1,
		'epl_sd_archive_show_vcard'		=>	1,
	);

	foreach($new_fields_defaults as $key	=>	&$value) {
		if(isset($epl_settings[$key])) {
			unset($epl_settings[$key]);
		}
	}
	update_option( 'epl_settings', $epl_settings );
}
/** Only Remove Extension Settings if EPL core is set to uninstall **/
if( function_exists( 'epl_get_option' ) && epl_get_option( 'uninstall_on_delete' ) == 1 ) {
	register_deactivation_hook( EPL_SD_PLUGIN_FILE, 'epl_sd_uninstall' );
}