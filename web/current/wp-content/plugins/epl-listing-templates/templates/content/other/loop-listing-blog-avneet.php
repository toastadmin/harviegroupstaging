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

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-theme-property-blog-avneet epl-clearfix'); ?> <?php do_action('epl_archive_listing_atts'); ?>>
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

			<p><?php epl_the_excerpt(); ?></p>
		</div>

		<div class="entry-footer">
			<!-- Price -->
			<div class="entry-meta price">
				<?php do_action('epl_property_price'); ?>
			</div>

			<div class="entry-meta read-more">
				<?php do_action( 'epl_theme_read_more' );?>
			</div>


			<!-- Property Featured Icons -->
			<div class="entry-meta property-feature-icons">
				<?php do_action('epl_property_icons'); ?>
			</div>

		</div>

	<?php do_action('epl_property_after_content'); ?>
</div>
