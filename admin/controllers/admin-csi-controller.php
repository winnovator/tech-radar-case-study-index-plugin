<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once(plugin_dir_path(__DIR__) . "models/admin-csi.php");

class AdminCaseStudyIndexController extends AdminCaseStudyIndex {
    
    protected $formID;
    private $nfSubData;
    protected $convertedSubDataArr;

    public function __construct() {
        parent::__construct();
        $this->formID = 7;
        $this->nfSubData = $this->getSubsByFormID($this->formID);
        $this->convertedSubDataArr = $this->convertSubmissionArray($this->nfSubData);
    }

    private function convertSubmissionArray($arr) {
        $parentArr = [];
        $rowCount = count($arr);

        if ($rowCount > 0 && $arr != NULL) {
            foreach ($arr as $element) {
                $childArr = [
                    'id' => $element->get_extra_value('_seq_num'),
                    'project_name' => $element->get_field_value('project_name'),
                    'minor' => $element->get_field_value('minor'),
                    'project_stage' => $element->get_field_value('project_stage'),
                    'porter' => $element->get_field_value('porter'),
                    'sbi' => $element->get_field_value('sbi'),
                    'tiv' => $element->get_field_value('tiv'),
                    'tp' => $element->get_field_value('tp'),
                    'meta_trends' => $element->get_field_value('meta_trends'),
                    'company_sector' => $element->get_field_value('company_sector'),
                    'case_study_url' => $element->get_field_value('case_study_url')
                ];
    
                array_push($parentArr, $childArr);
            }
    
            return array_reverse($parentArr);
        }

        return $parentArr;
    }

    public function getNonce($name) {
        return wp_create_nonce($name);
    }

    public function savePublishedSubmissions($formID, $seqIdArr) {
        $allSeqIdArr = $this->getAllPublishedSubmissionByFormID($formID, true);
        $rowCountallSeqIdArr = count($allSeqIdArr);

        if ($rowCountallSeqIdArr > 0 && $allSeqIdArr != NULL) {
            foreach ($seqIdArr as $seqIdVal) {
                if ($this->checkForDuplicate($formID, $seqIdVal) > 0) {
                    continue;
                }
    
                $this->addSubmissionToPublished($formID, $seqIdVal);
            }

            foreach ($allSeqIdArr as $seqIdVal) {
                if (in_array($seqIdVal, $seqIdArr)) {
                    continue;
                }
                
                $this->deleteSubmissionFromPublished($formID, $seqIdVal);
            }
        }

        if ($rowCountallSeqIdArr == 0 && $allSeqIdArr != NULL) {
            $this->deleteAllPublishedSubmissionByFormID($formID);
        }
    }
}