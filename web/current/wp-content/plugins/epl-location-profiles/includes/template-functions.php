<?php
/**
 * Template Functions
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Functions/Templates
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Attempts to load templates in order of priority
 *
 * @since 1.0
 */
function epl_lp_get_template_part($template,$arguments=array()) {

	$base_path		= apply_filters('epl_lp_templates_base_path',EPL_LP_PATH_TEMPLATES_CONTENT);
	$default		= $template;
	$find[] 		= epl_template_path() . $template;
	$template       = locate_template( array_unique( $find ) );
	if(!$template) {
		$template	=	$base_path . $default;
	}
	if( !isset($arguments['epl_author']) ) {
		global $epl_author;
	}
	extract($arguments);
	include( $template);
}

/**
 * Widget List
 *
 * @since 1.0
 */
function epl_lp_location_profiles_list( $display = 0 , $image = 'admin-list-thumb', $d_align = 'none' , $d_excerpt = 0) {

	$arg_list = get_defined_vars();
	epl_lp_get_template_part('widget-content-location-profiles-list.php',$arg_list);

}

/**
 * Card Template
 *
 * @since 1.0
 */
function epl_lp_location_profiles_card( $display = 0 , $image = 'thumbnail', $d_align = 'none' , $d_excerpt = 0, $d_more = 0, $more_text = '' ) {

	$arg_list = get_defined_vars();
	epl_lp_get_template_part('widget-content-location-profiles-card.php',$arg_list);

}

/**
 * Single Profile Template
 *
 * @since 1.0
 */
function epl_lp_location_profiles_single() {
	global $epl_settings;

	$display_author		= isset( $epl_settings['epl_lp_single_author_box'] )	? $epl_settings['epl_lp_single_author_box']	: '1';
	$display_gallery	= isset( $epl_settings['epl_lp_single_gallery'] )	? $epl_settings['epl_lp_single_gallery']	: '1';
	$display_tabbed_info	= isset( $epl_settings['epl_lp_single_tabbed_info'] )	? $epl_settings['epl_lp_single_tabbed_info']	: '1';
	$display_map		= isset( $epl_settings['epl_lp_single_map'] )		? $epl_settings['epl_lp_single_map']		: '1';

	include( EPL_LP_PATH_TEMPLATES . 'location-profiles-template-meta.php' );
	$arg_list = get_defined_vars();
	epl_lp_get_template_part('content-location-profiles-single.php',$arg_list);

}

/**
 * Loop Template
 *
 * @since 1.0
 */
function epl_lp_location_profiles_loop() {
	global $epl_settings;
	$arg_list = get_defined_vars();
	epl_lp_get_template_part('loop-location-profiles.php',$arg_list);
}

/**
 * Tabbed Box Template
 *
 * @since 1.0
 */
function epl_lp_location_profiles_tab_left() {

	$arg_list = get_defined_vars();
	include( EPL_LP_PATH_TEMPLATES . 'location-profiles-template-meta.php' );
	epl_lp_tab_container();
}

/**
 * Load and enqueue front end scripts and stylesheets
 *
 * @since 1.0
 */
function epl_lp_enqueue_scripts() {

	wp_enqueue_style( 'epl-lp-front-styles', EPL_LP_PLUGIN_URL . 'css/style.css',	false,	EPL_LP_VER );
	wp_enqueue_script( 'epl-lp-front-scripts', EPL_LP_PLUGIN_URL . 'js/location-profiles-jquery-front-scripts.js', array('jquery'),	EPL_LP_VER );
}
add_action( 'wp_enqueue_scripts', 'epl_lp_enqueue_scripts' );

/**
 * Add Location Profile to Listings in the same taxonomy
 *
 * @since 1.0
 */
function epl_lp_single_action() {
	if ( taxonomy_exists('location') && is_single() ) {
			global $post;
			$terms = '';

			$terms = get_the_terms( $post->ID, 'location' );
			if ($terms != '') {
				foreach($terms as $term) {
					$term->slug;
				}
			$query = new WP_Query( array (
				'post_type'		=>	'location_profile',
				'location'		=>	$term->slug,
				'posts_per_page'=>	'1'
			) );

			if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						epl_lp_location_profiles_tab_left();
					}
				}

			}

			wp_reset_postdata();

		}
}
add_action('epl_single_extensions', 'epl_lp_single_action');

/**
 * Tab Box List Items
 *
 * @since 1.0
 */
function epl_lp_tab_box_list_items() {

	global $location_profile_meta;

	$output = '<ul>';

	for($i=1;$i<=5;$i++) {

		if($location_profile_meta['location_profile_list_item_'.$i][0] != '')
			$output .= '<li>' . epl_post_profile_location_list($i) . ' ' . $location_profile_meta['location_profile_list_item_'.$i][0]. '</li>';
	}

	$output .= '</ul>';

	return $output;

}

