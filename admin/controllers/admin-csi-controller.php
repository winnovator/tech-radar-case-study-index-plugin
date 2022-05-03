<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "models/admin-csi.php");

class AdminCaseStudyIndexController extends AdminCaseStudyIndex {
    
    private $nfSubData;
    private $wpCsiData;
    protected $convertedSubDataArr;

    public function __construct() {
        parent::__construct();
        $this->nfSubData = $this->getAllNfSubData();
        $this->wpCsiData = $this->getAllWpCsiData();
        $this->updateWpCsiTable($this->nfSubData);
        $this->convertedSubDataArr = $this->convertSubmissionArray();
    }
    
    private function convertSubmissionArray() {
        $parentArr = [];
        $nfSubArr = $this->nfSubData;
        $wpCsiArr = $this->wpCsiData;
        $nfSubArrRowCount = count($nfSubArr);
        $wpCsiArrRowCount = count($wpCsiArr);
        $counter = 0;
        
        if ($nfSubArrRowCount > 0 && !empty($nfSubArr) && $wpCsiArrRowCount > 0 && !empty($wpCsiArr)) {
            foreach ($wpCsiArr as $wpCsiElement)  { 
                $nfSubDataRow = $this->getSubBySubID($wpCsiElement->seq_num);

                if (empty($nfSubDataRow)) { return; }

                $url = get_admin_url() . 'admin.php?page=admin-csi-info&sub_id=' . $wpCsiElement->seq_num;

                $childArrNfSubData = [
                    'id' => $nfSubDataRow->get_extra_value('_seq_num'),
                    'project_name' => $nfSubDataRow->get_field_value('project_name'),
                    'sbi' => $nfSubDataRow->get_field_value('sbi'),
                    'project_owner' => $nfSubDataRow->get_field_value('project_owner')
                ];

                $childArrwpPublishedArr = [
                    'status' => $wpCsiArr[$counter]->new == 1 ? 'New' : 'Existing',
                    'published' => $wpCsiArr[$counter]->published == 1 ? 'Yes' : 'No',
                    'link' => '<a href="' . esc_url($url) . '" target="_blank"><span class="dashicons dashicons-admin-page"></span></a>'
                ];
                
                array_push($parentArr, array_merge($childArrNfSubData, $childArrwpPublishedArr));
                $counter++;
            }

            return array_reverse($parentArr);
        }
        
        return $parentArr;
    }
}