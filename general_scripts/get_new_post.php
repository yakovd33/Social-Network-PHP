<?php
    require_once('../config.php');
    require('../templates/feed_post_template.php');
    
    $post_stmt = $config->link->query("SELECT * FROM `posts` WHERE `user_id` = " . $_SESSION['user_id'] . " ORDER BY `posts`.`id` DESC LIMIT 1");
    $post_details = $post_stmt->fetch();
    $post_details_posted_date = 'Just Now';

    feed_post_template($post_details, $post_details_posted_date, $config);
?>