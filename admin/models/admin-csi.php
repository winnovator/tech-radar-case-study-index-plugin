<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');

class AdminCaseStudyIndex {

    private $nfObj;
    private $dbObj;

    public function __construct() {
        if (!function_exists('Ninja_Forms')) { return; }
        //Ninja_Forms() error a false positive. It works.
        $this->nfObj = Ninja_Forms();
        $this->dbObj = new DB();
    }

    public function getSubsByFormID($formID) {
        return $this->nfObj->form($formID)->get_subs();
    }

    public function checkForDuplicate($formID, $seqID) {
        $dbConn = $this->dbObj->open();
        $preparedStmt = $dbConn->prepare("SELECT COUNT(seq_id) FROM {$dbConn->prefix}csi_published WHERE form_id = %d AND seq_id = %d", [(int)$formID, (int)$seqID]);
        $results = $dbConn->get_var($preparedStmt);
        
        if ($results != NULL) {
            return $results;
        }   
    }

    public function addSubmissionToPublished($formID, $seqID) {
        $dbConn = $this->dbObj->open();
        $dbConn->insert("{$dbConn->prefix}csi_published", ['form_id' => (int)$formID, 'seq_id' => (int)$seqID]);
    }

    public function deleteSubmissionFromPublished($formID, $seqID) {
        $dbConn = $this->dbObj->open();
        $dbConn->delete("{$dbConn->prefix}csi_published", ['form_id' => (int)$formID, 'seq_id' => (int)$seqID]);
    }

    public function deleteAllPublishedSubmissionByFormID($formID) {
        $dbConn = $this->dbObj->open();
        $dbConn->delete("{$dbConn->prefix}csi_published", ['form_id' => (int)$formID]);
    }

    public function getAllPublishedSubmissionByFormID($formID, $plainArr = false) {
        $arr = [];
        $dbConn = $this->dbObj->open();
        $preparedStmt = $dbConn->prepare("SELECT seq_id FROM {$dbConn->prefix}csi_published WHERE form_id = %d", [(int)$formID]);
        $results = $dbConn->get_results($preparedStmt);
        $rowCount = count($results);

        if ($rowCount > 0 && $results != NULL) {
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

        return $arr;
    }
}