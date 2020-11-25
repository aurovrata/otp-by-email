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

 $success = get_post_meta($contact_form->id(), '_otp_on_success',true);
 $failure = get_post_meta($contact_form->id(), '_otp_on_failure',true);
  ?>

 <h3><?= __('Select redirect pages','otp-by-email')?></h3>
 <ul id="otp-by-email-settings">
   <li style="display: inline-block">
     <label for="_otp_on_success"></label><?= __('On success','otp-by-email')?>:</label>
     <?php wp_dropdown_pages(array(
       'name'=>'_otp_on_success',
       'selected'=>$success,
       'show_option_none'=>__('Select a page...','opt-by-email'),
     ));?>
   </li>
   <li style="display: inline-block">
     <label for="_otp_on_failure"></label><?= __('On failure','otp-by-email')?>:</label>
     <?php wp_dropdown_pages(array(
       'name'=>'_otp_on_failure',
       'selected'=>$failure,
       'show_option_none'=>__('Select a page...','opt-by-email'),
     ));?>
   </li>
 </ul>
