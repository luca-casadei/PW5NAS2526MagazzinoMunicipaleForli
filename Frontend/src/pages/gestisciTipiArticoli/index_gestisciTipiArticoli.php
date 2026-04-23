<?php
    require __DIR__ . "/../../bootstrap.php";

    require $root_path->get_root_dir() . "/PageInfo.php";
    $page_info = new PageInfo("gestisciTipiArticoli", "Tipi Articoli nel magazzino",$root_path->get_root_dir() . "/pages/gestisciTipiArticoli/gestisciTipiArticoli.php");
    $page_info->add_script("/pages/gestisciTipiArticoli/script.js");
    $page_info->add_sheet("/pages/gestisciTipiArticoli/style.css");
    require $root_path->get_root_dir() . "/pages/base.php";
?>
