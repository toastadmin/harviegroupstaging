<?php
/**
 * Loop Property Template: Card JP
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-blog-card-jp epl-listing-grid-view-forced epl-masonry-forced'); ?>>
	<div class="epl-blog-internal-wrapper epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_loop_before_content'); ?>
			<div class="entry-header">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php do_action('epl_property_archive_featured_image'); ?>
				<?php endif; ?>
			</div>

			<div class="entry-content">

				<!-- Address -->
				<div class="property-address">
					<a href="<?php the_permalink(); ?>">
						<?php do_action('epl_property_address'); ?>
					</a>
				</div>

				<!-- Heading -->
				<h3 class="entry-title"><a href="<?php the_permalink() ?>"><?php do_action('epl_property_heading'); ?></a></h3>

				<?php epl_the_excerpt(); ?>

				<div class="entry-meta read-more">
					<a href="<?php the_permalink(); ?>"><?php do_action( 'epl_temp_read_more_label' ); ?></a>
				</div>

				<!-- Price -->
				<div class="entry-meta price">
					<?php do_action('epl_property_price'); ?>
				</div>
			</div>

			<div class="entry-footer">
				<!-- Floor Area -->
				<div class="property-feature-icons epl-clearfix">
					<?php do_action('epl_property_icons'); ?>
				</div>
			</div>
		<?php do_action('epl_property_loop_after_content'); ?>
	</div>
</div>
