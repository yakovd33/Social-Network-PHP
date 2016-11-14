<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['username']) && isset($_POST['message_text'])) {
            $username = $_POST['username'];
            $to_id = $config->link->query("SELECT `id` FROM `users` WHERE `username` = '{$username}'")->fetch()['id'];
            $message_index = $config->link->query("
                    SELECT * FROM `chat_messages`
                    WHERE `from_id` = {$_SESSION['user_id']} AND `to_id` = {$to_id}
                    OR `from_id` = {$to_id} AND `to_id` = {$_SESSION['user_id']}
            ")->rowCount() + 1;
            
            $message = trim(addslashes(urldecode(strip_tags($_POST['message_text']))), "<br>");
            if (isset($_FILES['additional_image'])) {
                if (!empty($message)) {
                       
                } else {
                    
                }
            } else {
                if (!empty($message)) {
                    $config->link->query("
                        INSERT INTO `chat_messages`(`id`, `from_id`, `to_id`, `type`, `group_id`, `photo_id`, `message`, `sent_date`, `chat_messages_index`)
                        VALUES (NULL, {$_SESSION['user_id']}, {$to_id}, 'text-message', NULL, NULL, '{$message}', CURRENT_TIMESTAMP, {$message_index})
                    ");
                }
            }
        }
    }
?>