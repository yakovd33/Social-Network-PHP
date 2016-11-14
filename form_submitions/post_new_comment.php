<?php
    require_once('../config.php');
    
    class comment_submition {
        private $link;
        
        public function __construct ($link) {
            $this->link = $link;
        }
        
        public function insert_image ($path, $scope) {
            $user_photos_index = $this->link->query("SELECT * FROM `photos` WHERE `user_id` = {$_SESSION['user_id']}")->rowCount() + 1;
            $statement = $this->link->query("INSERT INTO `photos`(`id`, `user_id`, `user_photos_index`, `date`, `path`, `type`, `active`) VALUES (NULL, {$_SESSION['user_id']}, {$user_photos_index}, NOW(), '{$path}', 'comment-image', 1)");
            
            return $this->link->lastInsertId();
        }
    }

    if ($config->user->loggedin()) {
        if (isset($_POST['postid']) && isset($_POST['comment_content'])) {
            $post_id = $_POST['postid'];
            $content = trim(addslashes(urldecode(strip_tags($_POST['comment_content']))), "<br>");
            $poster_id = $config->link->query("SELECT `user_id` FROM `posts` WHERE `id` = {$post_id}")->fetch()['user_id'];
            
            if ($config->post->is_post_commentable($post_id)) {
                if ($_SESSION['user_id'] != $poster_id) {
                    if ($config->check_friends_with($poster_id)) {
                    } else {
                        exit();
                    }
                }
                
                $comment_submition = new comment_submition($config->link);
                $post_comment_index_stmt = $config->link->query("SELECT * FROM `posts_comments` WHERE `post_id` = {$post_id} AND `active` = 1");
                $post_comment_index = $post_comment_index_stmt->rowCount() + 1;
                
                if (isset($_FILES['additional_image'])) {
                    $tmp_name = $_FILES["additional_image"]["tmp_name"];
                    $name = md5(date('Y-m-d H:i:s:u') . rand(0, 10000));
                    $file_name = $_FILES['additional_image']['name'];
                    $ext = end((explode(".", $file_name)));
                    move_uploaded_file($tmp_name, "../media/posts_pictures/" . $name . "." . $ext);
                    
                    $photo_id = $comment_submition->insert_image('posts_pictures/' . $name . "." . $ext, 'comment');
                    $insert_stmt = $config->link->query("
                        INSERT INTO `posts_comments`(`id`, `commenter_id`, `post_id`, `poster_id`, `date`, `comment`, `photo_id`, `post_comments_index`)
                        VALUES (NULL, {$_SESSION['user_id']}, {$post_id}, {$poster_id}, NOW(), '{$content}', {$photo_id}, {$post_comment_index})
                    ");
                } else {
                    $insert_stmt = $config->link->query("
                        INSERT INTO `posts_comments`(`id`, `commenter_id`, `post_id`, `poster_id`, `date`, `comment`, `photo_id`, `post_comments_index`)
                        VALUES (NULL, {$_SESSION['user_id']}, {$post_id}, {$poster_id}, NOW(), '{$content}', NULL, {$post_comment_index})
                    ");
                }

                if ($poster_id != $_SESSION['user_id']) {
                    $config->user->send_notific_to_user('comment_on_post', $poster_id, array('post_id' => $post_id, 'post_comment_index' => $post_comment_index));                
                }
            }
        }
    }
?>