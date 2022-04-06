<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');
require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/csi-settings.php');

class AdminCaseStudyIndexInfo {

    private $nfObj;
    private $dbObj;

    public function __construct() {
        if (!function_exists('Ninja_Forms')) { return; }
        
        //Ninja_Forms() error is a false positive. It works.
        $this->nfObj = Ninja_Forms();
        $this->dbObj = new DB();
    }

    public function getAllWpCsiData($subID) {
        $dbConn = $this->dbObj->open();

        $prepStmt = $dbConn->prepare("SELECT * FROM {$dbConn->prefix}csi WHERE seq_num = %d", [$subID]);
        $results = $dbConn->get_results($prepStmt);

        if (!empty($results)) {
            return $results;
        }

        return [];
    }

    public function getSubBySubID($subID) {
        $nfSubArr = $this->nfObj->form(CaseStudyIndexSettings::$formID)->get_subs();

        foreach ($nfSubArr as $nfSubElement) {
            if ($nfSubElement->get_extra_value('_seq_num') == $subID) {
                return $nfSubElement;
            }
        }
    }

    public function publishSub($subID) {
        $dbConn = $this->dbObj->open();
        $dbConn->update("{$dbConn->prefix}csi", ['published' => 1, 'new' => 0], ['seq_num' => $subID]);
    }

    public function depublishSub($subID) {
        $dbConn = $this->dbObj->open();
        $dbConn->update("{$dbConn->prefix}csi", ['published' => 0], ['seq_num' => $subID]);
    }

    public function denySub($subID) {
        $dbConn = $this->dbObj->open();
        $dbConn->delete("{$dbConn->prefix}csi", ['seq_num' => $subID]);
        $subModel = $this->getSubBySubID($subID);
        $subModel->delete();
    }
}