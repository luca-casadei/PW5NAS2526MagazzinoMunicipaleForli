<?php
    require __DIR__ . "/bootstrap.php";
    if ($session->is_logged_in()){
        header("Location: ./pages/classList/index_classList.php");
        exit(302);
    }

    require $root_path->get_root_dir() . "/PageInfo.php";
    $page_info = new PageInfo("login", "Login Account OlogramClass",$root_path->get_root_dir() . "/pages/login/login.php");
    $page_info->add_script("/pages/login/script.js");
    $page_info->add_sheet("/pages/login/style.css");
    require  $root_path->get_root_dir() . "/pages/base.php";
    //TODO
    
?>
