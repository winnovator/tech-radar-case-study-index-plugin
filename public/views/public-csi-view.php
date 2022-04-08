<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/public-csi-controller.php");

class PublicCaseStudyIndexView extends PublicCaseStudyIndexController {
    public function getPublicCsi() {
        $output = '';
        
        $output .= '<div class="csi-grid-container">';
        $output .= '<div id="csi-content" class="csi-content"></div>';
        $output .= '<div id="csi-side-panel"></div>';
        $output .= '<div id="csi-pagination"><div id="csi-pagination-bttns">';
        $output .= '<button id="csi-previous-page"><span class="dashicons dashicons-arrow-left"></span></button>';
        $output .= '<span id="csi-current-page"></span>';
        $output .= '<button id="csi-next-page"><span class="dashicons dashicons-arrow-right"></button>';
        $output .= '</div></div>';
        $output .= '</div>';

        return $output;
    }
}