<?php

class AttendeeEvent {

    private $event;
    private $attendee;
    private $paid;

    public function getEvent() {
        return $this->event;
    }

    public function getAttendeee() {
        return $this->attendee;
    }

    public function getPaid() {
        return $this->paid;
    }

}

?>
