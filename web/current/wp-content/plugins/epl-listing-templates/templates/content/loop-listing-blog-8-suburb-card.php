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

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-property-blog suburb-card epl-listing-grid-view-forced epl-masonry-forced'); ?>>

	<div class="epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_before_content'); ?>

			<!-- Heading -->
			<div class="property-heading-wrapper">
				<h3 class="rec-property-heading-title entry-title"><a href="<?php the_permalink() ?>"><?php echo epl_property_suburb(); ?></a></h3>
				<div class="property-feature-icons">
					<?php do_action('epl_property_icons'); ?>
				</div>
			</div>
			<div class="property-featured-image-wrapper">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php do_action('epl_property_archive_featured_image'); ?>
				<?php endif; ?>
			</div>

			<div class="property-content">
				<div class="property-info price">
					<?php do_action('epl_property_price'); ?>
				</div>
				<div class="epl-more-link">
					<a class="more-link" href="<?php the_permalink(); ?>"><?php do_action( 'epl_temp_read_more_label' ); ?></a>
				</div>
			</div>
		<?php do_action('epl_property_after_content'); ?>
	</div>
</div>
