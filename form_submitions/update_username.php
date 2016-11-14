<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['new_username'])) {
            if (isset($_POST['update_username_token'])) {
                if ($_POST['update_username_token'] == $_SESSION['update_username_session']) {
                    $username = trim(htmlentities($_POST['new_username']));
                    if (strlen($username) > 2 && strlen($username) < 11) {
                        $config->link->query("UPDATE `users` SET `username` = '{$username}' WHERE `id` = {$_SESSION['user_id']}");
                        echo 'username Updated.';
                    } else {
                        echo 'username Has To Be Between 2-10 Characters.</br>';
                    }
                } else {
                    echo 'Invalid Token.';
                }
            } else {
                echo 'No Security Token.';
            }
        }
    }
?>