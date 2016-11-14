<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['new_fullname'])) {
            if (isset($_POST['update_fullname_token'])) {
                if ($_POST['update_fullname_token'] == $_SESSION['update_fullname_session']) {
                    $fullname = trim(htmlentities($_POST['new_fullname']));
                    if (strlen($fullname) > 2 && strlen($fullname) < 27) {
                        $config->link->query("UPDATE `users` SET `fullname` = '{$fullname}' WHERE `id` = {$_SESSION['user_id']}");
                        echo 'Fullname Updated.';
                    } else {
                        echo 'Fullname Has To Be Between 2-26 Characters.</br>';
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