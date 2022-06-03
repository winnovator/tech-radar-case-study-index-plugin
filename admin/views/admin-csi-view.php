<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/nf.php');
require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-controller.php");

class AdminCaseStudyIndexView extends AdminCaseStudyIndexController {

    public function renderFormFields() {
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Projectnaam</th>';
        echo '<th>Sector (SBI-code)</th>';
        echo '<th>Projecteigenaar</th>';
        echo '<th>Status</th>';
        echo '<th>Gepubliceerd</th>';
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