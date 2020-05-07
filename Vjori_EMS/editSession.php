<?php
session_start();
if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
    $_SESSION["redirected"] = true;
    header("Location: login.php");
    exit();
} else {
    echo "You have logedin " . $_SESSION["name"];
}
require 'Business/Crud.php';
$db = new Crud();
echo "<br>";
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
        $db->updateSession($_SESSION["session_id_for_update"], $filtered_inputs['name'], $filtered_inputs['numberAllowed'], $_POST['event'], $startdate, $enddate);
        header("Location: eventmanager.php");
        exit();
    } else {
        foreach ($errors as $error) {
            echo $error, '<br>';
        }
    }
}
?>
<?php
require 'navigation.php';
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
            <input class="form-control" name="startdate" value="2019-10-21T13:45:00" type="datetime-local" id="startdate">
            <label for="enddate">End Date</label>
            <input class="form-control" name="enddate"  value="2019-10-22T13:45:00" type="datetime-local"  id="enddate">
            <label for="numberAllowed">Number allowed</label>
            <input class="form-control"
                   type="text"
                   name="numberAllowed"
                   required>
            <label for="event">Event</label>
            <select id="event" class="form-control" name="event">
                <?php
                $venues;
                if ($_SESSION["role"] == 1) {
                    $venues = $db->getEventListForAdmin();
                } else {
                    $venues = $db->getEventList($_SESSION["id"]);
                }
                foreach ($venues as $venue) {
                    echo "<option value='{$venue->getIdevent()}'>{$venue->getName()}</option>";
                }
                ?>
            </select>
        </div>
        <div class="row">
            <button type="submit"
                    id="add"
                    class="btn btn-primary col-6"
                    >Update</button>
            <button type="reset"
                    id="login"
                    class="btn btn-success col-6"
                    >Reset</button>
        </div>
    </form>


    <?php
    require 'footer.php';
    ?>
