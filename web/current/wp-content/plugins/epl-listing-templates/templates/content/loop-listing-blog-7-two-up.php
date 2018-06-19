<?php
/*
 * Loop Property Template: Two Up
 *
 * @package easy-property-listings
 * @subpackage Theme
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class("epl-property-blog two-up epl-masonry-forced"); ?>>
	<div class="epl-two-up-wrapper epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_loop_before_content'); ?>
			<div class="entry-header">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="property-box property-featured-image-wrapper">
						<?php do_action('epl_property_archive_featured_image' , 'large' ); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="entry-content">
				<!-- Address -->
				<div class="property-address">
					<a href="<?php the_permalink(); ?>">
						<?php do_action('epl_property_address'); ?>
					</a>
				</div>

				<div class="entry-excerpt">
					<?php // the_excerpt(); ?>
				</div>

				<!-- Property Featured Icons -->
				<div class="property-feature-icons">
					<?php do_action('epl_property_icons'); ?>
				</div>
				<!-- Price -->
				<div class="address price">
					<?php do_action('epl_property_price'); ?>
				</div>

			</div>
		<?php do_action('epl_property_loop_after_content'); ?>
	</div>
</div>
