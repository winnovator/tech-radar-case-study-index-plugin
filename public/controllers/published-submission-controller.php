<?php
require(plugin_dir_path(__DIR__) . "models/published-submission.php");

class PublishedSubmissionController extends PublishedSubmission {
    
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
            $fieldDataArr = array_slice($fieldValuesWithoutDuplicates, 0, count($fieldValuesWithoutDuplicates) - 2);

            array_push($parrentArr, $fieldDataArr);
        }

        return array_reverse($parrentArr);
    }

    public function renderFormFields($formID) {
        $this->nfSubFields = $this->getFieldsFromFieldModelByFormID($formID);
        $output = '';

        if ($this->nfSubFields == NULL) { return; }
        
        $output .= '<tr>';
        foreach ($this->nfSubFields as $nfSubField) {
            if ($nfSubField->get_setting('key') == 'submit' || $nfSubField->get_setting('label') == 'Submit') { continue; }
            $output .= '<th>' . esc_html($nfSubField->get_setting('label')) . '</th>';
        }
        
        $output .= '</tr>';

        return $output;
    }

    public function renderFormData($formID) {
        $nfSubData = $this->getSubsFromSubModelByFormID($formID);
        $convertedSubDataArr = $this->convertSubArr($nfSubData);
        $output = '';

        if ($nfSubData == NULL) { return; }
        
        foreach ($convertedSubDataArr as $convertedSubArr) {
            $output .= '<tr>';

            foreach ($convertedSubArr as $convertedSubValue) {
                $output .= "<td>". esc_html($convertedSubValue) . "</td>";
                
            }

            $output .= '</tr>';
        }

        return $output;
    }
}
