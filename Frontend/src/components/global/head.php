<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            echo $page_info->get_title();
        ?>
    </title>
    <?php foreach ($page_info->get_sheets() as $sheet): ?>
        <link rel="stylesheet" href="<?php echo $sheet; ?>"> 
    <?php endforeach; ?>
    

    <?php foreach ($page_info->get_scripts() as $script): ?>
        <script type="module" src="<?php echo $script; ?>"></script> 
    <?php endforeach; ?>
</head>
