<?php
    require_once('../../config.php');

    if (isset($_POST['action']) && isset($_POST['request_sender_id'])) {
        $sender_id = $_POST['request_sender_id'];
        $action = $_POST['action'];

        if ($action == 'approve') {
            $approve_stmt = $config->link->query("UPDATE `friendships` SET `request_state` = 'approved', `approved_date` = NOW() WHERE `user_one_id` = {$sender_id} and `user_two_id` = {$_SESSION['user_id']}");
        } else if ($action == 'decline') {
            $decline_stmt = $config->link->query("DELETE FROM `friendships` WHERE `user_one_id` = {$sender_id} and `user_two_id` = {$_SESSION['user_id']}");
        }
    }
?>