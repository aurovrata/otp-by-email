<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/aurovrata/
 * @since      1.0.0
 *
 * @package    Otp_By_Email
 * @subpackage Otp_By_Email/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Otp_By_Email
 * @subpackage Otp_By_Email/admin
 * @author     aurovrata <vrata@syllogic.in>
 */
class Otp_By_Email_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/otp-by-email-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/otp-by-email-admin.js', array( 'jquery' ), $this->version, false );

	}
  /**
   * Set up email tags
   * hooked on cf7 filter 'wpcf7_collect_mail_tags'
   * @since 1.0.0
   * @param      Array    $mailtags     tag-name.
   * @return     string    $p2     .
  **/
  public function otp_mail_tag( $mailtags = array() ) {
    $contact_form = WPCF7_ContactForm::get_current();
    $tags = $contact_form->scan_form_tags();
  	foreach ( (array) $tags as $tag ) {
  	  if ( !empty($tag['name']) && 'email' == $tag['basetype'] ) {
        $mailtags[] = 'otp-'.$tag['name'];
      }
    }
    return $mailtags;
  }
  /**
  * Add otp panels to the cf7 post editor to redirect to pages.
  * hooked to 'wpcf7_editor_panels'
  *
  *@since 1.0.0
  * @param Array $panel array of panels presented as tabs in the editor, $id => array( 'title' => $panel_title, 'callback' => $callback_function).  The $callback_function must be a valid function to echo the panel html script.
  */
  public function otp_tab($panels){
		$contact_form = WPCF7_ContactForm::get_current();
		$tags = $contact_form->scan_form_tags();
  	foreach ( (array) $tags as $tag ) {
  	  if ( !empty($tag['name']) && 'email' == $tag['basetype'] ) $display=true;
		}
		if($display){
	    $panels['otp-by-email']=array(
	      'title'=>__('OTP', 'otp-by-email'),
	      'callback'=>array($this, 'display_tab_settings')
	    );
		}
    return $panels;
  }
	/**
	* Callback fn to display OTP tab in cf7 editor page.
	*
	*@since 1.0.0
	*/
	public function display_tab_settings(){
		$contact_form = WPCF7_ContactForm::get_current();
		include_once 'partials/otp-by-email-admin-display.php';
	}
	/**
	*
	*
	*@since 1.0.0
	*@param string $param text_description
	*@return string text_description
	*/
	public function save_pages($form){
		if(isset($_POST['_otp_on_success'])){
			update_post_meta($form->id(),'_otp_on_success', absint( $_POST['_otp_on_success']));
			update_post_meta($form->id(),'_otp_on_failure', absint( $_POST['_otp_on_failure']));
		}
	}
}
