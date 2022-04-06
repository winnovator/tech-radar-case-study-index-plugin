<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-info-controller.php");

class AdminCaseStudyIndexInfoRouter extends AdminCaseStudyIndexInfoController {
    public function postCsiDataSubmit() {
        if (isset($_POST['post_sub_id']) && isset($_POST['button_action'])) {
            if (check_admin_referer('admin_csi_nonce', 'admin_csi_nonce')) {
                if ($_POST['button_action'] == 'publish') {
                    $this->publishSub($_POST['post_sub_id']);
                    wp_redirect(wp_get_referer());
                }

                if ($_POST['button_action'] == 'depublish') {
                    $this->depublishSub($_POST['post_sub_id']);
                    wp_redirect(wp_get_referer());
                }

                if ($_POST['button_action'] == 'delete') {
                    $this->denySub($_POST['post_sub_id']);
                    wp_redirect('admin.php?page=admin-csi');
                }
            }
        }
    }
}

add_action('admin_post_publish_admin_csi_data', [new AdminCaseStudyIndexInfoRouter, 'postCsiDataSubmit']);