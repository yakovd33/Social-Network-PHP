<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['username']) && isset($_POST['message_text'])) {
            $username = $_POST['username'];
            $to_id = $config->link->query("SELECT `id` FROM `users` WHERE `username` = '{$username}'")->fetch()['id'];
            $message_hash = md5(date('Y-m-d H:i:s:u') . rand(0, 10000));
            $messages_stmt = $config->link->query("
                    SELECT * FROM `chat_messages`
                    WHERE `from_id` = {$_SESSION['user_id']} AND `to_id` = {$to_id}
                    OR `from_id` = {$to_id} AND `to_id` = {$_SESSION['user_id']}
                    LIMIT 15, 
            ");
        }
    }
?>