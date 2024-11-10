<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://arteeo.ch
 * @since             1.0.0
 * @package           Arteeo_Anonymous_Cf7
 *
 * @wordpress-plugin
 * Plugin Name:       Arteeo Anonymous Contact with Contact Form 7
 * Plugin URI:        https://arteeo.ch
 * Description:       A user can submit a message anonymously, no email or anything needed, with the answer - if wanted - available at a unique URL, accessible with a password that only the submitter knows
 * Version:           1.0.0
 * Author:            Arteeo
 * Author URI:        https://arteeo.ch/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       arteeo-anonymous-cf7
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
define( 'ARTEEO_ANONYMOUS_CF7_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-arteeo-anonymous-cf7-activator.php
 */
function activate_arteeo_anonymous_cf7() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-arteeo-anonymous-cf7-activator.php';
	Arteeo_Anonymous_Cf7_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-arteeo-anonymous-cf7-deactivator.php
 */
function deactivate_arteeo_anonymous_cf7() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-arteeo-anonymous-cf7-deactivator.php';
	Arteeo_Anonymous_Cf7_Deactivator::deactivate();
}

/**
 * The code that runs during plugin uninstall.
 * This action is documented in includes/class-arteeo-anonymous-cf7-uninstaller.php
 */
function uninstall_arteeo_anonymous_cf7() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-arteeo-anonymous-cf7-uninstaller.php';
	Arteeo_Anonymous_Cf7_Uninstaller::uninstall();
}

register_activation_hook( __FILE__, 'activate_arteeo_anonymous_cf7' );
register_deactivation_hook( __FILE__, 'deactivate_arteeo_anonymous_cf7' );
register_uninstall_hook( __FILE__, 'uninstall_arteeo_anonymous_cf7' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-arteeo-anonymous-cf7.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_arteeo_anonymous_cf7() {

	$plugin = new Arteeo_Anonymous_Cf7();
	$plugin->run();

}
run_arteeo_anonymous_cf7();
