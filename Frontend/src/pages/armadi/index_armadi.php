<?php
    require __DIR__ . "/../../bootstrap.php";

    require $root_path->get_root_dir() . "/PageInfo.php";
    $page_info = new PageInfo("armadi", "Armadi e Scorte",$root_path->get_root_dir() . "/pages/armadi/armadi.php");
    $page_info->add_script("/pages/armadi/script.js");
    $page_info->add_sheet("/pages/armadi/style.css");
    require $root_path->get_root_dir() . "/pages/base.php";
?>
