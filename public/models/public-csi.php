<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/db.php');
require_once(ABSPATH . 'wp-content/plugins/tech-radar-case-study-index-plugin/includes/csi-settings.php');

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

    public function getPublishedSubs() {
        $arr = [];

        $dbConn = $this->dbObj->open();
        $preparedStmt = $dbConn->prepare("SELECT seq_num FROM {$dbConn->prefix}csi WHERE published = %d", [1]);
        $results = $dbConn->get_results($preparedStmt);

        foreach ($results as $result) {
            array_push($arr, $this->getSubBySubID($result->seq_num));
        }

        return $arr;
    }
    

    public function getSubBySubID($subID) {
        $nfSubArr = $this->nfObj->form(CaseStudyIndexSettings::$formID)->get_subs();

        foreach ($nfSubArr as $nfSubElement) {
            if ($nfSubElement->get_extra_value('_seq_num') == $subID) {
                return $nfSubElement;
            }
        }
    }
}
