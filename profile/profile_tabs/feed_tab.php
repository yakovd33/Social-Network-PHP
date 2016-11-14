<?php
    require_once('../config.php');
    require('../templates/feed_template.php');

    include 'general_scripts/new_post_form.php';

    function load_profile_feed_tab($username, $config) {
        $userid = $config->link->query("SELECT `id` FROM `users` WHERE `username` = '{$username}'")->fetch()['id'];        
?>

<?php
        if (!isset($_POST['page'])) {
            $stmt = $config->link->query("
                SELECT * FROM `posts` WHERE `user_id` = {$userid} OR `other_user_wall_id` = {$userid}
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
                SELECT * FROM `posts` WHERE `user_id` = {$userid} OR `other_user_wall_id` = {$userid}
                AND posts.scope <> 'only me'
                AND `deleted` = 0
                ORDER BY posts.id DESC
                LIMIT " . $_POST['page'] * 10 . ", 10
            ");
        }
    ?>
    
    <?php
        echo '<div id="feed-posts-wrap">';
            if ($stmt->rowCount() > 0) {
                feed_posts($stmt, $config);
            } else {
                echo 'No Posts To Display';
            }
        echo '</div>';
    }
?>