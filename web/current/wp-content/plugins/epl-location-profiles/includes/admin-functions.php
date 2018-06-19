<?php
/**
 * Admin Functions
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Functions/Admin
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Location Profile Key
 *
 * @since       1.0
 */
function epl_lp_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=>	'',
		'fields'	=>	array(
			array(
				'name'	=>	'location_profiles',
				'label'	=>	'Location Profiles license key',
				'type'	=>	'text'
			)
		)
	);

	return $fields;
}
add_filter('epl_license_options_filter', 'epl_lp_license_options_filter', 10, 3);

/**
 * Extension Settings
 *
 * @since 1.0
 */
function epl_lp_extensions_options_filter($epl_fields = null) {
	$fields = array();
	$epl_lp_fields = array(
		'label'		=>	__('Location Profiles' , 'epl-location-profiles')
	);

	$opts_lp_listing_count = array('-1'	=>	'all');
	for($i=4; $i<=60; $i++) {
		$opts_lp_listing_count[$i] = $i;
	}

	$fields[] = array(
		'label'		=>	__('Settings' , 'epl-location-profiles'),
		'intro'		=>	'',
		'fields'	=>	apply_filters('epl_lp_settings_tab',array(
			array(
				'name'	=>	'epl_lp_post_type_name',
				'label'	=>	__('Location Profile Post Type Name' , 'epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('This is the custom post type slug which forms your page URL. Note: Make sure you re-save your WordPress permalinks after changing this to avoid "not found" errors. Visit Dashboard > Settings > Permalinks and press Save Changes. Use a singular term e.g <strong>Suburb Profile</strong>, instead of Suburb Profile<strong>s</strong>. Default is <strong>Location Profile</strong>.' , 'epl-location-profiles'),
			),
			array(
				'name'	=>	'epl_lp_single_tab_orientation',
				'label'	=>	__('Tabs Orientation', 'epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Vertical','epl-location-profiles'),
					'0'	=>	__('Horizontal','epl-location-profiles')
				),
				'default'	=>	'1',
			)
		) )
	);

	$fields[] = array(
		'label'		=>	__('List Labels','epl-location-profiles'),
		'intro'		=>	'',
		'fields'	=>	apply_filters('epl_lp_list_labels_settings_tab', array(

			array(
				'name'		=>	'epl_lp_labels_help',
				'content'	=>	'<p style="margin-top:0">' . __('The settings below adjust the basic configuration options for the slider display. Enter the labels of the list items that appear on the first tab of the location profile box These can give a quick overview of key things about a location. They will appear when editing a location profile and will appear in-front of the value in the list. E.g. Population: 2,400','epl-location-profiles') . '</p>',
				'type'		=>	'help',
			),

			array(
				'name'	=>	'epl_lp_label_tab_list_name',
				'label'	=>	__('Bullet List Tab Label','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Fast Facts','epl-location-profiles'),
			),

			array(
				'name'	=>	'epl_lp_label_list_item_1',
				'label'	=>	__('Bullet List Name 1','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Number of properties','epl-location-profiles'),
			),

			array(
				'name'	=>	'epl_lp_label_list_item_2',
				'label'	=>	__('Bullet List Name 2','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Growth Rate','epl-location-profiles'),
			),

			array(
				'name'	=>	'epl_lp_label_list_item_3',
				'label'	=>	__('Bullet List Name 3','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Number of schools','epl-location-profiles'),
			),

			array(
				'name'	=>	'epl_lp_label_list_item_4',
				'label'	=>	__('Bullet List Name 4','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Population','epl-location-profiles'),
			),

			array(
				'name'	=>	'epl_lp_label_list_item_5',
				'label'	=>	__('Bullet List Name 5','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Investment type','epl-location-profiles'),
			)
		) )
	);

	$fields[] = array(
		'label'		=>	__('Tab Box Labels','epl-location-profiles'),
		'intro'		=>	'',
		'fields'	=>	apply_filters('epl_lp_tab_box_labels_settings_tab', array(

			array(
				'name'		=>	'epl_lp_tab_labels_help',
				'content'	=>	'<p style="margin-top:0">' . __('These will display when editing and viewing a location profile. Also these are the labels that will appear in the location profile tabbed box which when linked by the location will appear on listings that have a matching location. When editing a location profile each tab box works like the WordPress editor so you can put text, images and links.','epl-location-profiles') . '</p>',
				'type'		=>	'help',
			),

			array(
				'name'	=>	'epl_lp_label_tab_0',
				'label'	=>	__('Tab 0 Name','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Shopping','epl-location-profiles')
			),

			array(
				'name'	=>	'epl_lp_label_tab_1',
				'label'	=>	__('Tab 1 Name','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Property Facts','epl-location-profiles')
			),

			array(
				'name'	=>	'epl_lp_label_tab_2',
				'label'	=>	__('Tab 2 Name','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Education','epl-location-profiles')
			),

			array(
				'name'	=>	'epl_lp_label_tab_3',
				'label'	=>	__('Tab 3 Name','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: Facilities','epl-location-profiles'),
			),

			array(
				'name'	=>	'epl_lp_label_tab_4',
				'label'	=>	__('Tab 4 Name','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: History','epl-location-profiles'),
			),

			array(
				'name'	=>	'epl_lp_label_tab_5',
				'label'	=>	__('Tab 5 Name','epl-location-profiles'),
				'type'	=>	'text',
				'help'	=>	__('E.g.: About Us','epl-location-profiles'),
			)
		) )
	);

	$fields[] = array(
		'label'		=>	__('Single','epl-location-profiles'),
		'intro'		=>	'',
		'fields'	=>	apply_filters('epl_lp_single_settings_tab', array(

			array(
				'name'		=>	'epl_lp_single_help',
				'content'	=>	'<p style="margin-top:0">' . __('Adjust how the single location profile displays with the settings below.','epl-location-profiles') . '</p>',
				'type'		=>	'help',
			),

			array(
				'name'	=>	'epl_lp_single_tabbed_info',
				'label'	=>	__('Show the tabbed info-box','epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'1',
			),
			array(
				'name'	=>	'epl_lp_single_gallery',
				'label'	=>	__('Display attached images in a gallery','epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'1',
			),
			array(
				'name'	=>	'epl_lp_single_map',
				'label'	=>	__('Display the map','epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'1',
			),
			array(
				'name'	=>	'epl_lp_single_author_box',
				'label'	=>	__('Display author box','epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'1',
			),
			array(
				'name'	=>	'epl_lp_single_display_listings',
				'label'	=>	__('Show Listings','epl-location-profiles'),
				'type'	=>	'radio',
				'opts'	=>	array(
					true	=>	__('Enable','epl-location-profiles'),
					false	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	false
			),
			array(
				'name'	=>	'epl_lp_single_listing_count',
				'label'	=>	__('Number of Listings','epl-location-profiles'),
				'type'	=>	'select',
				'opts'	=>	$opts_lp_listing_count,
				'help'	=>	__('Number of listings displayed.','epl-location-profiles'),
				'default'	=>	'10',
			),
			array(
				'name'		=>	'epl_lp_single_listing_template',
				'label'		=>	__('Default Template', 'epl-location-profiles'),
				'type'		=>	'select',
				'opts'		=>	array(
					'default' 	=> __('Default' , 'epl-location-profiles'),
					'card' 		=> __('Card' , 'epl-location-profiles'),
					'slim' 		=> __('Slim' , 'epl-location-profiles'),
					'table' 	=> __('Table' , 'epl-location-profiles'),
					'table_open' 	=> __('Table Open' , 'epl-location-profiles'),
				),
			)
		) )
	);
	$epl_lp_fields['fields'] = $fields;
	$epl_fields['listing_profiles'] = $epl_lp_fields;
	return $epl_fields;
}
add_filter('epl_extensions_options_filter_new', 'epl_lp_extensions_options_filter', 10, 1);

/**
 * Add options to staff directory extension
 *
 * @since 2.2
 */
function epl_lp_staff_dir_single_opts($fields) {

	$opts_sd_listing_count = array('-1'	=>	'all');
	for($i=4; $i<=60; $i++) {
		$opts_sd_listing_count[$i] = $i;
	}

	$tm_fields = array(
		array(
			'name'	=>	'epl_sd_show_location_profiles',
			'label'	=>	__('Show Location Profiles','epl-location-profiles'),
			'type'	=>	'radio',
			'opts'	=>	array(
				'yes'	=>	__('Enable','epl-location-profiles'),
				'no'	=>	__('Disable','epl-location-profiles')
			),
			'default'	=>	'no'
		),
		array(
			'name'	=>	'epl_sd_location_profiles_count',
			'label'	=>	__('Number of Location Profiles','epl-location-profiles'),
			'type'	=>	'select',
			'opts'	=>	$opts_sd_listing_count,
			'help'	=>	__('Number of Location Profiles displayed for each staff member.','epl-location-profiles')
		)
	);

	return array_merge($fields,$tm_fields);
}
add_filter('epl_sd_single_tab_settings','epl_lp_staff_dir_single_opts');

/**
 * Add location profiles to Dashboard Widget
 *
 * @since 2.0
 */
function epl_lp_filter_dashboard_widget_posts($posts) {
	$posts[] = 'location_profile';
	return $posts;
}
add_filter('epl_filter_dashboard_widget_posts','epl_lp_filter_dashboard_widget_posts');

/**
 * Add advanced map settings
 *
 * @since 2.0
 */
function epl_lp_map_settings($fields) {

	$fields[] = array(
		'label'		=>	__('Location Profiles','epl-location-profiles'),
		'intro'		=>	__('<h3 style="margin-top:0;">Location Profiles Map Tabs</h3> <p>Select the map tabs you want to display on a single location profile.</p>','epl-location-profiles'),
		'fields'	=>	apply_filters('epl_am_list_lp_map_tab', array(

			array(
				'name'		=>	'epl_am_lp_enable_sat_view',
				'label'		=>	__('Satellite Tab', 'epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'1',
			),
			array(
				'name'		=>	'epl_am_lp_enable_street_view',
				'label'		=>	__('Street Tab', 'epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'0',
			),
			array(
				'name'		=>	'epl_am_lp_enable_transit_view',
				'label'		=>	__('Transit Tab', 'epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'1',
			),

			array(
				'name'		=>	'epl_am_lp_enable_bike_view',
				'label'		=>	__('Bike Tab', 'epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'1',
			),

			array(
				'name'		=>	'epl_am_lp_enable_comparables_view',
				'label'		=>	__('Comparables Tab', 'epl-location-profiles'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-location-profiles'),
					'0'	=>	__('Disable','epl-location-profiles')
				),
				'default'	=>	'1',
			),

		))
	);
	return $fields;
}
add_filter('epl_am_setting_group', 'epl_lp_map_settings', 10, 1);

/**
 * Load and enqueue admin scripts and stylesheets
 *
 * @since 2.3
 */
function epl_lp_admin_enqueue_scripts( $screen ) {
	// load admin style on help & documentation pages as well
	if($screen = 'edit.php' ) {
		wp_enqueue_style(	'epl-lp-admin-styles', 	EPL_LP_CSS_URL . 'style-admin.css',	FALSE,	EPL_LP_VER );
	}
}
add_action( 'admin_enqueue_scripts', 'epl_lp_admin_enqueue_scripts' );

/**
 * Manage columns for Location Profiles
 *
 * @since 2.0
 */
function epl_lp_manage_columns_heading( $columns ) {
	$columns = array(
		'cb' 		=> '<input type="checkbox" />',
		'thumb' 	=> __('Image', 'epl-location-profiles'),
		'title' 	=> __('Title', 'epl-location-profiles'),
		'suburb' 	=> __('Linked Suburb', 'epl-location-profiles'),
		'author' 	=> __('Author', 'epl-location-profiles'),
		'date' 		=> __('Date', 'epl-location-profiles'),
	);
	return $columns;
}
add_filter( 'manage_edit-location_profile_columns', 'epl_lp_manage_columns_heading' ) ;

/**
 * Column values for Location Profiles
 *
 * @since 2.0
 */
function epl_lp_manage_columns_value( $column, $post_id ) {
	global $post;
	switch( $column ) {
		/* If displaying the 'Featured' image column. */
		case 'thumb' :
			/* Get the featured Image */
			if( function_exists('the_post_thumbnail') )
				echo the_post_thumbnail('admin-list-thumb');
			break;

		/* If displaying the 'location' column. */
		case 'suburb' :
			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'location' );
			/* If terms were found. */
			if ( !empty( $terms ) ) {
				$out = array();
				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'location' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'location', 'display' ) )
					);
				}
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Location Set' );
			}
			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
add_action( 'manage_location_profile_posts_custom_column', 'epl_lp_manage_columns_value', 10, 2 );