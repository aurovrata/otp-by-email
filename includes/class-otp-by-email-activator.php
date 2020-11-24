<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profiles.wordpress.org/aurovrata/
 * @since      1.0.0
 *
 * @package    Otp_By_Email
 * @subpackage Otp_By_Email/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Otp_By_Email
 * @subpackage Otp_By_Email/includes
 * @author     aurovrata <vrata@syllogic.in>
 */
class Otp_By_Email_Activator {

  /**
  * Short Description. (use period)
  *
  * Long Description.
  *
  * @since    1.0.0
  */
  public static function activate() {
    if(!is_plugin_active( 'contact-form-7/wp-contact-form-7.php' )){
      exit(__('This plugin requires the Contact Form 7 plugin to be installed', 'otp-by-email'));
    }
  }
  
}
