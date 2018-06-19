<?php
/**
 * Single Page Big Image
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Template/Big
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('epl-sd-single epl-sd-big-image directory-page-dynamic epl-clearfix'); ?>>
    <div class="entry-header">
        <?php if (has_post_thumbnail()) { ?>
            <div class="entry-image">
                <?php the_post_thumbnail('index_thumbnail', array('class' => 'index-thumbnail')); ?>
            </div>
        <?php }
        ?>
    </div>

    <div class="entry-content">

        <div class="contact-details epl-clearfix">
            <div class="contact-details-left">
                <!--- Author Page Style --->
                <?php if ($author_style == 1) { ?>
                    <h5 class="author-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <?php } else { ?>
                    <h5 class="author-title">
                        <a href="<?php echo get_author_posts_url($epl_author->author_id); ?>">
                            <?php the_author_meta('display_name', $epl_author->author_id); ?>
                        </a>
                    </h5>
                <?php } ?>

                <div class="author-position"><?php echo $epl_author->get_author_position() ?></div>

                <?php if ($epl_author->get_author_mobile() != '') { ?>
                    <span class="label-mobile"><?php _e('Mobile', 'epl-staff-directory'); ?> </span>
                    <span class="mobile"><?php echo $epl_author->get_author_mobile() ?></span>
                <?php } ?>
            </div>

            <div class="contact-details-right author-social-buttons">
                <?php
                    $social_icons = apply_filters('epl_display_author_social_icons', array('email', 'facebook', 'twitter', 'google', 'linkedin', 'skype'));
                    foreach ($social_icons as $social_icon) {
                        echo call_user_func(array($epl_author, 'get_' . $social_icon . '_html'));
                    }
                ?>
            </div>
        </div>

        <?php
        	if( function_exists('epl_the_content') ) {
        		epl_the_content();
    		} else {
				the_content();
    		}
    	?>

	<?php do_action( 'epl_sd_single_recent_posts' ); ?>

	<?php do_action( 'epl_sd_single_staff_listings' ); ?>

	<?php

		// allow other extension to add their content on single staff page
		do_action('epl_sd_single_extension');

		wp_link_pages(
			array(
				'before' => '<p><strong>' . __('Pages:', 'epl-staff-directory') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number'
			)
		);
		edit_post_link(__('Edit this entry.', 'epl-staff-directory'), '<p class="edit-entry-link">', '</p>');
	?>
    </div>
</div>
