<?php

/**
 * Plugin Name:       Technology Radar Case Study Index Plugin
 * Description:       Plugin for the Technology Radar Case Study Index.
 * Version:           0.1
 * Author:            Mike Harman
 * Text Domain:       tech-radar-case-study-index-plugin
 * Domain Path:       /languages
*/

TechRadarCaseStudyIndexPlugin::checkAccess();

class TechRadarCaseStudyIndexPlugin {

    private $db;
    
    public function __construct() {
        require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');
        $db = new DB();
        $this->db = $db->conn();
    }

    public static function checkAccess() {
        if (!defined('ABSPATH')) {
            die;
        }
    }

    public function activate() {
        flush_rewrite_rules();
        $this->db->query("CREATE TABLE {$this->db->prefix}csi_published (ID INT AUTO_INCREMENT PRIMARY KEY NOT NULL, form_id INT NOT NULL, seq_id INT NOT NULL)");
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }

    public function getTable($attr) {
        include_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/public/views/published-submission-view.php');
        $publishedSubmissionViewObj = new PublishedSubmissionView();

        return $publishedSubmissionViewObj->getPublishedTable($args['form_id']);
    }

    public function register() {
        add_action('admin_menu', [$this, 'addAdminPages']);
        add_shortcode('qt-table', [$this, 'getTable']);
        $this->enqueue();
    }

    public function addAdminPages() {
        add_menu_page('Case Study Index', 'Case Study Index', 'manage_options', 'case-study-index-main', [$this, 'addMainView'], 'dashicons-editor-table', 110);
        add_menu_page('QT Table test', 'QT Table test', 'manage_options', 'qt-table-main-test', [$this, 'publishedSubmissionView'], 'dashicons-editor-table', 120);
    }
    public function addMainView() {
        require_once(plugin_dir_path(__FILE__) . 'admin/views/main-view.php');
    }

    public function publishedSubmissionView() {
        require_once(plugin_dir_path(__FILE__) . 'public/views/published-submission-view.php');
    }

    public function enqueue() {
        wp_enqueue_script("jquery.min", plugins_url("/assets/shared/js/jquery.min.js", __FILE__));
        wp_enqueue_style("datatables.min", plugins_url("/assets/admin/css/datatables.min.css", __FILE__));
        wp_enqueue_style("custom.datatable", plugins_url("/assets/admin/css/custom.datatable.css", __FILE__));
        wp_enqueue_script("datatables.min", plugins_url("/assets/admin/js/datatables.min.js", __FILE__));
        wp_enqueue_script("custom.datatable", plugins_url("/assets/admin/js/custom.datatable.js", __FILE__));
        wp_enqueue_script("isotope", plugins_url("/assets/public/js/isotope.js", __FILE__));
        wp_enqueue_style("published.submission.view", plugins_url("/assets/public/css/published.submission.view.css", __FILE__));
        wp_enqueue_script("published.submission.view", plugins_url("/assets/public/js/published.submission.view.js", __FILE__));
    }
}

$techRadarCaseStudyIndexPlugin = new TechRadarCaseStudyIndexPlugin();

$techRadarCaseStudyIndexPlugin->register();

//Activate plugin
register_activation_hook(__FILE__, [$techRadarCaseStudyIndexPlugin, 'activate']);

//Deactivate plugin
register_deactivation_hook(__FILE__, [$techRadarCaseStudyIndexPlugin, 'deactivate']);