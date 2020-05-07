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

<button type="button" name='newUser' onclick="window.location.href = 'admin-newUser.php'" id='newUser' class="btn btn-success">New User</button>
<?php
require 'Business/Crud.php';
unset($_SESSION['attendee_id_for_update']);
$db = new Crud();
echo $db->getAttendees();
?>

<?php
require 'footer.php';
?>