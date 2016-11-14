var new_message_audio = new Audio('media/audio/newm.mp3');

function update_chat_logged_friends_num () {
    $.ajax({
        url: 'chat/get_num_connected_friends.php',
        processData: false,
        contentType: false,
        method: "GET",
        success: function (data) {
            $("#chat #num-logged-friends").text(data);
        }
    });
}

update_chat_logged_friends_num();
setInterval(function () {
    
}, 20000);

function load_active_chatboxes () {
    $.ajax({
        url: 'chat/get_active_chatboxes.php',
        processData: false,
        contentType: false,
        method: "GET",
        success: function (data) {
            $("#chat-boxes").html(data);
            chatbox_actions();
            $.each($(".chat-box .chat-content-wrap"), function () {
                $(this).scrollTop(0, 0)
            });

            roundMessages();
        }
    });
}

load_active_chatboxes();

function load_logged_friends () {
    $.ajax({
        url: 'chat/logged_friends_list.php',
        processData: false,
        contentType: false,
        method: "GET",
        success: function (data) {
            $("#chat #logged-friends").html(data);
            load_chatbox();
            $("#logged-friends").mouseover(function () {
                $(this).addClass('hovered');
            });

            $("#logged-friends").mouseout(function () {
                $(this).removeClass('hovered');
            });
        }
    });
}

function load_chat_list () {
    load_logged_friends();
    setInterval(load_logged_friends(), 20000);
}

load_chat_list();

function load_chatbox () {
    // Logged open
    $.each($(".chatbox-trigger"), function () {
        $(this).unbind("click").click(function () {
            if ($(this).data('username') != undefined) {
                // Check if chatbox is open
                if ($(".chat-box[data-username='" + $(this).data('username') + "']").length == 0) {
                    chatbox_data = new FormData;
                    chatbox_data.append('username', $(this).data('username'));

                    $.ajax({
                        url: 'chat/get_chatbox.php',
                        processData: false,
                        contentType: false,
                        method: "POST",
                        data: chatbox_data,
                        success: function (data) {
                            $("#chat-boxes").append(data);
                            chatbox_actions();
                        }
                    });
                } else {
                    // Make chatbox visible and main
                    $.each($(".chat-box"), function () {
                        $(this).css('order', '1');
                    });

                    $(".chat-box[data-username='" + $(this).data('username') + "']").show().css('order', '1').find(".chatbox-wrap").show();
                }
            }
        });
    });
}

function chatbox_actions () {
    // Toggle chatbox wrap
    $.each($(".chat-box"), function () {
        $(this).keydown(function (e) {
            if (e.which == 27) {
                $(this).find(".chatbox-wrap").hide();
            }
        });

        $(this).find(".name-options").click(function () {
            $(this).parent().find(".chatbox-wrap").toggle();

            // Toggle active chatbox mini
            active_data = new FormData;
            active_data.append('username', $(this).parent().data('username'));

            if ($(this).parent().find(".chatbox-wrap").css('display') == 'none') {
                active_data.append('mini', 1);
                $.ajax({
                    url: 'chat/insert_active_chat_by_username.php',
                    processData: false,
                    contentType: false,
                    method: "POST",
                    data: active_data,
                });
            } else {
                $.ajax({
                    url: 'chat/insert_active_chat_by_username.php',
                    processData: false,
                    contentType: false,
                    method: "POST",
                    data: active_data,
                });
            }
        });

        // Toggle message date
        $(this).find(".message").click(function () {
            $(this).find(".sent-date").fadeToggle();  
        });

        // Close chatbox
        $(this).find(".close-chatbox").click(function () {
            $(this).parent().parent().unbind("click");
            $(this).parent().parent().parent().hide();
            active_data = new FormData;
            active_data.append('username', $(this).parent().parent().parent().data('username'));

            $.ajax({
                url: 'chat/delete_active_chat_by_username.php',
                processData: false,
                contentType: false,
                method: "POST",
                data: active_data,
            });
        });

        // New message
        $(this).find(".new-message-input").unbind("keydown").keydown(function (e) {
            if (e.keyCode == 13 && !e.shiftKey) {
                window.message_input = $(this);
                chatbox_new_message('username', $(this).parent().parent().parent().data('username'));
                $(this).html('');
            }
        });
    });
}

