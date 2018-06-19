<?php
/**
 * Settings
 *
 * @package     EPL-BR
 * @subpackage  Extension Settings
 * @copyright   Copyright (c) 2014, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
function epl_br_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=>	'',
		'fields'	=>	array(
			array(
				'name'	=>	'brochures',
				'label'	=>	'Brochures license key',
				'type'	=>	'text'
			)
		)
	);
	
	return $fields;
}
add_filter('epl_license_options_filter', 'epl_br_license_options_filter', 10, 3);

function epl_br_extensions_options_filter($epl_fields = null) {
	$fields = array();
	$epl_br_fields = array(
		'label'		=>	__('Brochures', 'epl-br'),
	);
	$fields[] = array(
		'label'		=>	__('Settings', 'epl-br'),
		'fields'	=>	array(
			
			array(
				'name'		=>	'epl_br_button_label',
				'label'		=>	__('Default Button Label', 'epl-br'),
				'type'		=>	'text',
			),
			
			array(
				'name'		=>	'epl_br_header_logo',
				'label'		=>	__('Header Banner Image', 'epl-br'),
				'type'		=>	'image',
			),

			array(
				'name'		=>	'epl_br_office_details',
				'label'		=>	__('Office Details', 'epl-br'),
				'type'		=>	'editor',
			),
			
			array(
				'name'		=>	'epl_br_disclaimer',
				'label'		=>	__('Disclaimer text', 'epl-br'),
				'type'		=>	'textarea',
			),
			
			array(
				'name'		=>	'epl_br_theme_css',
				'label'		=>	__('Enable theme CSS for brochures', 'epl-br'),
				'type'		=>	'radio',
				'opts'		=>	array(
								1	=>	__('Enable','epl-br'),
								0	=>	__('Disable','epl-br')
							),
			)
		)
	);
	
	$fields[] = array(
		'label'		=>	__('Single', 'epl-br'),
		'fields'	=>	array(
			
			array(
				'name'		=>	'epl_br_brochure_style',
				'label'		=>	__('Brochure Style', 'epl-br'),
				'type'		=>	'select',
				'opts'		=>	array(
					'default' 	=> __('Default' , 'epl-br'),
					'wide' 		=> __('Wide (2 attached images max)' , 'epl-br'),
					'row' 		=> __('Wide Row (4 attached images max)' , 'epl-br'),
				),
			),
			
			array(
				'name'		=>	'epl_br_attached_images',
				'label'		=>	__('Number of attached images on brochure', 'epl-br'),
				'type'		=>	'number',
			)
		)
	);
	
	$fields[] = array(
		'label'		=>	__('Stock List' , 'epl-br'),
		'fields'	=>	array(
			
			array(
				'name'		=>	'epl_br_brochure_list_template',
				'label'		=>	__('Default Template', 'epl-br'),
				'type'		=>	'select',
				'opts'		=>	array(
					'default' 	=> __('Default' , 'epl-br'),
					'card' 		=> __('Card' , 'epl-br'),
					'slim' 		=> __('Slim' , 'epl-br'),
					'table' 	=> __('Table' , 'epl-br'),
					'table_open' 	=> __('Table Open' , 'epl-br'),
				),
			)
		)
	);
	$fields = apply_filters('epl_br_option_fields',$fields);
	$epl_br_fields['fields'] = $fields;
	$epl_fields['brochures'] = $epl_br_fields;
	return $epl_fields;
}
add_filter('epl_extensions_options_filter_new', 'epl_br_extensions_options_filter', 10, 3);

/**
 * Enable Image Upload in Extension settings
 *
 * @since 1.0
 */
function epl_br_enqueue_scripts($screen) {
	if($screen == 'easy-property-listings_page_epl-extensions') {
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
	}
}
add_action('admin_enqueue_scripts','epl_br_enqueue_scripts');