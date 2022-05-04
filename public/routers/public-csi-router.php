<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/public-csi-controller.php");

class PublicCaseStudyIndexRouter extends PublicCaseStudyIndexController {
    public function getPublicCsiData() {
        if (isset($_GET['public_csi_security_nonce'])) {
            if (check_ajax_referer('wp_rest', 'public_csi_security_nonce')) {
                echo wp_send_json($this->convertedSubDataArr, 200);
                wp_die();
            }

            wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
            wp_die();
        }

        wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
        wp_die();
    }

    public function getSinglePublicCsiData($request) {
        if (isset($_GET['public_csi_security_nonce'])) {
            if (check_ajax_referer('wp_rest', 'public_csi_security_nonce')) {
                if (isset($request['id'])) {
                    if (!empty($this->getSingleSub($request['id']))) {
                        echo wp_send_json($this->getSingleSub($request['id']), 200);
                        wp_die();
                    }
    
                    wp_send_json_error(new WP_Error('Not found', 'Not found.'), 404);
                    wp_die();
                }
    
                wp_send_json_error(new WP_Error('Not found', 'Not found.'), 404);
                wp_die();
            }

            wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
            wp_die();
        }

        wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
        wp_die();
    }

    public function getAllSbiData() {
        if (isset($_GET['public_csi_security_nonce'])) {
            if (check_ajax_referer('wp_rest', 'public_csi_security_nonce')) {
                if ($this->getAllSbiCodes()) {
                    echo wp_send_json($this->getAllSbiCodes(), 200);
                    wp_die();
                }

                wp_send_json_error(new WP_Error('Not found', 'Not found.'), 404);
                wp_die();
            }

            wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
            wp_die();
        }

        wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
        wp_die();
    }
}