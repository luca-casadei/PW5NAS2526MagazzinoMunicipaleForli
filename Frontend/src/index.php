<?php
    require __DIR__ . "/bootstrap.php";

    require $root_path->get_root_dir() . "/PageInfo.php";
    $page_info = new PageInfo("home", "Home",$root_path->get_root_dir() . "/pages/home/home.php");
    $page_info->add_script("/pages/home/script.js");
    $page_info->add_sheet("/pages/home/style.css");
    require  $root_path->get_root_dir() . "/pages/base.php";
?>
