<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-controller.php");

class AdminCaseStudyIndexRouter extends AdminCaseStudyIndexController
{
    public function postCheckBoxValues()
    {
        if (isset($_POST['form_id']) || isset($_POST['sub_seq_id_values'])) {
            echo 'es';
            if (check_admin_referer('admin_csi_nonce', 'admin_csi_nonce')) {
                $formID = $_POST['form_id'];
                $seqID = isset($_POST['sub_seq_id_values']) ? $_POST['sub_seq_id_values'] : [];
                $this->savePublishedSubmissions($formID, $seqID);
                wp_redirect(admin_url('admin.php?page=admin-csi&success=true'));
            }
        }
    }
}

add_action('admin_post_publish_admin_csi_data', [new AdminCaseStudyIndexRouter, 'postCheckBoxValues']);