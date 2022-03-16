<?php
    require_once(ABSPATH . 'wp-content/plugins/qt-table-plugin/includes/nf.php');
    NF::checkForNf();

    require_once(plugin_dir_path(__DIR__) . "controllers/main-controller.php");
    $mainControllerObj = new MainController();
?>

<div class="wrap">
    <h1>Main</h1>
    <div id="table-wrap">
        <table id="nfFormTable" class="display">
            <thead>
                <tr>
                    <th>Forms</th>
                </tr>
            </thead>
            <tbody>
            <?php $mainControllerObj->renderAllForms(); ?>
            </tbody>
        </table>
    </div>
</div>