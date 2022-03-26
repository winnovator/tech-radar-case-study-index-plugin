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
    
    public function __construct() {
        require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');
        $this->db = new DB();
    }

    public function activate() {
        flush_rewrite_rules();
        $dbConn = $this->db->open();
        $dbConn->query("CREATE TABLE {$this->db->prefix}csi_published (ID INT AUTO_INCREMENT PRIMARY KEY NOT NULL, form_id INT NOT NULL, seq_id INT NOT NULL)");
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }

    public function getPublicCsi() {
        include_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/public/views/csi-public-view.php');
        $publishedSubViewObj = new PublishedSubmissionView();
        return $publishedSubViewObj->getPublicCsi();
    }

    public function register() {
        add_action('admin_menu', [$this, 'addCsiAdminPages']);
        add_shortcode('csi', [$this, 'getCsi']);
        add_action('admin_enqueue_scripts', [$this, 'csiAdminEnqueue']);
        add_action('wp_enqueue_scripts', [$this, 'csiPublicEnqueue']);
    }

    public function addCsiAdminPages() {
        add_menu_page('Case Study Index', 'Case Study Index', 'manage_options', 'csi-main', [$this, 'addCsiMainView'], 'dashicons-editor-table', 110);
        add_menu_page('QT Table test', 'QT Table test', 'manage_options', 'qt-table-main-test', [$this, 'publishedSubmissionView'], 'dashicons-editor-table', 120);
    }
    public function addCsiMainView() {
        require_once(plugin_dir_path(__FILE__) . 'admin/views/csi-admin-view.php');
    }

    public function publishedSubmissionView() {
        require_once(plugin_dir_path(__FILE__) . 'public/views/csi-public-view.php');
    }

    public function csiAdminEnqueue() {
        wp_enqueue_script('jquery.min', plugins_url('/assets/shared/js/jquery.min.js', __FILE__));
        wp_enqueue_style('datatables.min', plugins_url('/assets/admin/css/datatables.min.css', __FILE__));
        wp_enqueue_style('custom.datatable', plugins_url('/assets/admin/css/custom.datatable.css', __FILE__));
        wp_enqueue_script('datatables.min', plugins_url('/assets/admin/js/datatables.min.js', __FILE__));
        wp_enqueue_script('custom.datatable', plugins_url('/assets/admin/js/custom.datatable.js', __FILE__));
        wp_enqueue_style('published.submission.view', plugins_url('/assets/public/css/published.submission.view.css', __FILE__));
        wp_enqueue_script('published.submission.view', plugins_url('/assets/public/js/published.submission.view.js', __FILE__));
        wp_localize_script('published.submission.view','csi_ajax_obj', array('csi_url' => admin_url('admin-ajax.php'), 'csi_nonce' => wp_create_nonce('csi_ajax_nonce')));
    }

    public function csiPublicEnqueue() {
        wp_enqueue_style('published.submission.view', plugins_url('/assets/public/css/published.submission.view.css', __FILE__));
        wp_enqueue_script('published.submission.view', plugins_url('/assets/public/js/published.submission.view.js', __FILE__));
        wp_localize_script('published.submission.view','csi_ajax_obj', array('csi_url' => admin_url('admin-ajax.php'), 'csi_nonce' => wp_create_nonce('csi_ajax_nonce')));
    }
}

add_action( 'init', [new TechRadarCaseStudyIndexPlugin(), 'register'] );
register_activation_hook(__FILE__, [$techRadarCaseStudyIndexPlugin, 'activate']);
register_deactivation_hook(__FILE__, [$techRadarCaseStudyIndexPlugin, 'deactivate']);