<?php
if (!defined('ABSPATH')) {
    wp_die();
}

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.windesheim.tech
 * @since      1.0.0
 *
 * @package    Wtr_Csi
 * @subpackage Wtr_Csi/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php

require_once WTR_CSI_PLUGIN_PATH . 'admin/class-wtr-csi-admin-actions.php';
require_once WTR_CSI_PLUGIN_PATH . 'includes/class-wtr-csi-config.php';

$sub_id = isset($_GET['sub_id']) ? $_GET['sub_id'] : NULL;
$wtr_csi_admin_actions = new Wtr_Csi_Admin_Actions();
$sub = $wtr_csi_admin_actions->prepare_info_data($sub_id);

if (!$sub) {
    echo "<script>location.href = '" . esc_url(admin_url('admin.php?page=wtr-csi-admin-main')) . "'</script>";
}

?>

<div class="wrap">
    <h1>Case studie informatie pagina</h1>
    <div id="wtr-csi-admin-info-table-wrap">
        <table id="wtr-csi-admin-info-table">
            <?php
            foreach ($sub as $element) {
                echo '<tr>';
                echo '<th>' . $element['label'] . '</th>';

                switch ($element['type']) {
                    case 'text':
                        switch ($element['key']) {
                            case 'status':
                                echo '<td>' . esc_html($element['value'] == 1 ? 'Nieuw' : 'Bestaand') . '</td>';
                                break;
                            case 'published':
                                echo '<td>' . esc_html($element['value'] == 1 ? 'Ja' : 'Nee') . '</td>';
                                break;
                            case 'sbi':
                                echo '<td>' . esc_html($element['value'] . ' - ' . $wtr_csi_admin_actions->get_sbi_code_title($element['value'])) . '</td>';
                                break;
                            default:
                                if ($element['value']) {
                                    echo '<td>' . esc_html($element['value']) . '</td>';
                                } else {
                                    echo '<td>Geen data beschikbaar</td>';
                                }
                        }
                        break;
                    case 'check':
                        if ($element['value']) {
                            echo '<td>' . esc_html(implode(', ', $element['value'])) . '</td>';
                        }
                        else {
                            echo '<td>Geen data beschikbaar</td>';
                        }
                        break;
                    case 'link':
                        if ($element['value']) {
                            if ($wtr_csi_admin_actions->check_url_unsecure($element['value'])) {
                                echo '<td>De opgegeven link is onveilig. Het is geadviseerd om deze case studie te verwijderen.</td>';
                            } else {
                                $url = $wtr_csi_admin_actions->check_url_valid($element['value']);
    
                                if ($url) {
                                    echo '<td><a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a></td>';
                                } else {
                                    echo '<td>De volgende link is ongeldig: ' . esc_html($element['value']) . '</td>';
                                }
                            }
                        }
                        else {
                            echo '<td>Geen data beschikbaar</td>';
                        }

                        break;
                    case 'img':
                        if ($element['value']) {
                            echo '<td><img id="wtr-csi-admin-info-img" src="' . esc_url(implode('', $element['value'])) . '">' . '</td>';
                        } else {
                            echo '<td><img id="wtr-csi-admin-info-img" src="' . esc_url(plugin_dir_url(WTR_CSI_PLUGIN_PATH) . 'wtr-csi/shared/images/windesheim_tech_radar_logo.png') . '">' . '</td>';
                        }
                        break;
                }
            }
            ?>
        </table>
    </div>

    <div id="wtr-csi-admin-info-submit-wrap">
        <?php
        foreach ($sub as $element) {
            if ($element['key'] == 'published' && $element['value'] == 1) {
                echo '<button id="admin-csi-info-depublish-button" class="wtr-csi-admin-info-submit-button button action" type="button">Verbergen</button>';
            }

            if ($element['key'] == 'published' && $element['value'] == 0) {
                echo '<button id="admin-csi-info-publish-button" class="wtr-csi-admin-info-submit-button button action" type="button">Publiceren</button>';
            }
        }

        echo '<button id="admin-csi-info-delete-button" class="wtr-csi-admin-info-submit-button button action" type="button">Verwijderen</button>';
        ?>
    </div>
</div