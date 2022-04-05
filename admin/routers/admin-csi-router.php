<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-controller.php");

class AdminCaseStudyIndexRouter extends AdminCaseStudyIndexController {
    public function getAllSubData() {
        echo wp_send_json($this->convertedSubDataArr);
        wp_die();
    }
}

add_action('wp_ajax_get_csi_datatables_subdata', [new AdminCaseStudyIndexRouter, 'getAllSubData']);