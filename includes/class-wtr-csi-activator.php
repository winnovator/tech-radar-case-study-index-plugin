<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.windesheim.tech
 * @since      1.0.0
 *
 * @package    Wtr_Csi
 * @subpackage Wtr_Csi/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wtr_Csi
 * @subpackage Wtr_Csi/includes
 * @author     Mike Harman <mike.harman@windesheim.nl>
 */
class Wtr_Csi_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		flush_rewrite_rules();
		$query = "CREATE TABLE {$wpdb->prefix}csi (ID INT AUTO_INCREMENT PRIMARY KEY NOT NULL, form_id INT NOT NULL, seq_num INT NOT NULL, published TINYINT NOT NULL, new TINYINT NOT NULL)";
		$wpdb->query($query);
	}

}
