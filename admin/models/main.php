<?php

class Main {

    private $nfObj;

    public function __construct() {
        if (!function_exists('Ninja_Forms')) { return; };
        $this->nfObj = Ninja_Forms();
    }

    public function getAllFormData() {
        if (!function_exists('Ninja_Forms')) { return; };
        return $this->nfObj->form()->get_forms();
    }
}