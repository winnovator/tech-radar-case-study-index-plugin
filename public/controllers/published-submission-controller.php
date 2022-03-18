<?php
require(plugin_dir_path(__DIR__) . "models/published-submission.php");

class PublishedSubmissionController extends PublishedSubmission {

    private $formID;
    private $nfSubData;
    private $convertedSubDataArr;

    public function __construct() {
        $this->formID = 7;
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

            array_push($parentArr, $childArr);
        }

        return $parentArr;
    }

    public function renderFormData() {
        $output = '';

        foreach ($this->convertedSubDataArr as $convertedSubArr) {
            $classString = $this->createClassString($convertedSubArr);

            $output .= '<div id="element-container" class="element-item ' . $classString . '">';
            $output .= '<h1> <a href="'. esc_url($convertedSubArr['case_study_url']) . '" target="_blank">' . esc_html($convertedSubArr['project_name']) . ' - ' . esc_html($convertedSubArr['tp']) . ' </a></h1>';
            $output .= '<p>' . esc_html($convertedSubArr['minor']) . '</p>';
            $output .= '<p>' . esc_html($convertedSubArr['project_stage']) . '</p>';
            $output .= '<p>' . esc_html(implode(', ', $convertedSubArr['porter'])) . '</p>';
            $output .= '<p>' . esc_html($convertedSubArr['sbi']) . '</p>';
            $output .= '<p>' . esc_html(implode(', ', $convertedSubArr['meta_trends'])) . '</p>';
            $output .= '</div>';
        }

        return $output;
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
            return array_unique($returnArr);
        }

        return $returnArr;
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
