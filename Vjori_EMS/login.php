<?php
session_start();
if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]) {
    header("Location: welcome.php");
    exit();
} else if (isset($_SESSION["redirected"]) && $_SESSION["redirected"]) {
    echo "You need to login";
    $_SESSION["redirected"] = false;
} else {
    require $_SERVER['DOCUMENT_ROOT'] . '/Business/Crud.php';
    $errors = array();
    $filtered_inputs = array();
    date_default_timezone_set('Europe/Zagreb');
    $date = date("m d, Y H:i");
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (filter_has_var(INPUT_POST, 'name')) {
            if (empty($_POST['name'])) {
                $errors[] = "Please enter your name";
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
            $attendee = $db->getAttendee($filtered_inputs['name'], $filtered_inputs['password']);
            if (count($attendee) == 0) {
                echo "<script type='text/javascript'>alert('Your username/password was wrong');</script>";
            } else {
                foreach ($attendee as $row) {
                    if ("{$row->getName()}" == $filtered_inputs['name'] && "{$row->getPassword()}" == $filtered_inputs['password']) {
                        $_SESSION["loggedIn"] = true;
                        $_SESSION["name"] = $filtered_inputs['name'];
                        $_SESSION["id"] = $row->getIdattendee();
                        $_SESSION["role"] = $row->getRole();
                        setcookie("loginTime", $date, (int) (time() * 60));
                        header("Location: welcome.php");
                        exit();
                    }
                }
            }
        } else {
            echo "<script type='text/javascript'>alert('Something went wrong!');</script>";
        }
    }
}
?>
<?php
$pageTitle = 'Welcome to EMS - Login';
$navElements = '<li class="nav-item">
                        <a href="login.php" class="nav-link">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a href="#faq-section" class="nav-link">ABOUT US</a>
                    </li>';
$picture = "home-section";
require 'header.php';
?>
<div class="card w-25 mx-auto">

    <form class="d-flex flex-column"
          method="POST">

        <div class="form-group">

            <label for="name">Name</label>
            <input class="form-control"
                   type="text"
                   id='name'
                   name="name"
                   minlength="4"
                   maxlength="40"
                   placeholder="Enter your username"
                   required>
            <label for="password">Password</label>
            <input class="form-control"
                   type="password"
                   name="password"
                   id="password"
                   minlength="8"
                   maxlength="40"
                   placeholder="Enter your password"
                   required>
        </div>
        <p>Not a user? Register <a href="signup.php"> here</a></p>
        <div class="row justify-content-center ">
            <button type="submit"
                    id="login"
                    class="btn btn-primary"
                    onclick="" data-tilt>Login</button>
        </div>
    </form>

</div>
</div>
<div class="section justify-content-center  d-flex flex-column align-items-center" id="faq-section">

    <div class="mat-container">
        <h2>FAQ About Vjori's Event Management System</h2>
        <h4>Note: This is a tool created for school purpose project.</h4>
        <h4>How to use it?</h4>
        <h5>You can register as a user. If you want to register as Event Manager, the admin has to register you. Because not everyone
        can be a manager. All the functionalities asked in the PDF are met.</h5>
        <h4>Is this website 100% yours?</h4>
        <h5>This website is built in a couple of days. There are snippet of codes taken from my previous homeworks & projects done by me 
            in previous classes. Also there are some snippet of ideas taken on the internet.<strong>But do not worry, everything is referenced.</strong></h5>
        <h4>Technologies used to develop this tool?</h4>
        <h5>HTML5, CSS3, Bootstrap4, Javascript ES6, JQuery, PHP, MySQL PDO</h5>
    </div>
</div>
<?php
require 'footer.php';
?>