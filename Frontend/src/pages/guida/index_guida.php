<?php
    require __DIR__ . "/../../bootstrap.php";

    require $root_path->get_root_dir() . "/PageInfo.php";
    $page_info = new PageInfo("guida", "Guida al sito",$root_path->get_root_dir() . "/pages/guida/guida.php");
    $page_info->add_sheet("/pages/guida/style.css");
    require $root_path->get_root_dir() . "/pages/base.php";
?>
