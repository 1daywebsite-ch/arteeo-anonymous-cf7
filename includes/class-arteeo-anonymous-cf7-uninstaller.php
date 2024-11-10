<?php

/**
 * Fired during plugin uninstall
 *
 * @link       https://arteeo.ch
 * @since      1.0.0
 *
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/includes
 */

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 *
 * @since      1.0.0
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/includes
 * @author     Arteeo <info@arteeo.ch>
 */
class Arteeo_Anonymous_Cf7_Uninstaller {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {

		delete_option ('arteeo_anonymous_cf7_settings');

	}

}
