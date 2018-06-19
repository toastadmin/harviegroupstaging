<?php
/**
 * Widget List Template
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Template/Widget/List
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
?>

<li class="epl-location-profile-list-item">
	<!-- Featured Image -->
	<?php if ( $display == 'on' && has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( $image , array( 'class' => $d_align . ' ' . $image ) ); ?>
		</a>
	<?php endif; ?>
	<!-- END Featured Image -->
	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	<?php if ( $d_excerpt == 'on' ) {
		if( function_exists('epl_the_excerpt') ) {
			epl_the_excerpt();
		} else {
			the_excerpt();
		}
	}
	?>
</li>
