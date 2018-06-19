<?php
/**
 * Archive Template for Staff Directory Custom Post Type : directory
**/
global $epl_settings; 
get_header(); ?>
<section id="primary" class="site-content">
	<div id="content" role="main">
		<?php
		if ( have_posts() ) : ?>
			<div class="loop">
				<header class="archive-header loop-header">
					<h4 class="loop-title">
						<?php
							the_post();
							
							if ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() && function_exists( 'post_type_archive_title' ) ) { // Post Type Archive
								$title = post_type_archive_title( '', false );
							}
							else { // Default catchall just in case
								$title = __( 'Archive', 'epl-tm' );
							}
							
							if ( is_paged() )
								printf( '%s &ndash; Page %d', $title, get_query_var( 'paged' ) );
							else
								echo $title;
							
							rewind_posts();
						?>
					</h4>
				</header>
				
				<div class="loop-content">
					<?php
						$dir_counter = 1;
						while ( have_posts() ) : // The Loop
							the_post();
						
							if( is_sd_section_title() ) {

								epl_sd_loop_template($dir_counter,'section-header');

							} else {

								if ( has_post_thumbnail()  ) {
									epl_sd_loop_template($dir_counter,'simple-card');
								} else {
									epl_sd_loop_template($dir_counter,'simple-grav'); 
								}
						
							}
							$dir_counter++;
						endwhile; // end of one post
					?>
					<div class="epl-clearfix"></div>
				</div>
				
				<div class="loop-footer">
					<!-- Previous/Next page navigation -->
					<div class="loop-utility clearfix">
						<div class="alignleft"><?php previous_posts_link( __( '&laquo; Previous Page', 'it-l10n-Builder-Coverage' ) ); ?></div>
						<div class="alignright"><?php next_posts_link( __( 'Next Page &raquo;', 'it-l10n-Builder-Coverage' ) ); ?></div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_sidebar();
get_footer();
