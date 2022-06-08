<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/db.php');
require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/csi-settings.php');

class AdminCaseStudyIndexInfo {

    private $nfObj;
    private $dbObj;

    public function __construct() {
        if (!function_exists('Ninja_Forms')) { return; }
        
        //Ninja_Forms() error is a false positive. It works.
        $this->nfObj = Ninja_Forms();
        $this->dbObj = new DB();
    }

    protected function getAllWpCsiData($subID) {
        $dbConn = $this->dbObj->open();

        $prepStmt = $dbConn->prepare("SELECT * FROM {$dbConn->prefix}csi WHERE seq_num = %d", [$subID]);
        $results = $dbConn->get_results($prepStmt);

        if (!empty($results)) {
            return $results;
        }

        return [];
    }

    protected function getSubBySubID($subID) {
        $nfSubArr = $this->nfObj->form(CaseStudyIndexSettings::$formID)->get_subs();

        foreach ($nfSubArr as $nfSubElement) {
            if ($nfSubElement->get_extra_value('_seq_num') == $subID) {
                return $nfSubElement;
            }
        }
    }

    protected function publishSub($subID) {
        $dbConn = $this->dbObj->open();
        $dbConn->update("{$dbConn->prefix}csi", ['published' => 1, 'new' => 0], ['seq_num' => $subID]);
    }

    protected function depublishSub($subID) {
        $dbConn = $this->dbObj->open();
        $dbConn->update("{$dbConn->prefix}csi", ['published' => 0], ['seq_num' => $subID]);
    }

    protected function deleteSub($subID) {
        $dbConn = $this->dbObj->open();
        $dbConn->delete("{$dbConn->prefix}csi", ['seq_num' => $subID]);
        $subModel = $this->getSubBySubID($subID);
        $subModel->delete();
    }

    protected function getSingleSbiCode($code) {
        $sections = NULL;
        $result = NULL;

        if (file_exists(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/assets/shared/js/sbi/Sections.json')) {
            $sections = json_decode(file_get_contents(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/assets/shared/js/sbi/Sections.json'));
        }
        
        foreach ($sections as $section) {
            if (file_exists(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/assets/shared/js/sbi/' . $section->id . '.json')) {
                $codes = json_decode(file_get_contents(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/assets/shared/js/sbi/' . $section->id . '.json'));
                foreach ($codes as $element) {
                    if ($code == $element->id) {
                        $result = $element->title;
                    }
                }
            }
        }

        if ($result === NULL) {
            return "Onbekend";
        }

        return $result;
    }
}