<?php
    require_once(ABSPATH . 'wp-content/plugins/qt-table-plugin/includes/nf.php');
    NF::checkForNf();

    require_once(plugin_dir_path(__DIR__) . "controllers/submission-controller.php");
    $submissionControllerObj = new SubmissionController();
?>

<div class="wrap">
    <h1>Submissions</h1>
    <div id="table-wrap">
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <input type="hidden" name="action" value="publish_data">
            <input type="hidden" name="publish_data_nonce" value="<?php echo esc_attr($submissionControllerObj->getNonce()); ?>">
            <input type="hidden" name="form_id" value="<?php echo isset($_GET["form-id"]) ? esc_attr($_GET["form-id"]) : null; ?>">
            <table id="nfFormTable" class="display">
                <thead>
                    <?php $submissionControllerObj->renderFormFields(); ?>
                </thead>
                <tbody>
                    <?php $submissionControllerObj->renderFormData(); ?>
                </tbody>
            </table>
            <?php $submissionControllerObj->renderSubmitButton(); ?>
        </form>
    </div>
</div>