<?php
/**
 * Loop Property Template: ht
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
global $property;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog nifty epl-clearfix'); ?> <?php do_action('epl_archive_listing_atts'); ?>>

	<div class="epl-blog-internal-wrapper epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_before_content'); ?>
			<div class="entry-header">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php do_action('epl_property_archive_featured_image'); ?>
				<?php endif; ?>
			</div>

			<div class="entry-content">

				<!-- Heading -->
				<h3 class="entry-title"><a href="<?php the_permalink() ?>"><?php do_action('epl_property_heading'); ?></a></h3>

				<p><?php epl_the_excerpt(); ?></p>

				<!-- Address -->
				<div class="entry-meta property-address">
					<a href="<?php the_permalink(); ?>">
						<?php do_action('epl_property_address'); ?>
					</a>
				</div>

				<!-- Property Featured Icons -->
				<div class="entry-meta property-feature-icons">
					<?php do_action('epl_property_icons'); ?>
				</div>
			</div>


			<div class="entry-footer">
				<!-- Price -->
				<div class="entry-meta price">
					<?php do_action('epl_property_price'); ?>
				</div>

				<div class="entry-meta read-more">
					<?php do_action( 'epl_theme_read_more' );?>
				</div>
			</div>
		<?php do_action('epl_property_after_content'); ?>
	</div>
</div>
