<?php
function feed_post_template ($post, $time, $config) {
    $poster_info = $config->link->query("SELECT * FROM `users` WHERE `id` = {$post['user_id']}")->fetch();

    if ($post['scope'] == 'only me' && $poster_info['id'] == $_SESSION['user_id'] || $post['scope'] != 'only me') :
        if (strtotime($post['posted date']) - 10800 > time() - 60) {
            $new_self_post = 'new-self-post';
            $_SESSION['new_post_id'] = '';
        } else {
            $new_self_post = '';
        }
        
        if ($config->user->loggedin() && $post['user_id'] == $_SESSION['user_id']) {
            $self_post = 'self';
        } else {
            $self_post = '';
        }
    
    $post_posted_date = $time;
?>
    <div class="post-wrap <?php echo $self_post . " " . $new_self_post; ?>" data-postid="<?php echo $post['id']; ?>" <?php if ($time == 'Just Now') { echo 'style="animation-name: fadeIn; animation-duration: 2s;"'; } ?>>
                    <div class="post-user-info">
                        
                        <div class="user-profile-picture">
                            <a href="profile.php?username=<?php echo $poster_info['username'] ?>"><img src="media/<?php echo $config->user->get_other_users_profile_picture($post['user_id']); ?>"></a>
                        </div>
                        <div class="post-user-details">
                            <div>
                                <a href="profile.php?username=<?php echo $poster_info['username']; ?>"><span class="fullname"><?php echo $poster_info['fullname'] ?></span></a>
                                <?php if ($post['other_user_wall_id'] != NULL) : ?>
                                    <?php
                                        $host_details = $config->user->get_other_user_info_by_id($post['other_user_wall_id']);
                                    ?>
                                    <i class="fa fa-angle-right post-hosted" aria-hidden="true"></i>
                                    <a href="profile.php?username=<?php echo $host_details['username']; ?>"><span class="fullname"><?php echo $host_details['fullname'] ?></span></a>
                                <?php endif; ?>
                            </div>
                            <a href="post.php?user_id=<?php echo $post['user_id']; ?>&phash=<?php echo $post['post_hash']; ?>"><span class="posted_date"><?php echo $post_posted_date; ?></span></a>
                        </div>

                        <div class="post-options">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                            <div class="post-options-list">
                                <div class="post-options-list-item" id="post-options-delete-option">
                                    <div class="desc">Delete</div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                        <div class="post-content-wrap">
                            <?php
                                $post_text = $post['post_text'];
                            ?>
                            <div class="post-text" <?php if (ord(substr($post_text, 1)) >= 145 && ord(substr($post_text, 1)) <= 170) {echo 'dir="RTL"';} ?>>
                                <?php echo nl2br($post_text); ?>
                            </div>
                            <?php 
                                if ($post['repost_id'] != NULL) {
                                    load_post($post['repost_id'], $config);
                                }
                            ?>

                            <?php if ($post['photo_id'] != NULL) : ?>
                                <?php
                                    $post_photo = $config->link->query("SELECT `path` FROM `photos` WHERE `id` = {$post['photo_id']}")->fetch()['path'];
                                ?>
                                <div class="post-photo-wrap">
                                    <img class="post-photo" src="media/<?php echo $post_photo; ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="post-actions">
                            <?php
                                if ($config->user->loggedin()) {
                                    $user_liked = $config->link->query("SELECT * FROM `posts_likes` WHERE `liker_id` = {$_SESSION['user_id']} AND `post_id` = {$post['id']} AND `active` = 1");
                                }

                                if ($config->user->loggedin() && $user_liked->rowCount() == 1) {
                                    $did_user_likes = true;
                                    $heart_class = "fa-heart";
                                } else {
                                    $did_user_likes = false;
                                    $heart_class = "fa-heart-o";
                                }

                            if ($config->user->loggedin()) : ?>
                                <div class="heart <?php if ($did_user_likes) { echo ' clicked'; } ?>"><i class="fa <?php echo $heart_class; ?>" aria-hidden="true"></i> Heart</div>
                                <div class="comment"><i class="fa fa-comment" aria-hidden="true"></i> Comment</div>
                                <?php if ($post['type'] != 'repost') : ?>
                                    <div class="repost"><i class="fa fa-retweet" aria-hidden="true"></i> Repost</div>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                        
                        <div class="post-comments-section">
                            <div class="post-details">
                                <div class="num-hearts">
                                    <?php
                                        $hearts_stmt = $config->link->query("SELECT * FROM `posts_likes` WHERE `post_id` = {$post['id']} AND `active` = 1");
                                        if ($hearts_stmt->rowCount() > 0) {
                                            if ($hearts_stmt->rowCount() == 1) {
                                                $num_hearts = $hearts_stmt->rowCount() . ' Heart';
                                            } else {
                                                $num_hearts = $hearts_stmt->rowCount() . ' Hearts';
                                            }
                                        } else {
                                            $num_hearts = 'No Hearts';
                                        }
                                    ?>
                                    
                                    <span class="sum-hearters"><?php echo $num_hearts; ?></span> <i class="fa fa-heart" aria-hidden="true"></i>
                                    <?php if ($hearts_stmt->rowCount() > 0) : ?>
                                        <div class="post-hearts-list">
                                            <h6>Who Hearted This</h6>
                                            <?php while ($hearter = $hearts_stmt->fetch()) :?>
                                                <?php $hearter_fullname = $config->link->query("SELECT * FROM `users` WHERE `id` = {$hearter['liker_id']}")->fetch()['fullname']; ?>
                                                <div class="post-heart-user"><?php echo $hearter_fullname; ?></div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php
                                    $post_num_comments = $config->link->query("SELECT * FROM `posts_comments` WHERE `post_id` = {$post['id']} AND `active` = 1")->rowCount();
                                    if ($post_num_comments == 0) {
                                        $post_num_comments_display = 'No Comments';
                                    } elseif ($post_num_comments == 1) {
                                        $post_num_comments_display = '1 Comment';
                                    } else {
                                        $post_num_comments_display = $post_num_comments . ' Comments';
                                    }
                                ?>
                                <div class="num-comments">
                                    <span class="sum-commenters"><?php echo $post_num_comments_display; ?></span> <i class="fa fa-comments" aria-hidden="true"></i>
                                    <?php if ($post_num_comments > 0) : ?>
                                        <div class="post-commenters-list">
                                            <h6>Who Commented On This</h6>
                                            <?php
                                                $post_comments = $config->link->query("SELECT DISTINCT `commenter_id` FROM `posts_comments` WHERE `post_id` = {$post['id']} AND `active` = 1");
                                            ?>
                                            <?php while ($comment = $post_comments->fetch()) : ?>
                                                <?php $commenter_name = $config->link->query("SELECT `fullname` FROM `users` WHERE `id` = {$comment['commenter_id']}")->fetch()['fullname']; ?>
                                                <div class="post-commenter-user"><?php echo $commenter_name; ?></div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="comments-wrap" <?php if ($config->user->loggedin() && $config->user->getUserInfo()["show_comments_preview"] == 1) { echo 'style="display: block !important"'; } ?>>
                                 <?php 
                                    // New Comment
                                    if ($config->post->is_post_commentable($post['id'])) :
                                 ?>
                                 
                                <div class="new-comment-wrap">
                                    <div class="user-image grid__col grid__col--1-of-12">
                                        <img src="media/<?php echo $config->user_profile_picture ?>">
                                    </div>
                                    
                                    <form class="new-comment-input grid__col grid__col--11-of-12">
                                        <div contenteditable="plaintext-only" class="comment-text-input"></div>
                                        
                                        <input type="file" class="comment-additional-image">
                                        <div class="trigger-comment-additional-image">
                                            <i class="fa fa-camera-retro" aria-hidden="true"></i>
                                        </div>
                                        
                                        <div class="additional-image-preview">
                                            <div class="comment-additional-image">
                                                <div class="preview-wrap">
                                                    <span class="diselect-selected-pictures"><i class="fa fa-times"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php elseif ($config->user->loggedin()) : ?>
                                    <div class="comments-disabled-messsage">Comments are disabled for this post.</div>
                                <?php endif; ?>

                                
                                <!-- Comment Section -->
                                <div class="post-comment-section" data-page="1">
                                    <?php
                                        $commenter_info = $config->link->query("SELECT * FROM `users` WHERE `id` = 1")->fetch();
                                        $comment_posted_date = $config->time_to_text('2016-04-24 18:08:56');
                                    ?>
                                    
                                    <?php 
                                        if ($config->user->loggedin() && $config->user->getUserInfo()["show_comments_preview"]) {
                                            post_comments_preview($config, $post['id']);
                                        }
                                    ?>
                                </div>
                                <div class="comments-actions">
                                    <?php if ($post_num_comments > 5) : ?>
                                        <div class="comments-action comment-show-more"><span>Show More</span></div>
                                        <div class="comments-action comment-show-all"><span>Show All</span></div>    
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
    endif;
}