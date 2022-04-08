<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require(plugin_dir_path(__DIR__) . "models/public-csi.php");

class PublicCaseStudyIndexController extends PublicCaseStudyIndex {

    private $formID;
    protected $nfSubData;
    protected $convertedSubDataArr;

    public function __construct() {
        parent::__construct();
        $this->formID = CaseStudyIndexSettings::$formID;
        $this->nfSubData = $this->getPublishedSubs();
        $this->convertedSubDataArr = $this->convertSubArr($this->nfSubData);
    }

    public function convertSubArr($arr){
        $parentArr = [];
        $rowCount = count($arr);

        if ($rowCount > 0 && !empty($arr)) {
            foreach ($arr as $element) {

                if (empty($element)) { return; }

                $childArr = [
                    'id' => $element->get_extra_value('_seq_num'),
                    'project_name' => $element->get_field_value('project_name'),
                    'minor' => $element->get_field_value('minor'),
                    'project_stage' => $element->get_field_value('project_stage'),
                    'porter' => $element->get_field_value('porter'),
                    'sbi' => $element->get_field_value('sbi'),
                    'tech_innovations' => $element->get_field_value('tech_innovations'),
                    'tech_providers' => $element->get_field_value('tech_providers'),
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
}