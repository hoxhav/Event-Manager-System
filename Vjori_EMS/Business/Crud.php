<?php

/* Main class of all functions regarding database related */

class Crud {

    function __construct() {
        require_once('classes.php');
        $db = Db::getInstance();
        $this->dbh = $db->getConnection();
    }

    /*
     * Gets events that attendee is part or not depending on flag
     */

    function getEventObjects($_id, $flag) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT event.idevent, event.name, event.datestart, event.dateend, event.numberallowed, venue.name as 'venue' FROM event INNER JOIN venue ON venue.idvenue = event.venue WHERE event.idevent {$flag} IN (SELECT event FROM attendee_event INNER JOIN attendee ON attendee_event.attendee = attendee.idattendee WHERE attendee_event.attendee = :_id)");
            $stmt->bindParam(":_id", $_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Event");
            while ($events = $stmt->fetch()) {
                $data[] = $events;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /*
     * Gets all sessions based on event
     */

    function getSessionObjects($_id) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("select * from session where event = :_id");
            $stmt->bindParam(":_id", $_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Session");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /* Gets event based on id event */

    function getSpecificEvent($_id) {
        try {
            $stmt = $this->dbh->prepare("select * from event where idevent = :_id");
            $stmt->bindParam(":_id", $_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Event");
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /**
     * Gets sessions that an attendee is registered to
     */
    function getSessionObjectsForAttendee($event_id, $_id) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare('select * from session where event = :event_id and idsession IN (SELECT session FROM attendee_session INNER JOIN attendee ON attendee_session.attendee = attendee.idattendee WHERE attendee = :_id)');
            $stmt->bindParam(":event_id", $event_id, PDO::PARAM_STR);
            $stmt->bindParam(":_id", $_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Session");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /* Gets count of how many attendees are registered in an evenet */

    function getRegisteredAttendeePerEvent($_id) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT COUNT(attendee) as 'attendee' FROM attendee_event WHERE event = :_id LIMIT 1");
            $stmt->bindParam(":_id", $_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "AttendeeEvent");
            $attends = $stmt->fetch();
            return $attends->getAttendeee();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /* Gets count of how many attendees are registered in a session */

    function getRegisteredAttendeePerSession($_id) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT COUNT(attendee) as 'attendee' FROM attendee_session WHERE session = :_id LIMIT 1");
            $stmt->bindParam(":_id", $_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "AttendeeSession");
            $attends = $stmt->fetch();
            return $attends->getAttendee();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /* Gets a table of events
     */

    function getAllEvents($flag) {
        $data = $this->getEventObjects($_SESSION["id"], $flag);
        if (count($data) > 0) {
            $bigString = '<div class="d-flex justify-content-center"><table class="table table-responsive table-hover"><thead>';
            $bigString .= '<tr><th>Sessions<th>Name</th><th>Start Date</th><th>End Date</th><th>Allowed Number</th><th>Venue</th><th>Register</th></tr></thead><tbody>';
            $count = 1; //class="clickable" data-toggle="collapse" id="row' . $count . '" data-target=".row' . $count . '"
            foreach ($data as $row) {
                $bigString .= '<tr><td class="clickable" data-toggle="collapse" id="row' . $count . '" data-target=".row' . $count . '"><i class="fas fa-bars"></i></td>';
                $bigString .= "<td>{$row->getName()}</td><td>{$row->getDatestart()}</td><td>{$row->getDateend()}</td><td>{$this->getRegisteredAttendeePerEvent($row->getIdevent())}/{$row->getNumberallowed()}</td><td>{$row->getVenue()}</td><td><button class='btn btn-success' onclick='register({$row->getIdevent()})'  ><i class='fas fa-check'></i></button></td></tr>";
                $sessionResult = $this->getSessionObjects($row->getIdevent());

                if (count($sessionResult) > 0) {
                    foreach ($sessionResult as $sessions) {
                        $bigString .= '<tr class="collapse row' . $count . '">';
                        $bigString .= "<td>" . "-- Session " . "</td><td>{$sessions->getName()}</td><td>{$sessions->getStartdate()}</td><td>{$sessions->getEnddate()}</td><td>{$this->getRegisteredAttendeePerSession($sessions->getIdsession())}/{$sessions->getNumberallowed()}</td></tr>";
                    }
                }
                $count++;
            }
            $bigString .= "</tbody></table></div>";
        } else {
            $bigString = "<h2>No events found</h2>";
        }
        return $bigString;
    }

    /*
     * Gets events that user is registered to
     */

    function getMyEvents($flag) {
        $data = $this->getEventObjects($_SESSION["id"], $flag);
        if (count($data) > 0) {
            $bigString = '<div class="d-flex justify-content-center"><table class="table table-responsive table-hover"><thead>';
            $bigString .= '<tr><th>Sessions<th>Name</th><th>Start Date</th><th>End Date</th><th>Allowed Number</th><th>Venue</th><th>Unregister</th></tr></thead><tbody>';
            $count = 1; //class="clickable" data-toggle="collapse" id="row' . $count . '" data-target=".row' . $count . '"
            foreach ($data as $row) {
                $bigString .= '<tr><td class="clickable" data-toggle="collapse" id="row' . $count . '" data-target=".row' . $count . '"><i class="fas fa-bars"></i></td>';
                $bigString .= "<td>{$row->getName()}</td><td>{$row->getDatestart()}</td><td>{$row->getDateend()}</td><td>{$this->getRegisteredAttendeePerEvent($row->getIdevent())}/{$row->getNumberallowed()}</td><td>{$row->getVenue()}</td><td><button class='btn btn-danger' onclick='unregister({$_SESSION["id"]}, {$row->getIdevent()})'  ><i class='fas fa-trash'></i></button></td></tr>";
                $sessionResult = $this->getSessionObjectsForAttendee($row->getIdevent(), $_SESSION["id"]);

                if (count($sessionResult) > 0) {
                    foreach ($sessionResult as $sessions) {
                        $bigString .= '<tr class="collapse row' . $count . '">';
                        $bigString .= "<td>" . "-- Session " . "</td><td>{$sessions->getName()}</td><td>{$sessions->getStartdate()}</td><td>{$sessions->getEnddate()}</td><td>{$this->getRegisteredAttendeePerSession($sessions->getIdsession())}/{$sessions->getNumberallowed()}</td>";
                        $bigString .= "<td></td><td><button class='btn btn-danger' onclick = 'unregisterSession({$_SESSION["id"]},{$sessions->getIdsession()})' ><i class = 'fas fa-trash'></i></button></td></tr>";
                    }
                }
                $count++;
            }
            $bigString .= "</tbody></table></div>";
        } else {
            $bigString = "<h2>No events found</h2>";
        }
        return $bigString;
    }

    /*
     * Inserts attendee
     */

    function insert($_name, $_password, $_role) {
        try {
            $stmt = $this->dbh->prepare("insert into attendee (name,password, role)
		values (:name,:password,:role)");
            $stmt->execute(array(
                "name" => $_name,
                "password" => $_password,
                "role" => $_role
                    )
            );
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getAttendee($_name, $_password) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee where name = :_name and password = :_password");
            $stmt->bindParam(":_name", $_name, PDO::PARAM_STR);
            $stmt->bindParam(":_password", $_password, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getName($_name) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("select name from attendee where name = :_name");
            $stmt->bindParam(":_name", $_name, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function registerAttendeeToEvent($event_id) {
        try {
            $stmt = $this->dbh->prepare("insert into attendee_event (event,attendee, paid)
		values (:event_id,:attendee, 0)");
            $stmt->execute(array(
                "event_id" => $event_id,
                "attendee" => $_SESSION['id'],
                    )
            );

            $this->registerAttendeeToSession($event_id);
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function registerAttendeeToSession($event_id) {
        $sessionResult = $this->getSessionObjects($event_id);
        if (count($sessionResult) > 0) {
            foreach ($sessionResult as $sessions) {
                $stmt = $this->dbh->prepare("insert into attendee_session (session,attendee)
		values (:session,:attendee)");
                $stmt->execute(array(
                    "session" => $sessions->getIdsession(),
                    "attendee" => $_SESSION['id'],
                        )
                );
            }
        }
        try {

            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getRoleId($_name) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("select role from attendee where name = :_name LIMIT 1");
            $stmt->bindParam(":_name", $_name, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
            $attends = $stmt->fetch();
            return $attends->getRole();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getVenues() {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("select * from venue");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Venue");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getManagenedEvents($flag) {
        $data;
        if ($flag === "manager") {
            $data = $this->getEventList($_SESSION["id"]);
        }

        if ($flag === "admin") {
            $data = $this->getEventListForAdmin();
        }
        if (count($data) > 0) {
            $bigString = '<div class="d-flex justify-content-center"><table class="table table-responsive table-hover"><thead>';
            $bigString .= '<tr><th>Sessions<th>Name</th><th>Start Date</th><th>End Date</th><th>Allowed Number</th><th>Venue</th><th>Edit/View People/Delete</th></tr></thead><tbody>';
            $count = 1;
            foreach ($data as $row) {
                $bigString .= '<tr><td class="clickable" data-toggle="collapse" id="row' . $count . '" data-target=".row' . $count . '"><i class="fas fa-bars"></i></td>';
                $bigString .= "<td>{$row->getName()}</td><td>{$row->getDatestart()}</td><td>{$row->getDateend()}</td><td>{$this->getRegisteredAttendeePerEvent($row->getIdevent())}/{$row->getNumberallowed()}</td><td>{$row->getVenue()}</td><td><button class='btn btn-primary' onclick='editEvent({$row->getIdevent()})'  ><i class='fas fa-edit'></i></button><button class='btn btn-info' onclick='viewEventPeople({$row->getIdevent()})'  ><i class='fas fa-user-check'></i></button><button class='btn btn-danger' onclick='deleteEvent({$row->getIdevent()})'  ><i class='fas fa-trash'></i></button></td></tr>";
                $sessionResult = $this->getSessionObjects($row->getIdevent());

                if (count($sessionResult) > 0) {
                    foreach ($sessionResult as $sessions) {
                        $bigString .= '<tr class="collapse row' . $count . '">';
                        $bigString .= "<td>" . "-- Session " . "</td><td>{$sessions->getName()}</td><td>{$sessions->getStartdate()}</td><td>{$sessions->getEnddate()}</td><td>{$this->getRegisteredAttendeePerSession($sessions->getIdsession())}/{$sessions->getNumberallowed()}</td>";
                        $bigString .= "<td></td><td><button class='btn btn-primary' onclick='updateSession({$sessions->getIdsession()}, {$row->getIdevent()})'  ><i class='fas fa-edit'></i></button><button class='btn btn-info' onclick='viewSessionPeople({$sessions->getIdsession()})'  ><i class='fas fa-user-check'></i></button><button class='btn btn-danger' onclick='deleteSession({$sessions->getIdsession()})'  ><i class='fas fa-trash'></i></button></td></tr>";
                    }
                }
                $count++;
            }
            $bigString .= "</tbody></table></div>";
        } else {
            $bigString = "<h2>No events found</h2>";
        }
        return $bigString;
    }

    function getEventListForAdmin() {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT idevent, event.name, event.datestart, event.dateend, event.numberallowed, venue.name as 'venue' from event INNER JOIN venue ON event.venue = venue.idvenue");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Event");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getEventList($attendee_id) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT idevent, event.name, event.datestart, event.dateend, event.numberallowed, venue.name as 'venue' from event INNER JOIN venue ON event.venue = venue.idvenue INNER JOIN manager_event on event.idevent = manager_event.event INNER JOIN attendee on manager_event.manager = attendee.idattendee where attendee.idattendee = :attendee_id");
            $stmt->bindParam(":attendee_id", $attendee_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Event");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteAttendeeRegistrationEvent($attendee_id, $event_id) {
        $sessionResult = $this->getSessionObjectsForAttendee($event_id, $_SESSION["id"]);
        if (count($sessionResult) > 0) {
            foreach ($sessionResult as $sessions) {
                $this->deleteAttendeeRegistrationSession($attendee_id, $sessions->getIdsession());
            }
        }

        try {
            $stmt = $this->dbh->prepare("delete from attendee_event where attendee = :attendee_id and event = :event_id");
            $stmt->bindParam(":attendee_id", $attendee_id, PDO::PARAM_STR);
            $stmt->bindParam(":event_id", $event_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteAttendeeRegistrationSession($attendee_id, $session_id) {
        try {
            $stmt = $this->dbh->prepare("delete from attendee_session where attendee = :attendee_id and session = :session_id");
            $stmt->bindParam(":attendee_id", $attendee_id, PDO::PARAM_STR);
            $stmt->bindParam(":session_id", $session_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteEvent($event_id) {
        $this->_deleteSession($event_id);
        try {
            $stmt = $this->dbh->prepare("delete from event where idevent = :event_id");
            $stmt->bindParam(":event_id", $event_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function _deleteSession($event_id) {
        try {
            $stmt = $this->dbh->prepare("delete from session where event = :event_id");
            $stmt->bindParam(":event_id", $event_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteSession($session_id) {
        try {
            $stmt = $this->dbh->prepare("delete from session where idsession = :session_id");
            $stmt->bindParam(":session_id", $session_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteAttendee($attendee_id) {
        try {
            $stmt = $this->dbh->prepare("delete from attendee where idattendee = :attendee_id");
            $stmt->bindParam(":attendee_id", $attendee_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function updateEvent($id, $name, $datestart, $dateend, $numberallowed, $venue) {
        try {
            $stmt = $this->dbh->prepare("update event set name = :name, datestart = :datestart,dateend = :dateend ,numberallowed = :numberallowed, venue = :venue where idevent = :id");
            $stmt->execute(array(
                "id" => $id,
                "name" => $name,
                "datestart" => $datestart,
                "dateend" => $dateend,
                "numberallowed" => $numberallowed,
                "venue" => $venue
                    )
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function updateAttendee($id, $name, $password, $role) {
        try {
            $stmt = $this->dbh->prepare("update attendee set name = :name, password = :password,role = :role where idattendee = :id");
            $stmt->execute(array(
                "id" => $id,
                "name" => $name,
                "password" => $password,
                "role" => $role
                    )
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function updateSession($idsession, $name, $numberallowed, $event, $startdate, $enddate) {
        try {
            $stmt = $this->dbh->prepare("update session set name = :name, numberallowed = :numberallowed, event = :event, startdate = :startdate,enddate = :enddate where idsession = :idsession");
            $stmt->execute(array(
                "idsession" => $idsession,
                "name" => $name,
                "startdate" => $startdate,
                "enddate" => $enddate,
                "numberallowed" => $numberallowed,
                "event" => $event
                    )
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function addEvent($name, $datestart, $dateend, $numberallowed, $venue) {
        try {
            $stmt = $this->dbh->prepare("insert into event (name, datestart,dateend,numberallowed, venue)
		values (:name,:datestart,:dateend, :numberallowed, :venue)");
            $stmt->execute(array(
                "name" => $name,
                "datestart" => $datestart,
                "dateend" => $dateend,
                "numberallowed" => $numberallowed,
                "venue" => $venue
                    )
            );
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function addSession($name, $numberallowed, $event, $startdate, $enddate) {
        try {
            $stmt = $this->dbh->prepare("insert into session (name, numberallowed,event,startdate, enddate)
		values (:name,:numberallowed,:event, :startdate, :enddate)");
            $stmt->execute(array(
                "name" => $name,
                "numberallowed" => $numberallowed,
                "event" => $event,
                "startdate" => $startdate,
                "enddate" => $enddate
                    )
            );
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function addManagerToOwnEvent($event_id, $manager_id) {
        try {
            $stmt = $this->dbh->prepare("insert into manager_event (event, manager)
		values (:event_id, :manager_id)");
            $stmt->execute(array(
                "event_id" => $event_id,
                "manager_id" => $manager_id
                    )
            );
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function addManagerToOwnSessions($session_id, $manager_id) {
        try {
            $stmt = $this->dbh->prepare("insert into attendee_session (session, attendee)
		values (:session_id, :manager_id)");
            $stmt->execute(array(
                "session_id" => $session_id,
                "manager_id" => $manager_id
                    )
            );
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function showRegisteredAttendees($event_id) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT attendee.idattendee, attendee.name from attendee INNER JOIN attendee_event on attendee.idattendee = attendee_event.attendee INNER join event on attendee_event.event = event.idevent where event.idevent = :event_id");
            $stmt->bindParam(":event_id", $event_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function showRegisteredSessionAttendees($session_id) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT attendee.idattendee, attendee.name from attendee INNER JOIN attendee_session on attendee.idattendee = attendee_session.attendee INNER join session on attendee_session.session = session.idsession where session.idsession = :session_id");
            $stmt->bindParam(":session_id", $session_id, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
            while ($attends = $stmt->fetch()) {
                $data[] = $attends;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getAttendeeObjects() {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT idattendee, attendee.name, password, role.name as 'role' FROM attendee inner join role on attendee.role = role.idrole");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
            while ($events = $stmt->fetch()) {
                $data[] = $events;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getAttendees() {
        $data = $this->getAttendeeObjects();
        if (count($data) > 0) {
            $bigString = '<div class="d-flex justify-content-center"><table class="table table-responsive table-hover"><thead>';
            $bigString .= '<tr><th>Name</th><th>Password</th><th>Role</th><th>Edit/Delete</th></tr></thead><tbody>';
            $buttons;
            foreach ($data as $row) {
                if ($row->getName() === "admin") {
                    $buttons = '';
                } else {
                    $buttons = "<button class='btn btn-primary' onclick='editUser({$row->getIdattendee()})'  ><i class='fas fa-edit'></i></button><button class='btn btn-danger' onclick='deleteUser({$row->getIdattendee()})'  ><i class='fas fa-trash'></i></button>";
                }
                $bigString .= "<td>{$row->getName()}</td><td>PASSWORD HASHED</td><td>{$row->getRole()}</td><td>{$buttons}</td></tr>";
            }
            $bigString .= "</tbody></table></div>";
        } else {
            $bigString = "<h2>No events found</h2>";
        }
        return $bigString;
    }

    function getVenueTable() {
        $data = $this->getVenues();
        if (count($data) > 0) {
            $bigString = '<div class="d-flex justify-content-center"><table class="table table-responsive table-hover"><thead>';
            $bigString .= '<tr><th>Name</th><th>Capacity</th><th>Edit/Delete</th></tr></thead><tbody>';
            $count = 1;
            foreach ($data as $row) {
                $bigString .= "<td>{$row->getName()}</td><td>{$row->getCapacity()}</td><td><button class='btn btn-primary' onclick='editVenue({$row->getIdvenue()})'  ><i class='fas fa-edit'></i></button><button class='btn btn-danger' onclick='deleteVenue({$row->getIdvenue()})'  ><i class='fas fa-trash'></i></button></td></tr>";
                $count++;
            }
            $bigString .= "</tbody></table></div>";
        } else {
            $bigString = "<h2>No events found</h2>";
        }
        return $bigString;
    }

    function updateVenue($idvenue, $name, $capacity) {
        try {
            $stmt = $this->dbh->prepare("update venue set  name = :name,capacity = :capacity where idvenue = :idvenue");
            $stmt->execute(array(
                "idvenue" => $idvenue,
                "name" => $name,
                "capacity" => $capacity
                    )
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function insertVenue($name, $capacity) {
        try {
            $stmt = $this->dbh->prepare("insert into venue (name,capacity)
		values (:name,:capacity)");
            $stmt->execute(array(
                "name" => $name,
                "capacity" => $capacity
                    )
            );
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function deleteVenue($idvenue) {
        try {
            $stmt = $this->dbh->prepare("delete from venue where idvenue = :idvenue");
            $stmt->bindParam(":idvenue", $idvenue, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

}
