<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        $user_index = $config->link->query("SELECT `profile_photo_index` FROM `users` WHERE `id` = {$_SESSION['user_id']}")->fetch()['profile_photo_index'];
        echo $config->link->query("SELECT `path` FROM `photos` WHERE `user_photos_index` = {$user_index}")->fetch()['path'];
    }
?>