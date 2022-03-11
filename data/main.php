<?php
require(plugin_dir_path(__DIR__) . 'includes/db.php');

class Main {
    
    private $nfObj;

    public function __construct() {
        $dbObj = new DB();
        $this->dbObj = $dbObj->getDB();
        $this->nfObj = Ninja_Forms();
    }

    public function getAllFormData() {
        return $this->nfObj->form()->get_forms();
    }
}