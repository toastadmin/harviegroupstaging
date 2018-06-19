<?php
/**
 * Author Box: Simple Card Gravatar Image
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Template/Gravatar
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" class="epl-author-child epl-author-archive epl-author-grav epl-author-card epl-author <?php echo $grid_class; ?>">
	<div class="entry-content">
		<div class="epl-author-box epl-author-image">
			<?php
				if (function_exists('get_avatar')) {
					echo get_avatar( $epl_author->email , '180' );
				}
			?>
		</div>

		<div class="epl-author-box epl-author-details">
			<h5 class="epl-author-title"><a href="<?php the_permalink(); ?>"><? the_title(); ?></a></h5>
			<?php if($show_position) { ?>
			<div class="epl-author-position"><?php echo $epl_author->get_author_position() ?></div>
			<?php } ?>
			<?php if($show_mobile) { ?>
			<div class="epl-author-contact">
				<?php if ( $epl_author->get_author_mobile() != '' ) { ?>
					<span class="label-mobile"><?php _e('Mobile', 'epl-staff-directory'); ?> </span>
					<span class="mobile"><?php echo $epl_author->get_author_mobile() ?></span>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if($show_icons) { ?>
			<div class="epl-author-social-buttons">
				<?php
					$social_icons = apply_filters('epl_display_author_social_icons',array('email','facebook','twitter','google','linkedin','skype'));
					foreach($social_icons as $social_icon){
						echo call_user_func(array($epl_author,'get_'.$social_icon.'_html'));
					}
				?>
			</div>
			<?php } ?>
			<?php if($show_vcard) { ?>
			<div class="epl-author-social-buttons">
				<a href="?epl_sd_action=epl_sd_get_vcard&author_id=<?php echo $epl_author->get_author_id(); ?>">
					<?php _e('Download Vcard','epl-staff-directory'); ?>
				</a>
			</div>
			<?php }
				if ( $author_excerpt == 1) {
					echo '<div class="epl-author-content">';
						if( function_exists('epl_the_excerpt') ) {
							epl_the_excerpt();
						} else {
							the_excerpt();
						}
					echo '</div>';
				}
			?>
		</div>
	</div>
</div>
