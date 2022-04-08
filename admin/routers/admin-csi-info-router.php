<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-info-controller.php");

class AdminCaseStudyIndexInfoRouter extends AdminCaseStudyIndexInfoController {
    public function postCsiDataSubmit() {
        if (isset($_POST['post_sub_id']) && isset($_POST['button_action']) && $_POST['redirect_url']) {
            if (check_ajax_referer('wp_rest', 'admin_csi_info_security_nonce')) {
                if ($_POST['button_action'] == 'publish') {
                    $this->publishSub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "reload" }');
                    wp_die();
                }

                if ($_POST['button_action'] == 'depublish') {
                    $this->depublishSub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "reload" }');
                    wp_die();
                }

                if ($_POST['button_action'] == 'delete') {
                    $this->deleteSub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "redirect", "redirect_url" : "' . $_POST['redirect_url'] . '" }');
                    wp_die();
                }
            }
        }
    }
}