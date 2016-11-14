<?php
    require_once('../config.php');
    if ($config->user->loggedin()) {
        if (isset($_POST['username'])) {
            $username = $_POST['username'];
            $user_id = $user_details = $config->link->query("SELECT `id` FROM `users` WHERE `username` = '{$username}'")->fetch()['id'];
            $delete_active_chat_stmt = $config->link->query("DELETE FROM `users_active_chats` WHERE `user_id` = {$_SESSION['user_id']} && `user_chat_id` = {$user_id}");
        }
    }
?>