<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://arteeo.ch
 * @since      1.0.0
 *
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/includes
 * @author     Arteeo <info@arteeo.ch>
 */
class Arteeo_Anonymous_Cf7_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		$anonymous_form_page = get_page_by_path( 'anonymous-form-page' );
		if ( $anonymous_form_page ) {
			wp_delete_post( $anonymous_form_page->ID, true );
		}

		$anonymous_form = get_page_by_path('anonymous-form', OBJECT, 'wpcf7_contact_form');		
		if ( $anonymous_form ) {
			wp_delete_post( $anonymous_form->ID, true );
		}
		//delete_option ('arteeo_anonymous_cf7_settings');
	}

}
