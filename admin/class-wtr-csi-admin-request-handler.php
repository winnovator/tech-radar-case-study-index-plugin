<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once WTR_CSI_PLUGIN_PATH . 'includes/class-wtr-csi-config.php';
require_once WTR_CSI_PLUGIN_PATH . 'admin/class-wtr-csi-admin-mailer.php';
require_once WTR_CSI_PLUGIN_PATH . 'admin/class-wtr-csi-admin-actions.php';

/**
 * Wtr_Csi_Admin_Request_Handler
 */
class Wtr_Csi_Admin_Request_Handler {
    
    /**
     * wtr_csi_admin_mailer
     *
     * @var mixed
     */
    private $wtr_csi_admin_mailer;    
    
    /**
     * wtr_csi_admin_actions
     *
     * @var mixed
     */
    private $wtr_csi_admin_actions;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        if (!function_exists('Ninja_Forms')) { wp_redirect(admin_url('admin.php?page=wtr-csi-admin-nf-not-installed')); }
        $this->wtr_csi_admin_mailer = new Wtr_Csi_Admin_Mailer();
        $this->wtr_csi_admin_actions = new Wtr_Csi_Admin_Actions();
        $this->wtr_csi_admin_actions->update_wp_csi_table();
    }
    
    /**
     * get_all_subs
     *
     * @return void
     */
    public function get_all_subs() {
        if (check_ajax_referer('wp_rest', 'wtr_csi_admin_main_nonce')) {
            echo wp_send_json($this->wtr_csi_admin_actions->prepare_datatable_data(), 200);
            wp_die();
        }

        wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
    }
    
    /**
     * submit_post_csi
     *
     * @return void
     */
    public function submit_post_csi() {
        if (isset($_POST['post_sub_id']) && isset($_POST['button_action']) && $_POST['redirect_url']) {
            if (check_ajax_referer('wp_rest', 'wtr_csi_admin_info_nonce')) {
                if ($_POST['button_action'] == 'publish') {

                    if (Wtr_Csi_Config::$email_on) {
                        if (isset($_POST['admin_csi_email']) && !empty($_POST['admin_csi_email'] &&
                            isset($_POST['admin_csi_status']) && !empty($_POST['admin_csi_status']) &&
                            $_POST['admin_csi_status'] == 1)) {
                            $this->wtr_csi_admin_mailer->csi_mailer($_POST['admin_csi_email']);
                        }
                    }

                    $this->wtr_csi_admin_actions->publish_sub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "reload" }', 200);
                    wp_die();
                }

                if ($_POST['button_action'] == 'depublish') {
                    $this->wtr_csi_admin_actions->depublish_sub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "reload" }', 200);
                    wp_die();
                }

                if ($_POST['button_action'] == 'delete') {
                    $this->wtr_csi_admin_actions->delete_sub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "redirect", "redirect_url" : "' . $_POST['redirect_url'] . '" }', 200);
                    wp_die();
                }
            }
        }
        
        wp_send_json_error(new WP_Error('Forbidden access', 'You are not allowed on this page.'), 403);
    }
}