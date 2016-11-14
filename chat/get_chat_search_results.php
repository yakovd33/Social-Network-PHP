<?php
    require_once('../config.php');

    if (isset($_POST['q'])) {
        $search_query = $_POST['q'];
        $total_results = 0;

        $get_users_matching_id_stmt = $config->link->query("SELECT * FROM `users` WHERE `username` LIKE '%{$search_query}' OR `username` LIKE '{$search_query}%'");
        
        // Find chats with friends
        while ($user = $get_users_matching_id_stmt->fetch()) {
            if ($user['id'] != $_SESSION['user_id']) {
                if ($config->check_friends_with($user['id'])) {
                    $total_results++;
            ?>
                    <div class="friend chatbox-trigger" data-username="<?php echo $user['username']; ?>">
                        <div class="wrap">
                            <div class="pp" style="background-image: url('media/<?php echo $config->user->get_other_users_profile_picture($user['id']); ?>')"></div>
                            <div class="textual-wrap">
                                <div class="fullname"><?php echo $user['fullname']; ?></div>
                                <?php
                                    if ($config->user->is_other_user_loggedin($user['id'])) {
                                ?>
                                        <div class="logged-tray"></div>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
        }

        if ($total_results == 0) {
            echo '<h6>No Results</h6>';
        }
    }
?>