<?php
/**
 * Scripts & Styles
 *
 * @package     EPL_FS
 * @subpackage  Scripts/Styles
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load and enqueue admin scripts and stylesheets
 */
function epl_fs_admin_enqueue_scripts($screen) {
	//wp_enqueue_style( 'epl-fs-admin-style', EPL_FS_PLUGIN_URL_CSS . 'style-admin.css' );
	//wp_enqueue_script( 'epl-fs-common-scripts', EPL_FS_PLUGIN_URL_JS . 'common.js', 'jquery' );
}
add_action( 'admin_enqueue_scripts', 'epl_fs_admin_enqueue_scripts' );

/**
 * Load and enqueue front end scripts and stylesheets only if shortcode is present		
 */
function epl_fs_wp_enqueue_scripts() {
	global $post;
	if( !is_404() && ( has_shortcode( $post->post_content, 'epl_frontend_submission') || has_shortcode( $post->post_content, 'epl_frontend_user_listings')) ) {
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_media();
		wp_enqueue_style( 'epl-fs-autocomplete', EPL_FS_PLUGIN_URL_CSS . 'autocomplete.css' );
		wp_enqueue_style( 'epl-fs-front-style', EPL_FS_PLUGIN_URL_CSS . 'style-front.css' );
		wp_enqueue_script( 'epl-fs-front-scripts', EPL_FS_PLUGIN_URL_JS . 'scripts.js', 'jquery' );
		
		// enqueue core css & js required
		$current_dir_path = EPL_PLUGIN_URL.'lib/assets';
		wp_enqueue_style(	'epl-jquery-validation-engine-style', 		$current_dir_path . '/css/validationEngine-jquery.css' );
		wp_enqueue_script(	'epl-jquery-validation-engine-lang-scripts', 	$current_dir_path . '/js/jquery-validationEngine-en.js', array('jquery') );
		wp_enqueue_script(	'epl-jquery-validation-engine-scripts', 	$current_dir_path . '/js/jquery-validationEngine.js', 	array('jquery') );
		wp_enqueue_script(	'jquery-datetime-picker',			$current_dir_path . '/js/jquery-datetime-picker.js', 	array('jquery') );
		wp_enqueue_style(	'jquery-ui-datetime-picker-style',  		$current_dir_path . '/css/jquery-ui.min.css');

		wp_localize_script( 'epl-fs-front-scripts', 'epl_fs_vars', array(
			'ajaxurl'	=>	admin_url('admin-ajax.php'),
		) );

	}
}
add_action( 'wp_enqueue_scripts', 'epl_fs_wp_enqueue_scripts' );
