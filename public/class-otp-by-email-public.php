<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/aurovrata/
 * @since      1.0.0
 *
 * @package    Otp_By_Email
 * @subpackage Otp_By_Email/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Otp_By_Email
 * @subpackage Otp_By_Email/public
 * @author     aurovrata <vrata@syllogic.in>
 */
class Otp_By_Email_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Otp_By_Email_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Otp_By_Email_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/otp-by-email-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Otp_By_Email_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Otp_By_Email_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/otp-by-email-public.js', array( 'jquery' ), $this->version, false );

	}
  /**
   *Setup location data
   * hooked on cf7 filter 'wpcf7_posted_data'
   * @since 1.0.0
   * @param      Array    $posted_data     an array of field-name=>value pairs.
   * @return     Array    filtered $posted_data     .
  **/
  public function setup_otp_link($posted_data){
    //get the corresponding cf7 form
    if( !isset($_POST['_wpcf7'])){
      return $posted_data;
    }
    $form_id = absint($_POST['_wpcf7']);
    $contact_form = WPCF7_ContactForm::get_instance($form_id);
    $tags = $contact_form->scan_form_tags();

		foreach ( (array) $tags as $tag ) {
			if ( empty( $tag['name'] ) || 'email' != $tag['basetype'] ) {
				continue;
			}
      $field = $tag['name'];
      if(!empty($_POST[$field])){
        $email = sanitize_text_field($_POST[$field]);
        $posted_data['otp-'.$field] = self::otp_link($email, $form_id);
      }
    }
    return $posted_data;
  }
  /**
	* get a link for given email.
  * @since 1.0.0
  */
  static public function otp_link($email, $id = 0){
		$nonce = self::otp_nonce( $email);
		// debug_msg("$nonce -> $email");
		$link = home_url("index.php?otp-by-email={$nonce}");
		$validations = get_option('otp-by-email',array());
		$validations[$nonce]= array('email'=>$email, 'form'=>$id);
    update_option('otp-by-email',$validations);
		return $link;
  }
	/**
	* function to create a token-indepent, user-independent, single tick nonce.
  * @param String $email a valid email to encode intoa unique nonce.
  * @param String $form_id the id of the form on which this email was submitted. (Defaults to 0).
  * @return String a unique time-limited nonce toidentify the email.
	*/
	static public function otp_nonce($email, $form_id=0){
		//allow user to set lifetime.
		$life = apply_filters('otp_by_email_lifetime',3 * DAY_IN_SECONDS, $email, $form_id);
 		$life = ceil( time() / ( $life * 1.0 ) );
    return substr( wp_hash( $life . '|' . $email , 'nonce' ), -12, 10 );
	}
	/**
	* Validate otp link.
	* hooked  on 'parse_request'
	*@since 1.0.0
	*@param WP $wp text_description
	*@return String text_description
	*/
	public function validate_otp($wp){
		if (array_key_exists('otp-by-email', $wp->query_vars) and !empty($wp->query_vars['otp-by-email']) ){
      $nonce = $wp->query_vars['otp-by-email'];
      $validations = get_option('otp-by-email',array());
			// debug_msg($validations, $nonce);
			if( empty($validations[$nonce]) ){ //non-valid access to this page.
				$url = apply_filters('otp_by_email_invalid_request', home_url());
				wp_redirect($url , 302, 'otp-by-email');
				exit;
			}
			$url = '';
			$msg = __('Unable to validate your email!','otp-by-email');
			$email = $validations[$nonce];
      if( hash_equals( self::otp_nonce($email['email']), $nonce ) ){
				$url = apply_filters('otp_by_email_validated','',$email['email'], $email['form']);
				$msg = __('Thank you for validating your email!','otp-by-email');
			}else{
				$url = apply_filters('otp_by_email_failed','',$email['email'], $email['form']);
			}
			unset($validations[$nonce]); //remove email.
			update_option('otp-by-email',$validations);

			if(!empty($url) and wp_redirect($url, 302, 'otp-by-email')) exit;
			else wp_die($msg);
		}
	}
	/**
	* Register custom request link query argument.
	* hooked on 'query_vars'.
	*@since 1.0.0
	*@param Array $vars array of registered request query variaables.
	*@return Array array of registered request query variaables.
	*/
	public function register_custom_link($vars){
		$vars[] = 'otp-by-email';
		return $vars;
	}
	/**
	* Redirec to page on success.
	* Hooked on 'otp_by_email_validated'
	*@since 1.0.0
	*@param String $url filter url to redirect to.
	*@param String $email email validated.
	*@param String $form_id form to which email was submitted.
	*@return String url to redirect to.
	*/
	public function redirect_validated($url, $email, $form_id){
		if($form_id>0){
			$page = get_post_meta($form_id, '_otp_on_success', true);
			if(!empty($page)) $url = get_permalink($page);
		}
		return $url;
	}
	/**
	* Redirec to page on success.
	* Hooked on 'otp_by_email_failed'
	*@since 1.0.0
	*@param String $url filter url to redirect to.
	*@param String $email email validated.
	*@param String $form_id form to which email was submitted.
	*@return String url to redirect to.
	*/
	public function redirect_failed($url, $email, $form_id){
		if($form_id>0){
			$page = get_post_meta($form_id, '_otp_on_failure', true);
			if(!empty($page)) $url = get_permalink($page);
		}
		return $url;
	}
}
/**
* Funiton to get a link for email validation.
* @param String $email a valid email.
* @param String $form_id the id of the form on which this email was submitted. (Defaults to 0).
* @return String a unique link to validate the email, reurns false if the $email is invalid.
* @since 1.0
*/
function get_otp_by_email_link($email, $form_id=0){
	$email = sanitize_email($email);
	if($email){
  	return Otp_By_Email_Public::otp_link($email, $form_id);
	}else return false;
}
