<?php
/**
 * Loop Property Template: Slim home open list
 *
 * @package easy-property-listings
 * @subpackage Theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-blog-flexbox-thumbs'); ?>>

	<div class="epl-blog-internal-wrapper epl-property-blog-entry-wrapper epl-clearfix">
		<?php do_action('epl_property_loop_before_content'); ?>
			<div class="entry-header">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php do_action('epl_property_archive_featured_image' , 'thumbnail' ); ?>
				<?php endif; ?>
			</div>

			<div class="entry-footer">
				<!-- Price -->
				<div class="entry-meta price">
					<?php do_action('epl_property_price'); ?>
				</div>
			</div>
		<?php do_action('epl_property_loop_after_content'); ?>
	</div>
</div>
