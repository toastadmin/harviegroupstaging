<?php
/*
 * Loop Property Template: Slim home open list
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class("epl-listing-post epl-property-blog epl-property-blog-slim epl-clearfix"); ?>>
	<div class="epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_loop_before_content'); ?>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="property-box slim property-box-left property-featured-image-wrapper">
					<?php do_action('epl_property_archive_featured_image'); ?>
				</div>
			<?php endif; ?>

			<div class="property-box slim property-box-right property-content">
				<!-- Heading -->
				<h3 class="entry-title"><a href="<?php the_permalink() ?>"><?php do_action('epl_property_heading'); ?></a></h3>

				<?php //the_excerpt(); ?>

			<!-- Address -->
			<div class="property-address">
				<a href="<?php the_permalink(); ?>">
					<?php do_action('epl_property_tab_address'); ?>
				</a>
			</div>

				<?php //do_action('epl_property_inspection_times'); ?>

				<!-- Property Featured Icons -->
				<div class="property-feature-icons">
					<?php do_action('epl_property_icons'); ?>
				</div>

				<div class="price">
					<?php do_action('epl_property_price'); ?>
				</div>
			</div>
		<?php do_action('epl_property_loop_after_content'); ?>
	</div>
</div>
