<?php
    require_once('../config.php');
    if ($config->user->loggedin()) {
        $get_friends = $config->link->query("SELECT * FROM `friendships` WHERE `user_one_id` = {$_SESSION['user_id']} OR `user_two_id` = {$_SESSION['user_id']} AND `request_state` = 'approved'");
        $connected_counter = 0;
        
        while ($friend = $get_friends->fetch()) {
            
            if ($friend['user_one_id'] == $_SESSION['user_id']) {
                $friend_id = $friend['user_two_id'];
            } else if ($friend['user_two_id'] == $_SESSION['user_id']) {
                $friend_id = $friend['user_one_id'];
            }
            
            $friend_details = $config->link->query("SELECT * FROM `users` WHERE `id` = {$friend_id}")->fetch();
            
            if (strtotime($friend_details['last login'])  >= time()) {
                $connected_counter++;
            }
        }
        
        echo $connected_counter;
    }
?>