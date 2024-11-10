<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://arteeo.ch
 * @since      1.0.0
 *
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/includes
 * @author     Arteeo <info@arteeo.ch>
 */
class Arteeo_Anonymous_Cf7 {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Arteeo_Anonymous_Cf7_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ARTEEO_ANONYMOUS_CF7_VERSION' ) ) {
			$this->version = ARTEEO_ANONYMOUS_CF7_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'arteeo-anonymous-cf7';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Arteeo_Anonymous_Cf7_Loader. Orchestrates the hooks of the plugin.
	 * - Arteeo_Anonymous_Cf7_i18n. Defines internationalization functionality.
	 * - Arteeo_Anonymous_Cf7_Admin. Defines all hooks for the admin area.
	 * - Arteeo_Anonymous_Cf7_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-arteeo-anonymous-cf7-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-arteeo-anonymous-cf7-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-arteeo-anonymous-cf7-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-arteeo-anonymous-cf7-public.php';

		$this->loader = new Arteeo_Anonymous_Cf7_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Arteeo_Anonymous_Cf7_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Arteeo_Anonymous_Cf7_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Arteeo_Anonymous_Cf7_Admin( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'wp_loaded', $plugin_admin, 'arteeo_anonymous_cf7_add_form' );
		$this->loader->add_action( 'init', $plugin_admin, 'arteeo_anonymous_cf7_custom_post_type' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'arteeo_anonymous_cf7_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'arteeo_anonymous_cf7_settings' );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Arteeo_Anonymous_Cf7_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'the_password_form', $plugin_public, 'arteeo_anonymous_cf7_custom_password_form' );
		
		$all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
		if (stripos(implode($all_plugins), 'wp-contact-form-7.php')) {

			//$this->loader->add_filter( 'wpcf7_ajax_json_echo', $plugin_public, 'arteeo_anonymous_cf7_after_form_submit' );
			
			$this->loader->add_action( 'wpcf7_before_send_mail', $plugin_public, 'arteeo_anonymous_cf7_anon_form_submit', 10, 1 );
			
			/*$this->loader->add_filter( 'wpcf7_submission_result', $plugin_public, 'arteeo_anonymous_cf7_after_form_submit', 10, 2 );*/
			
			$this->loader->add_filter( 'wpcf7_display_message', $plugin_public, 'arteeo_anonymous_cf7_after_form_submit', 10, 2 );
			
			$this->loader->add_action( 'wp_footer', $plugin_public, 'arteeo_anonymous_cf7_form_response' );
			
			
		}

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Arteeo_Anonymous_Cf7_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
