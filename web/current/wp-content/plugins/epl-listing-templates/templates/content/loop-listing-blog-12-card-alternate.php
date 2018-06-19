<?php
/**
 * Loop Property Template: Card home open list
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog card-alternate epl-listing-grid-view-forced epl-masonry-forced'); ?>>
	<div class="epl-blog-internal-wrapper epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_loop_before_content'); ?>
			<div class="entry-header">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php do_action('epl_property_archive_featured_image'); ?>
				<?php endif; ?>
			</div>
			<div class="entry-content">
				<!-- Heading -->
				<h3 class="entry-title"><a href="<?php the_permalink() ?>"><?php do_action('epl_property_heading'); ?></a></h3>
				<?php epl_the_excerpt(); ?>
			</div>

			<div class="entry-footer">
				<!-- Price -->
				<div class="entry-meta price">
					<?php do_action('epl_property_price'); ?>
				</div>

				<!-- Property Featured Icons -->
				<div class="property-feature-icons">
					<?php do_action('epl_property_icons'); ?>
				</div>
			</div>
		<?php do_action('epl_property_loop_after_content'); ?>
	</div>
</div>
