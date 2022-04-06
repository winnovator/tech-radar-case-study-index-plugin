<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');
require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/csi-settings.php');

class AdminCaseStudyIndex {

    private $nfObj;
    private $dbObj;

    public function __construct() {
        if (!function_exists('Ninja_Forms')) { return; }
        //Ninja_Forms() error is a false positive. It works.
        $this->nfObj = Ninja_Forms();
        $this->dbObj = new DB();
    }

    public function getAllNfSubData() {
        return $this->nfObj->form(CaseStudyIndexSettings::$formID)->get_subs();
    }

    public function getSubBySubID($subID) {
        $nfSubArr = $this->nfObj->form(CaseStudyIndexSettings::$formID)->get_subs();

        foreach ($nfSubArr as $nfSubElement) {
            if ($nfSubElement->get_extra_value('_seq_num') == $subID) {
                return $nfSubElement;
            }
        }
    }

    public function getAllWpCsiData() {
        $dbConn = $this->dbObj->open();

        $stmt = "SELECT * FROM {$dbConn->prefix}csi";
        $results = $dbConn->get_results($stmt);

        if (!empty($results)) {
            return $results;
        }

        return [];
    }

    private function getAllWpCsiIds() {
        $arr = [];
        $dbConn = $this->dbObj->open();

        $stmt = "SELECT seq_num FROM {$dbConn->prefix}csi";
        $results = $dbConn->get_results($stmt);

        if ($results != NULL) {
            foreach ($results as $result) {
                array_push($arr, (int)$result->seq_num);
            }
        }

        return $arr;
    }

    private function getAllNfSubIds() {
        $arr = [];
        $allNfSubArr = $this->getAllNfSubData();

        if ($allNfSubArr != NULL) {
            foreach ($allNfSubArr as $nfSubArrelement) {
                array_push($arr, (int)$nfSubArrelement->get_extra_value('_seq_num'));
            }
        }
        
        return $arr;
    }

    public function updateWpCsiTable() {
        $dbConn = $this->dbObj->open();
        $allWpCsiIdArr = $this->getAllWpCsiIds();
        $allNfSubIdArr = $this->getAllNfSubIds();

        foreach ($allNfSubIdArr as $nfSubIdElement) {
            if (in_array($nfSubIdElement, $allWpCsiIdArr)) {
                continue;
            }
            
            $dbConn->insert("{$dbConn->prefix}csi", ['form_id' => CaseStudyIndexSettings::$formID, 'seq_num' => $nfSubIdElement, 'published' => 0, 'new' => 1]);
        }

        foreach ($allWpCsiIdArr as $wpCsiIdElement) {
            if (in_array($wpCsiIdElement, $allNfSubIdArr)) {
                continue;
            }
            
            $dbConn->delete("{$dbConn->prefix}csi", ['seq_num' => $wpCsiIdElement]);
        } 
    }
}