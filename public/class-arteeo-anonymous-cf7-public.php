<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://arteeo.ch
 * @since      1.0.0
 *
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/public
 * @author     Arteeo <info@arteeo.ch>
 */
class Arteeo_Anonymous_Cf7_Public {

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
	
	public function arteeo_anonymous_cf7_after_form_submit( $message, $status ) {
		global $arteeo_anonymous_cf7_password;
		global $arteeo_anonymous_cf7_timestamp;
		
		$arteeo_anonymous_cf7_url = site_url() . '/anonymous-form/' . $arteeo_anonymous_cf7_timestamp . '/';
		
		if( 'mail_sent_ok' == $status){
		  $form = wpcf7_get_current_contact_form();
		  
			if ( $form->title() == 'Anonymous Contact Form' ) {
				$arteeo_anonymous_cf7_options = get_option( 'arteeo_anonymous_cf7_settings' );
				$arteeo_anonymous_cf7_header_text = $arteeo_anonymous_cf7_options['aa_cf7_header_text'];
				$arteeo_anonymous_cf7_intro_text = $arteeo_anonymous_cf7_options['aa_cf7_intro_text'];
				$arteeo_anonymous_cf7_password_text = $arteeo_anonymous_cf7_options['aa_cf7_password_text'];
				$arteeo_anonymous_cf7_link_text = $arteeo_anonymous_cf7_options['aa_cf7_link_text'];
				$arteeo_anonymous_cf7_additional = $arteeo_anonymous_cf7_options['aa_cf7_additional_text'];
				$arteeo_anonymous_cf7_message = '<div class="aa_after_form_submit" style="text-align:center;"><h2>' . $arteeo_anonymous_cf7_header_text . '</h2><h4>' . $arteeo_anonymous_cf7_intro_text . '</h4><h4>' . $arteeo_anonymous_cf7_password_text . '</h4><div class="copied"></div><div class="copy-to-clipboard"><input type="text" value="' . $arteeo_anonymous_cf7_password . '" readonly><span class="copy-password"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="30" aria-hidden="true" focusable="false"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.625 5.5h9.75c.069 0 .125.056.125.125v9.75a.125.125 0 0 1-.125.125h-9.75a.125.125 0 0 1-.125-.125v-9.75c0-.069.056-.125.125-.125ZM4 5.625C4 4.728 4.728 4 5.625 4h9.75C16.273 4 17 4.728 17 5.625v9.75c0 .898-.727 1.625-1.625 1.625h-9.75A1.625 1.625 0 0 1 4 15.375v-9.75Zm14.5 11.656v-9H20v9C20 18.8 18.77 20 17.251 20H6.25v-1.5h11.001c.69 0 1.249-.528 1.249-1.219Z"></path></svg></span></div><h3>' . $arteeo_anonymous_cf7_link_text . '</h3><h4><a href="' . $arteeo_anonymous_cf7_url . '" target="_blank">' . $arteeo_anonymous_cf7_url . '</a></h4><h5>' . $arteeo_anonymous_cf7_additional . '</h5></div>';
			}
		}	
		return htmlentities( $arteeo_anonymous_cf7_message );
	}	
	
	function arteeo_anonymous_cf7_form_response() {
		?>
		<script type="text/javascript">
			var wpcf7Elm = document.querySelector( '.wpcf7' );
			
			wpcf7Elm.addEventListener( 'wpcf7submit', function( event ) {
				setTimeout(function(){
					const successMessage = document.querySelector('.wpcf7-response-output');
					const htmlMessage = htmlEntitiesDecode(successMessage.innerText);
					successMessage.innerHTML = htmlMessage;
				}, 0);
			}, false );			

			function htmlEntitiesDecode(str) {
				return jQuery('<textarea />').html(str).text();
			}
			jQuery(document).on("click",".copy-password",function() {
				jQuery(".copy-to-clipboard input").focus();
				jQuery(".copy-to-clipboard input").select();
				document.execCommand("copy");
				jQuery(".copied").text("Copied to clipboard").show().fadeOut(2500);
			});
		</script>
		<?php
	}	

