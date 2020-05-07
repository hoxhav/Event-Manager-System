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
$output = '';
$filtered_inputs = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (filter_has_var(INPUT_POST, 'name')) {
        if (empty($_POST['name'])) {
            $errors[] = "Please enter your first name";
        } else if (!filter_var((filter_input(INPUT_POST, 'name')), FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) {
            $errors[] = "Please enter a VALID name";
        } else {
            $filtered_inputs['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        }
    }


    if (filter_has_var(INPUT_POST, 'password')) {
        if (empty($_POST['password'])) {
            $errors[] = "Please enter your password";
        } else {
            $filtered_inputs['password'] = hash('sha256', $_POST['password']);
        }
    }


    foreach ($errors as $error) {
        echo $error, '<br>';
    }

    if (count($errors) == 0) {
        $db = new Crud();
        if (count($db->getName(($filtered_inputs['name']))) > 0) {
            $output = "Username already exists, please choose another one";
        } else {
            $privilege;
            if ($_POST[optradio] === "User") {
                $privilege = 3;
            } else {
                $privilege = 2;
            }
            if (isset($_POST['update'])) {
                $db->updateAttendee($_SESSION['attendee_id_for_update'], $filtered_inputs['name'], $filtered_inputs['password'], $privilege);
            } else if (isset($_POST['addUser'])) {
                $db->insert($filtered_inputs['name'], $filtered_inputs['password'], $privilege);
            }
            header("Location: admin-registeredusers.php");
        }
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
                   placeholder="Enter your name"
                   required>


            <label for="password">Password</label>
            <input class="form-control"
                   type="password"
                   name="password"
                   minlength="8"
                   maxlength="40"
                   placeholder="Enter your password"
                   required>
        </div>
        <label for="radio">User Privilege</label>
        <label class="radio-inline"><input type="radio" name="optradio" value='User' checked>User</label>
        <label class="radio-inline"><input type="radio" name="optradio" value='Manager'>Event Manager</label>
        <p><?php echo $output ?></p>
        <div class="row">
            <button type="submit"
                    id="addUser"
                    name='addUser'
                    class="btn btn-primary col-4"
                    <?php if (isset($_SESSION['attendee_id_for_update'])) { ?> disabled <?php } ?>
                    >Register User</button>
            <button type="submit"
                    id="update"
                    name="update"
                    class="btn btn-info col-4"
                    <?php if (!isset($_SESSION['attendee_id_for_update'])) { ?> disabled <?php } ?>
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