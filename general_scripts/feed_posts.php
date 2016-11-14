<?php
    require_once('../config.php');
    require('../templates/feed_template.php');
    include 'load_repost.php';
    if ($config->user->getUserInfo()["show_comments_preview"] == 1) {
        require_once('get_post_comments_preview.php');
    }

    if ($config->user->loggedin()) {
        if (!isset($_POST['page'])) {
            $stmt = $config->link->query("
                SELECT * FROM `posts` WHERE `user_id` IN
                (SELECT `user_two_id` FROM `friendships` WHERE `user_one_id` = {$_SESSION['user_id']}
                UNION SELECT `user_one_id` FROM `friendships` WHERE `user_two_id` = {$_SESSION['user_id']} UNION SELECT {$_SESSION['user_id']} from friendships)
                AND `deleted` = 0
                ORDER BY posts.id DESC
                LIMIT 0, 10
            ");
        ?>
            <div id="user-new-posts">
            
            </div>
        <?php
        } else {
            $stmt = $config->link->query("
                SELECT * FROM `posts` WHERE `user_id` IN
                (SELECT `user_two_id` FROM `friendships` WHERE `user_one_id` = {$_SESSION['user_id']}
                UNION SELECT `user_one_id` FROM `friendships` WHERE `user_two_id` = {$_SESSION['user_id']} UNION SELECT {$_SESSION['user_id']} from friendships)
                AND `deleted` = 0
                ORDER BY posts.id DESC
                LIMIT " . $_POST['page'] * 10 . ", 10
            ");
        }
    ?>
    
    
    <?php
        if ($stmt->rowCount() > 0) {
            feed_posts($stmt, $config);
        }
        } else {
            echo 'No Posts To Display <a href="discover.php" class="discover">Discover</a>';
        }
?>