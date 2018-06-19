<?php
/*
 * Single Property Template: Heading Top
 *
 * @package easy-property-listings
 * @subpackage Theme
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'epl-property-single view-condensed' ); ?>>
	<!-- title, meta, and date info -->

	<div class="entry-header epl-clearfix">
		<h2 class="entry-title"><?php do_action('epl_property_heading'); ?></h2>
	</div>

	<!-- post content -->
	<div class="entry-content epl-clearfix">

		<?php do_action( 'epl_temp_single_featured_image'); ?>

		<div class="tab-wrapper">
			<div class="epl-tab-section">
				<div class="title-meta-wrapper">
					<div class="entry-col property-details">
						<?php do_action('epl_property_before_title'); ?>
						<h1 class="entry-title">
							<?php do_action('epl_property_title'); ?>
						</h1>
						<?php do_action('epl_property_after_title'); ?>

						<div class="property-feature-icons epl-clearfix">
							<?php do_action('epl_property_icons'); ?>
						</div>
					</div>

					<div class="entry-col property-pricing-details">
						<!-- Property Price-->
						<?php do_action('epl_property_price_before'); ?>
						<div class="property-meta pricing">
							<?php do_action('epl_property_price'); ?>
						</div>
						<?php do_action('epl_property_price_after'); ?>

					</div>
				</div>
			</div>

			<?php do_action( 'epl_buttons_single_property' ); ?>

			<?php do_action( 'epl_property_inspection_times' ); ?>
			<?php do_action( 'epl_property_available_dates' );// meant for rent only ?>
			<div class="tab-content">
				<?php
					do_action('epl_property_content_before');
					do_action('epl_property_the_content');
					do_action('epl_property_content_after');
				?>
			</div>
			<?php do_action('epl_property_tab_section_before'); ?>

			<div class="epl-tab-section">
				<?php do_action('epl_property_tab_section'); ?>
			</div>

			<?php do_action('epl_property_tab_section_after'); ?>

				<?php do_action( 'epl_property_gallery' ); ?>
				<?php do_action( 'epl_property_map' ); ?>
				<?php do_action( 'epl_single_extensions' ); ?>

				<?php do_action( 'epl_single_author' ); ?>
		</div>

	</div>
	<!-- categories, tags and comments -->
	<div class="entry-footer epl-clearfix">
		<div class="entry-meta">
			<?php wp_link_pages( array( 'before' => '<div class="entry-utility entry-pages">' . __( 'Pages:', 'epl-listing-templates' ) . '', 'after' => '</div>', 'next_or_number' => 'number' ) ); ?>
		</div>
	</div>
</div>
<!-- end property -->
