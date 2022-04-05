<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require(plugin_dir_path(__DIR__) . "models/public-csi.php");

class PublicCaseStudyIndexController extends PublicCaseStudyIndex
{

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

    public function getNfSubData($fieldKey, $model, $unique = false)
    {
        $arr = [];
        $fieldArr = [];
        $returnArr = NULL;
        $rowCount = count($model);

        if ($rowCount > 0 && $model != NULL) {
            foreach ($model as $element) {
                if (is_array($element->get_field_value($fieldKey))) {
                    foreach ($element->get_field_value($fieldKey) as $fieldArrElement) {
                        array_push($fieldArr, $fieldArrElement);
                    }

                    $returnArr = $fieldArr;
                } else {
                    array_push($arr, $element->get_field_value($fieldKey));
                    $returnArr = $arr;
                }
            }

            if ($unique) {
                if (is_array($returnArr)) {
                    return array_reverse(array_unique($returnArr));
                }
            }
        }

        return $arr;
    }
}