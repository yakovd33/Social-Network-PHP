<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['username'])) {
            $username = $_POST['username'];
            $user_id = $config->link->query("SELECT `id` FROM `users` WHERE `username` = '{$username}'")->fetch()['id'];
            // Delete old
            $config->link->query("DELETE FROM `users_active_chats` WHERE `user_id` = {$_SESSION['user_id']} AND `user_chat_id` = {$user_id}");
            
            if (isset($_POST['mini'])) {
                $is_mini = 1;
            } else {
                $is_mini = 0;
            }
            
            // Insert new
            $config->link->query("INSERT INTO `users_active_chats`(`id`, `user_id`, `user_chat_id`, `group_chat_id`, `is_mini`) VALUES (NULL, {$_SESSION['user_id']}, {$user_id}, NULL, {$is_mini})");
        }
    }
?>