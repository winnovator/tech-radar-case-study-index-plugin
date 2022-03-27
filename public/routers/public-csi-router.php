<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/public-csi-controller.php");

class PublicCaseStudyIndexRouter extends PublicCaseStudyIndexController
{
    public function getPublicCsiData()
    {
        if (isset($_GET['action']) && isset($_GET['public_csi_security_nonce'])) {
            if (check_ajax_referer('public_csi_ajax_nonce', 'public_csi_security_nonce')) {
                echo json_encode($this->convertedSubDataArr);
                wp_die();
            }
        }
    }
}

add_action('wp_ajax_get_csi_data', [new PublicCaseStudyIndexRouter, 'getPublicCsiData']);
add_action('wp_ajax_nopriv_get_csi_data', [new PublicCaseStudyIndexRouter, 'getPublicCsiData']);