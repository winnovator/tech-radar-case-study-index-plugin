<?php
    require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/nf.php');
    NF::checkForNf();

    require_once(plugin_dir_path(__DIR__) . "controllers/main-controller.php");
    $submissionControllerObj = new MainController();
?>

<div class="wrap">
    <h1>Main</h1>
    <div id="table-wrap">
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <input type="hidden" name="action" value="publish_data">
            <input type="hidden" name="publish_data_nonce" value="<?php echo esc_attr($submissionControllerObj->getNonce()); ?>">
            <input type="hidden" name="form_id" value="2">
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