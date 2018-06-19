<?php
/**
 * Template for content card
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Template/Card
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-location-profile-card epl-clearfix'); ?>>

	<?php if ( $display == 'on' && has_post_thumbnail() ) : ?>
		<div class="entry-header">
			<div class="epl-location-profile-widget-image">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( $image , array('class' => $d_align . ' ' . $image )); ?>
				</a>
			</div>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
		<?php if ( $d_excerpt == 'on' ) {
			if( function_exists('epl_the_excerpt') ) {
				epl_the_excerpt();
			} else {
				the_excerpt();
			}
		} ?>
		<?php // Read More
		if ( $d_more == 'on') { ?>
			<form class="epl-property-button more-link" action="<?php the_permalink(); ?>" method="post">
				<input type=submit value="<?php echo $more_text; ?>" />
			</form>
		<?php } ?>
	</div>
</div>
