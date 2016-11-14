<?php
    require_once('../config.php');
    require('../templates/get_chatbox_template.php');

    if ($config->user->loggedin()) {
        if (isset($_POST['username'])) {
            $username = $_POST['username'];
            $user_details = $config->link->query("SELECT * FROM `users` WHERE `username` = '{$username}'")->fetch();
            
            // Insert to user active chats
            $config->link->query("INSERT INTO `users_active_chats`(`id`, `user_id`, `user_chat_id`, `group_chat_id`, `is_mini`) VALUES (NULL, {$_SESSION['user_id']}, {$user_details['id']}, NULL, 0)");
            get_chatbox_temp ('username', $username, $config, false);
        }
    }
?>