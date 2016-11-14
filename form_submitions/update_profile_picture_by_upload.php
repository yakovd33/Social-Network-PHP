<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $tmp_name = $file["tmp_name"];
            $name = md5(date('Y-m-d H:i:s:u') . rand(0, 10000));
            $file_name = $file['name'];
            $ext = end((explode(".", $file_name)));
            
            $path = "pps/" . $name . "." . $ext;
            move_uploaded_file($tmp_name, "../media/" . $path);
            
            $user_photos_index = $config->link->query("SELECT * FROM `photos` WHERE `user_id` = {$_SESSION['user_id']}")->rowCount() + 1;
            $insert_to_photos_table = $config->link->query("INSERT INTO `photos`(`id`, `user_id`, `user_photos_index`, `date`, `path`, `type`, `active`) VALUES (NULL, {$_SESSION['user_id']}, {$user_photos_index}, NOW(), '{$path}', 'profile-picture', 1)");
            $uploaded_photo_id = $config->link->lastInsertId();
            $index = $config->link->query("SELECT `user_photos_index` FROM `photos` WHERE `id` = {$uploaded_photo_id}")->fetch()['user_photos_index'];
            $update_user_pp_stmt = $config->link->query("UPDATE `users` SET `profile_photo_index` = {$index} WHERE `id` = {$_SESSION['user_id']}");
        }
    }
?>