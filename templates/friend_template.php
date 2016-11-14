<?php
    function fetch_friend ($friendship, $userid, $config) {
        if ($friendship['user_one_id'] == $userid) {
            $friend_id = $friendship['user_two_id'];
        } else {
            $friend_id = $friendship['user_one_id'];
        }

        $friend_details = $config->link->query("SELECT * FROM `users` WHERE `id` = {$friend_id}")->fetch();
?>
        <div class="friend">
            <a href="profile.php?username=<?php echo $friend_details['username']; ?>">
                <div class="pp">
                    <img src="media/<?php echo $config->user->get_other_users_profile_picture($friend_details['id']); ?>">
                </div>
            </a>
            <div class="text-info">
                <a class="fullname" href="profile.php?username=<?php echo $friend_details['username']; ?>"><?php echo $friend_details['fullname']; ?></a>
                <?php if ($friend_id != $_SESSION['user_id']) : ?>
                    <div class="num_mutual_friends"><?php echo $config->user->get_mutual_friends($friend_details['id']); ?></div>
                    <div class="friends-since">Friends Since <?php echo date("m/d/y", strtotime($config->user->get_friendship_date_with_user($friend_id))); ?></div>
                <?php endif; ?>
            </div>
        </div>
<?php
    }
?>