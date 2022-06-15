<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once WTR_CSI_PLUGIN_PATH . 'admin/class-wtr-csi-admin-request-handler.php';

/**
 * Wtr_Csi_Admin_Rest_Api_Endpoints
 */
class Wtr_Csi_Admin_Rest_Api_Endpoints {    
    /**
     * register_routes
     *
     * @return void
     */
    public static function register_routes() {
        register_rest_route('wtr-csi/v1', '/admin-main/sub', array(
			'methods' => 'GET',
			'callback' => array(new Wtr_Csi_Admin_Request_Handler(), 'get_all_subs'),
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		));

		register_rest_route('wtr-csi/v1', '/admin-info/sub', array(
			'methods' => 'POST',
			'callback' => array(new Wtr_Csi_Admin_Request_Handler(), 'submit_post_csi'),
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		));
    }
}