<?php
    require_once('../../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['userid'])) {
            $stmt = $config->link->query("INSERT INTO `friendships`
            (`id`, `user_one_id`, `user_two_id`, `sent_date`, `request_state`)
            VALUES (NULL, {$_SESSION['user_id']}, {$_POST['userid']}, NOW(), NULL)");
        }
    }
?>