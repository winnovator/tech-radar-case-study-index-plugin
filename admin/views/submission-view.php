<?php
    require(plugin_dir_path(__DIR__) . "controllers/submission-controller.php");

    $submissionControllerObj = new SubmissionController();
?>

<div class="wrap">
    <h1>Submissions</h1>
    <div id="table-wrap">
        <form action="" method="post">
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