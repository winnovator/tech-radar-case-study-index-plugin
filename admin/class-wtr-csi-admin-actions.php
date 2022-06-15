<?php
if (!defined('ABSPATH')) {
    wp_die();
}

require_once WTR_CSI_PLUGIN_PATH . '/includes/class-wtr-csi-config.php';

/**
 * Wtr_Csi_Admin_Actions
 */
class Wtr_Csi_Admin_Actions {    
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
        if (!function_exists('Ninja_Forms')) { wp_redirect('www.google.nl'); }
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->nf = Ninja_Forms();
    }
    
    /**
     * get_all_nf_subs
     *
     * @return array
     */
    public function get_all_nf_subs() {
        $subs = $this->nf->form(Wtr_Csi_Config::$form_id)->get_subs();
        if (!$subs) { return false; }
        return $subs;
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
     * get_all_wp_csi_data
     *
     * @return array
     */
    public function get_all_wp_csi_data() {
        $db_conn = $this->wpdb;
        $stmt = "SELECT * FROM {$db_conn->prefix}csi ORDER BY seq_num DESC";
        $results = $db_conn->get_results($stmt);
        if (!$results) { return false; }
        return $results;
    }
    
    /**
     * get_wp_csi_data
     *
     * @param  mixed $sub_id
     * @return array
     */
    public function get_wp_csi_data($sub_id) {
        if (!$sub_id) { return false; }
        $db_conn = $this->wpdb;
        $prepStmt = $db_conn->prepare("SELECT * FROM {$db_conn->prefix}csi WHERE seq_num = %d", array($sub_id));
        $results = $db_conn->get_results($prepStmt);
        if (!$results) { return false; }
        return $results;
    }
  
    /**
     * get_all_wp_csi_ids
     *
     * @return array
     */
    private function get_all_wp_csi_ids() {
        $arr = array();
        $db_conn = $this->wpdb;
        $stmt = "SELECT seq_num FROM {$db_conn->prefix}csi";
        $results = $db_conn->get_results($stmt);

        if (!$results) { return false; }

        foreach ($results as $element) {
            array_push($arr, (int)$element->seq_num);
        }

        return $arr;
    }
    
    /**
     * get_all_nf_sub_ids
     *
     * @return array
     */
    private function get_all_nf_sub_ids() {
        $arr = array();
        $subs = $this->get_all_nf_subs();
        if (!$subs) { return false; }

        foreach ($subs as $element) {
            array_push($arr, (int)$element->get_extra_value('_seq_num'));
        }
        
        return $arr;
    }
    
    /**
     * update_wp_csi_table
     *
     * @return void
     */
    public function update_wp_csi_table() {
        $db_conn = $this->wpdb;
        $sub_ids = $this->get_all_nf_sub_ids();
        $wp_csi_ids = $this->get_all_wp_csi_ids();

        if (!$sub_ids) { $sub_ids = array(); }
        if (!$wp_csi_ids) { $wp_csi_ids = array(); }

        foreach ($sub_ids as $element) {
            if (in_array($element, $wp_csi_ids)) {
                continue;
            }
            
            $prepared_stmt = $db_conn->prepare("INSERT IGNORE INTO {$db_conn->prefix}csi (form_id, seq_num, published, new) VALUES(%d, %d, %d, %d)", array(Wtr_Csi_Config::$form_id, $element, 0, 1));
            $db_conn->query($prepared_stmt);
        }

        foreach ($wp_csi_ids as $element) {
            if (in_array($element, $sub_ids)) {
                continue;
            }
            
            $db_conn->delete("{$db_conn->prefix}csi", array('seq_num' => $element));
        } 
    }
    
    /**
     * publish_sub
     *
     * @param  mixed $sub_id
     * @return void
     */
    public function publish_sub($sub_id) {
        if (!$sub_id) { return false; }
        $db_conn = $this->wpdb;
        $db_conn->update("{$db_conn->prefix}csi", array('published' => 1, 'new' => 0), array('seq_num' => $sub_id));
    }
    
    /**
     * depublish_sub
     *
     * @param  mixed $sub_id
     * @return void
     */
    public function depublish_sub($sub_id) {
        if (!$sub_id) { return false; }
        $db_conn = $this->wpdb;
        $db_conn->update("{$db_conn->prefix}csi", array('published' => 0), array('seq_num' => $sub_id));
    }
    
    /**
     * delete_sub
     *
     * @param  mixed $sub_id
     * @return void
     */
    public function delete_sub($sub_id) {
        if (!$sub_id) { return false; }
        $sub_model = $this->get_nf_sub($sub_id);
        if (!$sub_model) { return false; }
        $sub_model->delete();
        $db_conn = $this->wpdb;
        $db_conn->delete("{$db_conn->prefix}csi", array('seq_num' => $sub_id));
    }
    
    // /**
    //  * get_all_sbi_data
    //  *
    //  * @return array
    //  */
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
            if (isset($element->id)) {
                if ($element->id == $code) {
                    return $element->title;
                }
            }
        }

        return 'Onbekend';
    }
    
    /**
     * prepare_datatable_data
     *
     * @return array
     */
    public function prepare_datatable_data() {
        $result = array();
        $subs = $this->get_all_nf_subs();
        $index = 0;
        if (!$subs) { return false; }

        foreach ($subs as $element) {
            $id = $element->get_extra_value('_seq_num');
            $wp_csi_data = $this->get_wp_csi_data($id);
            $url = get_admin_url() . 'admin.php?page=wtr-csi-admin-info&sub_id=' . $id;
            
            $child_arr = [
                'id' => $id,
                'project_name' => $element->get_field_value('project_name'),
                'project_owner' => $element->get_field_value('project_owner'),
                'status' => $wp_csi_data[0]->new == 1 ? 'Nieuw' : 'Bestaand',
                'published' => $wp_csi_data[0]->published == 1 ? 'Ja' : 'Nee',
                'link' => '<a href="' . esc_url($url) . '" target="_blank"><span class="dashicons dashicons-admin-page"></span></a>'
            ];

            array_push($result, $child_arr);
            $index++;
        }
        
        return array_reverse($result);
    }
    
    /**
     * prepare_info_data
     *
     * @param  mixed $sub_id
     * @return array
     */
    public function prepare_info_data($sub_id) {
        if (!$sub_id) { return false; }
        $sub = $this->get_nf_sub($sub_id);
        $wp_csi_data = $this->get_wp_csi_data($sub_id);
        if (!$sub || !$wp_csi_data) { return false; }

        $arr = array(
            array('key' => 'id', 'value' => $sub->get_extra_value('_seq_num'), 'label' => 'ID', 'type' => 'text'),
            array('key' => 'status', 'value' => $wp_csi_data[0]->new, 'label' => 'Status', 'type' => 'text'),
            array('key' => 'published', 'value' => $wp_csi_data[0]->published, 'label' => 'Gepubliceerd', 'type' => 'text'),
            array('key' => 'project_name', 'value' => $sub->get_field_value('project_name'), 'label' => 'Projectnaam', 'type' => 'text'),
            array('key' => 'project_owner', 'value' => $sub->get_field_value('project_owner'), 'label' => 'Projecteigenaar', 'type' => 'text'),
            array('key' => 'project_owner_email', 'value' => $sub->get_field_value('project_owner_email'), 'label' => 'Email', 'type' => 'text'),
            array('key' => 'project_stage', 'value' => $sub->get_field_value('project_stage'), 'label' => 'Projectfase', 'type' => 'text'),
            array('key' => 'minor', 'value' => $sub->get_field_value('minor'), 'label' => 'Windesheim Minor', 'type' => 'text'),
            array('key' => 'value_chain', 'value' => $sub->get_field_value('value_chain'), 'label' => 'Value Chain (Michael Porter)', 'type' => 'check'),
            array('key' => 'sbi', 'value' => $sub->get_field_value('sbi'), 'label' => 'Sector (SBI-code)', 'type' => 'text'),
            array('key' => 'tech_innovations', 'value' => $sub->get_field_value('tech_innovations'), 'label' => 'Technologie innovaties', 'type' => 'text'),
            array('key' => 'tech_providers', 'value' => $sub->get_field_value('tech_providers'), 'label' => 'Technologieleveranciers', 'type' => 'text'),
            array('key' => 'tech_trends', 'value' => $sub->get_field_value('tech_trends'), 'label' => 'Trends', 'type' => 'check'),
            array('key' => 'company_sector', 'value' => $sub->get_field_value('company_sector'), 'label' => 'Bedrijfssector', 'type' => 'text'),
            array('key' => 'sdg', 'value' => $sub->get_field_value('sdg'), 'label' => 'SDG\'s', 'type' => 'check'),
            array('key' => 'sdg_impact_positive', 'value' => $sub->get_field_value('sdg_impact_positive'), 'label' => 'SDG impact positief', 'type' => 'text'),
            array('key' => 'sdg_impact_negative', 'value' => $sub->get_field_value('sdg_impact_negative'), 'label' => 'SDG impact negatief', 'type' => 'text'),
            array('key' => 'project_context', 'value' => $sub->get_field_value('project_context'), 'label' => 'Achtegrond', 'type' => 'text'),
            array('key' => 'project_problem', 'value' => $sub->get_field_value('project_problem'), 'label' => 'Probleemstelling', 'type' => 'text'),
            array('key' => 'project_goal', 'value' => $sub->get_field_value('project_goal'), 'label' => 'Doelstelling', 'type' => 'text'),
            array('key' => 'case_study_link', 'value' => $sub->get_field_value('case_study_link'), 'label' => 'Link', 'type' => 'link'),
            array('key' => 'case_study_video_link', 'value' => $sub->get_field_value('case_study_video_link'), 'label' => 'Videolink', 'type' => 'link'),
            array('key' => 'case_study_image', 'value' => $sub->get_field_value('case_study_image'), 'label' => 'Afbeelding', 'type' => 'img')
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
        $parsed_url = strpos($url, 'http') !== 0 ? 'http://' . $url : $url;

        if (filter_var($parsed_url, FILTER_VALIDATE_URL)) {
            return $parsed_url;
        }

        return false;
    }
}