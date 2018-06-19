<?php
/**
 * Recent Post Cards
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Template/Posts
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'epl-post-card' ); ?>>
	<div class="entry-header">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail">
				<?php the_post_thumbnail( 'thumbnail' ); ?>
			</div>
		<?php endif; ?>
	</div><!-- .entry-header -->
	<div class="entry-content">
		<h5><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h5>
	</div>
</div>
<!-- end .post -->