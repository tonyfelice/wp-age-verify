<?php
/**
 * The main plugin file.
 *
 * This file loads the main plugin class and gets things running.
 *
 * @since 0.0.1
 *
 * @package Age_Verify
 */

/**
 * Plugin Name: SEO Age Gate
 * Description: A simple way to ask visitors for their age before viewing your site.
 * Author:      Tony Felice
 * Author URI:  http://tonyfelice.co
 * Version:     0.0.3
 * Text Domain: seo-age-gate
 * Domain Path: /languages
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * The main class definition.
 */
require( plugin_dir_path( __FILE__ ) . 'includes/class-seo-age-gate.php' );

// Get the plugin running.
add_action( 'plugins_loaded', array( 'Age_Verify', 'get_instance' ) );

// Check that the admin is loaded.
if ( is_admin() ) {

	/**
	 * The admin class definition.
	 */
	require( plugin_dir_path( __FILE__ ) . 'includes/admin/class-seo-age-gate-admin.php' );

	// Get the plugin's admin running.
	add_action( 'plugins_loaded', array( 'Age_Verify_Admin', 'get_instance' ) );
}
