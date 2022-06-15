<?php
if (!defined('ABSPATH')) {
    wp_die();
}

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.windesheim.tech
 * @since      1.0.0
 *
 * @package    Wtr_Csi
 * @subpackage Wtr_Csi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wtr_Csi
 * @subpackage Wtr_Csi/public
 * @author     Mike Harman <mike.harman@windesheim.nl>
 */
class Wtr_Csi_Public {

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
		 * defined in Wtr_Csi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wtr_Csi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style('wtr-csi-public', plugin_dir_url( __FILE__ ) . 'css/wtr-csi-public.css', array(), $this->version, 'all');
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
		 * defined in Wtr_Csi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wtr_Csi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('wtr-csi-public', plugin_dir_url( __FILE__ ) . 'js/wtr-csi-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script('wtr-csi-public', 'wtr_csi_public_ajax', array('url' => esc_url(rest_url("wtr-csi/v1/public/sub")), 'nonce' => wp_create_nonce('wp_rest')));
		wp_localize_script('wtr-csi-public', 'wtr_csi_public_ajax_info', array('url' => esc_url(rest_url("wtr-csi/v1/public/sub/")), 'nonce' => wp_create_nonce('wp_rest')));
		wp_localize_script('wtr-csi-public', 'wtr_csi_public_ajax_all_sbi', array('url' => esc_url(rest_url("wtr-csi/v1/public/sbi")), 'nonce' => wp_create_nonce('wp_rest')));
		wp_localize_script('wtr-csi-public', 'wtr_csi_public_tech_radar_logo_image', array('url' => esc_url(plugin_dir_url(WTR_CSI_PLUGIN_PATH) . 'wtr-csi/shared/images/windesheim_tech_radar_logo.png')));
	}
	
	/**
	 * wtr_csi_public_register_shortcodes
	 *
	 * @return void
	 */
	public function wtr_csi_public_get_contents() {
		ob_start();
		require_once WTR_CSI_PLUGIN_PATH . 'public/partials/wtr-csi-public-template.php';
		$template = ob_get_contents();
		ob_end_clean();
		return $template;
	}
	
	/**
	 * wtr_csi_public_rest_api_endpoints
	 *
	 * @return void
	 */
	public function wtr_csi_public_rest_api_endpoints() {
		require_once WTR_CSI_PLUGIN_PATH . 'public/class-wtr-csi-public-rest-api-endpoints.php';
		Wtr_Csi_Public_Rest_Api_Endpoints::register_routes();
	}
}
