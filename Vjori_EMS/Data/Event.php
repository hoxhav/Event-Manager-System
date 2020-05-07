<?php

class Event {
    private $idevent;
    private $name;
    private $datestart;
    private $dateend;
    private $numberallowed;
    private $venue;
    
    public function getIdevent() {
        return $this->idevent;
    }

    public function getName() {
        return $this->name;
    }

    public function getDatestart() {
        return $this->datestart;
    }

    public function getDateend() {
        return $this->dateend;
    }

    public function getNumberallowed() {
        return $this->numberallowed;
    }

    public function getVenue() {
        return $this->venue;
    }


}

