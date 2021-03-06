<?php
/*
Plugin Name: WSUWP Graduate Degree Programs
Version: 1.0.0
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
 * @since 0.0.1
 *
 * @return \WSUWP_Graduate_Degree_Programs
 */
function WSUWP_Graduate_Degree_Programs() {
	return WSUWP_Graduate_Degree_Programs::get_instance();
}

/**
 * Retrieve the instance of the graduate degree faculty taxonomy.
 *
 * @since 0.0.1
 *
 * @return WSUWP_Graduate_Degree_Faculty_Taxonomy
 */
function WSUWP_Graduate_Degree_Faculty_Taxonomy() {
	return WSUWP_Graduate_Degree_Faculty_Taxonomy::get_instance();
}

/**
 * Retrieves the instance of the graduate degree program name taxonomy.
 *
 * @since 0.0.1
 *
 * @return WSUWP_Graduate_Degree_Program_Name_Taxonomy
 */
function WSUWP_Graduate_Degree_Program_Name_Taxonomy() {
	return WSUWP_Graduate_Degree_Program_Name_Taxonomy::get_instance();
}

/**
 * Retrieves the instance of the graduate degree degree type taxonomy.
 *
 * @since 0.0.1
 *
 * @return WSUWP_Graduate_Degree_Degree_Type_Taxonomy
 */
function WSUWP_Graduate_Degree_Degree_Type_Taxonomy() {
	return WSUWP_Graduate_Degree_Degree_Type_Taxonomy::get_instance();
}

/**
 * Retrieves the instance of the contact taxonomy.
 *
 * @since 0.0.1
 *
 * @return WSUWP_Graduate_Degree_Contact_Taxonomy
 */
function WSUWP_Graduate_Degree_Contact_Taxonomy() {
	return WSUWP_Graduate_Degree_Contact_Taxonomy::get_instance();
}
