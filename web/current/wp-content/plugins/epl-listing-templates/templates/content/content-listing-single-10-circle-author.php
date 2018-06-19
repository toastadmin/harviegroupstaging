<?php
/*
 * Single Property Template: Expanded
 *
 * @package easy-property-listings
 * @subpackage Theme
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'epl-listing-single epl-property-single view-expanded epl-theme-property-single epl-single-circle-author' ); ?>>
	<div class="entry-header clearfix">

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="epl-featured-image">
				<a href="<?php the_permalink(); ?>">
					<?php do_action( 'epl_property_featured_image' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="epl-author-image">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="epl-author-image-with-featured">
			<?php endif; ?>

					<?php if (function_exists('epl_author_tab_image')) : ?>
						<?php epl_author_tab_image(); // Staff Directory Image ?>

					<?php elseif (function_exists('get_avatar')) : ?>
						<?php echo get_avatar( get_the_author_meta( 'ID' ) , '100' ); ?>

					<?php endif; ?>


			<?php if ( has_post_thumbnail() ) : ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="entry-content epl-content epl-clearfix">

		<div class="entry-meta">
			<span class="author"><?php printf( '<span class="author">By ' . $epl_author->get_author_name() . '</span> /' ); ?></span>
			<span class="price">
				<?php do_action('epl_property_price_before'); ?><?php do_action('epl_property_price'); ?><?php do_action('epl_property_price_after'); ?>
			</span>
		</div>

		<div class="entry-meta">
			<div class="property-feature-icons epl-clearfix">
				<?php do_action('epl_property_icons'); ?>
			</div>
		</div>

		<div class="entry-meta">
			<?php do_action('epl_property_available_dates');// meant for rent only ?>
			<?php do_action('epl_property_inspection_times'); ?>
		</div>


		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h1>

		<?php do_action( 'epl_buttons_single_property' ); ?>

		<div class="tab-wrapper">

			<div class="epl-tab-section">
				<div class="tab-content">
					<!-- heading -->
					<h2 class="entry-title"><?php do_action('epl_property_heading'); ?></h2>

					<?php
						do_action('epl_property_content_before');

						the_content();

						do_action('epl_property_content_after');
					?>
				</div>
			</div>

			<div class="epl-tab-section">

				<div class="tab-content">
					<div class="tab-content property-details">
						<?php do_action('epl_property_land_category'); ?>
						<?php do_action('epl_property_commercial_category'); ?>
					</div>
				</div>
			</div>

			<?php do_action('epl_property_tab_section_before'); ?>
			<div class="epl-tab-section">
					<?php do_action('epl_property_tab_section'); ?>
			</div>
			<?php do_action('epl_property_tab_section_after'); ?>


			<?php do_action( 'epl_property_map' ); ?>

			<?php do_action( 'epl_single_extensions' ); ?>

			<?php do_action( 'epl_property_gallery' ); ?>




			<!-- Agent -->
			<?php
			if ( get_post_type() != 'rental' ) { ?>
				<div class="epl-tab-section">
					<h5 class="tab-title"><?php _e('Real Estate Agent', 'epl'); ?></h5>
					<div class="tab-content">
						<?php do_action( 'epl_single_author' ); ?>
					</div>
				</div>
			<?php } else { ?>
				<div class="epl-tab-section">
					<h5 class="tab-title"><?php _e('Property Manager', 'epl'); ?></h5>
					<div class="tab-content">
						<?php do_action( 'epl_single_author' ); ?>
					</div>
				</div>
			<?php } ?>
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
