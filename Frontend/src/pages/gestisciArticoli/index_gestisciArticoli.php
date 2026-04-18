<?php
    require __DIR__ . "/../../bootstrap.php";

    require $root_path->get_root_dir() . "/PageInfo.php";
    $page_info = new PageInfo("gestisciArticoli", "Articoli nel magazzino",$root_path->get_root_dir() . "/pages/gestisciArticoli/gestisciArticoli.php");
    $page_info->add_script("/pages/gestisciArticoli/script.js");
    $page_info->add_sheet("/pages/gestisciArticoli/style.css");
    require $root_path->get_root_dir() . "/pages/base.php";
?>
