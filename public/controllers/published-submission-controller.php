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
                'company_sector' => $element->get_field_value('company_sector')
            ];

            array_push($parentArr, $childArr);
        }

        return $parentArr;
    }

    public function renderFormData() {
        $output = '';

        foreach ($this->convertedSubDataArr as $convertedSubArr) {
            $output .= '<tr>';
            
            foreach ($convertedSubArr as $key => $element) {
                if ($key == 'porter' || $key == 'meta_trends') {
                    $output .= "<td>". implode(', ', $element) . "</td>";
                }
                else {
                    $output .= "<td>". $element . "</td>";
                }
            }

            $output .= '</tr>';
        }

        return $output;
    }
}
