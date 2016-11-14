<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['new_password_again'])) {
            if (isset($_POST['update_pass_token'])) {
                if ($_POST['update_pass_token'] == $_SESSION['update_password_session']) {
                    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['new_password_again'])) {
                        $current_pass = $_POST['current_password'];
                        
                        if (sha1($current_pass) == $config->user->getUserInfo()['password_hashed']) {
                            if ($_POST['new_password'] == $_POST['new_password_again']) {
                                if (strlen($_POST['new_password']) > 7) {
                                    $password = sha1($_POST['new_password']);
                                    $update_pass_stmt = $config->link->query("UPDATE `users` SET `password_hashed` = '{$password}' WHERE `id` = {$_SESSION['user_id']}");
                                    echo 'Password Updated.';
                                } else {
                                    echo 'Password Length Must Be Above 7 Characters.<br>';
                                }
                            } else {
                                echo 'New Passwords Do Not Match.</br>';
                            }
                        } else {
                            echo 'Current Password Is Incorrent.</br>';
                        }
                    } else {
                        echo 'All Fields Must Be Filled.</br>';
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