<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once WTR_CSI_PLUGIN_PATH . 'includes/class-wtr-csi-config.php';
require_once WTR_CSI_PLUGIN_PATH . 'public/class-wtr-csi-public-actions.php';

/**
 * Wtr_Csi_Public_Request_Handler
 */
class Wtr_Csi_Public_Request_Handler {
    
    /**
     * wtr_csi_public_actions
     *
     * @var mixed
     */
    private $wtr_csi_public_actions;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->wtr_csi_public_actions = new Wtr_Csi_Public_Actions();
    }
        
    /**
     * get_public_csi_data
     *
     * @return void
     */
    public function get_public_csi_data() {
        if (isset($_GET['public_csi_security_nonce'])) {
            if (check_ajax_referer('wp_rest', 'public_csi_security_nonce')) {
                echo wp_send_json($this->wtr_csi_public_actions->prepare_wtr_csi_public_main_data(), 200);
                wp_die();
            }

            wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
            wp_die();
        }

        wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
        wp_die();
    }
    
    /**
     * get_single_public_csi_data
     *
     * @param  mixed $request
     * @return void
     */
    public function get_single_public_csi_data($request) {
        if (isset($_GET['public_csi_security_nonce'])) {
            if (check_ajax_referer('wp_rest', 'public_csi_security_nonce')) {
                if (isset($request['id'])) {
                    if ($this->wtr_csi_public_actions->prepare_wtr_csi_public_info_data($request['id'])) {
                        echo wp_send_json($this->wtr_csi_public_actions->prepare_wtr_csi_public_info_data($request['id']), 200);
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
    
    /**
     * get_all_sbi_data
     *
     * @return void
     */
    public function get_all_sbi_data() {
        if (isset($_GET['public_csi_security_nonce'])) {
            if (check_ajax_referer('wp_rest', 'public_csi_security_nonce')) {
                if ($this->wtr_csi_public_actions->get_all_sbi_data()) {
                    echo wp_send_json($this->wtr_csi_public_actions->get_all_sbi_data(), 200);
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