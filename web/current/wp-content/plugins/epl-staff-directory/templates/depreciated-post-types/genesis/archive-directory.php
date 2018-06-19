<?php
/**
 * Archive Template for Staff Directory Custom Post Type : directory
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

 get_header(); 
 do_action( 'genesis_before_content_sidebar_wrap' );
 
 genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div id="content-sidebar-wrap">',
		'context' => 'content-sidebar-wrap',
	) );

		do_action( 'genesis_before_content' );
		genesis_markup( array(
			'html5'   => '<main %s>',
			'xhtml'   => '<div id="content" class="hfeed">',
			'context' => 'content',
		) );
			do_action( 'genesis_before_loop' );
				?>
				
				<div id="primary">
					<div id="content" role="main">
						<?php
						if ( have_posts() ) : ?>
							<div class="loop">
								<div class="loop-header">
									<h4 class="loop-title">
										<?php
											the_post();
							 
											if ( is_tax() && function_exists( 'epl_is_search' ) && false == epl_is_search() ) { // Tag Archive
												$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
												$title = sprintf( __( 'Property in %s', 'epl' ), $term->name );
											}
											else if ( function_exists( 'epl_is_search' ) && epl_is_search() ) { // Search Result
												$title = __( 'Search Result', 'epl' );
											}
											
											else if ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() && function_exists( 'post_type_archive_title' ) ) { // Post Type Archive
												$title = post_type_archive_title( '', false );
											} 
											
											else { // Default catchall just in case
												$title = __( 'Listing', 'epl' );
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
										<?php do_action('epl_pagination'); ?>
									</div>
								</div>
							</div>
						<?php 
						else : ?>
							<div class="hentry">
								<div class="entry-header clearfix">
									<h3 class="entry-title"><?php _e('Listing not Found', 'epl'); ?></h3>
								</div>
								
								<div class="entry-content clearfix">
									<p><?php _e('Listing not found, expand your search criteria and try again.', 'epl'); ?></p>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			do_action( 'genesis_after_loop' );
		genesis_markup( array(
			'html5' => '</main>', //* end .content
			'xhtml' => '</div>', //* end #content
		) );
		do_action( 'genesis_after_content' );

	echo '</div>'; //* end .content-sidebar-wrap or #content-sidebar-wrap
do_action( 'genesis_after_content_sidebar_wrap' );
get_footer();
