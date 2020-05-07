<?php

class Session {

    private $idsession;
    private $name;
    private $numberallowed;
    private $event;
    private $startdate;
    private $enddate;

    public function getIdsession() {
        return $this->idsession;
    }

    public function getName() {
        return $this->name;
    }

    public function getNumberallowed() {
        return $this->numberallowed;
    }

    public function getEvent() {
        return $this->event;
    }

    public function getStartdate() {
        return $this->startdate;
    }

    public function getEnddate() {
        return $this->enddate;
    }

}
