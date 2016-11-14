function getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$(document).ready(function () {
    $(document).keydown(function (e) {
        if (e.which == 27) {
            $("#poups-wrap").removeClass("toggled");
        }
    });
});

function popup_close_btn () {
    $.each($(".popup-close"), function () {
        $(this).click(function () {
            $("#poups-wrap").removeClass("toggled");
        });
    })
}

function comments_likes () {
    $.each($(".comment-actions .heart .heart-this-comment"), function () {
        $(this).click(function () {
            var commentid_like = new FormData;
            window.comment_id = $(this).parent().parent().parent().parent().data('commentid');
            commentid_like.append('commentid_like', comment_id = $(this).parent().parent().parent().parent().data('commentid'));
            
            var comment_like_response = $.ajax({
                url: 'form_submitions/comment_like.php',
                processData: false,
                contentType: false,
                method : "POST",
                data: commentid_like,
                success: function (e) {
                }
            });

            setTimeout(function () {
                if (comment_like_response.readyState == 4) {
                    btn_txt = JSON.parse(comment_like_response.responseText).btn;
                    hearts_num = JSON.parse(comment_like_response.responseText).num;
                    $($(".comment[data-commentid='" + window.comment_id + "']")).find(".comment-wrap").find(".comment-actions").find(".heart").find(".heart-this-comment").html(btn_txt);
                    $($(".comment[data-commentid='" + window.comment_id + "']")).find(".comment-wrap").find(".comment-actions").find(".heart").find(".num-hearts").find(".sum-hearters").html(hearts_num);
                    clearTimeout();
                }
            }, 200);



        });

    })
}

$(".selected-scope").click(function () {
   $(this).toggleClass("toggled");
   $(this).parent().find("#list").toggleClass("toggled");
});

$(".select-post-scope").find("#list").find(".scope-list-item").click(function () {
    $(".selected-scope").html($(this).html() + '<i class="fa fa-angle-down"></i>')
    $(".selected-scope").removeClass("toggled").parent().find("#list").removeClass("toggled");
})

// Repost like
function repost_like () {
    $.each($(".heart-this-repost"), function () {
        $(this).click(function () {
            repostid = new FormData;
            repostid.append('postid', $(this).parent().parent().parent().data('postid'));
            $(this).toggleClass("liked").css('animation-name', 'repost_like_pop').css('animation-duration', '0.3s');

            $.ajax({
                url: 'general_scripts/post_like.php',
                processData: false,
                contentType: false,
                method : "POST",
                data: repostid,
            })
        });
    });
}

function post_options () {
    $.each($(".post-options i"), function () {
        $(this).unbind("click").click(function () {
            $(this).parent().find(".post-options-list").toggle();
        });
    });

    $.each($(".post-options-list-item"), function () {
        $(this).unbind("click").click(function () {
            if ($(this).attr('id') == "post-options-delete-option") {
                if (confirm("Are you sure you want to delete this post?")) {
                    window.desired_post_to_be_deleted_element = $(this).parent().parent().parent().parent();
                    post_data = new FormData;
                    post_data.append('postid', desired_post_to_be_deleted_element.data('postid'));
                    $.ajax({
                        url: 'general_scripts/delete_post.php',
                        processData: false,
                        contentType: false,
                        method: 'POST',
                        data: post_data,
                        success: function (e) {
                            window.desired_post_to_be_deleted_element.fadeOut(800);
                            setTimeout(function () {
                                window.desired_post_to_be_deleted_element.remove();
                            }, 1000);
                        }
                    });
                }
            }
        });
    });
}

// Logout
function logout () {
    $.ajax({
        url: 'general_scripts/logout.php'
    }).function (location.reload())
}

$("#logout").click(function (e) {
    logout();
});