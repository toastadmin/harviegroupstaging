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

<div id="post-<?php the_ID(); ?>" <?php post_class("epl-property-blog card-top epl-clearfix"); ?>>
	<div class="epl-property-blog-entry-wrapper epl-clearfix">
		<div class="entry-header">
			<!-- Address -->
			<h3 class="property-address">
				<span class="street-address alignleft">
				<!-- Address -->
					<a href="<?php the_permalink(); ?>">
						<?php do_action('epl_property_address'); ?>
					</a>
				</span>
				<span class="address price alignright"><?php do_action('epl_property_price'); ?></span>
			</h3>
		</div>
	    <?php do_action('epl_property_before_content'); ?>
		<div class="entry-content">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="property-box property-box-left property-featured-image-wrapper">
					<?php do_action('epl_property_archive_featured_image'); ?>
				</div>
			<?php endif; ?>
			<div class="property-box property-box-right property-content">
				<!-- Property Featured Icons -->
				<div class="property-feature-icons">
					<?php do_action('epl_property_icons'); ?>
				</div>
				<!-- Heading -->
				<h3 class="entry-title"><a href="<?php the_permalink() ?>">
					<?php do_action('epl_property_heading'); ?>
				</a></h3>

				<div class="entry-summary">
					<?php epl_the_excerpt( ); ?>
					<?php do_action( 'epl_buttons_loop_property' ); ?>
				</div>
			</div>
		</div>
		<div class="entry-footet">
			<?php do_action('epl_property_inspection_times'); ?>
		</div>
	    <?php do_action('epl_property_after_content'); ?>
	</div>
</div>
