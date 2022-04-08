<?php
if (!defined('ABSPATH')) {
    wp_die();
}

class DB {
    private $db;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }

    public function open() {
        return $this->db;
    }

    public function __destruct() {
        $this->db->close();
    }
}