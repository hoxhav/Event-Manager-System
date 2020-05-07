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
echo "<br>";
if (isset($_POST["event_id"])) {
    $db->registerAttendeeToEvent($_POST["event_id"]);
}
echo $db->getMyEvents('');
?>

<?php

require 'footer.php';
?>


