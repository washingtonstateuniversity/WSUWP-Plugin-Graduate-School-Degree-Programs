<?php
/*
Plugin Name: WSUWP Graduate Degree Programs
Version: 0.0.1
Description: Tracks and displays degree programs from the Graduate School at WSU.
Author: washingtonstateuniversity, jeremyfelt
Author URI: https://web.wsu.edu/
Plugin URI: https://github.com/washingtonstateuniversity/WSUWP-Plugin-Graduate-School-Degree-Programs
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// The core plugin class.
require dirname( __FILE__ ) . '/includes/class-wsuwp-graduate-degree-programs.php';

add_action( 'after_setup_theme', 'WSUWP_Graduate_Degree_Programs' );
/**
 * Start things up.
 *
 * @return \WSUWP_Graduate_Degree_Programs
 */
function WSUWP_Graduate_Degree_Programs() {
	return WSUWP_Graduate_Degree_Programs::get_instance();
}
