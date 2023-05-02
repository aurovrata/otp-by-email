=== OTP by Email for Contact Form 7 ===
Contributors: aurovrata
Donate link: https://www.paypal.com/donate?hosted_button_id=V6CMZPJSW7KXS
Tags: OTP, email validation, contact form 7 extension, OTP by email, email verification
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 6.2
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A small Contact Form 7 extension plugin to enable email confirmation by unique links sent to the email inbox.

== Description ==

Use this CF7 extension to enable OTP links to be inserted into confirmation/notification emails sent to email addresses submitted through your CF7 forms.  The OTP is a unique link the email inbox user needs to click to confirm their address.  The link is valid by default for 72 hours and can be customised.

== Installation ==


1. Install Contact Form 7 plugin
2. Install OTP by Email plugin extension
3. OTP tag requires an email field in your CF7 forms.
4. In CF7 forms with email fields, a mail tag `[otp-<field-name>]` will be available to insert into your notification/confirmation email which will all a unique time-limited URL for users to confirm their email.
5. In the form editor page, the OTP tab allows you to set the pages to redirect to when an email is validated or fails due to an outdated link.

== Frequently Asked Questions ==

= 0. What does this plugin do ? =

If you are collecting an email address in your CF7 form from a visitor, you can use this plugin to validate that email.

The email field name will be used to create a unique OTP mail tag shortcode to insert into your notification mail (sent to that email).  The shortcode will be replaced by a unique URL which the recipient can click on to validate their email.  This unique link is created using WordPress nonce API, and it stored in the DB as a transient with a default validity of 72 hours (3 days).  You can customise this validity period, please refer to the FAQ below for more details.  When a recipient clicks on a valid link, they are redirected to the <em>On Success</em> page you configured in the OTP tab.  If the link is no longer valid, they are taken to the <em>On failure</em> page instead, where you may give them the option to send them a new OTP link.

You can use this plugin to go beyond simple email validation, as each valid user now has a unique way to identify themselves on your server.  I have used this plugin to create a conference registration and submission form for speakers without having them to actually create a user account on the server.  A visitor registers as a speaker, and once their email is validated, they use their OTP link (which expires a week prior to the conference start date) in order to submit talks for the conference without having to have an account.  

If you need to explore such functionality, you can contact me on "vrata at syllogic dot in".


= 1. Is it possible to change the time-limit for the link validity ? =

The unique confirmation link has a 3-day (72 hours) validity by default, you can change it with the following filter,

`
add_filter('otp_by_email_lifetime', 'otp_by_email_validity',10,3);
function otp_by_email_validity($limit, $email, $form_id){
  //you can set different time limits for different email domains or form ID.
  $limit = 24 * HOUR_IN_SECONDS;  //limit is in seconds.
  return $limit;
}
`

= 2. How to retrieve an email that has been validated ? =

Use the following filter,

`
add_filter('otp_by_email_validated', 'otp_validated',10,3);
function otp_validated($url, $email, $form_id){
  //you can identify a validated email and the form ID from which it was submitted.
  //you can also change the URL to which you want to redirect.
  return $url;
}`

= 3. How to retrieve an email that failed due to an outdated link ? =

Use the following filter,

`
add_filter('otp_by_email_failed', 'otp_failed',10,3);
function otp_failed($url, $email, $form_id){
  //you can identify a failed email and the form ID from which it was submitted.
  //you can also change the URL to which you want to redirect.
  return $url;
}`

= 4. Is it possible to get an OTP link for an email programmatically ? =

Yes, you can use the following function to retrieve a unique link,

`
/**
* Funiton to get a link for email validation.
* @param String $email a valid email.
* @param String $form_id the id of the form on which this email was submitted. (Defaults to 0).
* @return String a unique link to validate the email, reurns false if the $email is invalid.
* @since 1.0
*/
$link = get_otp_by_email_link($email, $form_id=0);
`

== Screenshots ==


== Changelog ==
= 1.2.0 =
* clarify the OTP tab settings and mail tag functionality.
= 1.1.2 =
* fix form ID field bug.
= 1.1.1 =
* changed location of action hook otp_by_email_unique_link_created and added nonce field as attribute.
= 1.1.0 =
* added action hook otp_by_email_unique_link_created
* added nonce field in otp_by_email_validated filter hook attributes.
= 1.0.1 =
* fix display of tab in admin page.
