<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/aurovrata/
 * @since             1.0.0
 * @package           Otp_By_Email
 *
 * @wordpress-plugin
 * Plugin Name:       OTP by Email for Contact Form 7
 * Plugin URI:        https://github.com/aurovrata/otp-by-email
 * GitHub Plugin URI: https://github.com/aurovrata/otp-by-email
 * Description:       Enable OTP validation of an email field using the CF7 notification email.
 * Version:           1.1.1
 * Author:            aurovrata
 * Author URI:        https://profiles.wordpress.org/aurovrata/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       otp-by-email
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'OTP_BY_EMAIL_VERSION', '1.1.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-otp-by-email-activator.php
 */
function activate_otp_by_email() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-otp-by-email-activator.php';
	Otp_By_Email_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-otp-by-email-deactivator.php
 */
function deactivate_otp_by_email() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-otp-by-email-deactivator.php';
	Otp_By_Email_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_otp_by_email' );
register_deactivation_hook( __FILE__, 'deactivate_otp_by_email' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-otp-by-email.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_otp_by_email() {

	$plugin = new Otp_By_Email();
	$plugin->run();

}
run_otp_by_email();
