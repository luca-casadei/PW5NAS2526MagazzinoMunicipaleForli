<?php
    require __DIR__ . "/../../bootstrap.php";
    if (!$session->is_logged_in()){
        header("Location: ./../../index.php");
        exit(302);
    }
    require $root_path->get_root_dir() . "/PageInfo.php";
    $page_info = new PageInfo("classList", "Display of the OlogramClasses",$root_path->get_root_dir() . "/pages/classList/classList.php");
    $page_info->add_script("/pages/classList/script.js");
    $page_info->add_sheet("/pages/classList/style.css");
    require $root_path->get_root_dir() . "/pages/base.php";
?>
