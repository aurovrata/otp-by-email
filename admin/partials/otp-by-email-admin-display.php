<?php

/**
 * PDisplay OTP tab settings in CF& editor page.
 *
 * @link       https://profiles.wordpress.org/aurovrata/
 * @since      1.0.0
 *
 * @package    Otp_By_Email
 * @subpackage Otp_By_Email/admin/partials
 */
$tags = $contact_form->scan_form_tags();
$display = false;
foreach ( (array) $tags as $tag ) {
	if ( !empty($tag['name']) && 'email' == $tag['basetype'] ) $display=true;
}
if( $display ):
	$success = get_post_meta($contact_form->id(), '_otp_on_success',true);
	$failure = get_post_meta($contact_form->id(), '_otp_on_failure',true);
?>
 <h3><?= __('Select redirect pages','otp-by-email')?></h3>
 <ul id="otp-by-email-settings">
   <li style="display: inline-block">
     <label for="otp-on-success"><?= __('On success','otp-by-email')?>:</label>
     <?php wp_dropdown_pages(array(
       'id'=>'otp-on-success',
       'name'=>'_otp_on_success',
       'selected'=>$success,
       'show_option_none'=>__('Select a page...','opt-by-email'),
     ));?>
   </li>
   <li style="display: inline-block">
     <label for="otp-on-failure"><?= __('On failure','otp-by-email')?>:</label>
     <?php wp_dropdown_pages(array(
       'id'=>'otp-on-failure',
       'name'=>'_otp_on_failure',
       'selected'=>$failure,
       'show_option_none'=>__('Select a page...','opt-by-email'),
     ));?>
   </li>
 </ul>
 <?php else:?>
	<p><?php _e('You currently have no email field in your form.  You need to create an email field, and save your form for the OTP settings and mail tag to function.','opt-by-email')?></p>
<?php endif;?>
<p><?php _e('To understand how to use OTP by Email, please read the <a href="https://wordpress.org/plugins/otp-by-email/#faq">FAQ</a> on the plugin page.','otp-by-email');?></p>