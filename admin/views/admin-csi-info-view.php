<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/nf.php');
require_once(plugin_dir_path(__DIR__) . "controllers/admin-csi-info-controller.php");

class AdminCaseStudyIndexInfoView extends AdminCaseStudyIndexInfoController {
    public function __construct() {
        parent::__construct();
        if (empty($this->wpCsiData) || empty($this->nfSubData)) {
            echo "<script>location.href = '" . esc_url(admin_url('admin.php?page=admin-csi')) . "';</script>";
            wp_die();
        }
    }

    public function renderCsiData() {
        if (empty($this->wpCsiData) || empty($this->nfSubData)) {
            return;
        }

        $output = '';
        $status = $this->wpCsiData[0]->new;
        $published = $this->wpCsiData[0]->published;
        $projectName = $this->nfSubData->get_field_value('project_name');
        $projectOwner = $this->nfSubData->get_field_value('project_owner');
        $projectOwnerEmail = $this->nfSubData->get_field_value('project_owner_email');
        $minor = $this->nfSubData->get_field_value('minor');
        $porter = $this->nfSubData->get_field_value('porter');
        $sbi = $this->nfSubData->get_field_value('sbi');
        $techInnovations = $this->nfSubData->get_field_value('tech_innovations');
        $techProviders = $this->nfSubData->get_field_value('tech_providers');
        $trends = $this->nfSubData->get_field_value('meta_trends');
        $companySector = $this->nfSubData->get_field_value('company_sector');
        $sdg = $this->nfSubData->get_field_value('sdg');
        $projectContext = $this->nfSubData->get_field_value('project_context');
        $projectProblem = $this->nfSubData->get_field_value('project_problem');
        $projectGoal = $this->nfSubData->get_field_value('project_goal');
        $caseStudyUrl = $this->nfSubData->get_field_value('case_study_url');
        $caseStudyImage = $this->nfSubData->get_field_value('case_study_image');
        $caseStudyVideo = $this->nfSubData->get_field_value('case_study_video');

        $output .= '<table id="csi-info-data-table">';
        
        $output .= '<tr>';
        $output .= '<th>Status</th>';
        $output .= '<td id="admin-csi-status" data-admin-csi-status="' . esc_attr($status) . '">' . (esc_html($status) == 1 ? esc_html('Nieuw') : esc_html('Bestaand')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Gepubliceerd</th>';
       	$output .= '<td>' . (esc_html($published) == 1 ? esc_html('Ja') : esc_html('Nee')) . '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Projectnaam</th>';

        if ($projectName) {
            $output .= '<td>' . esc_html($projectName) . '</td>';
        }
        else {
            $output .= '<td>Geen naam bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Projecteigenaar</th>';

        if ($projectOwner) {
            $output .= '<td>' . esc_html($projectOwner) . '</td>';
        }
        else {
            $output .= '<td>Geen projecteigenaar bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Projecteigenaar email</th>';

        if ($projectOwnerEmail) {
            $output .= '<td id="admin-csi-email" data-admin-csi-email="' . esc_attr($projectOwnerEmail) . '">' . esc_html($projectOwnerEmail) . '</td>';
        }
        else {
            $output .= '<td>Geen projectemail bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Minor</th>';

        if ($minor) {
            $output .= '<td>' . esc_html($minor) . '</td>';
        }
        else {
            $output .= '<td>Geen minor bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Value Chain (Michael Porter)</th>';

        if ($porter) {
            $output .= '<td>' . esc_html(implode(', ', $porter)) . '</td>';
        }
        else {
            $output .= '<td>Geen Value Chain (Michael Porter) onderdelen bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Sector (SBI-code)</th>';

        if ($sbi) {
            $output .= '<td>' . esc_html($sbi . ' - ' . $this->getSingleSbiCode($sbi)) . '</td>';
        }
        else {
            $output .= '<td>Geen SBI-code bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Technologie Innovaties</th>';

        if ($techInnovations) {
            $output .= '<td>' . esc_html($techInnovations) . '</td>';
        }
        else {
            $output .= '<td>Geen technologie innovaties bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Technologie aanbieders</th>';

        if ($techProviders) {
            $output .= '<td>' . esc_html($techProviders) . '</td>';
        }
        else {
            $output .= '<td>Geen technologie aanbieders bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Trends</th>';

        if ($trends) {
            $output .= '<td>' . esc_html(implode(', ', $trends)) . '</td>';
        }
        else {
            $output .= '<td>Geen trends bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Bedrijfssector</th>';

        if ($companySector) {
            $output .= '<td>' . esc_html($companySector) . '</td>';
        }
        else {
            $output .= '<td>Geen bedrijfssector bekend.</td>';
        }
        
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>SDG\'s</th>';

        if ($sdg) {
            $output .= '<td>' . esc_html(implode(', ', $sdg)) . '</td>';
        }
        else {
            $output .= '<td>Geen SDG\'s bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project context</th>';

        if ($projectContext) {
            $output .= '<td>' . esc_html($projectContext) . '</td>';
        }
        else {
            $output .= '<td>Geen project context bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Project probleem</th>';

        if ($projectProblem) {
            $output .= '<td>' . esc_html($projectProblem) . '</td>';
        }
        else {
            $output .= '<td>Geen project probleem bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Projectdoel</th>';

        if ($projectGoal) {
            $output .= '<td>' . esc_html($projectGoal) . '</td>';
        }
        else {
            $output .= '<td>Geen projectdoel bekend.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Case studie link</th>';

        if ($caseStudyUrl) {
            if (count((array) json_decode($this->checkMaliciousUrl($caseStudyUrl)['body'])) > 0) {
                $output .= '<td>De opgegeven link is onveilig. Het is geadviseerd om deze case studie te verwijderen.</td>';
            }
            else {
                $url = substr($caseStudyUrl, 0, 4) === "http" || substr($caseStudyUrl, 0, 4) == 'https' ? $caseStudyUrl : 'https://' . $caseStudyUrl;
                $headers = @get_headers($url);
				$stat = substr($headers[0], 9, 3);
				
                if ($stat >= '200' && $stat < '400') {
                    $output .= '<td><a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a></td>';
                }
                else {
                    $output .= '<td>De volgende link is ongeldig: ' . esc_html($caseStudyUrl) . '</td>';
                }
            }
        }
        else {
            $output .= '<td>Geen case studie link bekend.</td>';
        }
        
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Case studie videolink</th>';
        
        if ($caseStudyVideo) {
            if (count((array) json_decode($this->checkMaliciousUrl($caseStudyVideo)['body'])) > 0) {
                $output .= '<td>De opgegeven videolink is onveilig. Het is geadviseerd om deze case studie te verwijderen.</td>';
            }
            else {
                $url = substr($caseStudyVideo, 0, 4) === "http" || substr($caseStudyVideo, 0, 4) == 'https' ? $caseStudyVideo : 'https://' . $caseStudyVideo;
                $headers = @get_headers($url);
				$stat = substr($headers[0], 9, 3);

                if ($stat >= '200' && $stat < '400') {
                    $output .= '<td><a id="csi-admin-info-video" href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a></td>';
                }
                else {
                    $output .= '<td>De volgende videolink is ongeldig: ' . esc_html($caseStudyVideo) . '</a></td>';
                }
            }
        }
        else {
            $output .= '<td>Geen case studie videolink beschikbaar.</td>';
        }

        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<th>Afbeelding</th>';

        if ($caseStudyImage) {
            $output .= '<td><img id="csi-admin-info-img" src="' . esc_url(implode('', $caseStudyImage)) . '">' . '</td>';
        }
        else {
            $output .= '<td><img id="csi-admin-info-img" src="' . esc_url(plugins_url('/assets/shared/images/windesheim_tech_radar_logo.png', __FILE__)) . '">' . '</td>';
        }

        $output .= '</tr>';

        $output .= '</table>';

        echo $output;
    }

    public function renderSubmitButton()
    {
        if (empty($this->wpCsiData) || empty($this->nfSubData)) {
            return;
        }

        $output = '';

        $output .= '<div id="submit-wrap">';

        if ($this->wpCsiData[0]->published == 1) {
            $output .= '<button id="admin-csi-info-depublish-button" class="submit-button button action" type="button">Verbergen</button>';
        }

        if ($this->wpCsiData[0]->published == 0) {
            $output .= '<button id="admin-csi-info-publish-button" class="submit-button button action" type="button">Publiceren</button>';
        }

        $output .= '<button id="admin-csi-info-delete-button" class="submit-button button action" type="button">Verwijderen</button>';
        $output .= '</div>';

        echo $output;
    }
}

$adminCsiInfoControllerObj = new AdminCaseStudyIndexInfoView();
?>

<div class="wrap">
    <h1>Info view</h1>
    <div id="table-wrap">
        <?php $adminCsiInfoControllerObj->renderCsiData(); ?>
        <?php $adminCsiInfoControllerObj->renderSubmitButton(); ?>
    </div>
</div>