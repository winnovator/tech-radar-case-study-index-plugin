<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once WTR_CSI_PLUGIN_PATH . '/includes/class-wtr-csi-config.php';

/**
 * Wtr_Csi_Public_Actions
 */
class Wtr_Csi_Public_Actions {    
    /**
     * wpdb
     *
     * @var mixed
     */
    private $wpdb;
        
    /**
     * nf
     *
     * @var mixed
     */
    private $nf;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        //Exit the function when Ninja Forms function doesn't exist
        if (!function_exists('Ninja_Forms')) { return; }
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->nf = Ninja_Forms();
    }
    
    /**
     * get_nf_sub
     *
     * @param  mixed $sub_id
     * @return object
     */
    public function get_nf_sub($sub_id) {
        if (!$sub_id) { return false; }
        $subs = $this->nf->form(Wtr_Csi_Config::$form_id)->get_subs();
        if (!$subs) { return false; }

        foreach ($subs as $element) {
            if ($element->get_extra_value('_seq_num') == $sub_id) {
                return $element;
            }
        }
    }
    
    /**
     * get_published_subs
     *
     * @return array
     */
    public function get_published_subs() {
        $db_conn = $this->wpdb;
        $prepared_stmt = $db_conn->prepare("SELECT seq_num FROM {$db_conn->prefix}csi WHERE published = %d", array(1));
        $results = $db_conn->get_results($prepared_stmt);
        if (!$results) { return false; }
        $arr = array();

        foreach ($results as $result) {
            array_push($arr, $this->get_nf_sub($result->seq_num));
        }

        if (!$results) { return false; }
        return $arr;
    }
    
    /**
     * get_all_sbi_data
     *
     * @return array
     */
    public function get_all_sbi_data() {
        $sections = json_decode(file_get_contents(WTR_CSI_PLUGIN_PATH . 'shared/js/sbi/Sections.json'));
        if (!$sections) { return false; }
        $dataArr = array();

        foreach ($sections as $section) {
            $codes = json_decode(file_get_contents(WTR_CSI_PLUGIN_PATH . 'shared/js/sbi/' . $section->id . '.json'));
            if (!$codes) { return false; }
            array_unshift($codes, array('id' => $section->id, 'title' => $section->title, 'parent_id' => isset($section->parent) ? $section->parent : NULL));
            array_push($dataArr, $codes);
        }     

        return array_merge(...$dataArr);
    }
    
    /**
     * get_sbi_code_title
     *
     * @param  mixed $code
     * @return string
     */
    public function get_sbi_code_title($code) {
        if (!$code) { return false; }
        $codes = $this->get_all_sbi_data();
        if (!$codes) { return false; }
        
        foreach ($codes as $element) {
            if ($element->id == $code) {
                return $element->title;
            }
        }

        return 'Onbekend';
    }
    
    /**
     * prepare_wtr_csi_public_main_data
     *
     * @return array
     */
    public function prepare_wtr_csi_public_main_data(){
        $published_subs = $this->get_published_subs();
        if (!$published_subs) { return false; }
        $parent_arr = array();

        foreach ($published_subs as $element) {

            $child_arr = array(
                'id' => $element->get_extra_value('_seq_num'),
                'project_name'=> $element->get_field_value('project_name'),
                'minor' => $element->get_field_value('minor'),
                'value_chain' => $element->get_field_value('value_chain'),
                'sbi' => $element->get_field_value('sbi'),
                'tech_trends' => $element->get_field_value('tech_trends'),
                'sdg' => $element->get_field_value('sdg'),
                'case_study_image' => $element->get_field_value('case_study_image')
            );

            array_push($parent_arr, $child_arr);
        }

        return array_reverse($parent_arr);
    }
        
    /**
     * prepare_wtr_csi_public_info_data
     *
     * @param  mixed $sub_id
     * @return array
     */
    public function prepare_wtr_csi_public_info_data($sub_id) {
        if (!$sub_id) { return false; }
        $sub = $this->get_nf_sub($sub_id);
        if (!$sub) { return false; }
        $link = $sub->get_field_value('case_study_link');
        $video_link = $sub->get_field_value('case_study_video_link');
        
        $arr = array(
            'project_name'=> $sub->get_field_value('project_name'),
            'project_owner' => $sub->get_field_value('project_owner'),
            'project_owner_email' => $sub->get_field_value('project_owner_email'),
            'project_stage' => $sub->get_field_value('project_stage'),
            'minor' => $sub->get_field_value('minor'),
            'value_chain' => $sub->get_field_value('value_chain'),
            'sbi' => $sub->get_field_value('sbi'),
            'tech_innovations' => $sub->get_field_value('tech_innovations'),
            'tech_providers' => $sub->get_field_value('tech_providers'),
            'tech_trends' => $sub->get_field_value('tech_trends'),
            'company_sector' => $sub->get_field_value('company_sector'),
            'sdg' => $sub->get_field_value('sdg'),
            'sdg_impact_positive' => $sub->get_field_value('sdg_impact_positive'),
            'sdg_impact_negative' => $sub->get_field_value('sdg_impact_negative'),
            'project_context' => $sub->get_field_value('project_context'),
            'project_problem' => $sub->get_field_value('project_problem'),
            'project_goal' => $sub->get_field_value('project_goal'),
            'case_study_link' => (!$this->check_url_unsecure($link) ? ($this->check_url_valid($link) ? $this->check_url_valid($link) : '')  : ''),
            'case_study_video_link' => (!$this->check_url_unsecure($video_link) ? ($this->check_url_valid($video_link) ? $this->check_url_valid($video_link) : '')  : ''),
            'case_study_image' => $sub->get_field_value('case_study_image')
        );

        return $arr;
    }
    
    /**
     * check_url_unsecure
     *
     * @param  mixed $url
     * @return bool
     */
    public function check_url_unsecure($url) {
        if (!$url) { return false; }
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

        $request = wp_remote_post('https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . Wtr_Csi_Config::$google_api_key, $args);
        $response_body = json_decode($request['body']);

        if (isset($response_body->matches)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * check_url_valid
     *
     * @param  mixed $url
     * @return bool
     */
    public function check_url_valid($url) {
        if (!$url) { return false; }
        $parsed_url = strpos($this->url, 'http') !== 0 ? 'http://' . $url : $url;

        if (filter_var($parsed_url, FILTER_VALIDATE_URL)) {
            return $parsed_url;
        }

        return false;
    }
}