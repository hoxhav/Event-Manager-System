<?php
session_start();
if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
    $_SESSION["redirected"] = true;
    header("Location: login.php");
    exit();
} else {
    echo "You have logedin " . $_SESSION["name"];
}
?>
<?php
require 'navigation.php';
?>

<?php
require 'Business/Crud.php';

$db = new Crud();


$errors = array();
$filtered_inputs = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (filter_has_var(INPUT_POST, 'name')) {
        if (empty($_POST['name'])) {
            $errors[] = "Please enter your name";
        } else if (!filter_var((filter_input(INPUT_POST, 'name')), FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z ]+$/")))) {
            $errors[] = "Please enter a VALID name";
        } else {
            $filtered_inputs['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        }
    }
    if (filter_has_var(INPUT_POST, 'numberAllowed')) {
        if (empty($_POST['numberAllowed'])) {
            $errors[] = "Please the number allowed per event";
        } else if (!filter_var((filter_input(INPUT_POST, 'numberAllowed')), FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[0-9]+$/")))) {
            $errors[] = "Please enter a VALID number";
        } else {
            $filtered_inputs['numberAllowed'] = filter_input(INPUT_POST, 'numberAllowed', FILTER_SANITIZE_STRING);
        }
    }

    $startdate = substr_replace($_POST['startdate'], ' ', 10, -8);
    $enddate = substr_replace($_POST['enddate'], ' ', 10, -8);
    if (count($errors) == 0) {
        $event_id = $db->addEvent($filtered_inputs['name'], $startdate, $enddate, $filtered_inputs['numberAllowed'], $_POST['venue']);
        $db->registerAttendeeToEvent($event_id, $_SESSION["id"]);
        $db->addManagerToOwnEvent($event_id, $_SESSION["id"]);
        header("Location: myevents.php");
        exit();
    } else {
        foreach ($errors as $error) {
            echo $error, '<br>';
        }
    }
}
?>

<div class="card w-25 mx-auto">

    <form class="d-flex flex-column"
          method="POST"
          >

        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control"
                   type="text"
                   name="name"
                   maxlength="50"
                   required>

            <label for="startdate">Start Date</label>
            <input class="form-control" name="startdate" type="datetime-local" value="2019-10-20T13:45:00" id="startdate">
            <label for="enddate">End Date</label>
            <input class="form-control" name="enddate" type="datetime-local" value="2019-10-20T17:45:00" id="enddate">
            <label for="numberAllowed">Number allowed</label>
            <input class="form-control"
                   type="text"
                   name="numberAllowed"
                   required>
            <label for="venue">Venue</label>
            <select id="venue" class="form-control" name="venue">
                <?php
                //gets venues and adds it to a selection
                $venues = $db->getVenues();
                foreach ($venues as $venue) {
                    echo "<option value='{$venue->getIdvenue()}'>{$venue->getName()}</option>";
                }
                ?>
            </select>
        </div>
        <div class="row">
            <button type="submit"
                    id="add"
                    class="btn btn-primary col-6"
                    >Add</button>
            <button type="reset"
                    id="login"
                    class="btn btn-success col-6"
                    >Reset</button>
        </div>
    </form>

    <?php
    require 'footer.php';
    ?>