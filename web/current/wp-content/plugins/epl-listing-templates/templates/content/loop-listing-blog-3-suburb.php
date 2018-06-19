<?php
/*
 * Loop Property Template: Blog/Address Top
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class("epl-property-blog suburb-top epl-clearfix"); ?>>
	<div class="epl-property-blog-entry-wrapper epl-clearfix">
		<div class="entry-header address-header">
			<!-- Address -->
			<div class="address alignleft">
				<?php do_action('epl_property_suburb'); ?>
			</div>
			<div class="address price alignright">
				<?php do_action('epl_property_price'); ?>
			</div>
		</div>
	    <?php do_action('epl_property_before_content'); ?>
		<div class="entry-content">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="property-box property-box-left property-featured-image-wrapper">
					<?php do_action('epl_property_archive_featured_image'); ?>
				</div>
			<?php endif; ?>

			<div class="property-box property-box-right property-content">
				<!-- Heading -->
				<h3 class="entry-title">
					<a href="<?php the_permalink() ?>"><?php do_action('epl_property_heading'); ?></a>
				</h3>
				<?php epl_the_excerpt(); ?>

				<!-- Address -->
				<div class="property-address">
					<a href="<?php the_permalink(); ?>">
						<?php do_action('epl_property_address'); ?>
					</a>
				</div>

				<!-- Property Featured Icons -->
				<div class="property-feature-icons">
					<?php do_action('epl_property_icons'); ?>
				</div>
			</div>
		</div>
		<div class="entry-footer">
			<!-- Home Open -->
			<?php do_action('epl_property_inspection_times'); ?>
		</div>
		<?php do_action('epl_property_after_content'); ?>
	</div>
</div>
