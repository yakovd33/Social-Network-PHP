<?php
    require_once('config.php');

    function load_profile_photos_tab($username, $config) {
        $user_id = $config->link->query("SELECT `id` FROM `users` WHERE `username` = '{$username}'")->fetch()['id'];
        
        $get_photos_stmt = $config->link->query("SELECT * FROM `photos` WHERE `user_id` = {$user_id} AND `type` <> 'comment-image'"); 
?>
        <div id="tab-images">
            <?php while ($image = $get_photos_stmt->fetch()) :?>
                <div class="profile-photos-tab-photo">
                    <img src="media/<?php echo $image['path']; ?>">
                    <?php $photo_post_id = $config->link->query("SELECT `id` FROM `posts` WHERE `photo_id` = {$image['id']}")->fetch()['id']; ?>
                        <?php if ($photo_post_id != '') : ?>
                            <div class="photo-hover-info">
                                    <div class="num-hearts photo-hover-item">
                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                        <?php
                                            echo $config->link->query("SELECT * FROM `posts_likes` WHERE `post_id` = {$photo_post_id} AND `active` = 1")->rowCount();
                                        ?>
                                    </div>

                                    <div class="num-comments photo-hover-item">
                                        <i class="fa fa-comments" aria-hidden="true"></i>
                                        <?php
                                            echo $config->link->query("SELECT * FROM `posts_comments` WHERE `post_id` = {$photo_post_id} AND `active` = 1")->rowCount();
                                        ?>
                                    </div>
                            </div>

                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
<?php
    }
?>