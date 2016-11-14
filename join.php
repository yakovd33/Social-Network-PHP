<?php
    require('config.php');
    
    class join {
        private $link;
        
        public function __construct ($link) {
            $this->link = $link;
        }
        
        public function check_if_email_exists ($email) {
            $stmt = $this->link->query("SELECT `email` FROM `users` WHERE `email` = '{$email}'");
            if ($stmt->rowCount() == 0) {
                return true;
            } else {
                return false;
            }
        }
        
        public function check_if_username_exists ($username) {
            $stmt = $this->link->query("SELECT `username` FROM `users` WHERE `username` = '{$username}'");
            if ($stmt->rowCount() == 0) {
                return true;
            } else {
                return false;
            }
        }
        
        public function insert_user ($insert_data) {
            $insert = $this->link->prepare("INSERT INTO 
            `users`(`id`, `fullname`, `username`, `email`, `password_hashed`, `gender`, `country`, `city`, `birth date`, `biography`, `join date`, `last login`, `rank`, `cover_photo_index`, `profile_photo_index`, `email_validation`, `last_password_update`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, NULL)");
            $insert->execute($insert_data);
            return $this->link->lastInsertId();
        }
    }
    
    if (!$config->user->loggedin()) {
        $link = $config->db->getLink();
        
        if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['gender']) && isset($_POST['re_pass'])) {
            if (!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['re_pass'])) {
                $secretKey = '6LdUdhwTAAAAALX3z_jgXa0dfVVsQ5WCbHtXGPUh';
                $captcha = $_POST['g-recaptcha-response'];
                $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha));

                $email = $_POST['email'];
                $username = $_POST['username'];
                $fullname = $username;
                $gender = $_POST['gender'];
                $password = $_POST['password'];
                $re_pass = $_POST['re_pass'];
                
                if ($response->success) {
                    $join = new join($config->db->getLink());
                    
                    if ($join->check_if_email_exists($email) && $join->check_if_username_exists($username)) {
                        $_SESSION['user_id'] = $join->insert_user(array(NULL, $fullname, $username, $email, sha1($password), $gender, NULL, NULL, NULL, NULL, 'member', NULL, NULL, 0));
                        header("Location: index.php");
                    } else {
                        
                    }
                }
            }
        }
        
        if (isset($_GET['xhr_validation']) && isset($_GET['email']) && isset($_GET['username']) && isset($_GET['password']) && isset($_GET['re_pass'])) {
            $join = new join($config->db->getLink());
            
            $validation = [
                'email_exists' => false,
                'username_exists' => false,
                'password_length_greater_than_7' => false,
                'passwords_matching' => false,
            ];
            
            if ($join->check_if_email_exists($_GET['email'])) {
                $validation['email_exists'] = true;
            }
            
            if ($join->check_if_username_exists($_GET['username'])) {
                $validation['username_exists'] = true;
            }
            
            if (strlen($_GET['password']) > 7) {
                $validation['password_length_greater_than_7'] = true;
            }
            
            if ($_GET['password'] == $_GET['re_pass']) {
                $validation['password_matching'] = true;
            }
        }
    }
?>