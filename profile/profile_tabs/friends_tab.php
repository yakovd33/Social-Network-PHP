<?php
    require('templates/friend_template.php');

    function load_profile_friends_tab($username, $config) {
        $userid = $config->link->query("SELECT `id` FROM `users` WHERE `username` = '{$username}'")->fetch()['id'];

        $basic_stmt = "SELECT * FROM `friendships` WHERE `user_one_id` = {$userid} OR `user_two_id` = {$userid} AND `request_state` = 'approved'";

        $get_all_friends_stmt = $config->link->query($basic_stmt);
        $friendships = $get_all_friends_stmt->fetchAll();

        $num_total_friends = $get_all_friends_stmt->rowCount();
        $num_mutual_friends = 0;
        $num_family_friends = 6;

        foreach ($friendships as $friendship) {
            if ($friendship['user_one_id'] == $userid) {
                $friend_id = $friendship['user_two_id'];
            } else {
                $friend_id = $friendship['user_one_id'];
            }

            if ($config->check_friends_with($friend_id)) {
                $num_mutual_friends++;
            }
        }

?>
        <div id="friends-tab-tabs">
            <div class="tab-choice" id="friend-tab-sub-tab-all-friends">All Friends <span class="tab-trigger-additional-info"><?php echo $num_total_friends; ?></span></div>
            <?php if ($userid != $_SESSION['user_id']) : ?>
                <div class="tab-choice" id="friend-tab-sub-tab-mutual-friends">Mutual Friends <span class="tab-trigger-additional-info"><?php echo $num_mutual_friends; ?></span></div>
            <?php endif; ?>
            <div class="tab-choice" id="friend-tab-sub-tab-family-friends">Family <span class="tab-trigger-additional-info"><?php echo $num_family_friends; ?></span></div>
        </div>
        
        <div class="friends-tab-subtab" id="friends-tab-all-subtab" <?php if (isset($_GET['subtab']) && $_GET['subtab'] != 'all') { echo 'style="display: none;"'; } ?>>
<?php
            foreach ($friendships as $friendship) {
                if ($friendship['user_one_id'] == $userid) {
                    $friend_id = $friendship['user_two_id'];
                } else {
                    $friend_id = $friendship['user_one_id'];
                }
                
                $friend_details = $config->link->query("SELECT * FROM `users` WHERE `id` = {$friend_id}")->fetch();

                fetch_friend($friendship, $userid, $config);
            }
?>
            </div>

            <?php if ($userid != $_SESSION['user_id']) : ?>
                <div class="friends-tab-subtab" id="friends-tab-mutual-subtab" <?php if (!isset($_GET['subtab']) || $_GET['subtab'] != 'mutual') { echo 'style="display: none;"'; } ?>>
<?php
                    foreach ($friendships as $friendship) {
                        if ($friendship['user_one_id'] == $userid) {
                            $friend_id = $friendship['user_two_id'];
                        } else {
                            $friend_id = $friendship['user_one_id'];
                        }

                        $friend_details = $config->link->query("SELECT * FROM `users` WHERE `id` = {$friend_id}")->fetch();

                        if ($config->check_friends_with($friend_id)) {
                            fetch_friend($friendship, $userid, $config);
                        }
                    }
?>
            </div>
        <?php endif; ?>
<?php
    }
?>