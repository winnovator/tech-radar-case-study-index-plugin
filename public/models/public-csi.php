<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');

class PublicCaseStudyIndex
{

    private $dbObj;
    private $nfObj;

    public function __construct() {
        if (!function_exists('Ninja_Forms')) { return; }
        //Ninja_Forms() error a false positive. It works.
        $this->nfObj = Ninja_Forms();
        $this->dbObj = new DB();
    }

    public function getSubsByFormID($formID) {
        $arr = [];
        $results = $this->nfObj->form($formID)->get_subs();
        $seqIDs = $this->getAllPublishedSubsByFormID($formID, true);
        $rowCountResults = count($results);
        $rowCountSeqIDs = count($seqIDs);

        if ($rowCountResults > 0 && $results != NULL && $rowCountSeqIDs > 0 && $seqIDs != NULL) {
            foreach ($results as $result) {
                if (in_array($result->get_extra_value('_seq_num'), $seqIDs)) {
                    array_push($arr, $result);
                }
            }
        }

        return $arr;
    }

    private function getAllPublishedSubsByFormID($formID, $plainArr = false) {
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
            } else {
                return $results;
            }
        }

        return $arr;
    }
}
