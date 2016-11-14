<?php
    require_once('../config.php');
    if ($config->user->loggedin()) {
        $get_friends = $config->link->query("SELECT * FROM `friendships` WHERE `user_one_id` = {$_SESSION['user_id']} OR `user_two_id` = {$_SESSION['user_id']} AND `request_state` = 'approved' ORDER BY `sent_date`");
        $total = array();
        
        while ($friend = $get_friends->fetch()) {
            if ($friend['user_one_id'] == $_SESSION['user_id']) {
                $friend_id = $friend['user_two_id'];
            } else if ($friend['user_two_id'] == $_SESSION['user_id']) {
                $friend_id = $friend['user_one_id'];
            }

            $friend_details = $config->link->query("SELECT * FROM `users` WHERE `id` = {$friend_id}")->fetch();
            
            if (strtotime($friend_details['last login']) >= time()) {
                array_push($total, $friend_details);
            }
        }
                
        foreach ($total as $friend) {
    ?>
            <div class="friend chatbox-trigger" data-username="<?php echo $friend['username']; ?>">
                <div class="wrap">
                    <div class="pp" style="background-image: url('media/<?php echo $config->user->get_other_users_profile_picture($friend['id']); ?>')"></div>
                    <div class="textual-wrap">
                        <div class="fullname"><?php echo $friend['fullname']; ?></div>
                        <div class="logged-tray"></div>
                    </div>
                </div>
            </div>
    <?php
        }
    }
?>