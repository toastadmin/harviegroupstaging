<?php
/*
 * Single Property Template: 2 Column Option 3
 *
 * @package easy-property-listings
 * @subpackage Theme
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'epl-property-single view-2-column epl-2-column-option-3' ); ?>>

	<?php do_action( 'epl_temp_single_featured_image'); ?>

	<!-- title, meta, and date info -->
	<div class="entry-2-col content-col">
		<div class="entry-content epl-clearfix">
			<!-- post content -->
			<h2 class="entry-title"><?php do_action('epl_property_heading'); ?></h2>

			<?php do_action('epl_property_before_title'); ?>
			<h1 class="entry-title"><?php do_action('epl_property_title'); ?></h1>
			<?php do_action('epl_property_after_title'); ?>


				<?php
					do_action('epl_property_content_before');
					do_action('epl_property_the_content');
					do_action('epl_property_content_after');
				?>
				<?php do_action( 'epl_single_listing_contact_form' ); ?>

				<?php do_action( 'epl_single_extensions' ); ?>
		</div>
	</div>

	<div class="entry-2-col sidebar-col">
		<div class="title-meta-wrapper">

			<div class="property-pricing-details">
				<?php do_action('epl_property_price_before'); ?>
				<div class="property-meta pricing">
					<?php do_action('epl_property_price'); ?>
				</div>
				<?php do_action('epl_property_price_after'); ?>
			</div>

			<div class="property-feature-icons epl-clearfix">
				<?php do_action('epl_property_icons'); ?>
			</div>

			<?php do_action('epl_property_tab_section_before'); ?>
			<div class="epl-tab-section">
				<?php do_action('epl_property_tab_section'); ?>
			</div>
			<?php do_action('epl_property_tab_section_after'); ?>
		</div>

		<?php do_action( 'epl_property_inspection_times' ); ?>
		<?php do_action( 'epl_property_available_dates' );// meant for rent only ?>

		<div class="epl-tab-section">
			<div class="tab-content">
				<?php do_action( 'epl_buttons_single_property' ); ?>
			</div>
		</div>
		<div class="epl-tab-section">
			<?php do_action( 'epl_property_map' ); ?>
		</div>
		<div class="epl-tab-section">
			<?php do_action( 'epl_single_author' ); ?>
		</div>
	</div>

	<div class="entry-footer epl-clearfix">

	</div>

	<div class="entry-meta">
		<?php wp_link_pages( array( 'before' => '<div class="entry-utility entry-pages">' . __( 'Pages:', 'epl-listing-templates' ) . '', 'after' => '</div>', 'next_or_number' => 'number' ) ); ?>
	</div>
</div>
<!-- end property -->
