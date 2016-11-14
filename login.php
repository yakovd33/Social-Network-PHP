<?php
    require('config.php');
    $link = $config->db->getLink();
    
    class login {
        public function set_login_session ($value) {
            $_SESSION['user_id'] = $value;
        }
    }
    
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        if (isset($_POST['page_url'])) {
            $page_url = $_POST['page_url'];
            
            $password_hashed = sha1($password);
            
            if ($page_url == null || !file_exists($page_url)) {
                $page_url = 'index.php';
            }
        }
        
        $statement = $link->query("SELECT * FROM `users` WHERE `email` = '{$email}' AND `password_hashed` = '{$password_hashed}'");
        
        if ($statement->rowCount() == 1) {
            $login = new login();
            $login->set_login_session($statement->fetch()['id']);
            
            if (!isset($_POST['remember'])) {
            }

            if (isset($_POST['page_url'])) {
                header("Location: " . $page_url);
            }
        } else {
            header("Location: " . $page_url . "?login_fail&retry_login_email=" . $email);
        }
    }
?>