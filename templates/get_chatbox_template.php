<?php

function get_chatbox_temp ($type, $context, $config, $is_mini) {
    if ($type == 'username') {
        $user_details = $config->link->query("SELECT * FROM `users` WHERE `username` = '{$context}'")->fetch();
    }

    $chat_messages_stmt = $config->link->query("
        SELECT * FROM (
            SELECT * FROM `chat_messages`
            WHERE `from_id` = {$_SESSION['user_id']} AND `to_id` = {$user_details['id']}
            OR `from_id` = {$user_details['id']} AND `to_id` = {$_SESSION['user_id']}
            ORDER BY `id` DESC
            LIMIT 0, 10
        ) AS T1 ORDER BY id ASC
    ");
?>
        <div class="chat-box" data-username="<?php echo $user_details['username']; ?>" data-lastupdated="<?php echo time(); ?>">
            <div class="name-options">
                <div class="chat-name">
                    <a href="profile.php?username=<?php echo $user_details['username']; ?>"><?php echo $user_details['fullname']; ?></a>
                    <?php if ($config->user->is_other_user_loggedin($user_details['id'])) : ?>
                        <div class="logged-tray logged"></div>
                    <?php endif; ?>
                </div>
                
                <div class="chatbox-options">
                    <div class="option-menu">
                        
                    </div>
                    
                    <div class="close-chatbox">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            
            <div class="chatbox-wrap" style="<?php if ($is_mini) {echo 'display: none';} ?>">
                <div class="chat-content-wrap">
                    <?php while ($message = $chat_messages_stmt->fetch()) : ?>
                        <div class="message <?php if ($message['from_id'] == $_SESSION['user_id']) {echo 'self';} ?>">
                            <div class="message-content-text">
                                <?php echo $message['message']; ?>
                            </div>
                            <div class="sent-date">
                                <?php echo $config->time_to_text($message['sent_date']); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>

                    <?php if ($chat_messages_stmt->rowCount() == 0) : ?>
                        <h6>No Messages.</h6>
                    <?php endif; ?>
                </div>
                <div class="new-message">
                    <div contenteditable="plaintext-only" class="new-message-input" placeholder="Type a new message..."></div>
                    <div class="message-additional">
                        <input type="file" class="message-additional-image" style="display: none;">
                        <div class="message-additional-item trigger-message-additional-image"><i class="fa fa-camera-retro" aria-hidden="true"></i></div>
                        <div class="message-additional-item emoji-selection-trigger"><i class="fa fa-smile-o" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}
?>