<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://arteeo.ch
 * @since      1.0.0
 *
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/admin
 * @author     Arteeo <info@arteeo.ch>
 */
class Arteeo_Anonymous_Cf7_Admin {

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
	 * Register a custom post type called "anonymous-form".
	 *
	 * @see get_post_type_labels() for label keys.
	 */
	public function arteeo_anonymous_cf7_custom_post_type() {
		$labels = array(
			'name'                  => _x( 'Anonymous Form', 'Post type general name', 'arteeo-anonymous-cf7' ),
			'singular_name'         => _x( 'Anonymous Form', 'Post type singular name', 'arteeo-anonymous-cf7' ),
			'menu_name'             => _x( 'Anonymous Form', 'Admin Menu text', 'arteeo-anonymous-cf7' ),
			'name_admin_bar'        => _x( 'Anonymous Form', 'Add New on Toolbar', 'arteeo-anonymous-cf7' ),
			'add_new'               => __( 'Add New Form', 'arteeo-anonymous-cf7' ),
			'add_new_item'          => __( 'Add New Anonymous Form', 'arteeo-anonymous-cf7' ),
			'new_item'              => __( 'New Anonymous Form', 'arteeo-anonymous-cf7' ),
			'edit_item'             => __( 'Edit Anonymous Form', 'arteeo-anonymous-cf7' ),
			'view_item'             => __( 'View Anonymous Form', 'arteeo-anonymous-cf7' ),
			'all_items'             => __( 'All Anonymous Forms', 'arteeo-anonymous-cf7' ),
			'search_items'          => __( 'Anonymous Form Search', 'arteeo-anonymous-cf7' ),
			'not_found'             => __( 'No Anonymous Form found', 'arteeo-anonymous-cf7' ),
			'not_found_in_trash'    => __( 'No Anonymous Form Found in Trash', 'arteeo-anonymous-cf7' ),
		);
	 
		$args = array(
			'label'               => __( 'Anonymous Form', 'arteeo-anonymous-cf7' ),
			'description'         => __( 'Anonymous Form', 'arteeo-anonymous-cf7' ),
			'labels'              => $labels,
			'supports'            => array('title', 'editor', 'author', 'revisions', 'custom-fields'),
			'taxonomies'          => array( 'post_tag' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'query_var'          => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-page',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'rewrite'             => array('slug' => __( 'anonymous-form', 'arteeo-anonymous-cf7' )),
		);
		register_post_type( 'anonymous-form', $args );
	}
	
	public function arteeo_anonymous_cf7_add_form() {
		if ( class_exists ('WPCF7') ) {
			/*1st Step: Create CF7 Form*/
			$arteeo_anonymous_form_title = 'Anonymous Contact Form';
			$slug_form = 'anonymous-form';
			$post_type_cf7 = 'wpcf7_contact_form';
			$arteeo_anonymous_cf7_settings = get_option( 'arteeo_anonymous_cf7_settings' );
			$arteeo_anonymous_cf7_form_text = '[response]<label>' . $arteeo_anonymous_cf7_settings[ 'aa_cf7_form_text'] . '</label>[textarea anonymous-message class:anonymous-textarea][submit "Abschicken"]';
			
			$form_args = array(
				'post_type' => $post_type_cf7,
				'post_title' => $arteeo_anonymous_form_title,
				'post_content' => '',
				'post_status' => 'publish',
				'post_author' => 1,
				'post_name' => $slug_form,
				'meta_input'    =>  array(
					'_form' => $arteeo_anonymous_cf7_form_text
				),
			);
			
			if ( ! function_exists( 'post_exists' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/post.php' );
				if ( ! post_exists( $arteeo_anonymous_form_title,'','','wpcf7_contact_form') ) {
					$post_id = wp_insert_post($form_args);
				}
			}
			
			/*2nd Step: Create Page with form*/
			$form_page_slug = 'anonymous-form-page';
			$form_page_content = '<!-- wp:core/shortcode -->[contact-form-7 id="" title="Anonymous Contact Form"]<!-- /wp:core/shortcode -->';
			
			$form_page_args = array(
				'post_type' => 'page',
				'post_title' => $arteeo_anonymous_form_title,
				'post_status' => 'publish',
				'post_author' => 1,
				'post_name' => $form_page_slug,
				'post_content' => $form_page_content
			);
			
			/*WP Query to check if page with same title*/
			$page_with_form = get_posts(
				array(
					'post_type'              => 'page',
					'title'                  => $arteeo_anonymous_form_title,
					'post_status'            => 'publish',
					'numberposts'            => 1,
				)
			);

			$page_got_by_title = null;
			if ( empty( $page_with_form ) ) {
				wp_insert_post( $form_page_args );
			} 			
				
		}
	}

	/**
	 * Adds options page
	 */

	public function arteeo_anonymous_cf7_settings_page() {
		add_options_page( 
			__( 'Anonymous Form Settings', 'textdomain' ),
			__( 'Anonymous Form', 'textdomain' ),
			'manage_options',
			'anonymous-form',
			array($this, 'arteeo_anonymous_cf7_settings_callback')
		);
	}	 

	public function arteeo_anonymous_cf7_settings_callback(  ) {
			?>
			<form action='options.php' method='post'>

				<h2>Anonymous Form Settings</h2>

				<?php
				settings_fields( 'arteeo_anonymous_cf7' );
				do_settings_sections( 'arteeo_anonymous_cf7' );
				submit_button();
				?>

			</form>
			<?php
		}
	
	public function arteeo_anonymous_cf7_settings(  ) {
		register_setting( 'arteeo_anonymous_cf7', 'arteeo_anonymous_cf7_settings' );
		add_settings_section(
			'arteeo_anonymous_cf7_section',
			__( 'Form Texts', 'arteeo-anonymous-cf7' ),
			array($this,'arteeo_anonymous_cf7_section_callback'),
			'arteeo_anonymous_cf7'
		);

		add_settings_field( 
			'arteeo_anonymous_cf7_form_text', 
			__( 'Anonymous Form Text', 'arteeo-anonymous-cf7' ),
			array($this,'arteeo_anonymous_cf7_form_text_render'), 
			'arteeo_anonymous_cf7', 
			'arteeo_anonymous_cf7_section' );
			
		add_settings_field( 
			'arteeo_anonymous_cf7_header_text', 
			__( 'Header Text', 'arteeo-anonymous-cf7' ),
			array($this,'arteeo_anonymous_cf7_after_form_header_text_render'), 
			'arteeo_anonymous_cf7', 
			'arteeo_anonymous_cf7_section' );
		
		add_settings_field( 
			'arteeo_anonymous_cf7_after_form_into_text', 
			__( 'Introduction Text', 'arteeo-anonymous-cf7' ),
			array($this,'arteeo_anonymous_cf7_after_form_intro_text_render'), 
			'arteeo_anonymous_cf7', 
			'arteeo_anonymous_cf7_section' );

		add_settings_field( 
			'arteeo_anonymous_cf7_after_form_password_text', 
			__( 'Password Text', 'arteeo-anonymous-cf7' ),
			array($this,'arteeo_anonymous_cf7_after_form_password_text_render'), 
			'arteeo_anonymous_cf7', 
			'arteeo_anonymous_cf7_section' );

		add_settings_field( 
			'arteeo_anonymous_cf7_after_form_link_text', 
			__( 'Link Text', 'arteeo-anonymous-cf7' ),
			array($this,'arteeo_anonymous_cf7_after_form_link_text_render'), 
			'arteeo_anonymous_cf7', 
			'arteeo_anonymous_cf7_section' );

		add_settings_field( 
			'arteeo_anonymous_cf7_after_form_additional_text', 
			__( 'Additional (Closing) Text', 'arteeo-anonymous-cf7' ),
			array($this,'arteeo_anonymous_cf7_after_form_additional_text_render'), 
			'arteeo_anonymous_cf7', 
			'arteeo_anonymous_cf7_section' );

		add_settings_field( 
			'arteeo_anonymous_cf7_access_reply_text', 
			__( 'Access Reply with Password Text', 'arteeo-anonymous-cf7' ),
			array($this,'arteeo_anonymous_cf7_access_reply_text_render'), 
			'arteeo_anonymous_cf7', 
			'arteeo_anonymous_cf7_section' );			

	}	
	
	public function arteeo_anonymous_cf7_section_callback(  ) {
		echo __( 'You can edit the texts that appear for the anonymous user: the anonymous form itself, the header, the intro, password and link text, and additional text. Defaults texts are given below. Feel free to change them to suit your needs.', 'arteeo-anonymous-cf7' );
	}	

	public function arteeo_anonymous_cf7_form_text_render() {
		$options = get_option( 'arteeo_anonymous_cf7_settings' );
		echo '<textarea class="widefat" id="aa_cf7_form_text" name="arteeo_anonymous_cf7_settings[aa_cf7_form_text]" rows="1" width="100%">' . $options[ 'aa_cf7_form_text'] . '</textarea>';
	}
	
	public function arteeo_anonymous_cf7_after_form_header_text_render() {
		$options = get_option( 'arteeo_anonymous_cf7_settings' );
		echo '<textarea class="widefat" id="aa_cf7_header_text" name="arteeo_anonymous_cf7_settings[aa_cf7_header_text]" rows="1" width="100%">' . $options[ 'aa_cf7_header_text'] . '</textarea>';
	}	
	
	public function arteeo_anonymous_cf7_after_form_intro_text_render() {
		$options = get_option( 'arteeo_anonymous_cf7_settings' );
		echo '<textarea class="widefat" id="aa_cf7_intro_text" name="arteeo_anonymous_cf7_settings[aa_cf7_intro_text]" rows="3" width="100%">' . $options[ 'aa_cf7_intro_text'] . '</textarea>';
	}

	public function arteeo_anonymous_cf7_after_form_password_text_render() {
		$options = get_option( 'arteeo_anonymous_cf7_settings' );
		echo '<textarea class="widefat" id="aa_cf7_password_text" name="arteeo_anonymous_cf7_settings[aa_cf7_password_text]" rows="2" width="100%">' . $options[ 'aa_cf7_password_text'] . '</textarea>';
	}

	public function arteeo_anonymous_cf7_after_form_link_text_render() {
		$options = get_option( 'arteeo_anonymous_cf7_settings' );
		echo '<textarea class="widefat" id="aa_cf7_link_text" name="arteeo_anonymous_cf7_settings[aa_cf7_link_text]" rows="2" width="100%">' . $options[ 'aa_cf7_link_text'] . '</textarea>';
	}

	public function arteeo_anonymous_cf7_after_form_additional_text_render() {
		$options = get_option( 'arteeo_anonymous_cf7_settings' );
		echo '<textarea class="widefat" id="aa_cf7_additional_text" name="arteeo_anonymous_cf7_settings[aa_cf7_additional_text]" rows="3" width="100%">' . $options[ 'aa_cf7_additional_text'] . '</textarea>';
	}

	public function arteeo_anonymous_cf7_access_reply_text_render() {
		$options = get_option( 'arteeo_anonymous_cf7_settings' );
		echo '<textarea class="widefat" id="aa_cf7_access_reply_text" name="arteeo_anonymous_cf7_settings[aa_cf7_access_reply_text]" rows="3" width="100%">' . $options[ 'aa_cf7_access_reply_text'] . '</textarea>';
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
		 * defined in Arteeo_Anonymous_Cf7_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Arteeo_Anonymous_Cf7_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/arteeo-anonymous-cf7-admin.css', array(), $this->version, 'all' );

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
		 * defined in Arteeo_Anonymous_Cf7_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Arteeo_Anonymous_Cf7_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/arteeo-anonymous-cf7-admin.js', array( 'jquery' ), $this->version, false );

	}

}
