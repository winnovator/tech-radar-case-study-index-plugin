<?php
    require_once(plugin_dir_path(__DIR__) . "controllers/main-controller.php");

    class MainRouter extends MainController{
        public function postCheckBoxValues() {
            if (isset($_POST['form_id']) || isset($_POST['sub_seq_id_values'])) {
                $formID = $_POST['form_id'];
                $seqID = isset($_POST['sub_seq_id_values']) ? $_POST['sub_seq_id_values'] : [];
                $this->savePublishedSubmissions($formID, $seqID);
                wp_redirect(admin_url('admin.php?page=case-study-index-main'));
            }
        }
    }

    add_action('admin_post_publish_data', [new MainRouter,'postCheckBoxValues']);