<?php
    require_once('../config.php');

    $config->link->query("UPDATE `users` SET `show_comments_preview` = !`show_comments_preview` WHERE `id` = {$_SESSION['user_id']}");
?>