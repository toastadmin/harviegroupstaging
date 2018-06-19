<?php
/*
 * Loop Property Template: Bizbo
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class("epl-property-blog thumbnail-slim epl-clearfix"); ?>>
	<div class="epl-property-blog-entry-wrapper epl-clearfix">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="property-box epl-one-quarter property-featured-image-wrapper">
				<?php do_action('epl_property_archive_featured_image'); ?>
				<!-- Home Open -->
				<?php do_action('epl_property_inspection_times'); ?>
			</div>
		<?php endif; ?>

		<div class="property-box epl-three-quarter">
			<div class="entry-header epl-clearfix">
				<span class="address alignleft"><?php echo epl_property_category(); ?></span>
				<span class="address alignright"><?php do_action('epl_property_price'); ?></span>
			</div>

			<h3 class="entry-title"><a href="<?php the_permalink() ?>">
				<?php do_action('epl_property_heading'); ?>
			</a></h3>
			<?php do_action('epl_property_before_content'); ?>
			<div class="entry-content">
				<!-- Address -->
				<div class="property-address">
					<?php do_action('epl_property_address'); ?>
				</div>

				<?php epl_the_excerpt(); ?>

				<!-- Property Featured Icons -->
				<div class="property-feature-icons">
					<?php do_action('epl_property_icons'); ?>
				</div>
			</div>
			<?php do_action('epl_property_after_content'); ?>
		</div>
	</div>
</div>