/**
 * Tab Menu Items
 *
 * @since 1.0
 */
function epl_lp_tab_box_menu_items() {

	global $location_profile_meta , $epl_settings;
	$menu_facts		=	epl_lp_tab_box_list_items();

	echo '<ul class="location-profiles-tabs">';
	$fact_label = isset($epl_settings['epl_lp_label_tab_list_name']) ?
				$epl_settings['epl_lp_label_tab_list_name'] :
				__( 'Fast Facts', 'epl-location-profiles' );

	if( !empty($menu_facts) ) {
		echo '<li class="tab-link location-profiles-current" data-tab="location-profiles-tab-facts">' .
				 $fact_label.
			 '</li>';
	 }

	for($i=0;$i<=5;$i++) {

		$current = ($i == 0 && empty($menu_facts) ) ? '-current' : '';

		if( !empty( $location_profile_meta['location_profile_tab_'.$i][0] ) ) {
			echo '<li class="tab-link location-profiles' . $current . '" data-tab="location-profiles-tab-' . $i . '">' .
				 epl_post_profile_location_tab($i) .
			 '</li>';
		}


	}
	echo '</ul>';

}

/**
 * Tab Items Content
 *
 * @since 1.0
 */
function epl_lp_tab_box_content() {

	global $location_profile_meta;
	$facts		=	epl_lp_tab_box_list_items();

	if( !empty($facts) ) {
		echo '<div id="location-profiles-tab-facts" class="location-profiles-box location-profiles-tab-content location-profiles-current">';
			echo '<h6>' . __( 'Fast Facts about ', 'epl-location-profiles' ) . '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h6>';
			echo '<div class="location-profiles-content">' . $facts . '</div>';
		echo '</div>';
	 }

	for($i=0;$i<=5;$i++) {

		$current = ($i == 0 && empty($facts) ) ? '-current' : '';

		if( !empty( $location_profile_meta['location_profile_tab_'.$i][0] ) ) {
			echo '<div id="location-profiles-tab-' . $i . '" class="location-profiles-box location-profiles-tab-content location-profiles' . $current . '">';
				echo '<div class="location-profiles-content">' .$location_profile_meta['location_profile_tab_'.$i][0] . '</div>';
			echo '</div>';
		}
	}
}

/**
 * Tab Box Container
 *
 * @since 1.0
 */
function epl_lp_tab_container() {

	$value = epl_lp_tab_box_list_items();

	if (!empty( $value ) ) {

		global $epl_settings;
		$class = 'epl-location-profiles-tabs-left';
		if( isset($epl_settings['epl_lp_single_tab_orientation'] ) && $epl_settings['epl_lp_single_tab_orientation'] == 0 ) {
			$class = 'epl-location-profiles-tabs-horizontal';
		}
	?>

		<div id="epl-lp-box-<?php the_ID(); ?>" class="epl-location-profiles-box <?php echo $class; ?> epl-location-profiles-box-outer-wrapper epl-clearfix">
			<h3><?php // the_title() ?></h3>
			<div class="epl-location-profiles-list">
				<?php epl_lp_tab_box_menu_items() ?>
			</div>
			<div class="epl-location-profiles-content">
				<?php epl_lp_tab_box_content() ?>
			</div>
		</div>
	<?php
	}
}

/**
 * Location Address
 *
 * @since 1.0
 */
function epl_lp_map_address() {
	global $property;
	$address_fields = array(
				'location_profile_state'	=>	'location_profile_state',
				'location_profile_postcode'	=>	'location_profile_postcode',
			);
	$address = $property->post->post_title.' ';
	foreach($address_fields as $address_field ) {
		$address .= $property->get_property_meta($address_field).' ';
	}
	return $address;
}
add_filter('epl_map_address','epl_lp_map_address');

/**
 * Theme compat
 *
 * If theme compat mode is on add the template into post's content
 *
 * @since 2.0
 */
function epl_lp_theme_compat($content) {

	global $epl_settings,$post;

	if( !isset($epl_settings['epl_feeling_lucky']) || $epl_settings['epl_feeling_lucky'] != 'on') {
		return $content;
	}

	if ( is_singular('location_profile') ) {

			epl_lp_location_profiles_single();

	} elseif( is_post_type_archive('location_profile') ) {

			epl_lp_location_profiles_loop();

	} else {
		return $content;
	}
}

add_filter('the_content','epl_lp_theme_compat');

/**
 * Apply the theme compat options
 *
 * @since 2.0
 */
