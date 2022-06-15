<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once WTR_CSI_PLUGIN_PATH . 'includes/class-wtr-csi-config.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.windesheim.tech
 * @since      1.0.0
 *
 * @package    Wtr_Csi
 * @subpackage Wtr_Csi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wtr_Csi
 * @subpackage Wtr_Csi/admin
 * @author     Mike Harman <mike.harman@windesheim.nl>
 */
class Wtr_Csi_Admin {

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
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Wtr_Csi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wtr_Csi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$this->check_nf_status();

		wp_enqueue_style('dashicons', array(), $this->version, 'all');
		wp_enqueue_style('datatables.min', plugin_dir_url(__FILE__) . 'css/datatables.min.css',  array(), $this->version, 'all');
        wp_enqueue_style('wtr-csi-admin-main', plugin_dir_url(__FILE__) . 'css/wtr-csi-admin-main.css', array(), $this->version, 'all');
		wp_enqueue_style('wtr-csi-admin-info', plugin_dir_url(__FILE__) . 'css/wtr-csi-admin-info.css',  array(), $this->version, 'all');
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
		 * defined in Wtr_Csi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wtr_Csi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$this->check_nf_status();

		wp_enqueue_script('jquery', array('jquery'), $this->version, false);
		wp_enqueue_script('datatables.min', plugin_dir_url(__FILE__) . 'js/datatables.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script('wtr-csi-admin-main', plugin_dir_url(__FILE__) . 'js/wtr-csi-admin-main.js', array('jquery'), $this->version, false);
        wp_localize_script('wtr-csi-admin-main', 'wtr_csi_admin_main', array('url' => esc_url(rest_url("wtr-csi/v1/admin-main/sub")), 'nonce' => wp_create_nonce('wp_rest')));
        wp_localize_script('wtr-csi-admin-main', 'wtr_csi_admin_datatables_dutch_lang', array('url' => plugin_dir_url(__FILE__) . 'js/dataTables.dutch.json'));

		wp_enqueue_script('wtr-csi-admin-info', plugin_dir_url(__FILE__) . 'js/wtr-csi-admin-info.js', array('jquery'), $this->version, false);
        wp_localize_script('wtr-csi-admin-info', 'wtr_csi_admin_info', array('url' => esc_url(rest_url("wtr-csi/v1/admin-info/sub")), 'nonce' => wp_create_nonce('wp_rest'), "redirect_url" => esc_url_raw(admin_url('admin.php?page=wtr-csi-admin-main'))));
	}
	
	/**
	 * wtr_csi_admin_main_menu
	 *
	 * @return void
	 */
	public function wtr_csi_admin_main_menu() {
		add_menu_page('Case Studie Index', 'Case Studie Index', 'manage_options', 'wtr-csi-admin-main', array($this, 'wtr_csi_admin_main_page'), 'dashicons-editor-table', 110);
		add_submenu_page(null, 'Case Study Index Info', 'Case Study Index Info', 'manage_options', 'wtr-csi-admin-info', array($this, 'wtr_csi_admin_info_page'));
		add_submenu_page(null, 'Ninja Forms Inactief', 'Ninja Forms Inactief', 'manage_options', 'wtr-csi-admin-nf-inactive', array($this, 'wtr_csi_admin_nf_inactive_page'));
	}
	
	/**
	 * wtr_csi_admin_main_page
	 *
	 * @return string
	 */
	public function wtr_csi_admin_main_page() {
		ob_start();

		require_once WTR_CSI_PLUGIN_PATH . 'admin/partials/wtr-csi-admin-main-template.php';

		$template = ob_get_contents();

		ob_end_clean();

		echo $template;
	}
	
	/**
	 * wtr_csi_admin_info_page
	 *
	 * @return string
	 */
	public function wtr_csi_admin_info_page() {
		ob_start();

		require_once WTR_CSI_PLUGIN_PATH . 'admin/partials/wtr-csi-admin-info-template.php';

		$template = ob_get_contents();

		ob_end_clean();

		echo $template;
	}
	
	/**
	 * wtr_csi_admin_nf_inactive_page
	 *
	 * @return string
	 */
	public function wtr_csi_admin_nf_inactive_page() {
		ob_start();

		require_once WTR_CSI_PLUGIN_PATH . 'admin/partials/wtr-csi-admin-nf-inactive-template.php';

		$template = ob_get_contents();

		ob_end_clean();

		echo $template;
	}
	
	/**
	 * wtr_csi_admin_rest_api_endpoints
	 *
	 * @return void
	 */
	public function wtr_csi_admin_rest_api_endpoints() {
		require_once WTR_CSI_PLUGIN_PATH . 'admin/class-wtr-csi-admin-rest-api-endpoints.php';
		Wtr_Csi_Admin_Rest_Api_Endpoints::register_routes();
	}
	
	/**
	 * check_nf_status
	 *
	 * @return void
	 */
	public function check_nf_status() {
		$admin_url = isset($_GET['page']) ? $_GET['page'] : '';

		if (!function_exists('Ninja_Forms')) {
			switch ($admin_url) {
				case 'wtr-csi-admin-main':
					wp_redirect(admin_url('admin.php?page=wtr-csi-admin-nf-inactive'));
					break;
				case 'wtr-csi-admin-info':
					wp_redirect(admin_url('admin.php?page=wtr-csi-admin-nf-inactive'));
					break;
			}
		}
	}
}
