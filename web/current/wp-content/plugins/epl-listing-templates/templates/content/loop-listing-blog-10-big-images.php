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

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-blog-big-image'); ?> <?php do_action('epl_archive_listing_atts'); ?>>
	<div class="epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_before_content'); ?>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="property-box property-box-left property-featured-image-wrapper">
					<?php do_action('epl_property_archive_featured_image' , 'large'); ?>
				</div>
			<?php endif; ?>

			<div class="property-box property-box-right property-content">
				<div class="entry-content">

					<!-- Address -->
					<div class="property-address">
						<a href="<?php the_permalink(); ?>">
							<?php do_action('epl_property_suburb'); ?>
							<span class="street-address"><?php echo $property->get_formatted_property_address(); ?></span>
							<?php //do_action('epl_property_address'); ?>
						</a>
					</div>
					<!-- Price -->
					<div class="rec-price">
						<?php do_action('epl_property_price'); ?>
					</div>
					<div class="property-feature-icons epl-clearfix">
						<?php do_action('epl_property_icons'); ?>
					</div>

					<div class="rec-land-details">
						<?php do_action('rec_land_details'); ?>
					</div>

					<div class="rec-listing-more-info">
						<a href="<?php the_permalink(); ?>"><?php do_action( 'epl_temp_read_more_label' ); ?></a>
					</div>
				</div>
			</div>
		<?php do_action('epl_property_after_content'); ?>
	</div>
</div>
