<?php
/**
 * Template Functions
 *
 * @package     EPL-LISTING-TEMPLATES
 * @subpackage  Functions/Admin
 * @copyright   Copyright (c) 2015, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Masonry script for front end
 *
 * Initiate flexslider & enable masonry on archives if enabled from admin panel
 *
 * @since 1.0
 */
function epl_temp_inline_js_vars() {
    global $epl_settings;

    ?>
    <script>
        var listingsMasonEnabled = <?php echo isset($epl_settings['listings_masonry']) ? $epl_settings['listings_masonry'] : 0 ?>;
	</script><?php
}
add_action('wp_head', 'epl_temp_inline_js_vars');

/**
 * Front end styles & scripts
 *
 * @since 1.0
 */
function epl_temp_front_scripts() {

	$current_dir_path = plugins_url('', __FILE__);

	if ( epl_get_option('listings_masonry') == 1 ) {
		wp_enqueue_script('jquery-masonry');
	}

	// Show or Hide the Grid/List View (Default is show)
	if ( is_epl_post_archive() && epl_get_option('epl_temp_hide_grid_list' , 1 ) != 1 ) {
		wp_enqueue_style( 'epl-temp-front-hide-grid', 	EPL_TEMPLATES_CSS . 'style-listing-templates-hide-grid.css', 	false, 			EPL_TEMPLATES_VERSION );
	}

	wp_enqueue_script(	'epl-temp-front-scripts', 	$current_dir_path . '/js/epl-temp-scripts.js', 			array('jquery'),	EPL_TEMPLATES_VERSION );
	wp_enqueue_style( 	'epl-temp-front-styles', 	EPL_TEMPLATES_CSS . 'style-listing-templates.css', 		false, 			EPL_TEMPLATES_VERSION );
	wp_enqueue_style( 	'epl-temp-front-styles-single', EPL_TEMPLATES_CSS . 'style-listing-templates-single.css', 	false, 			EPL_TEMPLATES_VERSION );
}
add_action('wp_enqueue_scripts', 'epl_temp_front_scripts');

/**
 * Listing Categories
 *
 * @since 1.0
 */
function epl_temp_add_listing_categories($field) {
	$post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
	if ($post_id == 0)
		return $field;

	$post = get_post($post_id);
	if(is_null($post))
		return;

	$sales_listings = array('property', 'land', 'commercial', 'business', 'commercial_land', 'rural');
	if (in_array($post->post_type, $sales_listings) && !in_array($post->post_type, $field['exclude'])) {
		$field['opts']['comingsoon'] = __('Coming soon', 'epl-listing-templates');
	}
	return $field;
}
add_action('epl_meta_property_category', 'epl_temp_add_listing_categories');

/**
 * Attempts to load templates in order of priority
 *
 * @since 1.0
 */
function epl_temp_get_template_part( $template, $arguments=array() ) {

	$base_path		= EPL_TEMPLATES_PATH_CONTENT;
	$default		= $template;
	$find[] 		= epl_template_path() . $template;
	$template       	= locate_template( array_unique( $find ) );
	if(!$template) {
		$template	= $base_path . $default;
	}
	if( !isset($arguments['epl_author']) ) {
		global $epl_author;
	}
	extract($arguments);

	include( $template);
}

/**
 * Single Template
 *
 * Override default templates of core epl posts as selected from admin
 *
 * @since 1.0
 */
function epl_switch_single_templates () {

	$selected = (int) epl_get_option('epl_display_single_property');

	$list = epl_temp_single_mapper();
	if( isset($list[$selected]) ) {
		epl_temp_get_template_part( "$list[$selected].php" );
	}
}


/**
 * Archive Loop Templates
 *
 * @since 1.0
 */
function epl_switch_loop_templates() {

	$selected = (int) epl_get_option('epl_property_card_style');

	$list = epl_temp_archive_mapper();
	if( isset($list[$selected]) ) {
		epl_temp_get_template_part( "$list[$selected].php" );
	}

}

function epl_templ_custom_archive_wrapper_classes($classes,$context) {

	if($context == 'archive'){

		$flexbox_wrapper = epl_get_option( 'epl_property_card_style' , false );

		$template = '';
		if ( $flexbox_wrapper == 14 ) {
			$template = ' epl-flexbox-wrapper';
		}
		$masonry = epl_get_option( 'listings_masonry' , 0 );
		if ( $masonry == 1 ) {
			$template = ' epl-masonry-forced-wrapper';
		}

		$classes = 'epl-theme-property-blog-wrapper '.$template;
	}

	return $classes;
}

