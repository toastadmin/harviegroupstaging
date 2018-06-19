<?php
/**
 * Single Page
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Template/Single
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('epl-sd-single directory-page-dynamic epl-clearfix'); ?>>
    <div class="entry-header">
        <?php
        if (class_exists('Easy_Property_Listings')) {
            $author_box_type = isset($epl_settings['epl_sd_staff_author_box_type']) ? $epl_settings['epl_sd_staff_author_box_type'] : '';
            if ($author_box_type == 1) {
                do_action('epl_sd_advanced_author_box');
            } else {
                do_action('epl_single_author');
            }
        } else {
            the_title();
        }
        ?>
    </div>
    <div class="entry-content">

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
