<?php
require(plugin_dir_path(__DIR__) . "models/main.php");

class MainController extends Main {

    public function renderAllForms() {
        if (!function_exists('Ninja_Forms')) { return; };
        
        $allFormData = $this->getAllFormData();

        foreach ($allFormData as $formData) {
            echo '<tr><td><a href="' . esc_url(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH). '?page=qt-table-submissions&form-id=' . $formData->get_id()) . '">' . esc_html($formData->get_id()) . '. ' . esc_html($formData->get_settings('title')) . '</a></td></tr>';
        }
    }
}
