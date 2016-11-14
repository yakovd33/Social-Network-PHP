<?php
    require_once('../config.php');
    
    if (isset($_POST['postid'])) {
        
        $user_liked = $config->link->query("SELECT * FROM `posts_likes` WHERE `liker_id` = {$_SESSION['user_id']} AND `post_id` = {$_POST['postid']} AND `active` = 1");;
        $poster_id = $config->link->query("SELECT `user_id` FROM `posts` WHERE `id` = {$_POST['postid']}")->fetch()['user_id'];
        
        if ($user_liked->rowCount() == 0) {
            $like_insert_stmt = $config->link->query("INSERT INTO `posts_likes`(`id`, `liker_id`, `post_id`, `poster_id`, `date`, `active`) VALUES (NULL, {$_SESSION['user_id']}, {$_POST['postid']}, {$poster_id}, NOW(), '1')");
            if ($poster_id != $_SESSION['user_id']) {
                $config->user->send_notific_to_user('like_on_post', $poster_id, array('post_id' => $_POST['postid']));
            }
        } else {
            $like_delete_stmt = $config->link->query("UPDATE `posts_likes` SET `active` = '0' WHERE `liker_id` = {$_SESSION['user_id']} AND `post_id` = {$_POST['postid']}");
            //$delete_like_notific_stmt = $config->link->query("DELETE FROM `notifications` WHERE `last_from_user_id` = {$_SESSION['user_id']} AND `post_id` = {$_POST['postid']} AND `type` = 'like_on_post'");
        }
        
        $num_likes_stmt = $config->link->query("SELECT * FROM `posts_likes` WHERE `post_id` = {$_POST['postid']} AND `active` = 1");
        $num_likes = $num_likes_stmt->rowCount();
        
        if ($num_likes == 0) {
            echo 'No Hearts';
        } elseif ($num_likes == 1) {
            echo '1 Heart';
        } else {
            echo $num_likes . ' Hearts';
        }
    }
?>