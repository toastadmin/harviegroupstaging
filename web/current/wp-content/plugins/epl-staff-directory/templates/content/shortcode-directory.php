<?php
/**
 * Shortcode Listing Template
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Template/Shortcode
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.0
 */

/**
 * @var $attributes array 		Shortcode Attributes.
 * @var $query_open WP_Query 	Query object for listings.
 */

if ( $query_open->have_posts() ) {
	?>
	<div class="loop epl-shortcode epl-sd-shortcode">
		<div class="loop-content epl-shortcode-listing epl-sd-shortcode-content <?php echo epl_template_class( $attributes['template'] ); ?>">
			<?php
			if ( $attributes['tools_top'] == 'on' ) {
				do_action( 'epl_property_loop_start' );
			}
			while ( $query_open->have_posts() ) {
				$query_open->the_post();
				do_action( 'epl_loop_template' );
			}
			if ( $attributes['tools_bottom'] == 'on' ) {
				do_action( 'epl_property_loop_end' );
			}
			?>
		</div>
		<div class="loop-footer">
				<?php do_action( 'epl_pagination',array( 'query'	=> $query_open ) ); ?>
		</div>
	</div>
	<?php
	wp_reset_postdata();
} else {
	echo '<h3>' . __( 'Nothing found, please check back later.', 'easy-property-listings'  ) . '</h3>';
}