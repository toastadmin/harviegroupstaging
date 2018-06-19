<?php
/**
 * Install Functions
 *
 * @package     EPL-SLIDERS
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Set Settings on Plugin Install
 *
 * @since 1.0
 */
function epl_slider_install() {

	global $wpdb, $epl_options;

	$epl_settings = epl_settings();

	$new_fields_defaults =  array(
		'epl_slider_width'			=>	800,
		'epl_slider_height'			=>	600,
		'epl_slider_feature_image'		=>	true,

		'epl_slider_animationSpeed'		=>	1200,
		'epl_slider_reverseorder'		=>	'false',
		'epl_slider_popup'			=>	'false',
		'epl_slider_single_price_sticker'	=>	'true',

		'epl_slider_controlNav'			=>	1,
		'epl_slider_keyboard'			=>	'true',
		'epl_slider_arrow_style'		=>	'a17.png',

		'epl_slider_use_thumbnails'		=>	2,
		'epl_slider_thumb_orientation'		=>	1,
		'epl_slider_thumb_lanes'		=>	1,
		'epl_display_pieces'			=>	6,
		'epl_slider_thumb_width'		=>	120,
		'epl_slider_thumb_height'		=>	120,
		'epl_slider_spacingx'			=>	14,
		'epl_slider_spacingy'			=>	12,

		'epl_slider_slideshow'			=>	'true',
		'epl_slider_slideshowSpeed'		=>	5000,
		'epl_slider_transition'			=>	'fade_in_l',
		'epl_slider_pauseOnHover'		=>	1,

		'epl_slider_thumb_on_handheld'		=>	0,
		'epl_slider_width_mobile'		=>	400,
		'epl_slider_height_mobile'		=>	300,
		'epl_allow_swipe'			=>	3,

		'epl_slider_enable_archive'		=>	0,
		'epl_slider_archive_image_size'		=>	'epl-image-medium-crop',
		'epl_slider_archive_wrapper_width'	=>	300,
		'epl_slider_archive_wrapper_height'	=>	200,
		'epl_slider_archive_price_sticker'	=>	'true',
	);

	foreach($new_fields_defaults as $key	=>	$value) {
		if(!isset($epl_settings[$key])) {
			$epl_settings[$key] = $value;
		}
	}
	update_option( 'epl_settings', $epl_settings );
}
register_activation_hook( EPL_SLIDER_PLUGIN_FILE, 'epl_slider_install' );

/**
 * Delete plugin settings
 *
 * @since 1.1
 */
function epl_slider_uninstall() {

	global $wpdb, $epl_options;

	$epl_settings = epl_settings();

	$new_fields_defaults = array(
		'epl_slider_width'			=>	800,
		'epl_slider_height'			=>	600,
		'epl_slider_feature_image'		=>	true,

		'epl_slider_animationSpeed'		=>	1200,
		'epl_slider_reverseorder'		=>	'false',
		'epl_slider_popup'			=>	'false',
		'epl_slider_single_price_sticker'	=>	'true',

		'epl_slider_controlNav'			=>	1,
		'epl_slider_keyboard'			=>	'true',
		'epl_slider_arrow_style'		=>	'a17.png',

		'epl_slider_use_thumbnails'		=>	2,
		'epl_slider_thumb_orientation'		=>	1,
		'epl_slider_thumb_lanes'		=>	1,
		'epl_display_pieces'			=>	6,
		'epl_slider_thumb_width'		=>	120,
		'epl_slider_thumb_height'		=>	120,
		'epl_slider_spacingx'			=>	14,
		'epl_slider_spacingy'			=>	12,

		'epl_slider_slideshow'			=>	'true',
		'epl_slider_slideshowSpeed'		=>	5000,
		'epl_slider_transition'			=>	'fade_in_l',
		'epl_slider_pauseOnHover'		=>	1,

		'epl_slider_thumb_on_handheld'		=>	0,
		'epl_slider_width_mobile'		=>	400,
		'epl_slider_height_mobile'		=>	300,
		'epl_allow_swipe'			=>	3,

		'epl_slider_enable_archive'		=>	0,
		'epl_slider_archive_image_size'		=>	'epl-image-medium-crop',
		'epl_slider_archive_wrapper_width'	=>	300,
		'epl_slider_archive_wrapper_height'	=>	200,
		'epl_slider_archive_price_sticker'	=>	'true',
	);

	foreach($new_fields_defaults as $key	=>	&$value) {
		if(isset($epl_settings[$key])) {
			unset($epl_settings[$key]);
		}
	}
	update_option( 'epl_settings', $epl_settings );
}
/** Only Remove Extension Settings if EPL core is set to uninstall **/
if( epl_get_option( 'uninstall_on_delete' ) == 1 ) {
	register_deactivation_hook( EPL_SLIDER_PLUGIN_FILE, 'epl_slider_uninstall' );
}
