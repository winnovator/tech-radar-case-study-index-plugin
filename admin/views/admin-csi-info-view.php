<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/nf.php');
require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-info-controller.php");

class AdminCaseStudyIndexInfoView extends AdminCaseStudyIndexInfoController {

    public function __construct() {
        parent::__construct();
        if (empty($this->wpCsiData) || empty($this->nfSubData)) { echo "<script>location.href = '" . admin_url('admin.php?page=admin-csi') . "';</script>"; }
    }
    
    public function renderCsiData() {
        if (empty($this->wpCsiData) || empty($this->nfSubData)) { return; }

        $output = '';

        $output .= '<table id="csi-info-data-table">';
        $output .= '<tr>';
        $output .= '<th>Status</th>';
        $output .= '<td>' . ($this->wpCsiData[0]->new == 1 ? 'New' : 'Existing') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Published</th>';
        $output .= '<td>' . ($this->wpCsiData[0]->published == 1 ? 'Yes' : 'No') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Name</th>';
        $output .= '<td>' . $this->nfSubData->get_field_value('project_name') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Owner</th>';
        $output .= '<td>' . $this->nfSubData->get_field_value('minor') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Owner Email</th>';
        $output .= '<td>' . $this->nfSubData->get_field_value('project_owner_email') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Minor</th>';
        $output .= '<td>' . $this->nfSubData->get_field_value('minor') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Michael Porter</th>';
        $output .= '<td>' . implode(', ', $this->nfSubData->get_field_value('porter')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>SBI-code</th>';
        $output .= '<td>' .  $this->nfSubData->get_field_value('sbi') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Technology Innovatiob</th>';
        $output .= '<td>' .  $this->nfSubData->get_field_value('tech_innovations') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Technology Providers</th>';
        $output .= '<td>' .  $this->nfSubData->get_field_value('tech_providers') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Meta Trends</th>';
        $output .= '<td>' .  implode(', ', $this->nfSubData->get_field_value('meta_trends')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Company Sector</th>';
        $output .= '<td>' .  $this->nfSubData->get_field_value('company_sector') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Context</th>';
        $output .= '<td>' .  $this->nfSubData->get_field_value('project_context') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Problem</th>';
        $output .= '<td>' .  $this->nfSubData->get_field_value('project_problem') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project Goal</th>';
        $output .= '<td>' .  $this->nfSubData->get_field_value('project_goal') . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Case Study URL</th>';
        $output .= '<td><a href="' .  $this->nfSubData->get_field_value('case_study_url') . '" target="_blank">' . $this->nfSubData->get_field_value('case_study_url') .'</a></td>';
        $output .= '</tr>';

        $output .= '</table>';

        echo $output;
    }

    public function renderSubmitButton() {
        if (empty($this->wpCsiData) || empty($this->nfSubData)) { return; }

        $output = '';

        $output .= '<div id="submit-wrap">';
        
        if ($this->wpCsiData[0]->published == 1) {
            $output .= '<button class="submit-button button action" type="submit" name="button_action" value="depublish" onclick="return confirm(\'Are you sure you want to depublish this case study?\');">Depublish</button>';
        }

        if ($this->wpCsiData[0]->published == 0) {
            $output .= '<button class="submit-button button action" type="submit" name="button_action" value="publish" onclick="return confirm(\'Are you sure you want to publish this case study?\');">Publish</button>';
        }

        $output .= '<button class="submit-button button action" type="submit" name="button_action" value="delete" onclick="return confirm(\'Are you sure you want to delete this case study?\');">Delete</button>';
        
        $output .= '</div>';

        echo $output;
    }
}

    $adminCsiInfoControllerObj = new AdminCaseStudyIndexInfoView();
?>

<div class="wrap">
    <h1>Info view</h1>
    <div id="table-wrap">
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <input type="hidden" name="action" value="publish_admin_csi_data">
            <input type="hidden" name="admin_csi_nonce" value="<?php echo esc_attr($adminCsiInfoControllerObj->getNonce('admin_csi_nonce')); ?>">
            <input type="hidden" name="post_sub_id" value="<?php echo isset($_GET['sub_id']) ? esc_attr($_GET['sub_id']) : ''; ?>">
            <?php $adminCsiInfoControllerObj->renderCsiData(); ?>
            <?php $adminCsiInfoControllerObj->renderSubmitButton(); ?>
        </form>
    </div>
</div>