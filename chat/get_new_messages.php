<?php
    require_once('../config.php');
    if (isset($_POST['username']) && isset($_POST['chat-last-update'])) :
        $user_one_id = $_SESSION['user_id'];
        $user_two_username = $_POST['username'];
        $user_two_id = $config->link->query("SELECT * FROM `users` WHERE `username` = '{$user_two_username}'")->fetch()['id'];
        $last_update_time = $_POST['chat-last-update'];
        $new_messages_stmt = $config->link->query("SELECT * FROM `chat_messages`
        WHERE (`from_id` = {$user_one_id} && `to_id` = {$user_two_id}
        OR `from_id` = {$user_two_id} && `to_id` = {$user_one_id})
        AND `sent_date` >= FROM_UNIXTIME({$last_update_time}) ORDER BY `id`");

        $is_alien_messages_only = false;

        while ($message = $new_messages_stmt->fetch()) :
            if (!$is_alien_messages_only && $message['from_id'] != $_SESSION['user_id']) {
                $is_alien_messages_only = true;
            }
?>
                <div class="message <?php if ($message['from_id'] == $_SESSION['user_id']) {echo 'self';} ?>">
                <div class="message-content-text">
                    <?php echo $message['message']; ?>
                </div>
                <div class="sent-date">
                    <?php echo $config->time_to_text($message['sent_date']); ?>
                </div>
            </div>
<?php
            if ($is_alien_messages_only) {
                echo '<input type="hidden" value="is_alien">';
            }
        endwhile;

        log_m($log, "hey");
    endif;
?>