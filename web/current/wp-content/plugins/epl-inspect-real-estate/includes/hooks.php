<?php
function epl_ire_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=>	'',
		'fields'	=>	array(
			array(
				'name'	=>	'inspect_real_estate',
				'label'	=>	'Inspect Real Estate license key',
				'type'	=>	'text'
			)
		)
	);
	
	return $fields;
}
add_filter('epl_license_options_filter', 'epl_ire_license_options_filter', 10, 3);
function epl_ire_extensions_options_filter($epl_fields = null) {
	$fields = array();
	$epl_ire_fields = array(
		'label'		=>	__('Inspect Real Estate')
	);
	$fields[] = array(
		'label'		=>	'Settings',
		'intro'		=>	__('Enter your Inspect Real Estate account details below and press Save Changes','epl'),
		'fields'	=>	array(
			array(
				'name'	=>	'epl_ire_account',
				'label'	=>	'Account',
				'type'	=>	'text'
			),
			array(
				'name'	=>	'epl_ire_account_id',
				'label'	=>	'Account ID (Preferred)',
				'type'	=>	'text'
			)
		),
	);
	$epl_ire_fields['fields'] = $fields;
	$epl_fields['inspect_real_estates'] = $epl_ire_fields;
	return $epl_fields;
}
add_filter('epl_extensions_options_filter_new', 'epl_ire_extensions_options_filter', 10, 3);
