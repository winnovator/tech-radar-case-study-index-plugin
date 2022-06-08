<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "models/admin-csi-info.php");
require_once(WP_PLUGIN_DIR . '/tech-radar-case-study-index-plugin/includes/csi-settings.php');

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

    protected function checkMaliciousUrl($url) {
        $body = [
            'client' => [
                'clientId' => 'Windesheim Technology Radar',
                'clientVersion' => '1.0'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                'platformTypes' => ['ALL_PLATFORMS'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url]
                ]
            ]
        ];

        $args = [
            'method' => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($body)
        ];

        return wp_remote_post('https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . CaseStudyIndexSettings::$googleSafeBrowsingApiKey, $args);
    }
}