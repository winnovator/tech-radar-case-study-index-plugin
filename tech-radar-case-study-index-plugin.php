<?php
if (!defined('ABSPATH')) {
    wp_die();
}

/**
 * Plugin Name:       Technology Radar Case Study Index Plugin (New version)
 * Description:       Plugin for the Technology Radar Case Study Index. Works with Wordpress version 5.9.3 and Ninja Forms version 3.6.9.
 * Version:           0.2
 * Author:            Mike Harman
 * Text Domain:       tech-radar-case-study-index-plugin
 * Domain Path:       /languages
*/

class TechRadarCaseStudyIndexPlugin {

    private $db;
    private $publicCsiViewObj;
    
    public function __construct() {
        require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/db.php');
        $this->db = new DB();
    }

    public function activate() {
        flush_rewrite_rules();
        $dbConn = $this->db->open();
        $dbConn->query("CREATE TABLE {$dbConn->prefix}csi (ID INT AUTO_INCREMENT PRIMARY KEY NOT NULL, form_id INT NOT NULL, seq_num INT NOT NULL, published TINYINT NOT NULL, new TINYINT NOT NULL)");
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }

    public function getPublicCsi() {
        include_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/public/views/public-csi-view.php');
        $this->publicCsiViewObj = new PublicCaseStudyIndexView();
        return $this->publicCsiViewObj->getPublicCsi();
    }

    public function register() {
        if (current_user_can('manage_options')) {
            add_action('admin_menu', [$this, 'addCsiAdminPages']);
            add_action('admin_enqueue_scripts', [$this, 'csiAdminEnqueue']);
        }

        add_action('wp_enqueue_scripts', [$this, 'csiPublicEnqueue']);
        add_shortcode('csi', [$this, 'getPublicCsi']);
        
        add_action('rest_api_init', [$this, 'registerCsiRestEndpoints']);
    }

    public function addCsiAdminPages() {
        add_menu_page('Case Study Index', 'Case Study Index', 'manage_options', 'admin-csi', [$this, 'adminCsiView'], 'dashicons-editor-table', 110);
        add_submenu_page(null, 'Case Study Index Info', 'Case Study Index Info', 'manage_options', 'admin-csi-info', [$this, 'adminCsiInfoView']);
    }

    public function adminCsiView() {
        require_once(plugin_dir_path(__FILE__) . 'admin/views/admin-csi-view.php');
    }

    public function adminCsiInfoView() {
        require_once(plugin_dir_path(__FILE__) . 'admin/views/admin-csi-info-view.php');
    }

    public function publicCsiView() {
        require_once(plugin_dir_path(__FILE__) . 'public/views/public-csi-view.php');
    }

    public function csiAdminEnqueue() {
        wp_enqueue_script('jquery');

        wp_enqueue_style('datatables.min', plugins_url('/assets/admin/css/datatables.min.css', __FILE__));
        wp_enqueue_style('admin.csi.view', plugins_url('/assets/admin/css/admin.csi.view.css', __FILE__));
        wp_enqueue_script('datatables.min', plugins_url('/assets/admin/js/datatables.min.js', __FILE__));
        wp_enqueue_script('admin.csi.view', plugins_url('/assets/admin/js/admin.csi.view.js', __FILE__));
        wp_localize_script('admin.csi.view', 'admin_csi_ajax_obj', array('url' => esc_url(rest_url("csi-plugin/v1/admin-csi/overview")), 'nonce' => wp_create_nonce('wp_rest')));
        wp_enqueue_script('admin.csi.info.view', plugins_url('/assets/admin/js/admin.csi.info.view.js', __FILE__));
        wp_localize_script('admin.csi.info.view', 'admin_csi_info_ajax_obj', array('url' => esc_url(rest_url("csi-plugin/v1/admin-csi/info")), 'nonce' => wp_create_nonce('wp_rest'), "redirect_url" => esc_url_raw(admin_url('admin.php?page=admin-csi'))));
        wp_localize_script('admin.csi.info.view', 'admin_csi_info_tech_radar_logo_image', array('url' => plugins_url('/assets/shared/images/windesheim_tech_radar_logo.png',__FILE__)));

        if  (is_page('case-study-index')) {
            wp_enqueue_style('public.csi.view', plugins_url('/assets/public/css/public.csi.view.css', __FILE__));
            wp_enqueue_script('public.csi.view', plugins_url('/assets/public/js/public.csi.view.js', __FILE__));
            wp_localize_script('public.csi.view', 'public_csi_ajax_obj', array('url' => esc_url(rest_url("csi-plugin/v1/public-csi/overview")), 'nonce' => wp_create_nonce('wp_rest')));
            wp_localize_script('public.csi.view', 'public_csi_ajax_info_obj', array('url' => esc_url(rest_url("csi-plugin/v1/public-csi/sub/")), 'nonce' => wp_create_nonce('wp_rest')));
            wp_localize_script('public.csi.view', 'public_csi_ajax_all_sbi_obj', array('url' => esc_url(rest_url("csi-plugin/v1/public-csi/sbi/all")), 'nonce' => wp_create_nonce('wp_rest')));
            wp_localize_script('public.csi.view', 'public_csi_tech_radar_logo_image', array('url' => plugins_url('/assets/shared/images/windesheim_tech_radar_logo.png', __FILE__)));
        }
    }

    public function csiPublicEnqueue() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('dashicons');

        if  (is_page('case-study-index')) {
            wp_enqueue_style('public.csi.view', plugins_url('/assets/public/css/public.csi.view.css', __FILE__));
            wp_enqueue_script('public.csi.view', plugins_url('/assets/public/js/public.csi.view.js', __FILE__));
            wp_localize_script('public.csi.view', 'public_csi_ajax_obj', array('url' => esc_url(rest_url("csi-plugin/v1/public-csi/overview")), 'nonce' => wp_create_nonce('wp_rest')));
            wp_localize_script('public.csi.view', 'public_csi_ajax_info_obj', array('url' => esc_url(rest_url("csi-plugin/v1/public-csi/sub/")), 'nonce' => wp_create_nonce('wp_rest')));
            wp_localize_script('public.csi.view', 'public_csi_ajax_all_sbi_obj', array('url' => esc_url(rest_url("csi-plugin/v1/public-csi/sbi/all")), 'nonce' => wp_create_nonce('wp_rest')));
            wp_localize_script('public.csi.view', 'public_csi_tech_radar_logo_image', array('url' => plugins_url('/assets/shared/images/windesheim_tech_radar_logo.png',__FILE__)));
        }
     }

    public function registerCsiRestEndpoints() {
        require_once(plugin_dir_path(__FILE__) . 'admin/routers/admin-csi-router.php');
        require_once(plugin_dir_path(__FILE__) . 'admin/routers/admin-csi-info-router.php');
        require_once(plugin_dir_path(__FILE__) . 'public/routers/public-csi-router.php');
        require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/csi-api.php');
    }
}

add_action('init', [new TechRadarCaseStudyIndexPlugin(), 'register']);
register_activation_hook(__FILE__, [new TechRadarCaseStudyIndexPlugin(), 'activate']);
register_deactivation_hook(__FILE__, [new TechRadarCaseStudyIndexPlugin(), 'deactivate']);