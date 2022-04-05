<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "models/admin-csi-info.php");

class AdminCaseStudyIndexInfoController extends AdminCaseStudyIndexInfo {
    
    private $formID;
    protected $subID;
    protected $nfSubData;
    protected $wpCsiData;
    
    public function __construct() {
        parent::__construct();
        $this->formID = CaseStudyIndexSettings::$formID;
        $this->subID = isset($_GET['sub_id']) ? $_GET['sub_id'] : NULL;
        $this->nfSubData = $this->getSubBySubID($this->subID);
        $this->wpCsiData = $this->getAllWpCsiData($this->subID);
    }

    public function getNonce($name) {
        return wp_create_nonce($name);
    }
}