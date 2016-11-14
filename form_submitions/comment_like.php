<?php
    require_once('../config.php');

    if (isset($_POST['commentid_like'])) {
        $commentid = $_POST['commentid_like'];
        $check_if_user_liked_stmt = $config->link->query("SELECT * FROM `comments_likes` WHERE `comment_id` = {$commentid} AND `liker_id` = {$_SESSION['user_id']}")->rowCount();

        function get_num_hearts($config, $commentid) {
            $check_if_user_liked_stmt = $config->link->query("SELECT * FROM `comments_likes` WHERE `comment_id` = {$commentid} AND `liker_id` = {$_SESSION['user_id']}")->rowCount();
            
            if ($check_if_user_liked_stmt == 0) {
                $num_hearts = 'No Hearts';
            } else if ($check_if_user_liked_stmt == 1) {
                $num_hearts = '1 Heart';
            } else {
                $num_hearts = $check_if_user_liked_stmt . ' Hearts';
            }

            return $num_hearts;
        }

        if ($check_if_user_liked_stmt) {
            $ublike_stmt = $config->link->query("DELETE FROM `comments_likes` WHERE `comment_id` = {$commentid} AND `liker_id` = {$_SESSION['user_id']}");
            echo json_encode(array('btn' => 'Heart', 'num' => get_num_hearts($config, $commentid)));
        } else {
            $insert_like_stmt = $config->link->query("INSERT INTO `comments_likes`(`id`, `liker_id`, `comment_id`, `date`) VALUES (NULL , {$_SESSION['user_id']}, {$commentid}, NOW())");
            echo json_encode(array('btn' => 'UnHeart', 'num' => get_num_hearts($config, $commentid)));
        }
    }
?>