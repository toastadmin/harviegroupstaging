<?php
/**
 * Template for Tab Content
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Template/Single
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'epl-location_profile-single-tabs' ); ?>>

		<!-- Location Profile Tab -->
		<div class="tab-wrapper">

			<!-- Fast Facts -->
			<div class="tab-section">
				<h5 class="tab-title"><?php _e('Fast Facts','epl-location-profiles');?></h5>
				<div class="tab-content">
					<ul>
						<li><?php the_title(); ?>'s <?php _e('Postcode is','epl-location-profiles'); echo $location_profile_postcode; ?></li>
						<?php if ($location_profile_local_council != '') { echo '<li>Local Council is ' , $location_profile_local_council , '</li>'; } ?>
						<?php if ($location_profile_investors_are != '') { echo '<li>Investors are ' , $location_profile_investors_are , '</li>'; } ?>
					</ul>
				</div>
			</div>
			<!-- People & Property -->
			<?php if ($location_profile_people_property != '') { ?>
				<div class="tab-section">
					<h5 class="tab-title"><?php _e('People & Property','epl-location-profiles');?></h5>
					<div class="tab-content">
						<?php echo $location_profile_people_property; ?>
					</div>
				</div>
			<?php } ?>

			<!-- Location  -->
			<?php if ($location_profile_location != '') { ?>
				<div class="tab-section">
					<h5 class="tab-title"><?php _e('Location','epl-location-profiles');?></h5>
					<div class="tab-content">
						<?php echo $location_profile_location; ?>
					</div>
				</div>
			<?php } ?>

			<!-- Amenities -->
			<?php if ($location_profile_amenities != '') { ?>
				<div class="tab-section">
					<h5 class="tab-title"><?php _e('Amenities','epl-location-profiles');?></h5>
					<div class="tab-content">
						<?php echo $location_profile_amenities; ?>
					</div>
				</div>
			<?php } ?>

			<!-- Recreation -->
			<?php if ($location_profile_recreation != '') { ?>
				<div class="tab-section">
					<h5 class="tab-title"><?php _e('Recreation','epl-location-profiles');?></h5>
					<div class="tab-content">
						<?php echo $location_profile_recreation; ?>
					</div>
				</div>
			<?php } ?>

			<!-- Transport -->
			<?php if ($location_profile_transport != '') { ?>
				<div class="tab-section">
					<h5 class="tab-title"><?php _e('Transport','epl-location-profiles');?></h5>
					<div class="tab-content">
						<?php echo $location_profile_transport; ?>
					</div>
				</div>
			<?php } ?>

			<!-- Community Features -->
				<!--
				<div class="tab-section">
						<h5 class="tab-title">Location Profile Features</h5>
					<div class="tab-content">
						<?php //echo get_the_term_list($post->ID, 'community_feature', '<li>', '</li><li>', '</li>' ); ?>
					</div>
				</div>
				-->

			<!-- Gallery -->
			<?php // check if the post has a Post Thumbnail assigned to it.
				$attachments = get_children( array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image') );
				if ( $attachments ) { ?>
					<div class="tab-section">
						<h5 class="tab-title"><?php _e('Gallery','epl-location-profiles');?></h5>
						<div class="tab-content">
							<?php echo do_shortcode('[gallery columns="4" link="file"]'); ?>
						</div>
					</div>
				<?php } ?>


			<!-- End Sales Graph-->
		</div>

	<!-- categories, tags and comments -->
	<div class="entry-footer clearfix">
		<div class="entry-meta">
			<?php wp_link_pages( array( 'before' => '<div class="entry-utility entry-pages">' . __( 'Pages:', 'it-l10n-Builder' ) . '', 'after' => '</div>', 'next_or_number' => 'number' ) ); ?>
		</div>
	</div>
</div>