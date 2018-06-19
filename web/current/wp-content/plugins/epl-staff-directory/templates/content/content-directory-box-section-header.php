<?php
/**
 * Author Box: Simple Card
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Template/Section
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="post-<?php the_ID(); ?>" class="epl-author-section-header epl-author-parent epl-author-archive epl-author-simple epl-author-card epl-author <?php echo $grid_class; ?>">
	<div class="entry-content">
		<div class="epl-author-box epl-author-details">
			<div class="epl-author-info">
				<h3 class="epl-author-title"><?php the_title(); ?></h3>
			</div>
			<?php

				if ( $author_excerpt == 1) {
					echo '<div class="epl-author-content">';
						if( function_exists('epl_the_excerpt') ) {
							epl_the_content();
						} else {
							the_content();
						}
					echo '</div>';
				}
			?>
		</div>
	</div>
</div>
