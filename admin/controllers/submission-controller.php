<?php
require_once(plugin_dir_path(__DIR__) . "models/submission.php");

class SubmissionController extends Submission {
    
    private $formID;
    private $nfSubFields;
    private $nfSubData;
    private $convertedSubDataArr;

    public function __construct() {
        $this->formID = isset($_GET['form-id']) ? (int)$_GET['form-id'] : null;
        $this->nfSubFields = $this->getFieldsFromFieldModelByFormID($this->formID);
        $this->nfSubData = $this->getSubsFromSubModelByFormID($this->formID);
        $this->convertedSubDataArr = $this->convertSubArr($this->nfSubData);
    }

    private function removeDuplicateFieldValues($arr) {
        $newArr = [];

        foreach ($arr as $key => $value) {
            if (substr($key, 0, 7) == '_field_') {
                continue;
            }

            array_push($newArr, $value);
        }

        return $newArr;
    }

    public function convertSubArr($arr) {
        if (!function_exists('Ninja_Forms')) { return; };

        $parrentArr = [];

        foreach ($arr as $dataArr) {
            $fieldValues = $dataArr->get_field_values();
            $fieldValuesWithoutDuplicates = $this->removeDuplicateFieldValues($fieldValues);

            $idDataArr = array_reverse(array_slice($fieldValuesWithoutDuplicates, -2));
            $fieldDataArr = array_slice($fieldValuesWithoutDuplicates, 0, count($fieldValuesWithoutDuplicates) - 2);
            $newFieldValuesArr = array_merge($idDataArr, $fieldDataArr);

            array_push($parrentArr, $newFieldValuesArr);
        }

        return array_reverse($parrentArr);
    }

    public function renderFormFields() {
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Form ID</th>';

        foreach ($this->nfSubFields as $nfSubField) {
            if ($nfSubField->get_setting('key') == 'submit' || $nfSubField->get_setting('label') == 'Submit') { continue; }
            echo '<th>' . esc_html($nfSubField->get_setting('label')) . '</th>';
        }

        echo '<th>Published</th>';
        echo '</tr>';
    }

    public function renderFormData() {
        $allSeqIdArr = $this->getAllPublishedSubmissionByFormID($this->formID, true);
        
        foreach ($this->convertedSubDataArr as $convertedSubArr) {
            echo '<tr>';

            foreach ($convertedSubArr as $convertedSubValue) {
                echo "<td>". esc_html($convertedSubValue) . "</td>";
                
            }
            
            echo '<td>';
            echo '<input type="checkbox" name="sub_seq_id_values[]" value="' . esc_attr($convertedSubArr[0]) . '" ';

            if (count($allSeqIdArr) > 0) {
                echo in_array($convertedSubArr[0], $allSeqIdArr) ? 'checked ' : '';
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

