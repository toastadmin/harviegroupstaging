<?php
/**
 * Meta Valuse
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Fields
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $location_profile_meta;
$location_profile_meta 	= get_post_custom();
$post_type 		= get_post_type();


// This global function allows the title to be passed to the [location_profile_title] shortcode.
global $epl_lp_title;
$epl_lp_title 		= get_the_title();

$location_profile_meta_fields = array(
	'location_profile_name'		=>	'location_profile_name',
	'location_profile_state'	=>	'location_profile_state',
	'location_profile_postcode'	=>	'location_profile_postcode',
	'location_profile_video_url'	=>	'location_profile_video_url'
);

foreach($location_profile_meta_fields as $key =>	& $location_profile_meta_field) {

	if(isset($location_profile_meta[$key])) {
		$location_profile_meta_field = isset($location_profile_meta[$key][0])	?	$location_profile_meta[$key][0] : '';
	}
}

extract($location_profile_meta_fields);

$location_profile_coords = '';
$location_profile_coords = $location_profile_name .' '. $location_profile_state .' '. $location_profile_postcode;
