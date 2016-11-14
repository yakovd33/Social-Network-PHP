<?php
    require_once('../config.php');

    if (isset($_POST['postid'])) {
        // Check if repost post isn't a repost itself
        $repost_post_is_repost = $config->link->query("SELECT `repost_id` FROM `posts` WHERE `id` = {$_POST['postid']}");
        if ($repost_post_is_repost == NULL) {
            echo $postid = $_POST['postid'];
        } else {
            $postid = $repost_post_is_repost;
        }

        $postid = $_POST['postid'];
        $post_info = $config->link->query("SELECT * FROM `posts` WHERE `id` =  {$postid}")->fetch();
        $poster_info = $config->link->query("SELECT * FROM `users` WHERE `id` = {$post_info['user_id']}")->fetch();

        $briefed_text = substr($post_info['post_text'], 0, 50);
        if (strlen($briefed_text) >= 50) {
            $briefed_text .= "...";
        }
?>

        <div id="repost-box" data-postid="<?php echo $postid; ?>">
            <div id="repost-wrap">
                <div id="repost-top">
                    <div id="close-repost" class="popup-close">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </div>
                    
                    <div id="repost-poster-fullname">
                        Repost A Post By <?php echo $poster_info['fullname']; ?>
                    </div>
                </div>

                <div id="post-text">
                    <div id="repost-editor-wrap">
            <div id="post-input-wrap">
                    <div id="user-image">
                        <img src="media/pps/6fc0e8eb3dc7d34ef073c82de1327dbd.jpg">
                    </div>
                <div id="repost-text-brief-input">
                    <div id="post-input" placeholder="Share Your Thoughts..." contenteditable="plaintext-only"></div>
                    <div id="repost-brief">
                        <a href="profile.php?username=<?php echo $poster_info['username']; ?>"><div id="repost-poster-pp" style="background-image: url(media/<?php echo $config->user->get_other_users_profile_picture($post_info['id']); ?>)"></div></a>
                        <div id="repost-sum">
                            <div id="repost-sum-poster-fullname">
                                <a href="profile.php?username=<?php echo $poster_info['username']; ?>"><?php echo $poster_info['fullname']; ?></a>
                            </div>

                            <div id="repost-sum-text">
                                <?php echo $briefed_text; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <ul id="new-post-tags">
                <div id="ready-tags">
                </div>
                <li id="new-tag" contenteditable="plaintext-only"></li>
            </ul>
            
            
            <div id="new-post-actions">
                
                <div id="submition-actions">
                    <div id="select-post-scope">
                        <div id="selected-scope"><span class="scope-list-item"><i class="fa fa-globe"></i><span class="selected-scope-text">Public</span></span><i class="fa fa-angle-down"></i></div>
                        <div id="list">
                            <h6>Who Can See This?</h6>
                            <span class="scope-list-item"><i class="fa fa-globe"></i><span class="selected-scope-text">Public</span></span>
                            <span class="scope-list-item"><i class="fa fa-users"></i><span class="selected-scope-text">Friends</span></span>
                            <span class="scope-list-item"><i class="fa fa-lock"></i><span class="selected-scope-text">Only Me</span></span>
                        </div>
                    </div>
                    
                    <input type="submit" id="submit-new-post" value="Post">
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    
<?php
    }
?>