<?php
require('feed_post_template.php');

function feed_posts ($posts_stmt, $config) {
    while ($post = $posts_stmt->fetch()) {
        feed_post_template ($post, $config->time_to_text($post['posted date']), $config);
?>
                <?php if ($config->user->getUserInfo()["show_comments_preview"] == 1) : ?>
                    <script>
                        comments_load_more();
                    </script>
                <?php endif; ?>
<?php
        }
    }
?>