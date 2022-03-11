<?php
require(plugin_dir_path(__DIR__) . 'includes/db.php');

class Submissions {

    private $nfObj;

    public function __construct() {
        $this->nfObj = Ninja_Forms();
    }

    public function getNfSubFormLabel($formID, $id) {
        $db = $this->dbObj;
        $preparedQuery = $db->prepare('SELECT label FROM wp_nf3_fields WHERE parent_id = %d AND id = %d', [$formID, $id]);
        $data = $db->get_results($preparedQuery);
        return $data;
    }

    public function getFieldsFromFieldModelByFormID($formID) {
        return $this->nfObj->form($formID)->get_fields();
    }

    public function getSubsFromSubModelByFormID($formID) {
        return $this->nfObj->form($formID)->get_subs();
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
}