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
echo $db->getManagenedEvents("manager");
?>

<?php

require 'footer.php';
?>