add_filter('epl_template_class','epl_templ_custom_archive_wrapper_classes',10,2);

/**
 * Override Templates
 *
 * @since 1.0
 */
function epl_templ_template_overrides() {
	global $post;

	if(is_null($post))
		return;

	/** only trigger this hook if custom single template has been assigned from admin **/
	if( (int) epl_get_option('epl_display_single_property') > 0 && is_epl_core_post() ) {
		add_action('epl_single_template', 'epl_switch_single_templates');
	}

	/** only trigger this hook if custom archive template has been assigned from admin  **/
	if(
		(int) (epl_get_option('epl_property_card_style') > 0) &&
		(
			is_post_type_archive(epl_get_core_post_types()) ||
			has_shortcode($post->post_content, 'listing') ||
			has_shortcode($post->post_content, 'listing_category') ||
			has_shortcode($post->post_content, 'listing_open') ||
			has_shortcode($post->post_content, 'listing_feature') ||
			has_shortcode($post->post_content, 'listing_location')
		 )
	) {
		add_action('epl_loop_template', 'epl_switch_loop_templates');
	}
}
add_action('wp','epl_templ_template_overrides');

/**
 * Featured Image Setting
 *
 * @since 1.0
 */
function epl_temp_featured_image_setting() {

	if( !epl_get_option('epl_display_featured_image') ) {
		do_action('epl_property_featured_image');
	}
}
add_action( 'epl_temp_single_featured_image' , 'epl_temp_featured_image_setting' );

/**
 * Adding Author Box title with plural option which checks to make sure an actual user exists.
 *
 * @since 2.0.1
 */
function epl_temp_author_box_entry_title() {
	global $post, $property;

	$epl_posts 		= epl_get_active_post_types();
	$epl_posts 		= array_keys($epl_posts);
	$post_type		= get_post_type();

	if( is_single() && in_array( $post->post_type,$epl_posts ) ) {
		$property_second_agent	= $property->get_property_meta('property_second_agent');
		$second_author 		= get_user_by( 'login' , $property_second_agent );
		$plural			= $second_author !== false ?  "'s" : '';

		if ( $post_type == 'rental'){
			echo "<h5 class='epl-tab-title epl-author-box-title'>" . __( 'Property Manager' , 'epl-listing-templates' ) . $plural . "</h5>";
		} else {
			echo "<h5 class='epl-tab-title epl-author-box-title'>" . __( 'Real Estate Agent' , 'epl-listing-templates' ) . $plural . "</h5>";
		}
	}
}

/**
 * Author Box Entry Title
 *
 * @since 1.0
 */
function epl_temp_author_box_entry_title_enable() {
	global $epl_settings;
	$enable_title = isset($epl_settings['epl_display_author_title']) ? $epl_settings['epl_display_author_title'] : 0 ;
	return $enable_title;
}
if ( epl_temp_author_box_entry_title_enable() == 1 ) {
	add_action( 'epl_single_author' , 'epl_temp_author_box_entry_title' , 5 );
}

/**
 * Masonry Class
 *
 * @since 1.0
 */
function epl_temp_masonry_class( $classes ) {
	global $epl_settings, $post;

	$epl_posts 		= epl_get_active_post_types();
	$epl_posts 		= array_keys($epl_posts);
	$post_type		= get_post_type();

	if ( is_post_type_archive() && in_array( $post->post_type,$epl_posts )) {
		$classes[] = isset($epl_settings['listings_masonry']) && $epl_settings['listings_masonry'] == 1 ? 'epl-masonry' : '';
	}
	return $classes;
}
add_filter( 'post_class', 'epl_temp_masonry_class' );

add_action('epl_property_suburb','epl_property_suburb');

/**
 * Add Read More from Core
 *
 * @since 2.2
 */
add_action('epl_temp_read_more','epl_button_read_more');

/**
 * Masonry Class
 *
 * @since 2.2
 */
function epl_temp_read_more_label_callback() {

	echo epl_get_option('epl_temp_read_more' , __( 'View &raquo;' , 'epl-listing-templates' ) );
}
add_action( 'epl_temp_read_more_label', 'epl_temp_read_more_label_callback' );