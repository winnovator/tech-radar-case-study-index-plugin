<?php
    require_once(plugin_dir_path(__DIR__) . "controllers/published-submission-controller.php");

    class PublishedSubmissionView extends PublishedSubmissionController {
        public function getPublishedTable($form_ID) {
            $output = "";

            $output .= '<div class="wrap">';
            $output .= '<div id="table-wrap">';
            $output .= '<table id="nfFormTable" class="display">';
            $output .= '<thead>';
            $output .= $this->renderFormFields($form_ID);
            $output .= '</thead>';
            $output .= '<tbody>';
            $output .= $this->renderFormData($form_ID);
            $output .= '</tbody>';
            $output .= '</table>';
            $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
    }
?>