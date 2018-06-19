<?php
/**
 * Loop Property Template: Default
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
global $property;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-blog-feature epl-clearfix'); ?> <?php do_action('epl_archive_listing_atts'); ?>>
		<div class="epl-blog-internal-wrapper epl-property-blog-entry-wrapper epl-clearfix">
			<?php do_action('epl_property_before_content'); ?>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-header">
						<?php do_action('epl_property_archive_featured_image' , 'large'); ?>
						<!-- Home Open -->
					</div>
				<?php endif; ?>

				<div class="entry-content">
					<!-- Heading -->
					<h3 class="entry-title"><a href="<?php the_permalink() ?>"><?php do_action('epl_property_heading'); ?></a></h3>
					<?php epl_the_excerpt(); ?>

					<div class="entry-meta property-address">
						<a href="<?php the_permalink(); ?>">
							<?php do_action('epl_property_address'); ?>
						</a>
					</div>
				</div>

				<div class="entry-footer">


					<!-- Property Featured Icons -->
					<div class="entry-meta property-feature-icons">
						<?php do_action('epl_property_icons'); ?>
					</div>

					<!-- Price -->
					<div class="entry-meta price">
						<?php do_action('epl_property_price'); ?>
					</div>

				</div>
			<?php do_action('epl_property_after_content'); ?>
		</div>
</div>
