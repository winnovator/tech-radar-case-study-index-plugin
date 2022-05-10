<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-info-controller.php");
require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/csi-settings.php');

class AdminCaseStudyIndexInfoRouter extends AdminCaseStudyIndexInfoController {
    private function CsiMailer($to) {
        $subject = 'Uw case studie is geaccepteerd!';
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $body = '';

        $body .= '<p>Geachte Inzender,</p>';
        $body .= '<p>Uw case studie is geaccepteerd!</p>';
        $body .= '<p>Hartelijk dank.</p>';
        $body .= '<p>Met vriendelijke groet,</p>';
        $body .= '<p>Windesheim Technology Radar</p>';

        wp_mail($to, $subject, $body, $headers);
    }

    public function postCsiDataSubmit() {
        if (isset($_POST['post_sub_id']) && isset($_POST['button_action']) && $_POST['redirect_url']) {
            if (check_ajax_referer('wp_rest', 'admin_csi_info_security_nonce')) {
                if ($_POST['button_action'] == 'publish') {

                    if (CaseStudyIndexSettings::$emailOn) {
                        if (isset($_POST['admin_csi_email']) && !empty($_POST['admin_csi_email'] &&
                            isset($_POST['admin_csi_status']) && !empty($_POST['admin_csi_status']) &&
                            $_POST['admin_csi_status'] == 1)) {
                            $this->CsiMailer($_POST['admin_csi_email']);
                        }
                    }

                    $this->executePublishSub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "reload" }');
                    wp_die();
                }

                if ($_POST['button_action'] == 'depublish') {
                    $this->executeDepublishSub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "reload" }');
                    wp_die();
                }

                if ($_POST['button_action'] == 'delete') {
                    $this->executeDeleteSub($_POST['post_sub_id']);
                    wp_send_json('{ "action" : "redirect", "redirect_url" : "' . $_POST['redirect_url'] . '" }');
                    wp_die();
                }
            }
        }
    }
}
