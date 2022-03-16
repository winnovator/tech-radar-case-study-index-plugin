<?php

/**
 * Plugin Name:       QT Table Plugin
 * Description:       Simple custom table plugin with entry approval system. Only works with Ninja Forms. Made use of Datatable plugin.
 * Version:           0.1
 * Author:            Mike Harman
 * Text Domain:       qt-table-plugin
 * Domain Path:       /languages
*/

QTTablePlugin::checkAccess();

class QTTablePlugin {

    private $db;
    
    public function __construct() {
        require_once(ABSPATH . 'wp-content/plugins/qt-table-plugin/includes/db.php');
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
        $this->db->query("CREATE TABLE {$this->db->prefix}qt_published (ID INT AUTO_INCREMENT PRIMARY KEY NOT NULL, form_id INT NOT NULL, seq_id INT NOT NULL)");
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }

    public function register() {
        add_action('admin_menu', [$this, 'addAdminPages']);
        $this->enqueue();
    }

    public function addAdminPages() {
        add_menu_page('QT Table', 'QT Table', 'manage_options', 'qt-table-main', [$this, 'addMainView'], 'dashicons-editor-table', 110);
        add_submenu_page(null, 'submissions', 'submissions', 'manage_options', 'qt-table-submissions', [$this, 'addSubmissionView']);
    }

    public function addMainView() {
        require_once(plugin_dir_path(__FILE__) . 'admin/views/main-view.php');
    }

    public function addSubmissionView() {
        require_once(plugin_dir_path(__FILE__) . 'admin/views/submission-view.php');
    }

    public function enqueue() {
        wp_enqueue_style("datatables.min", plugins_url("/assets/shared/css/datatables.min.css", __FILE__));
        wp_enqueue_style("custom.datatable", plugins_url("/assets/shared/css/custom.datatable.css", __FILE__));
        wp_enqueue_script("jquery.min", plugins_url("/assets/shared/js/jquery.min.js", __FILE__));
        wp_enqueue_script("datatables.min", plugins_url("/assets/shared/js/datatables.min.js", __FILE__));
        wp_enqueue_script("custom.datatable", plugins_url("/assets/shared/js/custom.datatable.js", __FILE__));
    }
}

$qtTablePlugin = new QtTablePlugin();

$qtTablePlugin->register();

//Activate plugin
register_activation_hook(__FILE__, [$qtTablePlugin, 'activate']);

//Deactivate plugin
register_deactivation_hook(__FILE__, [$qtTablePlugin, 'deactivate']);