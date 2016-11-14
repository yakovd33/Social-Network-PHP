// Friendships Actions
// Send request
function send_request () {
    $.each($(".send-request"), function () {
        $(this).click(function () {
            id = $(this).parent().parent().parent().parent().data('uid');

            userid = new FormData;
            userid.append('userid', id);
            
            $.ajax({
                url: 'general_scripts/friendships/send_request.php',
                processData: false,
                contentType: false,
                method : "POST",
                data: userid,
                success: function (data) {
                    cancel_request();
                }
            });

            $(this).parent().html('<div class="cancel-request" title="Cancel Friend Request"><i class="fa fa-user-times" aria-hidden="true"></i></div>')
        });
    });
}

// Cancel request
function cancel_request () {
    $.each($(".cancel-request"), function () {
        $(this).click(function () {
            id = $(this).parent().parent().parent().parent().data('uid');

            userid = new FormData;
            userid.append('userid', id);
            $.ajax({
                url: 'general_scripts/friendships/cancel_request.php',
                processData: false,
                contentType: false,
                method : "POST",
                data: userid,
                success: function (data) {
                    send_request();
                }
            });

            $(this).parent().html('<div class="send-request" title="Send Friend Request"><i class="fa fa-user-plus" aria-hidden="true"></i></div>');
        });
    });
}

send_request();
cancel_request();