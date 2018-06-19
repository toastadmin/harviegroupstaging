<?php
/*
 * Loop Property Template: Project Card
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class("epl-listing-post epl-property-blog project-card epl-listing-grid-view-forced epl-masonry-forced"); ?>>
	<div class="epl-property-blog-entry-wrapper epl-clearfix">
		<div class="project-card-entry">
			<div class="entry-header">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="property-box property-featured-image-wrapper">

							<div class="epl-blog-image">
								<?php do_action('epl_property_archive_featured_image'); ?>
							</div>

						<?php //do_action('epl_property_inspection_times'); ?>
					</div>
				<?php endif; ?>
			</div>
	        <?php do_action('epl_property_before_content'); ?>
			<div class="entry-content">
				<!-- Address -->
				<div class="property-address">
					<a href="<?php the_permalink(); ?>">
						<?php do_action('epl_property_address'); ?>
					</a>
				</div>

				<!-- Heading -->
				<h3 class="entry-title"><a href="<?php the_permalink() ?>">
					<?php do_action('epl_property_heading'); ?>
				</a></h3>
				<div class="entry-excerpt">
					<?php epl_the_excerpt(); ?>
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
	        <?php do_action('epl_property_after_content'); ?>
		</div>
	</div>
</div>
