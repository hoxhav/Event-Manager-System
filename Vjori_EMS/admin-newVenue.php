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
$errors = array();
$filtered_inputs = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (filter_has_var(INPUT_POST, 'name')) {
        if (empty($_POST['name'])) {
            $errors[] = "Please enter your venue's name";
        } else if (!filter_var((filter_input(INPUT_POST, 'name')), FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z ]+$/")))) {
            $errors[] = "Please enter a VALID name";
        } else {
            $filtered_inputs['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        }
    }


    if (filter_has_var(INPUT_POST, 'capacity')) {
        if (empty($_POST['capacity'])) {
            $errors[] = "Please enter the venue's capacity";
        } else if (!filter_var((filter_input(INPUT_POST, 'capacity')), FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[0-9]+$/")))) {
            $errors[] = "Please enter a VALID name";
        } else {
            $filtered_inputs['capacity'] = filter_input(INPUT_POST, 'capacity', FILTER_SANITIZE_STRING);
        }
    }


    foreach ($errors as $error) {
        echo $error, '<br>';
    }

    if (count($errors) == 0) {
        $db = new Crud();
        if (isset($_POST['update'])) {
            $db->updateVenue($_SESSION['venue_id_for_update'], $filtered_inputs['name'], $filtered_inputs['capacity']);
        } else if (isset($_POST['addVenue'])) {
            $db->insertVenue($filtered_inputs['name'], $filtered_inputs['capacity']);
        }
        header("Location: admin-venues.php");
    } else {
        echo 'Wrong';
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
                   minlength="4"
                   maxlength="40"
                   required>


            <label for="capacity">Capacity</label>
            <input class="form-control"
                   type="text"
                   name="capacity"
                   minlength="1"
                   maxlength="40"
                   required>
        </div>
         <div class="row">
            <button type="submit"
                    id="addVenue"
                    name='addVenue'
                    class="btn btn-primary col-4" 
                    <?php if (isset($_SESSION['venue_id_for_update'])) { ?> disabled <?php } ?>
                    >Add Venue</button>
            <button type="submit"
                    id="update"
                    name="update"
                    class="btn btn-info col-4"
                    <?php if (!isset($_SESSION['venue_id_for_update'])) { ?> disabled <?php } ?>
                    >Update</button>
            <button type="reset"
                    id="login"
                    class="btn btn-success col-3"
                    >Reset</button>
        </div>
    </form>

</div>

<?php
require 'footer.php';
?>