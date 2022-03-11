<?php

class Main {

    private $nfObj;

    public function __construct() {
        $this->nfObj = Ninja_Forms();
    }

    public function getAllFormData() {
        return $this->nfObj->form()->get_forms();
    }
}