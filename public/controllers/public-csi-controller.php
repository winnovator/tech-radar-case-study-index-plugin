<?php
require(plugin_dir_path(__DIR__) . "models/published-submission.php");

class PublishedSubmissionController extends PublishedSubmission {

    private $formID;
    private $nfSubData;
    public $convertedSubDataArr;

    public function __construct() {
        $this->formID = 2;
        $this->nfSubData = $this->getSubsFromSubModelByFormID($this->formID);
        $this->convertedSubDataArr = $this->convertSubmissionArray($this->nfSubData);
    }
    
    public function renderFormFields() {
        $output = '';

        $output .= '<tr>';
        $output .= '<th>ID</th>';
        $output .= '<th>Project Name</th>';
        $output .= '<th>Windesheim Minor</th>';
        $output .= '<th>Project Stage</th>';
        $output .= '<th>Michael Porter\'s Value Chain</th>';
        $output .= '<th>SBI-code</th>';
        $output .= '<th>Technological Innovations Applied</th>';
        $output .= '<th>Technology Provider(s)</th>';
        $output .= '<th>Meta-trends</th>';
        $output .= '<th>Company Sector</th>';
        $output .= '</tr>';

        return $output;
    }

    public function convertSubmissionArray($arr) {
        $parentArr = [];

        foreach ($arr as $element) {
            $childArr = [
                'id' => $element->get_extra_value('_seq_num'),
                'project_name' => $element->get_field_value('project_name'),
                'minor' => $element->get_field_value('minor'),
                'project_stage' => $element->get_field_value('project_stage'),
                'porter' => $element->get_field_value('porter'),
                'sbi' => $element->get_field_value('sbi'),
                'tiv' => $element->get_field_value('tiv'),
                'tp' => $element->get_field_value('tp'),
                'meta_trends' => $element->get_field_value('meta_trends'),
                'company_sector' => $element->get_field_value('company_sector'),
                'case_study_url' => $element->get_field_value('case_study_url')
            ];

            for ($i = 0 ; $i < 5 ; $i++) {
                array_push($parentArr, $childArr);
            }
        }
        
        if (count($parentArr) > 0) {
            return array_reverse($parentArr);
        }
    }

    // public function renderFormData() {
    //     $output = '';

    //     if (count($this->convertedSubDataArr) > 0) {
    //         foreach ($this->convertedSubDataArr as $convertedSubArr) {
    //             $classString = $this->createClassString($convertedSubArr);
    
    //             for ($test = 0 ; $test < 10 ; $test++) {
    //                 $output .= '<div class="element-container element-item ' . $classString . '">';
    //                 $output .= '<h1> <a href="'. esc_url($convertedSubArr['case_study_url']) . '" target="_blank">' . esc_html($convertedSubArr['project_name']) . ' - ' . esc_html($convertedSubArr['tp']) . ' </a></h1>';
    //                 $output .= '<p>' . esc_html($convertedSubArr['minor']) . '</p>';
    //                 $output .= '<p>' . esc_html($convertedSubArr['project_stage']) . '</p>';
    //                 $output .= '<p>' . esc_html(implode(', ', $convertedSubArr['porter'])) . '</p>';
    //                 $output .= '<p>' . esc_html($convertedSubArr['sbi']) . '</p>';
    //                 $output .= '<p>' . esc_html(implode(', ', $convertedSubArr['meta_trends'])) . '</p>';
    //                 $output .= '</div>';
    //             }
    //         }
    //     }
    //     else {
    //         $output .= '<div id="element-container" class="element-item">No data available.</div>';
    //     }

    //     return $output;
    // }

    public function renderSidePanelData() {
        $allMinorUniqueArr = $this->getSubData('minor', true);
        $allProjectStageUniqueArr = $this->getSubData('project_stage', true);
        $allPorterUniqueArr = $this->getSubData('porter', true);
        $allSbiUniqueArr = $this->getSubData('sbi', true);
        $allMetaTrendsUniqueArr = $this->getSubData('meta_trends', true);

        if ($allMinorUniqueArr != NULL) {
            if (count($allMinorUniqueArr) > 0) {
                echo '<div>';
                echo '<h1>Windesheim Minor</h1>';
                echo '<ul>';
    
                foreach ($allMinorUniqueArr as $minor) {
                    echo '<li><label for="minor"><input type="checkbox" name="minor" value="' . esc_attr($minor) . '"/>' . esc_html($minor) . '</label></li>';
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
                    echo '<li><label for="project-stage"><input type="checkbox" name="project-stage" value="' . esc_attr($projectStage) . '"/>' . esc_html($projectStage) . '</label></li>';
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
                    echo '<li><label for="porter"><input type="checkbox" name="porter" value="' . esc_attr($porter) . '"/>' . esc_html($porter) . '</label></li>';
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
                    echo '<li><label for="sbi"><input type="checkbox" name="sbi" value="' . esc_attr($sbi) . '"/>' . esc_html($sbi) . '</label></li>';
                }
    
                echo '</ul>';
                echo '</div>';
            }
        }

        if ($allMetaTrendsUniqueArr != NULL) {
            if (count($allMetaTrendsUniqueArr) > 0) {
                echo '<div>';
                echo '<h1>Meta-trends(s)</h1>';
                echo '<ul>';
    
                foreach ($allMetaTrendsUniqueArr as $metaTrends) {
                    echo '<li><label for="meta-trends"><input type="checkbox" name="meta-trends" value="' . esc_attr($metaTrends) . '"/>' . esc_html($metaTrends) . '</label></li>';
                }
    
                echo '</ul>';
                echo '</div>';
            }
        }

        if ($allMinorUniqueArr == NULL && $allProjectStageUniqueArr == NULL && 
        $allPorterUniqueArr == NULL && $allSbiUniqueArr == NULL && $allMetaTrendsUniqueArr == NULL) {
            echo '<div>No filter data available.</div>';
        }

        echo '<div id="case-index-submit-container"><button id="case-index-submit">Verzenden</button></div>';
    }

    public function getSubData($fieldKey, $unique = false) {
        $arr = [];
        $fieldArr = [];
        $returnArr = NULL;
        
        foreach ($this->nfSubData as $element) {
            if (is_array($element->get_field_value($fieldKey))) {
                foreach ($element->get_field_value($fieldKey) as $fieldArrElement) {
                    array_push($fieldArr, $fieldArrElement);
                }

                $returnArr = $fieldArr;
            }
            else {
                array_push($arr, $element->get_field_value($fieldKey));
                $returnArr = $arr;
            }
        }

        if ($unique) {
            if (is_array($returnArr)) {
                return array_reverse(array_unique($returnArr));
            }
        }

        if (is_array($returnArr) && count($returnArr) > 0) {
            return array_reverse($returnArr);
        }
    }
    
    private function createClassString($arr) {
        $string = '';

        foreach ($arr as $key => $element) {
            if ($key == 'minor' || $key == 'project_stage' || $key == 'porter' || $key == 'sbi' || $key == 'meta_trends') {
                if (is_array($element) || is_array($element)) {
                    foreach ($element as $subElement) {
                        $string .= str_replace(' ', '-', strtolower($subElement));
                        $string .= ' ';
                    }
                }
                else {
                    $string .= str_replace(' ', '-', strtolower($element));
                    $string .= ' ';
                }
            }
        }

        return rtrim($string, ' ');
    }
}
