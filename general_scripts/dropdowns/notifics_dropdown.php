<?php
    require_once('../../config.php');
    
    if ($config->user->loggedin()) {
        $total = array();
        
        if (isset($_POST['page'])) {
            $page = $_POST['page'];
            
            if ($page == 1) {
                $start = 0;
            } else {
                $start = $page * 15;
            }
            
            $stmt = $config->link->query("SELECT * FROM `notifications` WHERE `to_user_id` = {$_SESSION['user_id']} LIMIT {$start}, 15");
            
            while ($notific = $stmt->fetch()) {
                $from_notific_details = $config->link->query("SELECT * FROM `users` WHERE `id` = {$notific['last_from_user_id']}")->fetch();
                if ($notific['type'] == 'like_on_post') {
                    $num_likes_minus_last = $get_post_num_likes - 1;
                    $post_details = $config->link->query("SELECT * FROM `posts` WHERE `id` = {$notific['post_id']}")->fetch();
                                                                                    
                    if ($get_post_num_likes <= 1) {
                        $and_other_likes = 'Liked A Post Of You.';
                    } else {
                        $and_other_likes = 'And ' . $num_likes_minus_last . ' Other People Hearted A Post Of You.';
                    }
        ?>
                    <a href="post.php?user_id=<?php echo $post_details['user_id']; ?>&phash=<?php echo $post_details['post_hash']; ?>">
                        <div class="notification like-notific">
                            <div class="notific-from-details">
                                <div class="pp" style="background-image: url('media/<?php echo $config->user->get_other_users_profile_picture($notific['last_from_user_id']); ?>');"></div>
                                
                                <div class="textual-details">
                                    <div class="fullname"><?php echo $from_notific_details['fullname'] ?></div>
                                    <span class="notific-details"><?php echo $and_other_likes; ?></span>
                                    <div class="date"><?php echo $config->time_to_text($notific['date']); ?></div>
                                </div>                                
                            </div>
                        </div>
                    </div>
        <?php
                } else if ($notific['type'] == '') {

                }
            }

            if ($stmt->rowCount() == 0) {
                echo '<div id="no-notifics-to-show">There are no new notifications.</div>';
            }
        }
    }
?>