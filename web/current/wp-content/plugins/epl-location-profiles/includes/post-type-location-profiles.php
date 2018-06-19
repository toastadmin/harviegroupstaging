<?php
/**
 * Post Type
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Functions/CPT
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Location Profile custom post type
 *
 * @since       1.0
 */
function epl_lp_register_custom_post_type_location_profile() {
	$profile_location_label = epl_post_profile_location_label();
	$profile_location_slug = sanitize_title($profile_location_label);

	$labels = array(
		'name'			=>	__($profile_location_label , 'epl-location-profiles'),
		'singular_name'		=>	__($profile_location_label , 'epl-location-profiles'),
		'menu_name'		=>	__($profile_location_label . 's' , 'epl-location-profiles'),
		'add_new'		=>	__('Add New', 'epl-location-profiles'),
		'add_new_item'		=>	__("Add New $profile_location_label" , 'epl-location-profiles'),
		'edit_item'		=>	__("Edit $profile_location_label" , 'epl-location-profiles'),
		'new_item'		=>	__("New $profile_location_label" , 'epl-location-profiles'),
		'update_item'		=>	__("Update $profile_location_label" , 'epl-location-profiles'),
		'all_items'		=>	__("All $profile_location_label" , 'epl-location-profiles'),
		'view_item'		=>	__("View $profile_location_label" , 'epl-location-profiles'),
		'search_items'		=>	__('Search ' . $profile_location_label . 's' , 'epl-location-profiles'),
		'not_found'		=>	__("$profile_location_label Not Found" , 'epl-location-profiles'),
		'not_found_in_trash'	=>	__("$profile_location_label Not Found in Trash" , 'epl-location-profiles'),
		'parent_item_colon'	=>	__("Parent $profile_location_label:" , 'epl-location-profiles')
	);
	$args = array(
		'labels'		=>	$labels,
		'public'		=>	true,
		'publicly_queryable'	=>	true,
		'show_ui'		=>	true,
		'show_in_menu'		=>	true,
		'query_var'		=>	true,
		'rewrite'		=>	array( 'slug' => $profile_location_slug ),
		'menu_icon'		=>	'dashicons-analytics',
		'capability_type'	=>	'post',
		'has_archive'		=>	true,
		'hierarchical'		=>	false,
		'menu_position'		=>	'26.87',
		'taxonomies'		=>	array(	'location' ),
		'supports'		=>	array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' )
	);
	register_post_type( 'location_profile', $args );
}
add_action( 'init', 'epl_lp_register_custom_post_type_location_profile', 0 );