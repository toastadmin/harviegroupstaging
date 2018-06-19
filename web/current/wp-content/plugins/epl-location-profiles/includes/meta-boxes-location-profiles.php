<?php
/**
 * Metaboxes
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Functions/Metaboxes
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers meta boxes
 *
 * @since 2.3
 * @return void
 */
function epl_lp_add_meta_box( $meta_box) {

	$meta_fields = array(
		array(
			'id'		=>	'epl-location-profiles-section-id',
			'label'		=>	__('Location Details', 'epl-location-profiles'),
			'post_type'	=>	'location_profile',
			'context'	=>	'normal',
			'priority'	=>	'high',
			'groups'	=>	array(
				array(
					'columns'	=>	'1',
					'label'		=>	'',
					'fields'	=>	apply_filters('epl_lp_meta_fields',array(

						/*array(
							'name'		=>	'location_profile_name',
							'label'		=>	__('Location Address including state and postcode/zip', 'epl-location-profiles'),
							'type'		=>	'text',
							'maxlength'	=>	'60'
						),

						array(
							'name'		=>	'location_profile_state',
							'label'		=>	__('State', 'epl-location-profiles'),
							'type'		=>	'text',
							'maxlength'	=>	'10'
						),

						array(
							'name'		=>	'location_profile_postcode',
							'label'		=>	__('Postcode/Zip', 'epl-location-profiles'),
							'type'		=>	'text',
							'maxlength'	=>	'60'
						),*/

						array(
							'name'		=>	'location_profile_video_url',
							'label'		=>	__('YouTube Video Link', 'epl-location-profiles'),
							'type'		=>	'text',
							'maxlength'	=>	'60'
						),

						array(
							'name'		=>	'location_profile_list_item_1',
							'label'		=>	epl_post_profile_location_list(1),
							'type'		=>	'text',
							'maxlength'	=>	'60'
						),

						array(
							'name'		=>	'location_profile_list_item_2',
							'label'		=>	epl_post_profile_location_list(2),
							'type'		=>	'text',
							'maxlength'	=>	'60'
						),

						array(
							'name'		=>	'location_profile_list_item_3',
							'label'		=>	epl_post_profile_location_list(3),
							'type'		=>	'text',
							'maxlength'	=>	'60'
						),

						array(
							'name'		=>	'location_profile_list_item_4',
							'label'		=>	epl_post_profile_location_list(4),
							'type'		=>	'text',
							'maxlength'	=>	'60'
						),

						array(
							'name'		=>	'location_profile_list_item_5',
							'label'		=>	epl_post_profile_location_list(5),
							'type'		=>	'text',
							'maxlength'	=>	'60'
						),

						array(
							'name'		=>	'location_profile_tab_0',
							'label'		=>	epl_post_profile_location_tab(0),
							'type'		=>	'editor'
						),

						array(
							'name'		=>	'location_profile_tab_1',
							'label'		=>	epl_post_profile_location_tab(1),
							'type'		=>	'editor'
						),

						array(
							'name'		=>	'location_profile_tab_2',
							'label'		=>	epl_post_profile_location_tab(2),
							'type'		=>	'editor'
						),

						array(
							'name'		=>	'location_profile_tab_3',
							'label'		=>	epl_post_profile_location_tab(3),
							'type'		=>	'editor'
						),

						array(
							'name'		=>	'location_profile_tab_4',
							'label'		=>	epl_post_profile_location_tab(4),
							'type'		=>	'editor'
						),

						array(
							'name'		=>	'location_profile_tab_5',
							'label'		=>	epl_post_profile_location_tab(5),
							'type'		=>	'editor'
						)
					) )
				)
			)
		)
	);
	foreach($meta_fields as $blocks) {
		$meta_box[] = $blocks;
	}
	return  $meta_box;
}
add_filter('epl_listing_meta_boxes','epl_lp_add_meta_box');

/**
 * Add listing details meta box to location_profile
 *
 * @since 2.2
 */
function epl_lp_enable_listing_meta_boxes($meta_box) {
	$meta_box['post_type'][] = 'location_profile';
	return $meta_box;
}
// Listing Address
add_filter('epl_meta_box_block_epl_property_address_section_id','epl_lp_enable_listing_meta_boxes');