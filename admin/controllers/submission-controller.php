<?php
require(plugin_dir_path(__DIR__) . "models/submission.php");

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
            echo '<th>' . $nfSubField->get_setting('label') . '</th>';
        }

        echo '<th>Published</th>';
        echo '</tr>';
    }

    public function renderFormData() {
        foreach ($this->convertedSubDataArr as $convertedSubArr) {
            echo '<tr>';

            foreach ($convertedSubArr as $convertedSubValue) {
                echo "<td> $convertedSubValue </td>";
                
            }
            
            echo '<td> <input type="checkbox" name="sub_published" value="' . $convertedSubArr[0] . '"> </td>';
            echo '</tr>';
        }

    }

    public function renderSubmitButton() {
        if (count($this->convertedSubDataArr) > 0) {
            echo "<div id='submit-wrap'><input id='submit-button' class='button action' type='submit' value='Submit'></div>";
        }
    }
}