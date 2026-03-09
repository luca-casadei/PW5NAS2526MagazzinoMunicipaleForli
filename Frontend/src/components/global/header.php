<header>
    <nav>
        <ul>
            <li>
                <a href="/pages/createClass/index_createClass.php" data-active=<?php echo ($page_info->get_page_id() === "createClass") ? "true" : "false"; ?>>Create</a>
            </li>
            <li>
                <a href="/pages/classList/index_classList.php" data-active=<?php echo ($page_info->get_page_id() === "classList") ? "true" : "false"; ?>>Classes</a>
            </li>
            <li>
                <a href="/pages/register/index_register.php" data-active=<?php echo ($page_info->get_page_id() === "register") ? "true" : "false"; ?>>Register</a>
            </li>
            <li>
                <a href="/index.php" data-active=<?php echo ($page_info->get_page_id() === "login") ? "true" : "false"; ?>>Login</a>
            </li>
            <li>
                <a id="logoutBtn" data-active=<?php echo ($page_info->get_page_id() === "logout") ? "true" : "false"; ?>>Logout</a>
            </li>
        </ul>
    </nav>
</header>
