<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['new_email'])) {
            if (isset($_POST['update_email_token'])) {
                if ($_POST['update_email_token'] == $_SESSION['update_email_session']) {
                    $email = trim(htmlentities($_POST['new_email']));
                    if (strlen($email) > 3 && strlen($email) < 255) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $config->link->query("UPDATE `users` SET `email` = '{$email}' WHERE `id` = {$_SESSION['user_id']}");
                            echo 'Email Updated.';
                        } else {
                            echo 'Invalid Email.</br>';
                        }
                    } else {
                        echo 'email Has To Be Between 4-254 Characters.</br>';
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