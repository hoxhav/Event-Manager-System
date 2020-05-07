<!doctype html>
<html lang="en">
    <head>
        <title><?php echo $pageTitle ?></title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="assets/style/style.css"/>
        <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png"/>
        <script src="assets/js/jquery-3.4.1.min.js"></script>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous"></head>

</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">

        <span id="nav-title">Event<span id="nav-title2">Management</span></span>

        <button 
            class="navbar-toggler hidden-lg-up"
            type="button"
            data-toggle="collapse"
            data-target="#collapsibleNavId">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav  ml-auto">
                <?php echo $navElements ?>
            </ul>
        </div>

    </nav>

    <div class="section container-fluid d-flex flex-column justify-content-center align-items-center" id="<?php echo $picture ?>">
