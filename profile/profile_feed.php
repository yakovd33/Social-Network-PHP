<?php
    require_once('../config.php');
    require('../templates/feed_template.php');
    
    if (isset($_POST['userid'])) :
        $userid = $_POST['userid'];
        if ($config->user->loggedin()) {
            if (!isset($_POST['page'])) {
                $stmt = $config->link->query("
                    SELECT * FROM `posts` WHERE (`user_id` = {$userid} OR `other_user_wall_id` = {$userid})
                    AND posts.scope <> 'only me'
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
                    SELECT * FROM `posts` WHERE (`user_id` = {$userid} OR `other_user_wall_id` = {$userid})
                    AND posts.scope <> 'only me'
                    AND `deleted` = 0
                    ORDER BY posts.id DESC
                    LIMIT " . $_POST['page'] * 10 . ", 10
                ");
            }
        ?>
        
        <?php
            if ($stmt->rowCount() > 0) {
                feed_posts($stmt, $config);
            } else {
                echo 'No Posts To Display';
            }
        }
    endif;
?>