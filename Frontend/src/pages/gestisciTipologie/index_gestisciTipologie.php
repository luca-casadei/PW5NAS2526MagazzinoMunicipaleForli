<?php
    require __DIR__ . "/../../bootstrap.php";

    require $root_path->get_root_dir() . "/PageInfo.php";
    $page_info = new PageInfo("gestisciTipologie", "Tipologie degli articoli",$root_path->get_root_dir() . "/pages/gestisciTipologie/gestisciTipologie.php");
    $page_info->add_script("/pages/gestisciTipologie/script.js");
    $page_info->add_sheet("/pages/gestisciTipologie/style.css");
    require $root_path->get_root_dir() . "/pages/base.php";
?>
