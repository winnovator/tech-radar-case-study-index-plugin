<?php
    require_once(plugin_dir_path(__DIR__) . "controllers/public-csi-controller.php");

    class PublicCaseStudyIndexView extends PublicCaseStudyIndexController {
        public function getPublicCsi() {
        }
    }

    $publicCsiControllerObj = new PublicCaseStudyIndexController();
?>

<div class="wrap">
    <div class='csi-grid-container'>
        <div id='csi-content' class='csi-content'></div>
        <div id='csi-side-panel'>
            <?php
                echo $publicCsiControllerObj->renderSidePanelData();
            ?>
        </div>
        <div id='csi-pagination'>
            <div id='csi-pagination-bttns'>
                <button id='csi-previous-page'>Prev</button>
                <span id='csi-current-page'></span>
                <button id='csi-next-page'>Next</button>
            </div>
        </div>
    </div>
</div>