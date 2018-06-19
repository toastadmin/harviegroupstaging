<?php
/**
 * Functions
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Functions/Global
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Post Type: Profile Location Label
 *
 * @since 1.0
 * @return String
 */
function epl_post_profile_location_label() {
	$label_location_profile = '';
	$epl_settings = epl_settings();

	if(!empty($epl_settings) && isset($epl_settings['epl_lp_post_type_name'])) {
		$label_location_profile = trim($epl_settings['epl_lp_post_type_name']);
	}
	if(empty($label_location_profile)) {
		$label_location_profile = 'Location Profile';
	}
	return $label_location_profile;
}

/**
 * Post Type: List Label
 *
 * @since 1.0
 * @return String
 */
function epl_post_profile_location_list($i='') {
	if($i != '') {
		$label_lp_list_item_i = '';
		$epl_settings = epl_settings();

		if(!empty($epl_settings) && isset($epl_settings['epl_lp_label_list_item_'.$i])) {
			$label_lp_list_item_i = trim($epl_settings['epl_lp_label_list_item_'.$i]);
		}
		if(empty($label_lp_list_item_i)) {
			$label_lp_list_item_i = 'Bullet List Name ' . $i;
		}
		return $label_lp_list_item_i;
	}
}

/**
 * Post Type: Tab Label
 *
 * @since 1.0
 * @return String
 */
function epl_post_profile_location_tab($i='') {
	$label_lp_tab_item_i = '';
	$epl_settings = epl_settings();

	if(!empty($epl_settings) && isset($epl_settings['epl_lp_label_tab_'.$i])) {
		$label_lp_tab_item_i = trim($epl_settings['epl_lp_label_tab_'.$i]);
	}
	if(empty($label_lp_tab_item_i)) {
		$label_lp_tab_item_i = __('Tab Name ', 'epl-location-profiles' ) . $i;
	}
	return $label_lp_tab_item_i;
}

/**
 * Enable Post Type in EPL core
 *
 * @since 2.1.1
 * @return String
 */
function epl_add_lp_to_post_types($post_types) {
	$post_types[] = 'location_profile';
	return $post_types;
}
add_filter('epl_additional_post_types','epl_add_lp_to_post_types');

/**
 * Add location profiles to single staff dir page
 *
 * @since 2.1
 */
function epl_lp_single_staff_location_profile_callback() {

	global $epl_author, $epl_settings;

	if( isset($epl_settings['epl_sd_show_location_profiles'])  && $epl_settings['epl_sd_show_location_profiles'] == 'yes') {

		$quantity 	= isset($epl_settings['epl_sd_location_profiles_count']) ? $epl_settings['epl_sd_location_profiles_count'] : '4';

		$suburbs_args = array(
			'post_type' 		=> 'location_profile',
			'author' 		=> $epl_author->author_id,
			'posts_per_page' 	=> $quantity
		);
		$suburb_query = new WP_Query($suburbs_args);
		if ($suburb_query->have_posts()) {
			?>
			<div class="epl-sd-location-profile directory-section epl-clearfix">
			<h4 class="epl-sd-section-title epl-tab-title"><?php echo isset($epl_settings['epl_lp_post_type_name']) ? $epl_settings['epl_lp_post_type_name'] : __('Suburbs','epl-location-profiles'); ?></h4>
			<?php
			while ($suburb_query->have_posts()) {
				$suburb_query->the_post();
				echo epl_lp_location_profiles_card();
			}
			wp_reset_postdata();
			?>
			</div>
			<?php
		}
	}
}
add_action( 'epl_sd_single_extension' , 'epl_lp_single_staff_location_profile_callback' , 20 );
