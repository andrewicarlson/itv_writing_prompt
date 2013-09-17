<?php
/*
Plugin Name: Writing Prompt
Plugin URI: http://writingprompt.ivanthevariable.com
Version: 1.0
Author: Ivan Carlson
Description: Writing Prompt...
Author URI: http://ivanthevariable.com
License: GPLv2 or Later
*/
// Setting our plugin-wide prefix so that we don't need to constantly write it out...
$prefix = "itv_writing_prompt";
// Run the function to start the whole shebang after we have the majority of core/pluggable.php loaded.
add_action( 'wp_loaded', $prefix . '_load_plugin' );
function itv_writing_prompt_load_plugin() {
	global $prefix;
	// If you're an admin user, add the scripts/styles. We don't want these cluttering up the head of the actual website.
	function itv_writing_prompt_helpers() {
		global $prefix;
		wp_register_style( $prefix . '_style', plugins_url( $prefix . '/style.css' ), '', '', 'all' );
		// And then load them...
		wp_enqueue_style($prefix . '_style');
	}
	add_action( 'admin_enqueue_scripts', $prefix . '_helpers' );
	// In the manner of keeping things organized, we'll now include the main info...
	require_once dirname( __FILE__ ) . '/form.php';
}
?>