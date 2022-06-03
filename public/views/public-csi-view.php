<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/public-csi-controller.php");

class PublicCaseStudyIndexView extends PublicCaseStudyIndexController {
    public function getPublicCsi() {
        $output = '';
        
        $output .= '<div id="csi-filter-button-container"><button id="csi-filter-button" type="button">Filter</button></div>';
        $output .= '<div id="csi-grid-container">';
        $output .= '<div id="csi-content" class="csi-content"><div class="csi-public-loader"></div></div>';
        $output .= '<div id="csi-public-info-modal-container"></div>';
        $output .= '<div id="csi-side-panel"><div class="csi-public-loader"></div></div>';
        $output .= '<div id="csi-pagination"><div id="csi-pagination-bttns">';
        $output .= '<button id="csi-previous-page"><span class="dashicons dashicons-arrow-left"></span></button>';
        $output .= '<span id="csi-current-page"></span>';
        $output .= '<button id="csi-next-page"><span class="dashicons dashicons-arrow-right"></button>';
        $output .= '</div></div>';
        $output .= '</div>';

        return $output;
    }
}