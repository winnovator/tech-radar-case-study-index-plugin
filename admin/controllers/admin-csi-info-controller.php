<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "models/admin-csi-info.php");

class AdminCaseStudyIndexInfoController extends AdminCaseStudyIndexInfo {
    
    protected $subID;
    protected $nfSubData;
    protected $wpCsiData;
    
    public function __construct() {
        parent::__construct();
        $this->subID = isset($_GET['sub_id']) ? $_GET['sub_id'] : NULL;
        $this->nfSubData = $this->getSubBySubID($this->subID);
        $this->wpCsiData = $this->getAllWpCsiData($this->subID);
    }

    protected function executePublishSub($id) {
        return $this->publishSub($id);
    }

    protected function executeDepublishSub($id) {
        return $this->depublishSub($id);
    }

    protected function executeDeleteSub($id) {
        return $this->deleteSub($id);
    }
}