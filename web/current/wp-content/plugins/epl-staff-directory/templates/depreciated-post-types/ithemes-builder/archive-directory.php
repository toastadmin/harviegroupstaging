<?php
/**
 * Staff Directory archive template: iThemes Builder
**/

function render_content() {
	global $epl_settings; 
	if ( have_posts() ) : ?>
		<div class="loop">
			<div class="loop-header">
				<h4 class="loop-title">
					<?php
						the_post();
						
						if ( is_tax() ) { // Tag Archive
							$title = sprintf( __( 'Archive for %s', 'epl' ), builder_get_tax_term_title() );
						}
						else if ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() && function_exists( 'post_type_archive_title' ) ) { // Post Type Archive
							$title = post_type_archive_title( '', false );
						}
						else { // Default catchall just in case
							$title = __( 'Archive', 'epl' );
						}
						
						if ( is_paged() )
							printf( '%s &ndash; Page %d', $title, get_query_var( 'paged' ) );
						else
							echo $title;
						
						rewind_posts();
					?>
				</h4>
			</div>
			
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
					<div class="alignleft"><?php previous_posts_link( __( '&laquo; Previous Page', 'epl' ) ); ?></div>
					<div class="alignright"><?php next_posts_link( __( 'Next Page &raquo;', 'epl' ) ); ?></div>
				</div>
			</div>
		</div>
		<?php
	else : // do not delete
		do_action( 'builder_template_show_not_found' );
	endif; // do not delete
}
add_action( 'builder_layout_engine_render_content', 'render_content' );
do_action( 'builder_layout_engine_render', basename( __FILE__ ) );
