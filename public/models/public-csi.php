<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/db.php');
require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/csi-settings.php');

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

    protected function getPublishedSubs() {
        $arr = [];

        $dbConn = $this->dbObj->open();
        $preparedStmt = $dbConn->prepare("SELECT seq_num FROM {$dbConn->prefix}csi WHERE published = %d", [1]);
        $results = $dbConn->get_results($preparedStmt);

        foreach ($results as $result) {
            array_push($arr, $this->getSubBySubID($result->seq_num));
        }

        return $arr;
    }

    protected function getSubBySubID($subID) {
        $nfSubArr = $this->nfObj->form(CaseStudyIndexSettings::$formID)->get_subs();

        foreach ($nfSubArr as $nfSubElement) {
            if ($nfSubElement->get_extra_value('_seq_num') == $subID) {
                return $nfSubElement;
            }
        }
    }

    protected function getAllSbiCodes() {
        $dataArr = [];
        $letterArr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
        
        foreach ($letterArr as $letter) {
            if (file_exists(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/assets/shared/js/sbi/' . $letter . '.json')) {
                $url = json_decode(file_get_contents(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/assets/shared/js/sbi/' . $letter . '.json'));
                array_push($dataArr, $url);
            }
        }

        return array_merge(...$dataArr);
    }
}
