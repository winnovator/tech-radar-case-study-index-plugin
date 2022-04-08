<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-controller.php");

class AdminCaseStudyIndexRouter extends AdminCaseStudyIndexController {
    public function getAllSubData() {
        if (check_ajax_referer('wp_rest', 'admin_csi_security_nonce')) {
            echo wp_send_json($this->convertedSubDataArr);
            wp_die();
        }
    }
}