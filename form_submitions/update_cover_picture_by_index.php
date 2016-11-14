<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['index']) && $_POST['index'] != 'undefined') {
            $index = $_POST['index'];
            $check_if_user_has_index = $config->link->query("SELECT * FROM `photos` WHERE `user_photos_index` = {$index} AND `user_id` = {$_SESSION['user_id']}");
            
            if ($check_if_user_has_index->rowCount() == 1) {
                $update_stmt = $config->link->query("UPDATE `users` SET `cover_photo_index` = {$index} WHERE `id` = {$_SESSION['user_id']}");
            }
        }
    }
?>