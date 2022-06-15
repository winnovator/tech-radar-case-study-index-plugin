<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.windesheim.tech
 * @since             1.0.0
 * @package           Wtr_Csi
 *
 * @wordpress-plugin
 * Plugin Name:       Windesheim Technology Radar Case Study Index
 * Plugin URI:        https://www.windesheim.tech
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Mike Harman
 * Author URI:        https://www.windesheim.tech
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wtr-csi
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
define( 'WTR_CSI_VERSION', '1.0.0' );
define( 'WTR_CSI_PLUGIN_PATH', plugin_dir_path(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wtr-csi-activator.php
 */
function activate_wtr_csi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wtr-csi-activator.php';
	Wtr_Csi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wtr-csi-deactivator.php
 */
function deactivate_wtr_csi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wtr-csi-deactivator.php';
	Wtr_Csi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wtr_csi' );
register_deactivation_hook( __FILE__, 'deactivate_wtr_csi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wtr-csi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wtr_csi() {

	$plugin = new Wtr_Csi();
	$plugin->run();

}
run_wtr_csi();
