<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "controllers/public-csi-controller.php");

class PublicCaseStudyIndexView extends PublicCaseStudyIndexController
{
    public function getPublicCsi()
    {
        $output = '';

        $output .= '<div class="wrap">';
        $output .= '<div class="csi-grid-container">';
        $output .= '<div id="csi-content" class="csi-content"></div>';
        $output .= '<div id="csi-side-panel">';
        $output .= $this->renderSidePanelData();
        $output .= '</div>';
        $output .= '<div id="csi-pagination">';
        $output .= '<div id="csi-pagination-bttns">';
        $output .= '<button id="csi-previous-page">Prev</button>';
        $output .= '<span id="csi-current-page"></span>';
        $output .= '<button id="csi-next-page">Next</button>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    private function renderSidePanelData()
    {
        $output = '';

        $allMinorUniqueArr = $this->getNfSubData('minor', $this->nfSubData, true);
        $allProjectStageUniqueArr = $this->getNfSubData('project_stage', $this->nfSubData, true);
        $allPorterUniqueArr = $this->getNfSubData('porter', $this->nfSubData, true);
        $allSbiUniqueArr = $this->getNfSubData('sbi', $this->nfSubData, true);
        $allMetaTrendsUniqueArr = $this->getNfSubData('meta_trends', $this->nfSubData, true);
        $rowCountAllMinorUniqueArr = count($allMinorUniqueArr);
        $rowCountAllProjectStageUniqueArr = count($allProjectStageUniqueArr);
        $rowCountAllPorterUniqueArr = count($allPorterUniqueArr);
        $rowCountAllSbiUniqueArr = count($allSbiUniqueArr);
        $rowCountAllMetaTrendsUniqueArr = count($allMetaTrendsUniqueArr);

        if (
            $rowCountAllMinorUniqueArr > 0 && $allMinorUniqueArr != NULL && $rowCountAllProjectStageUniqueArr > 0 && $allProjectStageUniqueArr != NULL &&
            $rowCountAllPorterUniqueArr > 0 && $allPorterUniqueArr != NULL && $rowCountAllSbiUniqueArr > 0 && $allSbiUniqueArr != NULL &&
            $rowCountAllMetaTrendsUniqueArr > 0 && $allMetaTrendsUniqueArr != NULL
        ) {

            $output .= '<div>';
            $output .= '<h1>Windesheim Minor</h1>';
            $output .= '<ul>';

            foreach ($allMinorUniqueArr as $minor) {
                $output .= '<li><label for="minor"><input type="checkbox" name="minor" value="' . esc_attr($minor) . '"/>' . esc_html($minor) . '</label></li>';
            }

            $output .= '</ul>';
            $output .= '</div>';

            $output .= '<div>';
            $output .= '<h1>Project Stage</h1>';
            $output .= '<ul>';

            foreach ($allProjectStageUniqueArr as $projectStage) {
                $output .= '<li><label for="project-stage"><input type="checkbox" name="project-stage" value="' . esc_attr($projectStage) . '"/>' . esc_html($projectStage) . '</label></li>';
            }

            $output .= '</ul>';
            $output .= '</div>';

            $output .= '<div>';
            $output .= '<h1>Michael Porter\'s Value Chain</h1>';
            $output .= '<ul>';

            foreach ($allPorterUniqueArr as $porter) {
                $output .= '<li><label for="porter"><input type="checkbox" name="porter" value="' . esc_attr($porter) . '"/>' . esc_html($porter) . '</label></li>';
            }

            $output .= '</ul>';
            $output .= '</div>';

            $output .= '<div>';
            $output .= '<h1>SBI-code</h1>';
            $output .= '<ul>';

            foreach ($allSbiUniqueArr as $sbi) {
                $output .= '<li><label for="sbi"><input type="checkbox" name="sbi" value="' . esc_attr($sbi) . '"/>' . esc_html($sbi) . '</label></li>';
            }

            $output .= '</ul>';
            $output .= '</div>';

            $output .= '<div>';
            $output .= '<h1>Meta-trends(s)</h1>';
            $output .= '<ul>';

            foreach ($allMetaTrendsUniqueArr as $metaTrends) {
                $output .= '<li><label for="meta-trends"><input type="checkbox" name="meta-trends" value="' . esc_attr($metaTrends) . '"/>' . esc_html($metaTrends) . '</label></li>';
            }

            $output .= '</ul>';
            $output .= '</div>';

            $output .= '<div id="csi-submit-container"><button id="csi-submit">Verzenden</button></div>';
        } else {
            $output .= '<div>No filter data available.</div>';
        }

        return $output;
    }  
}

$publicCsiControllerObj = new PublicCaseStudyIndexView();

echo $publicCsiControllerObj->getPublicCsi();