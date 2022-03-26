<?php
require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');

class Main extends DB {

    private $dbObj;
    private $nfObj;

    public function __construct() {
        if (!function_exists('Ninja_Forms')) { return; }
        $this->nfObj = Ninja_Forms();
        $this->dbObj = new DB();
    }

    public function getSubsByFormID($formID) {
        return $this->nfObj->form($formID)->get_subs();
    }

    public function checkForDuplicate($formID, $seqID) {
        $preparedStmt = $this->dbObj->prepare("SELECT COUNT(seq_id) FROM {$this->dbObj->prefix}csi_published WHERE form_id = %d AND seq_id = %d", [(int)$formID, (int)$seqID]);
        return $this->dbObj->get_var($preparedStmt);
    }

    public function addSubmissionToPublished($formID, $seqID) {
        $this->dbObj->insert("{$this->dbObj->prefix}csi_published", ['form_id' => (int)$formID, 'seq_id' => (int)$seqID]);
    }

    public function deleteSubmissionFromPublished($formID, $seqID) {
        $this->dbObj->delete("{$this->dbObj->prefix}csi_published", ['form_id' => (int)$formID, 'seq_id' => (int)$seqID]);
    }

    public function deleteAllPublishedSubmissionByFormID($formID) {
        $this->dbObj->delete("{$this->dbObj->prefix}csi_published", ['form_id' => (int)$formID]);
    }

    public function getAllPublishedSubmissionByFormID($formID, $plainArr = false) {
        $arr = [];

        $preparedStmt = $this->dbObj->prepare("SELECT seq_id FROM {$this->dbObj->prefix}csi_published WHERE form_id = %d", [(int)$formID]);
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