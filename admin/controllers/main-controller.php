<?php
require(plugin_dir_path(__DIR__) . "models/main.php");

class MainController extends Main {
    public function renderAllForms() {
        $mainModelObj = new Main();
        $allFormData = $mainModelObj->getAllFormData();

        foreach ($allFormData as $formData) {
            echo '<tr><td><a href="' . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH). '?page=qt-table-submissions&form-id=' . $formData->get_id() . '">' . $formData->get_id() . '. ' . $formData->get_settings('title') . '</a></td></tr>';
        }
    }
}
