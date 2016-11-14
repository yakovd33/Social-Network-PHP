<?php
    require_once('../../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['userid'])) {
            $stmt = $config->link->query("DELETE FROM `friendships` WHERE `user_one_id` = {$_SESSION['user_id']} AND `user_two_id` = {$_POST['userid']}");
        }
    }
?>