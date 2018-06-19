<?php
/**
 * Admin Hooks
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Functions/Admin
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Extension Settings
 *
 * @since 1.0
 */
function epl_sd_display_options_filter($epl_fields = null) {
	// Image Sizes
	if ( function_exists( 'epl_get_thumbnail_sizes' ) ) {
		$opts_sizes = array();
		$sizes = epl_get_thumbnail_sizes();
		foreach ($sizes as $k=>$v) {
			$v = implode(" x ", $v);
			$opts_sizes[ $k ] = $k . ' ' . $v;
		}
	}
	$fields = array();
	$epl_sd_fields = array(
		'label'		=>	__('Staff Directory','epl-staff-directory'),
	);

	$opts_sd_archive_style = array(
		'list'		=>	__('List','epl-staff-directory'),
		'grid'		=>	__('Grid','epl-staff-directory'),
	);

	$opts_sd_listing_count = array('-1'	=>	'all');
	for($i=4; $i<=60; $i++) {
		$opts_sd_listing_count[$i] = $i;
	}

	$fields[] = array(

		'label'		=>	__('Settings','epl-staff-directory'),
		'fields'	=>	apply_filters('epl_sd_general_settings',array(

			array(
				'name'	=>	'epl_sd_post_type_slug',
				'label'	=>	__('Staff Directory Post Slug' , 'epl-staff-directory'),
				'type'	=>	'text',
				'help'	=>	__('This is the custom post type slug which forms your page URL eg: /directory/. Note: Make sure you re-save your WordPress permalinks after changing this to avoid "not found" errors. Visit Dashboard > Settings > Permalinks and press Save Changes.' , 'epl-staff-directory'),
				'default' =>	__( 'Directory', 'epl-staff-directory'),
			)
		) )
	);

	$fields[] = array(

		'label'		=>	__('Author Box','epl-staff-directory'),
		'fields'	=>	apply_filters('epl_sd_author_box_tab_settings',array(

			array(
				'name'	=>	'epl_sd_staff_author_box_type',
				'label'	=>	__('Box Style','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	array(
					0	=>	__('Tabbed','epl-staff-directory'),
					1	=>	__('Bio','epl-staff-directory')
				),
			),

			array(
				'name'	=>	'epl_sd_staff_read_more',
				'label'	=>	__('Read More Label','epl-staff-directory'),
				'type'	=>	'text'
			),

			array(
				'name'	=>	'epl_sd_staff_image_type',
				'label'	=>	__('Image Type','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					0	=>	__('Use Gravatar Image','epl-staff-directory'),
					1	=>	__('Use Staff Directory Image','epl-staff-directory')
				),
			),

			array(
				'name'	=>	'epl_sd_staff_image_size_box',
				'label'	=>	__('Image Size in Advanced Author Box','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	$opts_sizes
			),

			array(
				'name'	=>	'epl_sd_link_to',
				'label'	=>	__('Author should link to','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					0	=>	__('Author Profile','epl-staff-directory'),
					1	=>	__('Staff Directory','epl-staff-directory')
				),
			),

			array(
				'name'	=>	'epl_sd_recent_listings_tab_count',
				'label'	=>	__('Number of recent listings in tab','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	$opts_sd_listing_count
			),
		) )
	);

	$fields[] = array(

		'label'		=>	__('Single','epl-staff-directory'),
		'fields'	=>	apply_filters('epl_sd_single_tab_settings',array(
			array(
				'name'	=>	'epl_sd_staff_single_style',
				'label'	=>	__('Page Style','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	array(
					0	=>	__('Author Box','epl-staff-directory'),
					1	=>	__('Big Image','epl-staff-directory')
				)
			),

			array(
				'name'	=>	'epl_sd_single_listing_count',
				'label'	=>	__('Number of Listings','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	$opts_sd_listing_count,
				'help'	=>	__('Number of listings displayed for each staff member.','epl-staff-directory')
			),

			array(
				'name'	=>	'epl_sd_listing_template',
				'label'	=>	__('Listing Template','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	array(
					'card'		=>	__('Card','epl-staff-directory'),
					'blog'		=>	__('Blog','epl-staff-directory'),
					'slim'		=>	__('Slim','epl-staff-directory'),
					'table'		=>	__('Table','epl-staff-directory'),
					'table_open'	=>	__('Table Open','epl-staff-directory'),
				),
				'help'	=>	__('template used to display staff listings','epl-staff-directory')
			),
			array(
				'name'	=>	'epl_sd_show_sold_leased',
				'label'	=>	__('Show sold/leased','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					'yes'	=>	__('Yes','epl-staff-directory'),
					'no'	=>	__('No','epl-staff-directory')
				),
				'default'	=>	'no'
			),

			array(
				'name'	=>	'epl_sd_single_leased_listing_count',
				'label'	=>	__('Number of Sold/Leased Listings','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	$opts_sd_listing_count,
				'help'	=>	__('Number of sold/leased listings displayed for each staff member.','epl-staff-directory')
			),

			array(
				'name'	=>	'epl_sd_single_recent_posts_display',
				'label'	=>	__('Show recent posts on staff directory','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					'yes'	=>	__('Yes','epl-staff-directory'),
					'no'	=>	__('No','epl-staff-directory')
				),
				'default'	=>	'yes'
			),

			array(
				'name'	=>	'epl_sd_single_recent_posts_count',
				'label'	=>	__('Number of blog posts','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	$opts_sd_listing_count,
				'help'	=>	__('Number of blog posts displayed for each staff member.','epl-staff-directory')
			),
		) )
	);

	$fields[] = array(

		'label'		=>	__('Archives','epl-staff-directory'),
		'fields'	=>	apply_filters('epl_sd_archive_tab_settings',array(
			array(
				'name'	=>	'epl_sd_archive_per_page',
				'label'	=>	__('Staff members per page','epl-staff-directory'),
				'type'	=>	'number',
				'help'	=>	__('Leave blank for WordPress default.','epl-staff-directory')
			),

			array(
				'name'	=>	'epl_sd_archive_style',
				'label'	=>	__('Page Style','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	$opts_sd_archive_style,
			),

			array(
				'name'	=>	'epl_sd_grid_archive_cols',
				'label'	=>	__('Number of Columns in Grid','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	array_combine(range(1,6),range(1,6)),
			),

			array(
				'name'	=>	'epl_sd_staff_image_size_loop',
				'label'	=>	__('Image Size on Archive Page','epl-staff-directory'),
				'type'	=>	'select',
				'opts'	=>	$opts_sizes
			),
			array(
				'name'	=>	'epl_sd_archive_excerpt',
				'label'	=>	__('Excerpt','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					0	=>	__('No Excerpt','epl-staff-directory'),
					1	=>	__('Display Excerpt','epl-staff-directory')
				)
			),
			array(
				'name'	=>	'epl_sd_archive_show_position',
				'label'	=>	__('Show Position','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					1	=>	__('Yes','epl-staff-directory'),
					0	=>	__('No','epl-staff-directory')
				)
			),
			array(
				'name'	=>	'epl_sd_archive_show_mobile',
				'label'	=>	__('Show Mobile','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					1	=>	__('Yes','epl-staff-directory'),
					0	=>	__('No','epl-staff-directory')
				)
			),
			array(
				'name'	=>	'epl_sd_archive_show_icons',
				'label'	=>	__('Show Icons','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					1	=>	__('Yes','epl-staff-directory'),
					0	=>	__('No','epl-staff-directory')
				)
			),
			array(
				'name'	=>	'epl_sd_archive_show_vcard',
				'label'	=>	__('Show Vcard','epl-staff-directory'),
				'type'	=>	'radio',
				'opts'	=>	array(
					1	=>	__('Yes','epl-staff-directory'),
					0	=>	__('No','epl-staff-directory')
				)
			),
		) )
	);

		/**
		$instruction = '<h3> syntax hightlight demonstration instruction </h3> ';

		$fields[] = array(

			'label'		=>	__('Help','epl-staff-directory'),
			'fields'	=>	apply_filters('epl_sd_help_tab_settings',array(
				array(
					'label'			=>	__('Staff dir help documentation'),
					'name'			=>	'epl_sd_general_help',
					'type'			=>	'help',
					'content'		=>	$instruction
				),

			) )
		);
		**/

	$epl_sd_fields['fields'] 		= $fields;
	$epl_fields['staff_directory'] = $epl_sd_fields;
	return $epl_fields;

}
add_filter('epl_extensions_options_filter_new', 'epl_sd_display_options_filter', 10, 3);

/**
 * License Key for Automatic updating
 *
 * @since 1.0
 */
function epl_sd_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=>	'',
		'fields'	=>	array(
			array(
				'name'	=>	'staff_directory',
				'label'	=>	'Staff Directory license key',
				'type'	=>	'text'
			)
		)
	);

	return $fields;
}
add_filter('epl_license_options_filter', 'epl_sd_license_options_filter', 10, 3);

/**
 * Add Staff Directory posts to EPL Dashboard Widget
 *
 * @since 1.0
 */
function epl_sd_filter_dashboard_widget_posts($posts) {
	$posts[] = 'directory';
	return $posts;
}
add_filter('epl_filter_dashboard_widget_posts','epl_sd_filter_dashboard_widget_posts');

/**
 * Add office phone number to user
 *
 * @since 1.0
 */
function epl_sd_user_contact_methods( $contactmethods ) {
	$contactmethods['epl_user_office']	= __('Office Phone', 'epl-staff-directory');
	return $contactmethods;
}
add_filter ('user_contactmethods','epl_sd_user_contact_methods',10,1);

/**
 * Manage Staff Columns
 *
 * @since 1.0
 */
function epl_sl_manage_directory_columns_heading( $columns ) {

	//EPL Suburb Label
	$location_label = function_exists( 'epl_labels' ) ? epl_labels('label_suburb') : __( 'Location' , 'epl-staff-directory' );

	$columns = array(
		'cb' 		=> '<input type="checkbox" />',
		'thumb' 	=> __('Image', 'epl-staff-directory'),
		'title' 	=> __('Name', 'epl-staff-directory'),
		'details' 	=> __('Details', 'epl-staff-directory'),
		// 'summary' 	=> __('Summary', 'epl-staff-directory'),
		'author' 	=> __('Linked Author', 'epl-staff-directory'),
		'department' 	=> __('Department', 'epl-staff-directory'),
		'suburb' 	=> $location_label,
		'order' 	=> __('Order', 'epl-staff-directory'),
		'comments' 	=> __('<span title="Comments" class="comment-grey-bubble"></span>', 'epl-staff-directory'),
		'date' 		=> __('Date', 'epl-staff-directory'),
	);

	/** Remove a Suburb Taxonomy if Core is not active **/
	$location_exist = taxonomy_exists('location');
	if ( ! $location_exist ) {
		unset(
			$columns['suburb']
		);
	}

	return $columns;
}
add_filter( 'manage_directory_posts_columns', 'epl_sl_manage_directory_columns_heading' );

/**
 * Columns Content
 *
 * @since 1.0
 */
function epl_sl_manage_directory_columns_value( $column, $post_id ) {
	global $post,$epl_author;
	switch( $column ) {
		/* If displaying the 'Featured' image column. */
		case 'thumb' :
			/* Get the featured Image */
			if( function_exists('the_post_thumbnail') )
				echo the_post_thumbnail('admin-list-thumb');
			break;

		/* If displaying the 'order' column. */
		case 'order' :
			/* Get the post meta. */
			$order = $post->menu_order;
			echo $order;
			break;


		/* If displaying the 'details' column. */
		case 'details' :
			do_action('epl_sd_manage_column_details');
			break;

		/* If displaying the 'summary' column. */
		case 'summary' :
			// Disabled as is slow to load Staff Pages
			$filters = array(
				array(
					'key'		=>	'property_status',
					'value'		=>	'leased',
					'string'	=>	apply_filters( 'epl_leased_label_status_filter' , __('Leased', 'epl-staff-directory') )
				),
				array(
					'key'		=>	'property_status',
					'value'		=>	'current',
					'string'	=>	__('Current','epl-staff-directory')
				),
				array(
					'key'		=>	'property_status',
					'value'		=>	'sold',
					'string'	=>	apply_filters( 'epl_sold_label_status_filter' , __('Sold', 'epl-staff-directory') )
				),
				array(
					'key'		=>	'property_status',
					'value'		=>	'withdrawn',
					'string'	=>	__('Withdrawn','epl-staff-directory')
				),
				array(
					'key'		=>	'property_status',
					'value'		=>	'offmarket',
					'string'	=>	__('Off Market','epl-staff-directory')
				),
			);

			foreach($filters as $filter_key 	=>	$filter_value){

				$count = epl_get_post_count('',$filter_value['key'],$filter_value['value'],$epl_author->author_id);
				if($count != 0){
					echo '<span>'.$count.' '.$filter_value['string'].' </span><br>';
				}
			}

			break;
		/* If displaying the 'department' column. */
		case 'department' :
			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'department' );
			/* If terms were found. */
			if ( !empty( $terms ) ) {
				$out = array();
				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'department' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'department', 'display' ) )
					);
				}
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Department Set' ,'epl-staff-directory');
			}
			break;
		/* If displaying the 'suburb' column. */
		case 'suburb' :
			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'location' );
			/* If terms were found. */
			if ( !empty( $terms ) ) {
				$out = array();
				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'suburb' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'suburb', 'display' ) )
					);
				}
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Suburb Set' ,'epl-staff-directory');
			}
			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
