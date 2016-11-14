<?php require_once('config.php'); ?>
<?php if ($config->user->loggedin()) : ?>
    <link rel="stylesheet" href="stylesheets/chat/chat.css">

    <div id="chat">
        <div id="chat-top">
            <div id="search-in-chat"><i class="fa fa-search" aria-hidden="true"></i><input type="text"></div>
        </div>
        
        <h6 class="chat-sub-title">Logged Friends <span id="num-logged-friends"></span></h6>
        <div id="logged-friends"></div>
        <div id="chat-search-results" style="display: none"></div>
    </div>
    
    <div id="chat-boxes">
    </div>

    <script src="scripts/chat/chat.js"></script>
<?php endif; ?>