	public function arteeo_anonymous_cf7_custom_password_form() {
		global $post;
		$arteeo_anonymous_cf7_options = get_option( 'arteeo_anonymous_cf7_settings' );
		$arteeo_anonymous_cf7_reply_text = $arteeo_anonymous_cf7_options['aa_cf7_access_reply_text'];
		$arteeo_anonymous_cf7_password_form = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
		' . __( '<h4>' . $arteeo_anonymous_cf7_reply_text . '</h4>', 'arteeo-anonymous-cf7' ) . '<div style="text-align:center;"><label for="password">' . __( '<h3>Password</h3>', 'arteeo-anonymous-cf7' ) . '</label><input name="post_password" id="password" type="password" size="20" required/></div><div style="text-align:center;display:block;"><input type="submit" name="Submit" value="' . esc_attr__( 'Submit', 'arteeo-anonymous-cf7' ) . '" /></div></form>';
		return $arteeo_anonymous_cf7_password_form;
	}	
	
	/**
	 * Defines function for CF7 hook 'wpcf7_before_send_mail', which allows manipulation of form data and creates the checkliste pdf as the form is sent
	 *
	 * @since 		1.0.0
	 * $param		$submission		CF7 instance containing the form
	 * $param		$posted_data	Contains all posted form data (after clicking submit)
	 */
	public function arteeo_anonymous_cf7_anon_form_submit($cf7) {	
		
		$submission = WPCF7_Submission::get_instance();
		
		if ( $submission ) {
			
			if ( $cf7->title() == 'Anonymous Contact Form' ) {
		
				$posted_data = $submission->get_posted_data();
				
				global $arteeo_anonymous_cf7_form_content;
				
				$arteeo_anonymous_cf7_form_content = '<!-- wp:heading {\\"level\\":3} --><h3>' . __( 'Complete Message:', 'arteeo-anonymous-cf7' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>' . $posted_data['anonymous-message'] . '</p><!-- /wp:paragraph --><!-- wp:separator {"align":"wide"} --><hr class="wp-block-separator alignwide"/><!-- /wp:separator -->';
				
				global $arteeo_anonymous_cf7_timestamp;
				$arteeo_anonymous_cf7_timestamp = time();
				
				$local_date = get_option( 'date_format' );
				$local_time = get_option( 'time_format' );
				
				$arteeo_anonymous_cf7_datetime = current_datetime()->format($local_date . ' - ' . $local_time);
				$arteeo_anonymous_cf7_posttitle = 'Nachricht vom ' . $arteeo_anonymous_cf7_datetime;
				
				global $arteeo_anonymous_cf7_password;
				$arteeo_anonymous_cf7_password = $this->createRandomPassword();
				
				$posted_data['anonymous-message-code'] = $arteeo_anonymous_cf7_password;

				$post_data = array (
					'comment_status'    => 'closed',
					'ping_status'       => 'closed',
					'post_name'         => $arteeo_anonymous_cf7_timestamp,
					'post_title'        => $arteeo_anonymous_cf7_posttitle,
					'post_content'		=> $arteeo_anonymous_cf7_form_content,
					'post_password'		=> $arteeo_anonymous_cf7_password,
					'post_status'       => 'publish',
					'post_type'         => 'anonymous-form' 
				);
				wp_insert_post( $post_data );
				
			}
		}
	}	
	
	public function createRandomPassword($length=8,$chars="") { 
		if ( $chars=="" ) {
			$chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ0123456789"; 
		}	
		srand((double)microtime()*1000000); 
		$i = 0; 
		$pass = '' ; 
	 
		while ($i < $length) { 
			$num = rand() % strlen($chars); 
			$tmp = substr($chars, $num, 1); 
			$pass = $pass . $tmp; 
			$i++; 
		} 
		return $pass; 
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
		 * defined in Arteeo_Anonymous_Cf7_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Arteeo_Anonymous_Cf7_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/arteeo-anonymous-cf7-public.css', array(), $this->version, 'all' );

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
		 * defined in Arteeo_Anonymous_Cf7_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Arteeo_Anonymous_Cf7_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/arteeo-anonymous-cf7-public.js', array( 'jquery' ), $this->version, false );

	}

}
