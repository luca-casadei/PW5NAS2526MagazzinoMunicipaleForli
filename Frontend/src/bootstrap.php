<?php
    date_default_timezone_set("Europe/Rome");
    require "Session.php";
    $session = new Session();

    require "PathFinder.php";
    $root_path = new PathFinder();
?>