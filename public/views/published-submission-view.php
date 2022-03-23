<?php
    require_once(plugin_dir_path(__DIR__) . "controllers/published-submission-controller.php");

    class PublishedSubmissionView extends PublishedSubmissionController {
        public function getPublishedTable($form_ID) {
        }
    }

    $publishedSubmissionControllerObj = new PublishedSubmissionController();
?>

<div class="wrap">
    <div class='grid-container'>
        <div id='content' class='content'></div>
        <div id='side-panel'>
            <?php
                echo $publishedSubmissionControllerObj->renderSidePanelData();
            ?>
        </div>
        <div id='pagination'>
            <div id='pagination-bttns'>
                <button id='previous-page'>Prev</button>
                <span id='current-page'></span>
                <button id='next-page'>Next</button>
            </div>
        </div>
    </div>
</div>