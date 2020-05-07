<?php

$navForAdminAndManager = '';
if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2) {
    $navForAdminAndManager = '<li class="nav-item"><a href="addevent.php" class="nav-link">ADD EVENT</a></li><li class="nav-item"><a href="addsessions.php" class="nav-link">ADD SESSIONS</a></li><li class="nav-item"><a href="eventmanager.php" class="nav-link">MY EVENTS MANAGER</a></li>';
}

if ($_SESSION["role"] == 1) {
    $navForAdminAndManager .= '<li class="nav-item"><a href="admin-dashboard.php" class="nav-link">DASHBOARD</a></li><li class="nav-item"><a href="admin-registeredusers.php" class="nav-link">REGISTERED USERS</a></li><li class="nav-item"><a href="admin-venues.php" class="nav-link">VENUES</a></li>';
}
$picture = "";
$pageTitle = 'Welcome ' . $_SESSION['name'];
$navElements = '<li class="nav-item">
                        <a href="welcome.php" class="nav-link">ALL EVENTS</a>
                    </li>
                    <li class="nav-item">
                        <a href="myevents.php" class="nav-link">MY EVENTS</a>
                    </li>' . $navForAdminAndManager .
        '<li class="nav-item">
                        <a href="logout.php" class="nav-link">LOG OUT</a>
                    </li>';
require 'header.php';
?>