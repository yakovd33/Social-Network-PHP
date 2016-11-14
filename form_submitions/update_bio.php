<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['bio'])) {
            $bio = trim(addslashes(urldecode(strip_tags($_POST['bio'], "<br><div>"))));
            
            if (strlen($bio) > 0) {
                $update_stmt = $config->link->query("UPDATE `users` SET `biography` = '{$bio}' WHERE `id` = {$_SESSION['user_id']}");
            }
        } 
    }
?>