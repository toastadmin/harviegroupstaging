<?php
/**
 * Register Department Taxonomy
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Functions/Taxonomy
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers and sets up the department taxonomy
 *
 * @since 1.0
 */
function epl_sd_register_taxonomy_departments() {
	$labels = array(
		'name'                       => _x( 'Department', 'Taxonomy General Name', 'epl-staff-directory' ),
		'singular_name'              => _x( 'Department', 'Taxonomy Singular Name', 'epl-staff-directory' ),
		'menu_name'                  => __( 'Departments', 'epl-staff-directory' ),
		'all_items'                  => __( 'All Departments', 'epl-staff-directory' ),
		'parent_item'                => __( 'Parent Department', 'epl-staff-directory' ),
		'parent_item_colon'          => __( 'Parent Department:', 'epl-staff-directory' ),
		'new_item_name'              => __( 'New Department Name', 'epl-staff-directory' ),
		'add_new_item'               => __( 'Add New Department', 'epl-staff-directory' ),
		'edit_item'                  => __( 'Edit Department', 'epl-staff-directory' ),
		'update_item'                => __( 'Update Department', 'epl-staff-directory' ),
		'separate_items_with_commas' => __( 'Separate Department with commas', 'epl-staff-directory' ),
		'search_items'               => __( 'Search Department', 'epl-staff-directory' ),
		'add_or_remove_items'        => __( 'Add or remove Department', 'epl-staff-directory' ),
		'choose_from_most_used'      => __( 'Choose from the most used Department', 'epl-staff-directory' ),
		'not_found'                  => __( 'Department Not Found', 'epl-staff-directory' ),
	);
	$rewrite = array(
		'slug'                       => 'department',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'department', array( 'directory' , 'testimonial' ) , $args );
}
add_action( 'init', 'epl_sd_register_taxonomy_departments', 0 );