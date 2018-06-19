<?php
/**
 * Map archive file to epl option value
 *
 * @since 2.2
 */
function epl_temp_archive_mapper($labels = false){

	$list = array(
		0 	=>	'default-details-right',
		1 	=>	'loop-listing-blog-1-top',
		2 	=>	'loop-listing-blog-2-super-slim',
		3 	=>	'loop-listing-blog-3-suburb',
		4 	=>	'loop-listing-blog-4-thumbnail',
		5	=>	'loop-listing-blog-5-project-card',
		6 	=>	'loop-listing-blog-6-vertical-card-fixed',
		7 	=>	'loop-listing-blog-7-two-up',
		8 	=>	'loop-listing-blog-8-suburb-card',
		9 	=>	'loop-listing-blog-9-bars',
		10 	=>	'loop-listing-blog-10-big-images',
		11 	=>	'loop-listing-blog-11-nifty',
		12 	=>	'loop-listing-blog-12-card-alternate',
		13 	=>	'loop-listing-blog-13-feature',
		14 	=>	'loop-listing-blog-14-flexbox-icons',
		15 	=>	'loop-listing-blog-15-circle-author',
		13 	=>	'loop-listing-blog-16-card-jp',

	);

	if( $labels ) {
		foreach ($list as $key => &$value) {
			preg_match('~loop-listing-blog-.*[0-9]-~',$value,$matches);
			$label = isset($matches[0]) ? str_replace($matches[0],'',$value) : $value;
			$value = ucwords( str_replace('-',' ',$label) );
		}
	}
	return $list;
}
/**
 * Map single file to epl option value
 *
 * @since 2.2
 */
function epl_temp_single_mapper($labels = false){

	$list = array(
		0 	=>	'expanded',
		1 	=>	'content-listing-single-1-condensed',
		3 	=>	'content-listing-single-3-2col-no-pic',
		4 	=>	'content-listing-single-4-2col',
		5	=>	'content-listing-single-5-project',
		6 	=>	'content-listing-single-6-2col-option-2',
		7 	=>	'content-listing-single-7-2col-option-3',
		8 	=>	'content-listing-single-8-heading-top',
		9 	=>	'content-listing-single-9-split',
		10 	=>	'content-listing-single-10-circle-author',
		11 	=>	'content-listing-single-11-text-two-columns',

	);

	if( $labels ) {
		foreach ($list as $key => &$value) {
			preg_match('~content-listing-single-.*[0-9]-~',$value,$matches);
			$label = isset($matches[0]) ? str_replace($matches[0],'',$value) : $value;
			$value = ucwords( str_replace('-',' ',$label) );
		}
	}
	return $list;
}