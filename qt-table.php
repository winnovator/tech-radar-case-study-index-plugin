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
    public static function checkAccess() {
        if (!defined('ABSPATH')) {
            die;
        }
    }

    public function activate() {
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }

    public function register() {
        add_action('admin_menu', [$this, 'addAdminPages']);
        $this->enqueue();
    }

    public function addAdminPages() {
        add_menu_page('QT Table', 'QT Table', 'manage_options', 'qt-table-main', [$this, 'addMainTemplate'], 'dashicons-editor-table', 110);
        add_submenu_page(null, 'submissions', 'submissions', 'manage_options', 'qt-table-submissions', [$this, 'addSubmissionTemplate']);
    }

    public function addMainTemplate() {
        require_once(plugin_dir_path(__FILE__) . 'templates/main-template.php');
    }

    public function addSubmissionTemplate() {
        require_once(plugin_dir_path(__FILE__) . 'templates/submission-template.php');
    }

    public function enqueue() {
        wp_enqueue_style("datatables.min", plugins_url("/assets/css/datatables.min.css", __FILE__));
        wp_enqueue_style("custom.datatable", plugins_url("/assets/css/custom.datatable.css", __FILE__));
        wp_enqueue_script("jquery.min", plugins_url("/assets/js/jquery.min.js", __FILE__));
        wp_enqueue_script("datatables.min", plugins_url("/assets/js/datatables.min.js", __FILE__));
        wp_enqueue_script("custom.datatable", plugins_url("/assets/js/custom.datatable.js", __FILE__));
    }
}

$qtTablePlugin = new QtTablePlugin();

$qtTablePlugin->register();

//Activate plugin
register_activation_hook(__FILE__, [$qtTablePlugin, 'activate']);

//Deactivate plugin
register_deactivation_hook(__FILE__, [$qtTablePlugin, 'deactivate']);