<?php
    $root_dir = $root_path->get_root_dir();
    $page_info->add_sheet("/components/global/style.css");
    $page_info->add_script("/components/global/logout.js");
?>
<!DOCTYPE html>
<html lang="it">
    <?php
        require $root_dir . "/components/global/head.php";
        require $root_dir . "/components/global/body.php";
    ?> 
</html>