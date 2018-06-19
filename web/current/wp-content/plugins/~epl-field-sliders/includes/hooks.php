<?php
/*
 * Field Sliders Settings
 * @since       1.0
 */
function epl_field_sliders_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=>	'',
		'fields'	=>	array(
			array(
				'name'	=>	'listing_filters',
				'label'	=>	'Listing Filters license key',
				'type'	=>	'text'
			)
		)
	);
	
	return $fields;
}
add_filter('epl_license_options_filter', 'epl_field_sliders_license_options_filter', 10, 3);



