<?php
/**
 * Register Directory Post Type
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Functions/CPT
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers and sets up the Directory custom post type
 *
 * @since 1.0
 * @return void
 */
function epl_sd_register_custom_post_type_directory() {

	$directory_slug	= epl_sd_get_custom_post_slug();

	$labels = apply_filters( 'epl_sd_directory_labels' , array(
		'name'				=>	__('Staff Directory', 'epl-staff-directory'),
		'singular_name'			=>	__('Staff', 'epl-staff-directory'),
		'menu_name'			=>	__('Staff', 'epl-staff-directory'),
		'add_new'			=>	__('Add New', 'epl-staff-directory'),
		'add_new_item'			=>	__('Add New Staff Member', 'epl-staff-directory'),
		'edit_item'			=>	__('Edit Staff Member', 'epl-staff-directory'),
		'new_item'			=>	__('New Staff Member', 'epl-staff-directory'),
		'update_item'			=>	__('Update Staff Member', 'epl-staff-directory'),
		'all_items'			=>	__('All Staff Members', 'epl-staff-directory'),
		'view_item'			=>	__('View Staff Member', 'epl-staff-directory'),
		'search_items'			=>	__('Search Staff Member', 'epl-staff-directory'),
		'not_found'			=>	__('Staff Member Not Found', 'epl-staff-directory'),
		'not_found_in_trash'		=>	__('Staff Member Not Found in Trash', 'epl-staff-directory'),
		'parent_item_colon'		=>	__('Parent Staff Member:', 'epl-staff-directory')
	) );

	$args = array(
		'labels'			=>	$labels,
		'public'			=>	true,
		'publicly_queryable'		=>	true,
		'show_ui'			=>	true,
		'show_in_menu'			=>	true,
		'query_var'			=>	true,
		'rewrite'			=>	array( 'slug' => $directory_slug ),
		'menu_icon'      	     	=>	'dashicons-groups',
		'capability_type'		=>	'page',
		'has_archive'			=>	true,
		'hierarchical'			=>	true,
		'exclude_from_search' 		=>	false,
		'menu_position'			=>	'26.86',
		'taxonomies'			=>	array( 'location', 'department' ),
		'supports'			=>	array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions', 'page-attributes' )
	);
	register_post_type( 'directory', $args );
}
add_action( 'init', 'epl_sd_register_custom_post_type_directory', 0 );

/**
 * Staff Directory Label
 *
 * @since 2.3
 * @return String
 */
function epl_sd_get_custom_post_slug() {
	global $epl_settings;

	$directory_slug		= isset( $epl_settings['epl_sd_post_type_slug'] ) ? sanitize_title( $epl_settings['epl_sd_post_type_slug'] ) : 'directory';

	if ( $directory_slug == '' ) {
		$directory_slug = 'directory';
	}

	return $directory_slug;
}