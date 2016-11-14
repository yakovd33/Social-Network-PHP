<?php
    require_once('form_submition.php');
    
    class new_post_submition extends form_submition {
        private $link;
        
        public function __construct ($link) {
            $this->link = $link;
        }
        
        public function user_has_more_than_one_post () {
            
        }
        
        public function last_post_scope () {
            
        }
        
        public function insert_image ($path, $type) {
            $user_photos_index = $this->link->query("SELECT * FROM `photos` WHERE `user_id` = {$_SESSION['user_id']}")->rowCount() + 1;
            $statement = $this->link->query("INSERT INTO `photos`(`id`, `user_id`, `user_photos_index`, `date`, `path`, `type`, `active`) VALUES (NULL, {$_SESSION['user_id']}, {$user_photos_index}, NOW(), '{$path}', '{$type}', 1)");
            
            return $this->link->lastInsertId();
        }
        
        public function new_post () {
            
        }
    }
    
    $post_submition = new new_post_submition($config->db->getLink());
    
    if ($config->user->loggedin()) {
        if (isset($_POST['new-post-token']) && isset($_POST['profile_userid'])) {
            $token = $_POST['new-post-token'];
            
            if ($post_submition->validate_token($token, 'new_post_token')) {
                if (isset($_POST['post_input'])) {
                    
                    if (!isset($_POST['post_scope'])) {
                        if ($post_submition->user_has_more_than_one_post()) {
                            $post_scope = $post_submition->last_post_scope();
                        } else {
                            $post_scope = 'public';
                        }
                    } else {
                        $post_scope = strtolower($_POST['post_scope']);
                    }
                    
                    if ($post_scope == 'public' || $post_scope == 'friends' || $post_scope == 'only me') {
                    } else {
                        if ($post_submition->user_has_more_than_one_post()) {
                            $post_scope = $post_submition->last_post_scope();
                        } else {
                            $post_scope = 'public';
                        }
                    }
                    
                    
                    $post_input = trim(addslashes(urldecode(strip_tags($_POST['post_input']))), "<br>");
                    $post_hash = md5(date('Y-m-d H:i:s:u') . rand(0, 10000));
                    
                    if (isset($_FILES['additional_image'])) {
                        $tmp_name = $_FILES["additional_image"]["tmp_name"];
                        $name = md5(date('Y-m-d H:i:s:u') . rand(0, 10000));
                        $file_name = $_FILES['additional_image']['name'];
                        $ext = end((explode(".", $file_name)));
                        move_uploaded_file($tmp_name, "../media/posts_pictures/" . $name . "." . $ext);
                        
                        // Image Type
                        if ($_POST['profile_userid'] == $_SESSION['user_id']) {
                            $image_type = 'timeline-image';
                        } else  {
                            $image_type = 'other-user-post';
                        }
                        
                        $photo_id = $post_submition->insert_image('posts_pictures/' . $name . "." . $ext, $image_type);
                        $insert_stmt = $config->link->query("INSERT INTO
                            `posts`(`id`, `post_text`, `posted date`, `user_id`, `type`, `other_user_wall_id`, `photo_id`, `repost_id`, `scope`, `post_hash`)
                            VALUES (NULL, '{$post_input}' , CURRENT_TIMESTAMP, {$_SESSION['user_id']}, 'image', {$_POST['profile_userid']}, {$photo_id}, NULL, '{$post_scope}', '{$post_hash}')");
                    } else {
                        if (strlen($post_input) > 0) {
                            $insert_stmt = $config->link->query("INSERT INTO 
                                `posts`(`id`, `post_text`, `posted date`, `user_id`, `type`, `other_user_wall_id`, `photo_id`, `repost_id`, `scope`, `post_hash`)
                                VALUES (NULL, '{$post_input}', CURRENT_TIMESTAMP, {$_SESSION['user_id']}, 'text-post', {$_POST['profile_userid']}, NULL, NULL, '{$post_scope}', '{$post_hash}')");
                        }
                    }
                }
            }
        }
    }
?>