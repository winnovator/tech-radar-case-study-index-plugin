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
        echo '<th>SBI</th>';
        echo '<th>Project owner</th>';
        echo '<th>Status</th>';
        echo '<th>Published</th>';
        echo '<th>Link</th>';
        echo '</tr>';
    }
}

    $adminCsiControllerObj = new AdminCaseStudyIndexView();
?>

<div class="wrap">
    <h1>Admin panel</h1>
    <div id="table-wrap">
        <table id="nfFormTable" class="display">
            <thead>
                <?php $adminCsiControllerObj->renderFormFields(); ?>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>