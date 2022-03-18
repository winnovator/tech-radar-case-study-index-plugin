<?php
    require_once(plugin_dir_path(__DIR__) . "controllers/published-submission-controller.php");

    class PublishedSubmissionView extends PublishedSubmissionController {
        public function getPublishedTable($form_ID) {
        }
    }

    $publishedSubmissionControllerObj = new PublishedSubmissionController();
    $allMinorUniqueArr = $publishedSubmissionControllerObj->getSubData('minor', true);
    $allProjectStageUniqueArr = $publishedSubmissionControllerObj->getSubData('project_stage', true);
    $allPorterUniqueArr = $publishedSubmissionControllerObj->getSubData('porter', true);
    $allSbiUniqueArr = $publishedSubmissionControllerObj->getSubData('sbi', true);
    // $allTivUniqueArr = $publishedSubmissionControllerObj->getSubData('tiv', true);
    // $allTpUniqueArr = $publishedSubmissionControllerObj->getSubData('tp', true);
    $allMetaTrendsUniqueArr = $publishedSubmissionControllerObj->getSubData('meta_trends', true);
    // $allCompanySectorUniqueArr = $publishedSubmissionControllerObj->getSubData('company_sector', true);
?>

<div class="wrap">
    <div class='grid-container'>
        <div id='content' class='content'>
            <?php
                echo $publishedSubmissionControllerObj->renderFormData();
            ?>
        </div>
        <div id='side-panel'>
            <?php

            if ($allMinorUniqueArr != NULL) {
                if (count($allMinorUniqueArr) > 0) {
                    echo '<div>';
                    echo '<h1>Windesheim Minor</h1>';
                    echo '<ul>';
        
                    foreach ($allMinorUniqueArr as $minor) {
                        echo '<li><label for="minor"><input type="checkbox" name="minor" value=".' . esc_attr(str_replace(' ', '-', strtolower($minor))) . '"/>' . esc_html($minor) . '</label></li>';
                    }
        
                    echo '</ul>';
                    echo '</div>';
                }
            }

            if ($allProjectStageUniqueArr != NULL) {
                if (count($allProjectStageUniqueArr) > 0) {
                    echo '<div>';
                    echo '<h1>Project Stage</h1>';
                    echo '<ul>';
        
                    foreach ($allProjectStageUniqueArr as $projectStage) {
                        echo '<li><label for="project-stage"><input type="checkbox" name="project-stage" value=".' . esc_attr(str_replace(' ', '-', strtolower($projectStage))) . '"/>' . esc_html($projectStage) . '</label></li>';
                    }
        
                    echo '</ul>';
                    echo '</div>';
                }
            }

            if ($allPorterUniqueArr != NULL) {
                if (count($allPorterUniqueArr) > 0) {
                    echo '<div>';
                    echo '<h1>Michael Porter\'s Value Chain</h1>';
                    echo '<ul>';
        
                    foreach ($allPorterUniqueArr as $porter) {
                        echo '<li><label for="porter"><input type="checkbox" name="porter" value=".' . esc_attr(str_replace(' ', '-', strtolower($porter))) . '"/>' . esc_html($porter) . '</label></li>';
                    }
        
                    echo '</ul>';
                    echo '</div>';
                }
            }

            if ($allSbiUniqueArr != NULL) {
                if (count($allSbiUniqueArr) > 0) {
                    echo '<div>';
                    echo '<h1>SBI-code</h1>';
                    echo '<ul>';
        
                    foreach ($allSbiUniqueArr as $sbi) {
                        echo '<li><label for="sbi"><input type="checkbox" name="sbi" value=".' . esc_attr(str_replace(' ', '-', strtolower($sbi))) . '"/>' . esc_html($sbi) . '</label></li>';
                    }
        
                    echo '</ul>';
                    echo '</div>';
                }
            }

            // if ($allTivUniqueArr != NULL) {
            //     if (count($allTivUniqueArr) > 0) {
            //         echo '<div>';
            //         echo '<h1>Technological Innovations Applied</h1>';
            //         echo '<ul>';
        
            //         foreach ($allTivUniqueArr as $tiv) {
            //             echo '<li><label for="tiv"><input type="checkbox" name="tiv"/>' . esc_html($tiv) . '</label></li>';
            //         }
        
            //         echo '</ul>';
            //         echo '</div>';
            //     }
            // }

            // if ($allTpUniqueArr != NULL) {
            //     if (count($allTpUniqueArr) > 0) {
            //         echo '<div>';
            //         echo '<h1>Technology Provider(s)</h1>';
            //         echo '<ul>';
        
            //         foreach ($allTpUniqueArr as $tp) {
            //             echo '<li><label for="tp"><input type="checkbox" name="tp"/>' . esc_html($tp) . '</label></li>';
            //         }
        
            //         echo '</ul>';
            //         echo '</div>';
            //     }
            // }

            if ($allMetaTrendsUniqueArr != NULL) {
                if (count($allMetaTrendsUniqueArr) > 0) {
                    echo '<div>';
                    echo '<h1>Meta-trends(s)</h1>';
                    echo '<ul>';
        
                    foreach ($allMetaTrendsUniqueArr as $metaTrends) {
                        echo '<li><label for="meta-trends"><input type="checkbox" name="meta-trends" value=".' . esc_attr(str_replace(' ', '-', strtolower($metaTrends))) . '"/>' . esc_html($metaTrends) . '</label></li>';
                    }
        
                    echo '</ul>';
                    echo '</div>';
                }
            }

            // if ($allCompanySectorUniqueArr != NULL) {
            //     if (count($allCompanySectorUniqueArr) > 0) {
            //         echo '<div>';
            //         echo '<h1>Company Sector</h1>';
            //         echo '<ul>';
        
            //         foreach ($allCompanySectorUniqueArr as $companySector) {
            //             echo '<li><label for="company-sector"><input type="checkbox" name="company-sector"/>' . esc_html($companySector) . '</label></li>';
            //         }
        
            //         echo '</ul>';
            //         echo '</div>';
            //     }
            // }

            if ($allMinorUniqueArr == NULL && $allProjectStageUniqueArr == NULL && 
            $allPorterUniqueArr == NULL && $allSbiUniqueArr == NULL && $allMetaTrendsUniqueArr == NULL) {
                echo '<div>No filter data available.</div>';
            }

            ?>
        </div>
        <div id='pagination'>
            <div id='pagination-bttns'>
                <button>1</button>
                <button>2</button>
                <button>3</button>
            </div>
        </div>
    </div>
</div>