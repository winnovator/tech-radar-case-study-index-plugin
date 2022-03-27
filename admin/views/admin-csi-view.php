<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/nf.php');
require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-controller.php");

class AdminCaseStudyIndexView extends AdminCaseStudyIndexController {

    public function renderFormFields() {
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Project Name</th>';
        echo '<th>Windesheim Minor</th>';
        echo '<th>Project Stage</th>';
        echo '<th>Michael Porter\'s Value Chain</th>';
        echo '<th>SBI-code</th>';
        echo '<th>Technological Innovations Applied</th>';
        echo '<th>Technology Provider(s)</th>';
        echo '<th>Meta-trends</th>';
        echo '<th>Company Sector</th>';
        echo '<th>Case Study URL</th>';
        echo '<th>Published</th>';
        echo '</tr>';
    }

    public function renderFormData() {
        $allSeqIdArr = $this->getAllPublishedSubmissionByFormID($this->formID, true);
        $rowCountconvertedSubDataArr = count($this->convertedSubDataArr);
        $rowCountAllSeqIdArr = count($allSeqIdArr);

        if ($rowCountconvertedSubDataArr > 0 && $this->convertedSubDataArr != NULL && $rowCountAllSeqIdArr > 0 && $allSeqIdArr != NULL) {
            foreach ($this->convertedSubDataArr as $convertedSubArr) {
                echo '<tr>';
                
                foreach ($convertedSubArr as $key => $element) {
                    if ($key == 'porter' || $key == 'meta_trends') {
                        echo '<td>'. implode(', ', $element) . '</td>';
                    }
                    else if ($key == 'case_study_url') {
                        echo '<td><a href="' . esc_url('http://'. $element) . '" target="_blank"> ' . esc_html($element) . ' </a></td>';
                    }
                    else {
                        echo "<td>". $element . "</td>";
                    }
                }
                
                echo '<td>';
                echo '<input type="checkbox" name="sub_seq_id_values[]" value="' . esc_attr($convertedSubArr['id']) . '" ';
    
                echo in_array($convertedSubArr['id'], $allSeqIdArr) ? esc_html('checked ') : '';
    
                echo '></td>';
                echo '</tr>';
            }
        }
    }

    public function renderSubmitButton() {
        $rowCount = count($this->convertedSubDataArr);

        if ($rowCount > 0) {
            echo "<div id='submit-wrap'><input id='submit-button' class='button action' type='submit' value='Save'></div>";
        }
    }
}

$adminCsiControllerObj = new AdminCaseStudyIndexView();
?>

<div class="wrap">
    <h1>Case Study Index - Admin panel</h1>
    <?php
    if (isset($_GET['success'])) {
        echo '<div id="csi-success-message"><strong>Submissions published!</strong></div>';
    }
    ?>
    <div id="table-wrap">
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <input type="hidden" name="action" value="publish_admin_csi_data">
            <input type="hidden" name="admin_csi_nonce" value="<?php echo esc_attr($adminCsiControllerObj->getNonce('admin_csi_nonce')); ?>">
            <input type="hidden" name="form_id" value="7">
            <table id="nfFormTable" class="display">
                <thead>
                    <?php $adminCsiControllerObj->renderFormFields(); ?>
                </thead>
                <tbody>
                    <?php $adminCsiControllerObj->renderFormData(); ?>
                </tbody>
            </table>
            <?php $adminCsiControllerObj->renderSubmitButton(); ?>
        </form>
    </div>
</div>