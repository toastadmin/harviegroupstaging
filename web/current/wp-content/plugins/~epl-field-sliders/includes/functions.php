<?php
function epl_field_sliders_enqueue_scripts() {

	wp_enqueue_script('jquery-ui-touch-punch', plugins_url('js/jquery.ui.touch-punch.min.js', dirname(__FILE__) ), array('jquery-ui-slider'));
	wp_enqueue_script('ep-field_sliders-script',plugins_url('js/lf.js', dirname(__FILE__) ));
	wp_enqueue_style('jquery-ui-css',plugins_url('css/jquery-ui.css', dirname(__FILE__) ));
	wp_enqueue_style('ep-field_sliders-style',plugins_url('css/lf.css', dirname(__FILE__) ));
}

add_action('wp_enqueue_scripts','epl_field_sliders_enqueue_scripts');
