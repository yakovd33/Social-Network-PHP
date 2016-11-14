<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        $update_stmt = $config->link->query("UPDATE `users` SET `last login`= NOW() WHERE `id` = {$_SESSION['user_id']}");
    }
?>