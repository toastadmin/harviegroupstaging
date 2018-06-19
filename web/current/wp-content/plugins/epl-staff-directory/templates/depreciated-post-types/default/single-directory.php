<?php
/**
 * Single Template for Property Custom Post Type : property
**/

get_header(); ?>

<div id="primary" class="site-content">
	 <div id="content" role="main">
		<?php
		if ( have_posts() ) : ?>
			<div class="loop">
				<div class="loop-content">
					<?php
						while ( have_posts() ) : // The Loop
							the_post();
							do_action('epl_sd_single_content');
							comments_template(); // include comments template
						endwhile; // end of one post
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>	
<?php get_sidebar(); ?>
<?php get_footer(); ?>