<?php

class PublishedSubmission {

    private $dbObj;
    private $nfObj;

    private function setNfObj() {
        if (!function_exists('Ninja_Forms')) { return; };
        $this->nfObj = Ninja_Forms();
    }

    private function setDbObj() {
        $db = new DB();
        $this->dbObj = $db->conn();
    }

    public function getFieldsFromFieldModelByFormID($formID) {
        if (!function_exists('Ninja_Forms')) { return; };
        $this->setNfObj();
        return $this->nfObj->form($formID)->get_fields();
    }

    public function getSubsFromSubModelByFormID($formID) {
        if (!function_exists('Ninja_Forms')) { return; };

        $arr = [];
        $seqIDs = $this->getAllPublishedSubmissionByFormID($formID, true);

        $this->setNfObj();
        $results = $this->nfObj->form($formID)->get_subs();

        foreach ($results as $result) {
            if (in_array($result->get_field_values()['_seq_num'], $seqIDs)) {
                array_push($arr, $result);
            }
        }

        return $arr;
    }

    private function getAllPublishedSubmissionByFormID($formID, $plainArr = false) {
        $arr = [];

        $this->setDbObj();
        $preparedStmt = $this->dbObj->prepare("SELECT seq_id FROM {$this->dbObj->prefix}qt_published WHERE form_id = %d", [(int)$formID]);
        $results = $this->dbObj->get_results($preparedStmt);

        if ($plainArr) {
            foreach ($results as $result) {
                array_push($arr, $result->seq_id);
            }
    
            return $arr;
        }
        else {
            return $results;
        }
    }
}