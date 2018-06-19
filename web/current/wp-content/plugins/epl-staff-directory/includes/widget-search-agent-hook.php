<?php
/**
 * Add Agent Search to EPL Listing Search Widget
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Functions/Global
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add staff search field to EPL - Listing Search Widget
 *
 * @since 2.3
 */
function epl_sd_author_search_widget_fields_backend_callback($fields) {
	$fields[] = array(
		'key'		=>	'search_agent',
		'label'		=>	__('Agent','epl-staff-directory'),
		'default'	=>	'off',
		'type'		=>	'checkbox',
	);
	return $fields;
}
add_filter('epl_search_widget_fields','epl_sd_author_search_widget_fields_backend_callback');