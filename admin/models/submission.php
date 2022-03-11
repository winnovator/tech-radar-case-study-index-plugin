<?php

class Submission {

    private $nfObj;

    private function setNfObj() {
        $this->nfObj = Ninja_Forms();
    }

    public function getFieldsFromFieldModelByFormID($formID) {
        $this->setNfObj();
        return $this->nfObj->form($formID)->get_fields();
    }

    public function getSubsFromSubModelByFormID($formID) {
        $this->setNfObj();
        return $this->nfObj->form($formID)->get_subs();
    }
}