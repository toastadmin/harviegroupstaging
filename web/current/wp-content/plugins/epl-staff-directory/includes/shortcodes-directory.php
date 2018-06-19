<?php
/**
 * Shortcodes
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Shortcode/Directory
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register [epl_directory] shortcode
 *
 * @since 2.2
 * @param mixed[] $items Array structure to count the elements of.
 */
function epl_sd_shortcode_directory_callback( $atts ) {

	$attributes = shortcode_atts( array(
		'id'			=>	false,
		'limit'			=>	'10', // Number of maximum posts to show
		'template'		=>	false,
		'location'		=>	'',
		'department'		=>	'',
		'tools_top'		=>	'off', // Tools before the loop like Sorter and Grid on or off
		'tools_bottom'		=>	'off',
		'sortby'		=>	'menu_order', // Options: menu_order, date : Default menu_order
		'sort_order'		=>	'ASC',
		'query_object' 		=> 	''  // only for internal use . if provided use it instead of custom query
	), $atts );

	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args = array(
		'post_type' 		=>	'directory',
		'posts_per_page'	=>	$attributes['limit'],
		'paged' 		=>	absint( $paged )
	);

	/** check if id is provided and is parent**/
	if( intval( $attributes['id'] ) > 0  ) {

		if(get_post_meta($id, 'epl_sd_section', true ) == 'yes')
			$args['post_parent'] = $id;
		else
			$args['p'] = $id;
	}

	if ( ! empty( $attributes['location'] ) ) {
		if ( ! is_array( $attributes['location'] ) ) {
			$attributes['location'] = array_map( 'trim', explode( ',', $attributes['location'] ) );
			$args['tax_query'][] = array(
				'taxonomy'	=> 'location',
				'field'		=> 'slug',
				'terms' 	=> $attributes['location'],
			);
		}
	}

	if ( ! empty( $attributes['department'] ) ) {
		if ( ! is_array( $attributes['department'] ) ) {
			$attributes['department'] = array_map( 'trim', explode( ',', $attributes['department'] ) );
			$args['tax_query'][] = array(
				'taxonomy'	=> 'department',
				'field'		=> 'slug',
				'terms' 	=> $attributes['department'],
			);
		}
	}

	if ( ! empty ( $attributes['sortby'] ) ) {
		if ( $attributes['sortby'] == 'date' ) {
			$args['orderby']  = 'post_date';
			$args['order']    = 'DESC';
		} else {
			$args['orderby']  = 'menu_order';
			$args['order']    = 'ASC';
		}
		$args['order']        = $attributes['sort_order'];
	}

	$query_open = new WP_Query( $args );

	if ( is_object( $attributes['query_object'] ) ) {
		$query_open = $attributes['query_object'];
	}

	ob_start();

	epl_sd_get_template_part(
		'shortcode-directory.php',
		array(
			'attributes' => $attributes,
			'query_open' => $query_open,
		)
	);

	return ob_get_clean();
}
add_shortcode( 'epl_directory', 'epl_sd_shortcode_directory_callback' );
