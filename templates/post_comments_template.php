<?php
    function comment_template ($comment, $post_details, $config) {
        $commenter_stmt = $config->link->query("SELECT * FROM `users` WHERE `id` = {$comment['commenter_id']}");
        $commenter_info = $commenter_stmt->fetch();
        
        if ($config->user->loggedin() && $commenter_info['id'] == $_SESSION['user_id']) {
            $self_text = "self";
        } else {
            $self_text = "";
        }
        
        $comment_posted_date = $config->time_to_text($comment['date']);
    ?>
            
        <div class="comment <?php echo $self_text; ?>" data-commentid="<?php echo $comment['id']; ?>">
            <div class="comment-options"></div>
            <a href="profile.php?username=<?php echo $commenter_info['username']; ?>"><div class="user-picture"><img src="media/<?php echo $config->user->get_other_users_profile_picture($comment['commenter_id']); ?>"></div></a>
            
            <div class="comment-wrap">                                            
                <div class="commenter-info">
                    <div class="textual">
                        <a href="profile.php?username=<?php echo $commenter_info['username']; ?>"><div class="fullname"><?php echo $commenter_info['fullname']; ?></div></a>
                    </div>
                </div>
                
                <div class="comment-content">
                    <?php echo $comment['comment']; ?>
                    <?php if ($comment['photo_id'] != NULL) : ?>
                        <div class="comment-photo-wrap">
                            <?php
                                $photo_path = $config->link->query("SELECT `path` FROM `photos` WHERE `id` = {$comment['photo_id']}")->fetch()['path'];
                            ?>
                            <img class="post-photo" src="media/<?php echo $photo_path; ?>">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="comment-actions">
                    <div class="posted-date"><a href="post.php?user_id=<?php echo $commenter_info['id']; ?>&phash=<?php echo $post_details['post_hash']; ?>&comment_i=<?php echo $comment['post_comments_index']; ?>"><?php echo $comment_posted_date; ?></a></div>
                    <?php if ($config->user->loggedin()) : ?>
                    <div class="heart">
                        <?php
                            $check_if_user_liked_stmt = $config->link->query("SELECT * FROM `comments_likes` WHERE `comment_id` = {$comment['id']} AND `liker_id` = {$_SESSION['user_id']}")->rowCount();
                            if ($check_if_user_liked_stmt == 1) {
                                $comment_heart_state = 'UnHeart';
                            } else {
                                $comment_heart_state = 'Heart';
                            }
                        ?>

                        <span class="heart-this-comment"><?php echo $comment_heart_state; ?></span>
                        <?php endif; ?>
                        
                        <?php
                            $get_comment_num_hearts_stmt = $config->link->query("SELECT * FROM `comments_likes` WHERE `comment_id` = {$comment['id']}")->rowCount();
                            if ($get_comment_num_hearts_stmt == 0) {
                                $comment_num_hearts = 'No Hearts';
                            } else {
                                if ($get_comment_num_hearts_stmt == 1) {
                                    $comment_num_hearts = $get_comment_num_hearts_stmt . ' Heart';
                                } else {
                                    $comment_num_hearts = $get_comment_num_hearts_stmt . ' Hearts';
                                }
                            }
                        ?>
                        <div class="num-hearts">                    
                            <span class="sum-hearters"><?php echo $comment_num_hearts; ?></span> <i class="fa fa-heart" aria-hidden="true"></i>
                            <div class="post-hearts-list" style="display: none;">
                            <h6>Who Hearted This</h6>
                                <div class="post-heart-user">Yakov Shitrit</div>
                                <div class="post-heart-user"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <div class="comment-comments">
                <div class="comment-comment">
                    
                </div>
            </div>
        </div>
<?php
    }
?>