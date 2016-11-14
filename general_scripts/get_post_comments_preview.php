<?php
    function post_comments_preview ($config, $postid) {
        $post_details = $config->link->query("SELECT * FROM `posts` WHERE `id` = {$postid}")->fetch(); 
        $comments_stmt = $config->link->query("SELECT * FROM `posts_comments` WHERE `post_id` = {$postid} AND `active` = 1 ORDER BY `date` DESC LIMIT 0, 5");
        
        while ($comment = $comments_stmt->fetch()) {
            comment_template ($comment, $post_details,  $config);
        }
    }
?>