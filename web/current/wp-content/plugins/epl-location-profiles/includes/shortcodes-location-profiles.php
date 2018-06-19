<?php
/**
 * Shortcodes
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Functions/Shortcodes
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * SHORTCODE :: [location_profile_title]
 * This shortcode allows you to insert the suburb name into a widget.
 * For use on single suburb profiles as this returns the current post's title
 *
 * @since 1.0
 */
function epl_lp_shortcode_location_profile_title( ) {

	global $epl_lp_title;
	$title = $epl_lp_title;

	return $title;
}
add_shortcode( 'location_profile_title', 'epl_lp_shortcode_location_profile_title' );

/**
 * SHORTCODE :: [location_profile]
 *
 * @since 2.0
 */
function epl_lp_shortcode_listing_callback( $atts ) {

	extract( shortcode_atts( array(
		'location'		=>	'', // Location slug. Should be a name like sorrento
		'location_id'		=>	'', // Location id.
		'limit'			=>	'10', // Number of maximum posts to show
		'template'		=>	false // Template can be set to "full" | Default is loop
	), $atts ) );

	ob_start();
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args = array(
		'post_type' 		=>	'location_profile',
		'posts_per_page'	=>	$limit,
		'paged' 		=>	$paged
	);

	if(!empty($location) ) {
		if( !is_array( $location ) ) {
			$location = explode(",", $location);
			$location = array_map('trim', $location);

			$args['tax_query'][] = array(
				'taxonomy' => 'location',
				'field' => 'slug',
				'terms' => $location
			);
		}
	}

	if(!empty($location_id) ) {
		if( !is_array( $location_id ) ) {
			$location_id = explode(",", $location_id);
			$location_id = array_map('trim', $location_id);

			$args['tax_query'][] = array(
				'taxonomy'	=> 'location',
				'field'		=> 'id',
				'terms' 	=> $location_id
			);
		}
	}

	$query_open = new WP_Query( $args );
	if ( $query_open->have_posts() ) { ?>
		<div class="loop epl-lp-shortcode">
			<div class="loop-content epl-lp-shortcode-listing">
				<?php
					while ( $query_open->have_posts() ) {
						$query_open->the_post();

						if ( $template == 'full' ) {
							epl_lp_location_profiles_single();
						} else {
							epl_lp_location_profiles_loop();
						}
					}
				?>
			</div>
			<div class="loop-footer">
				<!-- Previous/Next page navigation -->
				<div class="loop-utility clearfix">
					<div class="alignleft"><?php previous_posts_link( __( '&laquo; Previous Page', 'epl-location-profiles' ), $query_open->max_num_pages ); ?></div>
					<div class="alignright"><?php next_posts_link( __( 'Next Page &raquo;', 'epl-location-profiles' ), $query_open->max_num_pages ); ?></div>
				</div>
			</div>
		</div>
		<?php
	} else {
		echo '<h3>'.__('Nothing found, please check back later.', 'epl-location-profiles').'</h3>';
	}
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'location_profile', 'epl_lp_shortcode_listing_callback' );