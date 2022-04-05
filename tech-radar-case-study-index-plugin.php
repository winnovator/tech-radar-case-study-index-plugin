<?php
if (!defined('ABSPATH')) {
    wp_die();
}

/**
 * Plugin Name:       Technology Radar Case Study Index Plugin
 * Description:       Plugin for the Technology Radar Case Study Index.
 * Version:           0.1
 * Author:            Mike Harman
 * Text Domain:       tech-radar-case-study-index-plugin
 * Domain Path:       /languages
*/

class TechRadarCaseStudyIndexPlugin {

    private $db;
    private $publicCsiViewObj;
    
    public function __construct() {
        require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');
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
        include_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/public/views/pubic-csi-view.php');
        $this->publicCsiViewObj = new PublicCaseStudyIndexView();
        return $this->publicCsiViewObj->getPublicCsi();
    }

    public function register() {
        if (current_user_can('manage_options')) {
            add_action('admin_menu', [$this, 'addCsiAdminPages']);
            add_action('admin_enqueue_scripts', [$this, 'csiAdminEnqueue']);
        }

        add_action('wp_enqueue_scripts', [$this, 'csiPublicEnqueue']);
        add_shortcode('csi', [$this, 'getCsi']);
    }

    public function addCsiAdminPages() {
        add_menu_page('Case Study Index', 'Case Study Index', 'manage_options', 'admin-csi', [$this, 'adminCsiView'], 'dashicons-editor-table', 110);
        add_submenu_page(null, 'Case Study Index Info', 'Case Study Index Info', 'manage_options', 'admin-csi-info', [$this, 'adminCsiInfoView']);
        add_menu_page('QT Table test', 'QT Table test', 'manage_options', 'qt-table-main-test', [$this, 'publicCsiView'], 'dashicons-editor-table', 120);
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
        wp_enqueue_script('jquery.min', plugins_url('/assets/shared/js/jquery.min.js', __FILE__));
        wp_enqueue_style('datatables.min', plugins_url('/assets/admin/css/datatables.min.css', __FILE__));
        wp_enqueue_style('admin.csi.view', plugins_url('/assets/admin/css/admin.csi.view.css', __FILE__));
        wp_enqueue_script('datatables.min', plugins_url('/assets/admin/js/datatables.min.js', __FILE__));
        wp_enqueue_script('admin.csi.view', plugins_url('/assets/admin/js/admin.csi.view.js', __FILE__));
        wp_localize_script('admin.csi.view','admin_csi_ajax_obj', array('url' => admin_url('admin-ajax.php')));

        wp_enqueue_style('public.csi.view', plugins_url('/assets/public/css/public.csi.view.css', __FILE__));
        wp_enqueue_script('public.csi.view', plugins_url('/assets/public/js/public.csi.view.js', __FILE__));
        wp_localize_script('public.csi.view','public_csi_ajax_obj', array('url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('public_csi_ajax_nonce')));
    }

    public function csiPublicEnqueue() {
        wp_enqueue_style('public.csi.view', plugins_url('/assets/public/css/public.csi.view.css', __FILE__));
        wp_enqueue_script('public.csi.view', plugins_url('/assets/public/js/public.csi.view.js', __FILE__));
        wp_localize_script('public.csi.view', 'public_csi_ajax_obj', array('url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('public_csi_ajax_nonce')));
    }
}

add_action('init', [new TechRadarCaseStudyIndexPlugin(), 'register']);
register_activation_hook(__FILE__, [new TechRadarCaseStudyIndexPlugin(), 'activate']);
register_deactivation_hook(__FILE__, [new TechRadarCaseStudyIndexPlugin(), 'deactivate']);