<?php
    require_once('../config.php');
?>
    
    <?php
    function load_post ($postid, $config) {
            $post = $config->link->query("SELECT * FROM `posts` WHERE `id` = {$postid}")->fetch();
            $poster_info = $config->link->query("SELECT * FROM `users` WHERE `id` = {$post['user_id']}")->fetch();
            
            if ($post['scope'] == 'only me' && $poster_info['id'] == $_SESSION['user_id'] || $post['scope'] != 'only me') :
                if (strtotime($post['posted date']) - 10800 > time() - 60) {
                    $new_self_post = 'new-self-post';
                    $_SESSION['new_post_id'] = '';
                } else {
                    $new_self_post = '';
                }
                
                if ($post['user_id'] == $_SESSION['user_id']) {
                    $self_post = 'self';
                } else {
                    $self_post = '';
                }
            endif;
            
            $post_posted_date = $config->time_to_text($post['posted date']);
?>
            <div class="repost-wrap post-wrap <?php echo $self_post . " " . $new_self_post; ?>" data-postid="<?php echo $post['id']; ?>">
                <div class="post-user-info">
                    
                    <div class="user-profile-picture">
                        <a href="profile.php?username=<?php echo $poster_info['username'] ?>"><img src="media/<?php echo $config->user->get_other_users_profile_picture($post['user_id']); ?>"></a>
                    </div>
                    <div class="post-user-details">
                        <a href="profile.php?username=<?php echo $poster_info['username']; ?>"><span class="fullname"><?php echo $poster_info['fullname'] ?></span></a>
                        <a href="post.php?user_id=<?php echo $post['user_id']; ?>&phash=<?php echo $post['post_hash']; ?>"><span class="posted_date"><?php echo $post_posted_date; ?></span></a>
                    </div>

                    <?php
                        $check_if_liked_repost = $config->link->query("SELECT * FROM `posts_likes` WHERE `liker_id` = {$_SESSION['user_id']} AND `post_id` = {$postid} AND `active` = 1")->rowCount();
                        if ($check_if_liked_repost == 1) {
                            $like_state = 'liked';
                        } else {
                            $like_state = '';
                        }
                    ?>

                    <div class="repost-actions" title="Like This Post">
                        <div class="heart-this-repost <?php echo $like_state; ?>">
                            <i class="fa fa-heart" aria-hidden="true"></i>
                        </div>

                        <div class="enter-repost" title="Enter This Post">
                            <a href="post.php?user_id=<?php echo $post['user_id']; ?>&phash=<?php echo $post['post_hash']; ?>"><i class="fa fa-external-link-square" aria-hidden="true"></i></a>
                        </div>

                        <div class="repost-this-repost" title="Repost">
                            <i class="fa fa-retweet" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                    
                    <div class="post-options">
                        
                    </div>
                    
                    <div class="post-content-wrap">
                        <div class="post-text">
                            <?php echo $post['post_text'] ?>
                        </div>

                        <?php if ($post['photo_id'] != NULL) : ?>
                            <?php
                                $post_photo = $config->link->query("SELECT `path` FROM `photos` WHERE `id` = {$post['photo_id']}")->fetch()['path'];
                            ?>
                            <div class="post-photo-wrap">
                                <img class="post-photo" src="media/<?php echo $post_photo; ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
<?php 
    }

    if (isset($_POST['postid_toload'])) {
        load_post($_POST['postid_toload'], $config);
    }
?>