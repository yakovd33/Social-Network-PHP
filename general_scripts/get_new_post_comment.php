<?php
    require_once('../config.php');
    
    $comment_stmt = $config->link->query("SELECT * FROM `posts_comments` WHERE `commenter_id` = " . $_SESSION['user_id'] . " AND `active` = 1 ORDER BY `date` DESC LIMIT 1");
    $comment_details = $comment_stmt->fetch();
    $comment_posted_date = 'Just Now';
    $commenter_stmt = $config->link->query("SELECT * FROM `users` WHERE `id` = {$comment_details['commenter_id']}");
    $commenter_info = $commenter_stmt->fetch();
    $post_details = $config->link->query("SELECT * FROM `posts` WHERE `id` = {$comment_details['post_id']}")->fetch();
?>

<div class="comment self" style="animation-name: fadeIn; animation-duration: 2s;" data-commentid="<?php echo $comment_details['id']; ?>">
    <div class="comment-options"></div>
    <a href="profile.php?username=<?php echo $commenter_info['username']; ?>"><div class="user-picture"><img src="media/<?php echo $config->user->get_other_users_profile_picture($_SESSION['user_id']); ?>"></div></a>
    
    <div class="comment-wrap">                                            
        <div class="commenter-info">
            <div class="textual">
                <a href="profile.php?username=<?php echo $commenter_info['username']; ?>"><div class="fullname"><?php echo $commenter_info['fullname']; ?></div></a>
            </div>
        </div>
        
        <div class="comment-content">
            <?php echo $comment_details['comment']; ?>
            <?php if ($comment_details['photo_id'] != NULL) : ?>
                <div class="comment-photo-wrap">
                    <?php
                        $photo_path = $config->link->query("SELECT `path` FROM `photos` WHERE `id` = {$comment_details['photo_id']}")->fetch()['path'];
                    ?>
                    <img class="post-photo" src="media/<?php echo $photo_path; ?>">
                </div>
            <?php endif; ?>
        </div>
        
        <div class="comment-actions">
            <div class="posted-date"><a href="post.php?user_id=<?php echo $commenter_info['id']; ?>&phash=<?php echo $post_details['post_hash']; ?>&comment_i=<?php echo $comment_details['post_comments_index']; ?>"><?php echo $comment_posted_date; ?></a></div>
            <div class="heart">
                <?php
                    $check_if_user_liked_stmt = $config->link->query("SELECT * FROM `comments_likes` WHERE `comment_id` = {$comment_details['id']} AND `liker_id` = {$_SESSION['user_id']}")->rowCount();
                    if ($check_if_user_liked_stmt == 1) {
                        $comment_heart_state = 'UnHeart';
                    } else {
                        $comment_heart_state = 'Heart';
                    }
                ?>
                <span class="heart-this-comment"><?php echo $comment_heart_state; ?></span>
                
                <div class="num-hearts">                    
                    <span class="sum-hearters">No Hearts</span> <i class="fa fa-heart" aria-hidden="true"></i>
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