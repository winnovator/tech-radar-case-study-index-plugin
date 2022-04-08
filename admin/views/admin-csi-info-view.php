<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/nf.php');
require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-info-controller.php");

class AdminCaseStudyIndexInfoView extends AdminCaseStudyIndexInfoController {

    public function __construct() {
        parent::__construct();
        if (empty($this->wpCsiData) || empty($this->nfSubData)) { echo esc_js("<script>location.href = '" . esc_url(admin_url('admin.php?page=admin-csi')) . "';</script>"); }
    }
    
    public function renderCsiData() {
        if (empty($this->wpCsiData) || empty($this->nfSubData)) { return; }

        $output = '';

        $output .= '<table id="csi-info-data-table">';
        $output .= '<tr>';
        $output .= '<th>Status</th>';
        $output .= '<td>' . ($this->wpCsiData[0]->new == 1 ? esc_html('New') : esc_html('Existing')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Published</th>';
        $output .= '<td>' . ($this->wpCsiData[0]->published == 1 ? 'Yes' : 'No') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Name</th>';
        $output .= '<td>' . esc_html($this->nfSubData->get_field_value('project_name')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Owner</th>';
        $output .= '<td>' . esc_html($this->nfSubData->get_field_value('project_owner')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Owner Email</th>';
        $output .= '<td>' . esc_html($this->nfSubData->get_field_value('project_owner_email')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Minor</th>';
        $output .= '<td>' . esc_html($this->nfSubData->get_field_value('minor')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Michael Porter</th>';
        $output .= '<td>' . esc_html(implode(', ', $this->nfSubData->get_field_value('porter'))) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>SBI-code</th>';
        $output .= '<td>' .  esc_html($this->nfSubData->get_field_value('sbi')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Technology Innovatiob</th>';
        $output .= '<td>' .  esc_html($this->nfSubData->get_field_value('tech_innovations')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Technology Providers</th>';
        $output .= '<td>' .  esc_html($this->nfSubData->get_field_value('tech_providers')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Meta Trends</th>';
        $output .= '<td>' .  esc_html(implode(', ', $this->nfSubData->get_field_value('meta_trends'))) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Company Sector</th>';
        $output .= '<td>' .  esc_html($this->nfSubData->get_field_value('company_sector')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Context</th>';
        $output .= '<td>' .  esc_html($this->nfSubData->get_field_value('project_context')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Problem</th>';
        $output .= '<td>' .  esc_html($this->nfSubData->get_field_value('project_problem')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Goal</th>';
        $output .= '<td>' .  esc_html($this->nfSubData->get_field_value('project_goal')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Case Study URL</th>';
        $output .= '<td><a href="' .  esc_url($this->nfSubData->get_field_value('case_study_url')) . '" target="_blank">' . esc_html($this->nfSubData->get_field_value('case_study_url')) .'</a></td>';
        $output .= '</tr>';

        $output .= '</table>';

        echo $output;
    }

    public function renderSubmitButton() {
        if (empty($this->wpCsiData) || empty($this->nfSubData)) { return; }

        $output = '';

        $output .= '<div id="submit-wrap">';
        
        if ($this->wpCsiData[0]->published == 1) {
            $output .= '<button id="admin-csi-info-depublish-button" class="submit-button button action" type="button">Depublish</button>';
        }

        if ($this->wpCsiData[0]->published == 0) {
            $output .= '<button id="admin-csi-info-publish-button" class="submit-button button action" type="button">Publish</button>';
        }

        $output .= '<button id="admin-csi-info-delete-button" class="submit-button button action" type="button">Delete</button>';
        $output .= '</div>';

        echo $output;
    }
}

    $adminCsiInfoControllerObj = new AdminCaseStudyIndexInfoView();
?>

<div class="wrap">
    <h1>Info view</h1>
    <div id="table-wrap">
        <?php $adminCsiInfoControllerObj->renderCsiData(); ?>
        <?php $adminCsiInfoControllerObj->renderSubmitButton(); ?>
    </div>
</div>