<?php
/**
 * Loop Property Template: Mobile Open
 *
 * @package     EPL
 * @ 		Modification by N2sites for Harvie Open Inspection for Mobile Devices
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
global $property;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-property-table epl-table epl-table-open epl-clearfix'); ?>>
	<?php do_action('epl_property_before_content'); ?>
		<div class="mobile-inspection-tbl">
			<div class="mobile-inspection-row insp-time">
				<div class="mobile-inspection-col-left">
				<strong>Time</strong>
				</div>
				<div class="mobile-inspection-col-rght">
				<?php do_action('epl_property_inspection_times'); ?>
				</div>
			</div>
			<div class="mobile-inspection-row">
				<div class="mobile-inspection-col-left">
				<strong>Address</strong>
				</div>
				<div class="mobile-inspection-col-rght">
				<strong><a href="<?php the_permalink(); ?>"><?php do_action('epl_property_address'); ?></a></strong>
				</div>
			</div>
			<div class="mobile-inspection-row">
				<div class="mobile-inspection-col-left">
				<strong>Price</strong>
				</div>
				<div class="mobile-inspection-col-rght">
				<?php do_action('epl_property_price'); ?>
				</div>
			</div>
			<div class="mobile-inspection-row">
				<div class="mobile-inspection-col-left">
				<strong>Beds</strong>
				</div>
				<div class="mobile-inspection-col-rght">
				<?php do_action('epl_property_bed'); ?>
				</div>
			</div>
			<div class="mobile-inspection-row">
				<div class="mobile-inspection-col-left">
				<strong>Type</strong>
				</div>
				<div class="mobile-inspection-col-rght">
				<?php do_action('epl_property_category'); ?>
				</div>
			</div>
			<div class="mobile-inspection-row">
				<div class="mobile-inspection-col-left">
				<strong>Agent</strong>
				</div>
				<div class="mobile-inspection-col-rght">
				<?php the_author_meta('display_name', $epl_author->author_id); ?>
				</div>
			</div>
			<div class="mobile-inspection-row">
				<div class="mobile-inspection-col-left">
				</div>
				<div class="mobile-inspection-col-rght col-center">
				<a class="button-listing" href="<?php the_permalink(); ?>">Visit Listing</a>
				</div>
			</div>
		</div>
	<?php do_action('epl_property_after_content'); ?>

</div>