add_action( 'manage_directory_posts_custom_column', 'epl_sl_manage_directory_columns_value', 10, 2 );

/**
 * Columns Details Content
 *
 * @since 2.3
 */
function epl_sd_manage_column_details_callback() {

	global $epl_author;

	if ( $epl_author->position != '' ) {
		echo '<div class="epl-manage-column-meta epl-meta-sd-position">' . $epl_author->position . '</div>';
	}

	if ( $epl_author->mobile != '' ) {
		echo '<div class="epl-manage-column-meta epl-meta-sd-mobile">' . $epl_author->mobile . '</div>';
	}

	if ( $epl_author->email != '' ) {
		echo '<div class="epl-manage-column-meta epl-meta-sd-mobile">
			<a class="epl-manage-column-meta epl-meta-sd-email" href="mailto:' . $epl_author->email . '" title="'.__('Email', 'epl-staff-directory' ).'">' . $epl_author->email . '</a>
			</div>';
	}
}
add_action( 'epl_sd_manage_column_details' , 'epl_sd_manage_column_details_callback' );

/**
 * Manage Property Columns Sorting
 *
 * @since 2.3
 */
function epl_sd_manage_directory_sortable_columns( $columns ) {
	$columns['order']	= 'menu_order';
	$columns['department'] 	= 'department';
	$columns['suburb'] 	= 'suburb';
	return $columns;
}
add_filter( 'manage_edit-directory_sortable_columns', 'epl_sd_manage_directory_sortable_columns' );

/**
 * Load and enqueue admin scripts and stylesheets
 *
 * @since 2.3
 */
function epl_sd_admin_enqueue_scripts( $screen ) {
	// load admin style on help & documentation pages as well
	if($screen = 'edit.php' ) {
		wp_enqueue_style(	'epl-sd-admin-styles', 	EPL_SD_CSS_URL . 'style-admin.css',	FALSE,	EPL_SD_VER );
	}
}
add_action( 'admin_enqueue_scripts', 'epl_sd_admin_enqueue_scripts' );