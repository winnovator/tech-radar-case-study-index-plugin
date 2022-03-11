<?php 

include_once(plugin_dir_path(__DIR__) . "data/main.php");

$mainObj = new Main();
$allFormData = $mainObj->getAllFormData();

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
            <?php

            foreach ($allFormData as $formData) {
                echo '<tr><td><a href="' . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH). '?page=qt-table-submissions&form-id=' . $formData->get_id() . '">' . $formData->get_id() . '. ' . $formData->get_settings('title') . '</a></td></tr>';
            }
            
            ?>
            </tbody>
        </table>
    </div>
</div>