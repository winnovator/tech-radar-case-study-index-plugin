<?php
require_once(plugin_dir_path(__DIR__) . "models/main.php");

class MainController extends Main {
    
    private $formID;
    private $nfSubData;
    private $convertedSubDataArr;

    public function __construct() {
        $this->formID = 7;
        $this->nfSubData = $this->getSubsFromSubModelByFormID($this->formID);
        $this->convertedSubDataArr = $this->convertSubmissionArray($this->nfSubData);
    }

    public function renderFormFields() {
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Project Name</th>';
        echo '<th>Windesheim Minor</th>';
        echo '<th>Project Stage</th>';
        echo '<th>Michael Porter\'s Value Chain</th>';
        echo '<th>SBI-code</th>';
        echo '<th>Technological Innovations Applied</th>';
        echo '<th>Technology Provider(s)</th>';
        echo '<th>Meta-trends</th>';
        echo '<th>Company Sector</th>';
        echo '<th>Case Study URL</th>';
        echo '<th>Published</th>';
        echo '</tr>';
    }

    private function convertSubmissionArray($arr) {
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

        return array_reverse($parentArr);
    }

    public function renderFormData() {
        $allSeqIdArr = $this->getAllPublishedSubmissionByFormID($this->formID, true);

        foreach ($this->convertedSubDataArr as $convertedSubArr) {
            echo '<tr>';
            
            foreach ($convertedSubArr as $key => $element) {
                if ($key == 'porter' || $key == 'meta_trends') {
                    echo '<td>'. implode(', ', $element) . '</td>';
                }
                else if ($key == 'case_study_url') {
                    echo '<td><a href="' . esc_url('http://'. $element) . '" target="_blank"> ' . esc_html($element) . ' </a></td>';
                }
                else {
                    echo "<td>". $element . "</td>";
                }
            }
            
            echo '<td>';
            echo '<input type="checkbox" name="sub_seq_id_values[]" value="' . esc_attr($convertedSubArr['id']) . '" ';

            if (count($allSeqIdArr) > 0) {
                echo in_array($convertedSubArr['id'], $allSeqIdArr) ? esc_html('checked ') : '';
            }

            echo '></td>';
            echo '</tr>';
        }

    }

    public function renderSubmitButton() {
        if (count($this->convertedSubDataArr) > 0) {
            echo "<div id='submit-wrap'><input id='submit-button' class='button action' type='submit' value='Save'></div>";
        }
    }

    public function getNonce() {
        return wp_create_nonce('publish_data_nonce');
    }

    public function savePublishedSubmissions($formID, $seqIdArr) {
        $allSeqIdArr = $this->getAllPublishedSubmissionByFormID($formID, true);

        if (count($seqIdArr) > 0) {
            foreach ($seqIdArr as $seqIdVal) {
                if ($this->checkForDuplicate($formID, $seqIdVal) > 0) {
                    continue;
                }
    
                $this->addSubmissionToPublished($formID, $seqIdVal);
            }
        }

        if (count($seqIdArr) > 0) {
            foreach ($allSeqIdArr as $seqIdVal) {
                if (in_array($seqIdVal, $seqIdArr)) {
                    continue;
                }
                
                $this->deleteSubmissionFromPublished($formID, $seqIdVal);
            }
        }

        if (count($seqIdArr) == 0) {
            $this->deleteAllPublishedSubmissionByFormID($formID);
        }
    }

}

