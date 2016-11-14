$(document).ready(function () {    
    $(document).keydown(function (e) {
       if (e.which == 36) {
           $(".sidebar").scrollTop(0);
           $(".sidebar").css('top', '60px');
           
       } else if (e.which == 35) {
           $(".sidebar").scrollTop($(".sidebar").height());
           $(".sidebar").css('top', '60px');
       }
    });
});

$(document).bind('mousewheel', function (e) {
    if ($(".sidebar").innerHeight() >= $(window).height() - 50) {
        if (e.originalEvent.wheelDelta < 0) {
            // Down
            $(".sidebar").scrollTop($(".sidebar").scrollTop() + 10);

            if ($(".sidebar").scrollTop() >= 0) {
                $(".sidebar").css('top', '0px');
            }
        } else {
            // Up
            $(".sidebar").scrollTop($(".sidebar").scrollTop() - 10);
            
            if ($(".sidebar").scrollTop() <= 0) {
                $(".sidebar").css('top', '60px');
            }
        }
    }
});

// Profile Feed
$("#main-profile-picture").mouseover(function () {
   $(this).find("#trigger-image-update").css('bottom', '0px').css('opacity', '1')
});

$("#main-profile-picture").mouseleave(function () {
   $(this).find("#trigger-image-update").css('bottom', '-35px').css('opacity', '0')
});

// Friendships Actions
// Send request
function send_request () {
    $("#send-request").click(function () {
        id = window.userid;
        
        userid = new FormData;
        userid.append('userid', id);
        
        $.ajax({
            url: 'general_scripts/friendships/send_request.php',
            processData: false,
            contentType: false,
            method : "POST",
            data: userid,
            success: function (data) {
                $("#friend-request-actions").append('<div class="action-wrap"><div id="cancel-request" class="friendship-action" title="Cancel Friend Request"><i class="fa fa-user-times" aria-hidden="true"></i><h5>Cancel Friend Request</h5></div></div>');
                $("#send-request").parent().remove();
                cancel_request();
            }
        });
    });
}

// Cancel request
function cancel_request () {
    $("#cancel-request").click(function () {
        id = window.userid;
        
        userid = new FormData;
        userid.append('userid', id);
        
        $.ajax({
            url: 'general_scripts/friendships/cancel_request.php',
            processData: false,
            contentType: false,
            method : "POST",
            data: userid,
            success: function (data) {
                $("#cancel-request").parent().remove();
                $("#friend-request-actions").append('<div class="action-wrap"><div id="send-request" class="friendship-action" title="Send Friend Request"><i class="fa fa-user-plus" aria-hidden="true"></i><h5>Send A Friend Request</h5></div></div>')
                send_request();
            }
        });
    });
}

function approve_answer () {
    $.each($("#profile-friendship-approval-wrap .approval-option"), function () {
        $(this).click(function () {
            if ($(this).attr('id') == 'profile-approval-approve') {
                approve_request(true);
            } else {
                approve_request(false);
            }
        });
    });

    $("#friend-request-actions #toggle-friends-actions-lisy").click(function () {
        $(this).parent().find("#friends-actions").toggle();
    });
}

function approve_request (isApprove) {
    approve_data = new FormData;
    approve_data.append('request_sender_id', window.userid);
    if (isApprove) {
        // If user chooses to approve
        approve_data.append('action', 'approve');
    } else {
        approve_data.append('action', 'decline');
    }

    $.ajax({
        url: 'general_scripts/friendships/approve_request.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: approve_data,
        success: function (data) {
            location.reload();
        }
    });
}

send_request();
cancel_request();
approve_answer();

// Send Message from profile
function sidebar_trigger_message () {
    $("#trigger-message-from-sidebar").unbind("click").click(function () {
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
}

sidebar_trigger_message();