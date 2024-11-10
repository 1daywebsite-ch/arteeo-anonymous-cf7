<?php

/**
 * Fired during plugin activation
 *
 * @link       https://arteeo.ch
 * @since      1.0.0
 *
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Arteeo_Anonymous_Cf7
 * @subpackage Arteeo_Anonymous_Cf7/includes
 * @author     Arteeo <info@arteeo.ch>
 */
class Arteeo_Anonymous_Cf7_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if( ! get_option('arteeo_anonymous_cf7_settings') ) {
			$arteeo_anonymous_cf7_settings = array(
				'aa_cf7_form_text' => 'Deine Nachricht — ohne Name, Email-Adresse oder sonstige personenbezogenen Daten',
				'aa_cf7_header_text' => 'Danke für deine Nachricht!',
				'aa_cf7_intro_text' => 'Wir versuchen, dir so schnell als möglich zu antworten, in der Regel innert 48 Stunden.',
				'aa_cf7_password_text' => 'Dein Passwort, um die Antwort anzuschauen, lautet:',
				'aa_cf7_link_text' => 'Unsere Antwort findest du unter dieser Adresse:',
				'aa_cf7_additional_text' => 'Zum Schutz deiner Privatsphäre bitte beachte, dass nur du dieses Passwort angezeigt bekommst. Sollte jemand das Passwort und das oben angzeigte Link von dir versehentlich erhalten, könnte diese Person deine Nachricht lesen.',
				'aa_cf7_access_reply_text' => 'Hallo! Du kannst diesen Text nur lesen, wenn du das richtige Passwort hast. Falls du es verloren hast, schick uns einfach eine neue Nachricht und notiere dir das Passwort, das dir nach dem Abschicken der Nachricht angezeigt wird. Danke.'
			);	
			add_option('arteeo_anonymous_cf7_settings', $arteeo_anonymous_cf7_settings);
		}	
	}

}