// New message
function chatbox_new_message (type, context) {
	if (type == 'username') {
        window.new_message_content = $(".chat-box[data-username='" + context + "']").find(".new-message .new-message-input").html();
        message_data = new FormData;
        message_data.append('username', context);
        message_data.append('message_text', new_message_content);
        window.new_message_username = context;

        $.ajax({
            url: 'chat/new_message.php',
            processData: false,
            contentType: false,
            method: "POST",
            data: message_data,
            success: function (data) {
                //get_new_chatbox_messages('username', new_message_username);
                update_chatbox_lastupdated('username', window.new_message_username);
                append_inserted_message('username', new_message_username, window.new_message_content, '');
                roundMessages();
            }
        });
    }
}

function append_inserted_message (type, context, message_content, image) {
    if (type == 'username') {
        message = '<div class="message self"><div class="message-content-text">' + message_content + '</div></div>';
        $(".chat-box[data-username='" + context + "'] .chat-content-wrap").append(message);
    }
}

function get_new_chatbox_messages (type, context) {
    last_message_data = new FormData;
    last_message_data.append('username', context);
    last_message_data.append('chat-last-update', $(".chat-box[data-username='" + context + "']").data('lastupdated'));
    window.last_message_data_username = context;

    $.ajax({
        url: 'chat/get_new_messages.php',
        processData: false,
        contentType: false,
        method: "POST",
        data: last_message_data,
        success: function (messages) {
            $(".chat-box[data-username='" + last_message_data_username + "'] .chatbox-wrap .chat-content-wrap").append(messages);
            if (messages != '') {
                $(".chat-box[data-username='" + last_message_data_username + "'] .chatbox-wrap .chat-content-wrap").scrollTop($(".chat-box[data-username='" + last_message_data_username + "'] .chatbox-wrap .chat-content-wrap").scrollTop() + 50);
            }

            if (messages.slice(messages.length - 38) == '<input type="hidden" value="is_alien">') {
                new_message_audio.play();
            }

            setTimeout(function () {
                $(".chat-box[data-username='" + context + "']").attr('data-lastupdated', Math.floor(Date.now() / 1000)).data('lastupdated', Math.floor(Date.now() / 1000));
            }, 1000);

            roundMessages
        }
    });
}

// Update chat lastupdated
function update_chatbox_lastupdated (type, context) {
    if (type == 'all') {
        $.each($(".chat-box"), function () {
            $(this).data('lastupdated', Date.now()).attr('data-lastupdated', Date.now());
        });
    } else if (type == 'username') {
        $(".chat-box[data-username='" + context + "']").attr('data-lastupdated', Math.floor(Date.now() / 1000)).data('lastupdated', Math.floor(Date.now() / 1000));
    }
}

setInterval(function () {
    $.each($(".chat-box"), function () {
        if ($(this).data('username') != undefined) {
            get_new_chatbox_messages('username', $(this).data('username'));
        }
    });

    update_chatbox_lastupdated('all', '');
}, 5000);

// Search
$("#search-in-chat input").keyup(function () {
    var chatQuery = new FormData;
    chatQuery.append('q', $(this).val());

    $.ajax({
        url: 'chat/get_chat_search_results.php',
        processData: false,
        contentType: false,
        method: "POST",
        data: chatQuery,
        success: function (data) {
            $("#chat-search-results").html(data);
            load_chatbox();
            chatbox_actions();
            roundMessages();
        }
    });

    if ($(this).val().length > 0) {
        $("#chat-search-results").show();
        $("#logged-friends").hide();
    } else {
        $("#chat-search-results").hide();
        $("#logged-friends").show();
    }
});

function roundMessages () {
    i = 1;
    $.each($(".message-content-text"), function () {
        if (!$($(".message-content-text")[i]).parent().hasClass("self")) {
            $(this).addClass("last");
            i = 0;
        } else {
            i++;
        }
    });
}