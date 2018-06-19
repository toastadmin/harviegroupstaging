<?php
/**
 * Loop Property Template: Lux
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
global $property;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-clearfix'); ?> <?php do_action('epl_archive_listing_atts'); ?>>
	<?php do_action('epl_property_before_content'); ?>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-header">
				<?php do_action('epl_property_archive_featured_image' , 'index_thumbnail'); ?>
				<!-- Home Open -->
			</div>
		<?php endif; ?>

		<!-- Heading -->


		<div class="entry-content">
			<h3 class="entry-title"><a href="<?php the_permalink() ?>"><?php do_action('epl_property_heading'); ?></a></h3>
			<div class="entry-meta property-address">
				<a href="<?php the_permalink(); ?>">
					<?php do_action('epl_property_address'); ?>
				</a>
			</div>
            <div class="property-excerpt">
                <?php add_filter('excerpt_length', 'lux_demo_excerpt_length', 1000); ?>
                <?php remove_filter('excerpt_more', 'epl_property_new_excerpt_more'); ?>
				<?php the_excerpt(); ?>
                <?php remove_filter('excerpt_length', 'lux_demo_excerpt_length', 1000); ?>
                <?php add_filter('excerpt_more', 'epl_property_new_excerpt_more'); ?>
			</div>
            

		</div>

		<div class="entry-footer">
			<!-- Price -->
			<div class="entry-meta price">
				<?php do_action('epl_property_price'); ?>
			</div>

			<a class="epl-more-link	" href="<?php the_permalink(); ?>">
				<?php _e('See More'); ?>
			</a>

		</div>

	<?php do_action('epl_property_after_content'); ?>
</div>
