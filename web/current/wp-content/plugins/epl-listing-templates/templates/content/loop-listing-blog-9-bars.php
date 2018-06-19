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
$status		= $property->get_property_meta('property_status') ? ucfirst(strtolower($property->get_property_meta('property_status'))) : '';
?>

<div id="post-<?php the_ID(); ?>" <?php post_class("epl-property-blog epl-template-detailed epl-clearfix"); ?>>
	<div class="epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_loop_before_content'); ?>
			<div class="entry-header listings-top">
				<div class="listings-address">
					<span class="listings-status"><?php echo $status; ?></span>
					<strong><?php do_action('epl_property_address'); ?></strong>
					<span class="listings-price"><?php do_action('epl_property_price'); ?></span>
				</div>
			</div>
		    <?php do_action('epl_property_before_content'); ?>
			<div class="entry-content listings-summary epl-clearfix">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="property-box property-box-left property-featured-image-wrapper">
						<?php do_action('epl_property_archive_featured_image' , 'large' ); ?>
					</div>
				<?php endif; ?>
				<div class="property-box property-box-right property-content">
					<!-- Property Category -->
					<h3 class="secondary-heading"><?php do_action('epl_property_secondary_heading'); ?></h3>
					<!-- Heading -->
					<h2><?php do_action('epl_property_heading'); ?></h2>

					<div class="entry-content-wrapper">
						<?php epl_the_excerpt(); ?>
					</div>

					<div class="entry-summary">
						<?php do_action( 'epl_buttons_loop_property' ); ?>
					</div>
				</div>
			</div>
			<?php //do_action('epl_property_inspection_times'); ?>
			<?php do_action('epl_property_after_content'); ?>

			<div class="entry-footer listings-secondary">
				<div class="property-feature-icons listings-bbc">
					<?php do_action('epl_property_icons'); ?>
				</div>
				<div class="entry-summary listings-view">
					<a href="<?php the_permalink(); ?>"><?php do_action( 'epl_temp_read_more_label' ); ?></a>
				</div>
			</div>
		<?php do_action('epl_property_loop_after_content'); ?>
	</div>
</div>