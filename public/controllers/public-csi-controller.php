<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require(plugin_dir_path(__DIR__) . "models/public-csi.php");

class PublicCaseStudyIndexController extends PublicCaseStudyIndex {

    protected $nfSubData;
    protected $convertedSubDataArr;

    public function __construct() {
        parent::__construct();
        $this->nfSubData = $this->getPublishedSubs();
        $this->convertedSubDataArr = $this->convertSubArr($this->nfSubData);
    }

    private function convertSubArr($arr) {
        $parentArr = [];
        $rowCount = count($arr);

        if ($rowCount > 0 && !empty($arr)) {
            foreach ($arr as $element) {

                if (empty($element)) { return; }

                $childArr = [
                    'id' => $element->get_extra_value('_seq_num'),
                    'project_name' => $element->get_field_value('project_name'),
                    'minor' => $element->get_field_value('minor'),
                    'porter' => $element->get_field_value('porter'),
                    'sbi' => $element->get_field_value('sbi'),
                    'meta_trends' => $element->get_field_value('meta_trends'),
                    'sdg' => $element->get_field_value('sdg'),
                    'case_study_url' => $element->get_field_value('case_study_url'),
                    'case_study_image' => $element->get_field_value('case_study_image')
                ];

                array_push($parentArr, $childArr);
            }

            return array_reverse($parentArr);
        }

        return $parentArr;
    }

    protected function getSingleSub($subID) {
        $subArr = $this->getSubBySubID($subID);
        $arr = [];

        if (empty($subArr)) { return; }
        
        $arr = [
            'id' => $subArr->get_extra_value('_seq_num'),
            'project_name' => $subArr->get_field_value('project_name'),
            'project_owner' => $subArr->get_field_value('project_owner'),
            'project_owner_email' => $subArr->get_field_value('project_owner_email'),
            'minor' => $subArr->get_field_value('minor'),
            'porter' => $subArr->get_field_value('porter'),
            'sbi' => $subArr->get_field_value('sbi'),
            'tech_innovations' => $subArr->get_field_value('tech_innovations'),
            'tech_providers' => $subArr->get_field_value('tech_providers'),
            'meta_trends' => $subArr->get_field_value('meta_trends'),
            'company_sector' => $subArr->get_field_value('company_sector'),
            'sdg' => $subArr->get_field_value('sdg'),
            'project_context' => $subArr->get_field_value('project_context'),
            'project_problem' => $subArr->get_field_value('project_problem'),
            'project_goal' => $subArr->get_field_value('project_goal'),
            'case_study_url' => $subArr->get_field_value('case_study_url'),
            'case_study_video' => $subArr->get_field_value('case_study_video'),
            'case_study_image' => $subArr->get_field_value('case_study_image')
            ];

        return $arr;
    }
}