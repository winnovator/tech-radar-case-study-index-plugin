<?php

class NF {
    public static function checkForNf() {
        if (!function_exists('Ninja_Forms')) {
            echo '<p style="color: red; font-weight: bold; margin: 5px; padding: 9px 0 4px 0;"> The Ninja Forms plugin is required. Install or activate it now through the plugin.</p>';
            die;
        }
    }
}