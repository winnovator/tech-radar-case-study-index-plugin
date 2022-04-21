<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/public-csi-controller.php");

class PublicCaseStudyIndexRouter extends PublicCaseStudyIndexController {
    public function getPublicCsiData() {
        if (isset($_GET['public_csi_security_nonce'])) {
            if (check_ajax_referer('wp_rest', 'public_csi_security_nonce')) {
                echo wp_send_json($this->convertedSubDataArr);
                wp_die();
            }
        }
    }

    public function getSinglePublicCsiData($request) {
        if (isset($_GET['public_csi_security_nonce']) && isset($request['id'])) {
            if (!empty($this->getSingleSub($request['id']))) {
                echo wp_send_json($this->getSingleSub($request['id']));
                wp_die();
            }
            else {
                echo 'No sub found by this id.';
                wp_die();
            }
        }
    }
}