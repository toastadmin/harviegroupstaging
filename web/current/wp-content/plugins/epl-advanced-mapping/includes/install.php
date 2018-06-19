<?php
/**
 * Add default settings to epl upon activation
 * @since	2.0.0
**/
function epl_am_install() {

	global $wpdb, $epl_options;

	$epl_settings = epl_settings();

	$new_fields_defaults = array(
		'epl_am_custom_marker'			=>	0,
		'epl_am_map_enable'			=>	1,
		'epl_am_single_tab_position'		=>	1,
		'epl_am_single_map_height'		=>	400,
		'epl_am_infobox_position'		=>	'left',
		'epl_am_default_map_type'		=>	'SATELLITE',
		'epl_am_infobox_style'			=>	'rounded',
		'epl_am_disable_mousescroll'		=>	0,
		'epl_am_enable_sat_view'		=>	1,
		'epl_am_enable_street_view'		=>	0,
		'epl_am_enable_transit_view'		=>	1,
		'epl_am_enable_bike_view'		=>	1,
		'epl_am_enable_comparables_view'	=>	1,
		'epl_slider_eam_mode'			=>	'false',

		'epl_am_single_map_zoom'		=>	16,

		'epl_am_label_sat'			=>	__('Satellite', 'epl-am'),
		'epl_am_single_map_zoom_sat'		=>	20,

		'epl_am_enable_street_view'		=>	1,
		'epl_am_label_street'			=>	__('Street View', 'epl-am'),

		'epl_am_enable_transit_view'		=>	1,
		'epl_am_label_transit'			=>	__('Transit', 'epl-am'),
		'epl_am_single_map_zoom_transit'	=>	16,

		'epl_am_enable_bike_view'		=>	1,
		'epl_am_label_bike'			=>	__('Bike', 'epl-am'),
		'epl_am_single_map_zoom_bike'		=>	18,

		'epl_am_enable_comparables_view'	=>	1,
		'epl_am_label_comparables'		=>	__('Comparables', 'epl-am'),
		'epl_am_single_map_zoom_comparables'	=>	14,

		'epl_am_google_api'			=>	'',
		'epl_am_map_styles'			=>	'',

	);

	foreach($new_fields_defaults as $key	=>	$value) {
		if(!isset($epl_settings[$key])) {
			$epl_settings[$key] = $value;
		}
	}
	update_option( 'epl_settings', $epl_settings );
}
register_activation_hook( EPL_AM_PLUGIN_FILE, 'epl_am_install' );

/**
 * Delete plugin settings
 * @since	2.0.0
**/
function epl_am_uninstall() {

	global $wpdb, $epl_options;

	$epl_settings = epl_settings();

	$new_fields_defaults = array(
		'epl_am_custom_marker'			=>	0,
		'epl_am_map_enable'			=>	1,
		'epl_am_single_tab_position'		=>	1,
		'epl_am_single_map_height'		=>	400,
		'epl_am_infobox_position'		=>	'left',
		'epl_am_default_map_type'		=>	'SATELLITE',
		'epl_am_infobox_style'			=>	'rounded',
		'epl_am_disable_mousescroll'		=>	0,
		'epl_am_enable_sat_view'		=>	1,
		'epl_am_enable_street_view'		=>	0,
		'epl_am_enable_transit_view'		=>	1,
		'epl_am_enable_bike_view'		=>	1,
		'epl_am_enable_comparables_view'	=>	1,
		'epl_slider_eam_mode'			=>	'false',

		'epl_am_single_map_zoom'		=>	16,

		'epl_am_label_sat'			=>	__('Satellite', 'epl-am'),
		'epl_am_single_map_zoom_sat'		=>	20,

		'epl_am_enable_street_view'		=>	1,
		'epl_am_label_street'			=>	__('Street View', 'epl-am'),

		'epl_am_enable_transit_view'		=>	1,
		'epl_am_label_transit'			=>	__('Transit', 'epl-am'),
		'epl_am_single_map_zoom_transit'	=>	16,

		'epl_am_enable_bike_view'		=>	1,
		'epl_am_label_bike'			=>	__('Bike', 'epl-am'),
		'epl_am_single_map_zoom_bike'		=>	18,

		'epl_am_enable_comparables_view'	=>	1,
		'epl_am_label_comparables'		=>	__('Comparables', 'epl-am'),
		'epl_am_single_map_zoom_comparables'	=>	14,

		'epl_am_google_api'			=>	'',
		'epl_am_map_styles'			=>	'',
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
	register_deactivation_hook( EPL_AM_PLUGIN_FILE, 'epl_am_uninstall' );
}