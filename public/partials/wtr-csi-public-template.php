<?php
if (!defined('ABSPATH')) {
    wp_die();
}

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.windesheim.tech
 * @since      1.0.0
 *
 * @package    Wtr_Csi
 * @subpackage Wtr_Csi/public/partials
 */

?>

<div id="wtr-csi-public-filter-button-container">
    <button id="wtr-csi-public-filter-button" type="button" data-toggle="false">Filter</button>
</div>
<div id="wtr-csi-public-grid-container">
    <div id="wtr-csi-public-content" class="wtr-csi-public-content">
        <div class="wtr-csi-public-loader"></div>
    </div>
    <div id="wtr-csi-public-info-modal-container"></div>
    <div id="wtr-csi-public-side-panel">
        <div class="wtr-csi-public-loader"></div>
    </div>
    <div id="wtr-csi-public-pagination">
        <div id="wtr-csi-public-pagination-bttns">
            <button id="wtr-csi-public-previous-page"><span class="dashicons dashicons-arrow-left"></span></button>
            <span id="wtr-csi-public-current-page"></span>
            <button id="wtr-csi-public-next-page"><span class="dashicons dashicons-arrow-right"></span></button>
        </div>
    </div>
</div>