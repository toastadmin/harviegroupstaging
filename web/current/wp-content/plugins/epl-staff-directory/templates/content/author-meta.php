<?php
/**
 * Author Meta
 * This prepares the meta data for the author profile and author box
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
$author_id 	= get_the_author_meta( 'ID' );
$name 		= get_the_author_meta( 'display_name' );
$mobile 	= get_the_author_meta( 'mobile' );
$facebook 	= get_the_author_meta( 'facebook' );
$linkedin 	= get_the_author_meta( 'linkedin' );
$google 	= get_the_author_meta( 'google' );
$twitter 	= get_the_author_meta( 'twitter' );
$email 		= get_the_author_meta( 'email' );
$skype 		= get_the_author_meta( 'skype' );
$slogan 	= get_the_author_meta( 'slogan' );
$position 	= get_the_author_meta( 'position' );
$video 		= get_the_author_meta( 'video' );
$e_video 	= wp_oembed_get($video);
$contact_form	= get_the_author_meta( 'contact-form' );

// Prepare Social Icons // Need to convert to for each loop
// Email
if ( $email != '' ) {
	$i_email = '<a class="author-icon email-icon-24" href="mailto:' . $email . '" title="'.__('Contact', 'epl-staff-directory').' '.$name.' '.__('by Email', 'epl-staff-directory').'">'.__('Email', 'epl-staff-directory').'</a>';
}
// Twitter
if ( $twitter != '' ) {
	$i_twitter = '<a class="author-icon twitter-icon-24" href="http://twitter.com/' . $twitter . '" title="'.__('Follow', 'epl-staff-directory').' '.$name.' '.__('on Twitter', 'epl-staff-directory').'">'.__('Twitter', 'epl-staff-directory').'</a>';
}
// Google
if ( $google != '' ) {
	$i_google = '<a class="author-icon google-icon-24" href="https://plus.google.com/' . $google . '" title="'.__('Follow', 'epl-staff-directory').' '.$name.' '.__('on Google', 'epl-staff-directory').'">'.__('Google', 'epl-staff-directory').'</a>';
}
// Facebook
if ( $facebook != '' ) {
	$i_facebook = '<a class="author-icon facebook-icon-24" href="http://facebook.com/' . $facebook . '" title="'.__('Follow', 'epl-staff-directory').' '.$name.' '.__('on Facebook', 'epl-staff-directory').'">'.__('Facebook', 'epl-staff-directory').'</a>';
}
// Linked In
if ( $linkedin != '' ) {
	$i_linkedin = '<a class="author-icon linkedin-icon-24" href="http://au.linkedin.com/in/' . $linkedin . '" title="'.__('Follow', 'epl-staff-directory').' '.$name.' '.__('on Linkedin', 'epl-staff-directory').'">'.__('Linkedin', 'epl-staff-directory').'</a>';
}
// Skype
if ( $skype != '' ) {
	$i_skype = '<a class="author-icon skype-icon-24" href="http://skype.com/' . $skype . '" title="'.__('Follow', 'epl-staff-directory').' '.$name.' '.__('on Skype', 'epl-staff-directory').'">'.__('Skype', 'epl-staff-directory').'</a>';
}