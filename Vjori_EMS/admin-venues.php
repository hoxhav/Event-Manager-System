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

<button type="button" name='newVenue' onclick="window.location.href = 'admin-newVenue.php'" id='newVenue' class="btn btn-success">New Venue</button>

<?php
require 'Business/Crud.php';

unset($_SESSION['venue_id_for_update']);
$db = new Crud();
echo "<br>";
echo $db->getVenueTable(); //gets all the venue dashboard
?>

<?php
require 'footer.php';
?>