function epl_lp_apply_theme_compat_config() {

	global $epl_settings;

    // remove featured image on single pages in theme compat mode
    if( isset($epl_settings['epl_lucky_disable_single_thumb']) && $epl_settings['epl_lucky_disable_single_thumb'] == 'on') {

		if ( is_single('location_profile') ) {
			remove_all_actions( 'epl_property_featured_image' );
		}

	}

    // remove featured image on archive pages in theme compat mode
    if( isset($epl_settings['epl_lucky_disable_archive_thumb']) && $epl_settings['epl_lucky_disable_archive_thumb'] == 'on') {

    	if( is_post_type_archive('location_profile') ) {
			add_filter('post_thumbnail_html','epl_lp_remove_archive_thumbnail',20,5);
		}
	}

}
add_action('wp','epl_lp_apply_theme_compat_config',1);

/**
 * A workaround to avoid duplicate thumbnails for single listings being displayed on archive pages via theme & epl
 * attempts to null the post thumbnail image called from theme & display thumbnail image called from epl
 *
 * @since 2.0
 */
function epl_lp_remove_archive_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr) {
	if( is_admin() ) {
		return $html;
	}

	if( strpos($html, 'author-thumbnail') === FALSE ) {

		// the post thumbnail is probably theme's default . remove it
		$html = '';
	}

	return $html;
}

/**
 * Enable Sliders in location profiles
 *
 * @since 2.1
 */
function epl_enable_slider_in_lp($posts) {

	$posts[] = 'location_profile';
	return $posts;
}
add_filter('epl_filter_slider_post_types','epl_enable_slider_in_lp');

/**
 * Tab orientation
 *
 * @since 2.2
 */
function epl_lp_switch_tab_orientation() {
	global $epl_settings;

	if( is_singular('location_profile') && isset($epl_settings['epl_lp_single_tab_orientation'] ) && $epl_settings['epl_lp_single_tab_orientation'] == 0 ) { ?>
		<style>
			.epl-location-profiles-tabs-horizontal.epl-location-profiles-list {
				width: 100%;
			}

			ul.location-profiles-tabs {
				display: block;
			}

			ul.location-profiles-tabs li {
				display: inline-block;
			}

		</style>

		<?php
	}
}
add_action('wp_head','epl_lp_switch_tab_orientation');

/**
 * Register location profile as epl post
 *
 * @since 2.2
 */
function epl_lp_add_to_epl_posts($posts) {
	$posts[] = 'location_profile';
	return $posts;
}
add_filter('epl_additional_post_types','epl_lp_add_to_epl_posts');

/**
 * Switch Single Template
 *
 * @since 2.3
 */
function epl_lp_switch_single_template() {
	epl_lp_location_profiles_single();
}

/**
 * Switch Loop Template
 *
 * @since 2.3
 */
function epl_lp_switch_loop_template() {

    epl_lp_location_profiles_loop();
}

/**
 * Template Overrides
 *
 * @since 2.3
 */
function epl_lp_template_overrides() {

	if( is_singular('location_profile')) {
		add_action('epl_single_template', 'epl_lp_switch_single_template');
	}

	if( is_post_type_archive('location_profile')) {
		remove_action( 'epl_property_loop_start' , 'epl_switch_views_sorting' , 20 );
		add_action('epl_loop_template', 'epl_lp_switch_loop_template');
	}

}
add_action('wp','epl_lp_template_overrides');

/**
 * Add custom Advanced Maps Tabs
 *
 * @since 2.3
 */
function epl_am_post_type_location_profile_map_tabs() {

	global $epl_settings;
	$enabled_tabs = array();
	foreach(epl_am_get_default_tab_list() as $key	=>	$label) {

		if( isset($epl_settings['epl_am_lp_enable_'.$key.'_view']) ) {
			if($epl_settings['epl_am_lp_enable_'.$key.'_view'] == 1 ) {
				$enabled_tabs[$key] = $label;
			}
		} else {
			if($key !== 'street') {
				$enabled_tabs[$key] = $label;
			}
		}
	}
	return apply_filters('epl_lp_adv_tabs',$enabled_tabs);
}

/**
 * Display Listings on Single Location Profiles
 *
 * @since 1.0
 */
function epl_lp_listings_callback() {

	$enabled = epl_get_option( 'epl_lp_single_display_listings' );
	if ( $enabled == false )
		return;

	global $property,$epl_author,$post;
	if( is_null( $property ) )
		return;

	$template 	= epl_get_option( 'epl_lp_single_listing_template' , '' );
	$limit 		= epl_get_option( 'epl_lp_single_listing_count' , 10 );


	$terms = get_the_terms( $post->ID, 'location' );

	if ( $terms != '' ) {
		foreach($terms as $term) {
			$term->slug;
		}

		$shortcode_options	=	'[listing_location location=' . $term->slug . ' template=' . $template . ' limit=' . $limit . ']';

		echo do_shortcode( $shortcode_options );
	}
}
add_action('epl_location_profile_listings','epl_lp_listings_callback');