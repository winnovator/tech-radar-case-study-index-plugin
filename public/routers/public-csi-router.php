<?php
    require_once(plugin_dir_path(__DIR__) . "controllers/published-submission-controller.php");

    class PublicCsiRouter {
        public function getSubmissionData() {
            if (isset($_GET['action']) && isset($_GET['security_nonce'])) {
                if (check_ajax_referer('case_index_ajax_nonce', 'security_nonce')) {
                    $pubSubContrObj = new PublishedSubmissionController();
                    echo json_encode($pubSubContrObj->convertedSubDataArr);
                    wp_die();
                }
            }
        }
    }

    add_action('wp_ajax_get_case_index_data', [new PublicCsiRouter, 'getSubmissionData']);
    add_action('wp_ajax_nopriv_get_case_index_data', [new PublicCsiRouter, 'getSubmissionData']);
?>