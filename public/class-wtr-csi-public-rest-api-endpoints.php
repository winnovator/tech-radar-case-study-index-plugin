<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once WTR_CSI_PLUGIN_PATH . 'public/class-wtr-csi-public-request-handler.php';

/**
 * Wtr_Csi_Public_Rest_Api_Endpoints
 */
class Wtr_Csi_Public_Rest_Api_Endpoints {    
    /**
     * register_routes
     *
     * @return void
     */
    public static function register_routes() {
        register_rest_route('wtr-csi/v1', '/public/sub', array(
            'methods' => 'GET',
            'callback' => [new Wtr_Csi_Public_Request_Handler(), 'get_public_csi_data'],
            'permission_callback' => '__return_true'
        ));
        
        register_rest_route('wtr-csi/v1', '/public/sub/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => [new Wtr_Csi_Public_Request_Handler(), 'get_single_public_csi_data'],
            'permission_callback' => '__return_true'
        ));
        
        register_rest_route('wtr-csi/v1', '/public/sbi', array(
            'methods' => 'GET',
            'callback' => [new Wtr_Csi_Public_Request_Handler(), 'get_all_sbi_data'],
            'permission_callback' => '__return_true'
        ));
    }
}