<?php

class DB {
    
    private $db;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }

    public function conn() {
        return $this->db;
    }
}