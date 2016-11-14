<?php
    require_once('../config.php');
    require('../templates/get_chatbox_template.php');
    
    if ($config->user->loggedin()) {
        // Get active chats
        $get_active_chats_stmt = $config->link->query("SELECT * FROM `users_active_chats` WHERE `user_id` = {$_SESSION['user_id']}");
        
        while ($chat = $get_active_chats_stmt->fetch()) {
            if ($chat['group_chat_id'] == NULL) {
                $user_details = $config->link->query("SELECT * FROM `users` WHERE `id` = '{$chat['user_chat_id']}'")->fetch();
                $chat_messages_stmt = $config->link->query("
                    SELECT * FROM (
                        SELECT * FROM `chat_messages`
                        WHERE `from_id` = {$_SESSION['user_id']} AND `to_id` = {$user_details['id']}
                        OR `from_id` = {$user_details['id']} AND `to_id` = {$_SESSION['user_id']}
                        ORDER BY `id` DESC
                        LIMIT 0, 10
                    ) AS T1 ORDER BY id ASC
                ");

                if ($chat['is_mini']) {
                    $is_mini = true;
                } else {
                    $is_mini = false;
                }

                get_chatbox_temp('username', $user_details['username'], $config, $is_mini);
            }
        }
    }
?>