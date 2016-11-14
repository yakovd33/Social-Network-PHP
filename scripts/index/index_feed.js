function feed_listing () {
    $.ajax({
        url: 'general_scripts/feed_posts.php',
        success: function (e) {
            $("#feed-posts-wrap").html(e);
            repost_like();
            repost_repost();
            after_load_eaches();
        }
    })
        
    $("#feed-show-more").click(function () {
        if (localStorage.getItem('feed_page') > 1) {
            page = new FormData;
            page.append('page', page);
            localStorage.setItem('feed_page', parseInt(localStorage.getItem('feed_page')) + 1)
            
            $.ajax({
                url: 'general_scripts/feed_posts.php',
                processData: false,
                contentType: false,
                data: page,
                success: function (e) {
                    $("#feed-posts-wrap").append(e);
                    repost_like();
                    repost_repost();
                }
            });
        }  
    })
}

$(document).ready(function () {
   feed_listing();
});

function after_load_eaches () {
    $.each($(".num-hearts"), function () {
        $(this).hover(function () {
            $(this).parent().find(".post-hearts-list").show()
        });
        
        $(this).mouseleave(function () {
           $(this).parents().find(".post-hearts-list").hide() 
        });
    });
    
    $.each($(".num-comments"), function () {
        $(this).hover(function () {
            $(this).parent().find(".post-commenters-list").show()
        });
        
        $(this).mouseleave(function () {
            $(this).parents().find(".post-commenters-list").hide() 
        });
    });
    
    $.each($(".comment-additional-image"), function () {
        $(this).change(function () {
            var file = $(this).prop("files")[0];
            var reader = new FileReader();
        
             reader.addEventListener("load", function () {
                $(this).parent().find(".additional-image-preview .preview-wrap").css('background-image', 'url("' + reader.result + '")');
                $(this).parent().find(".additional-image-preview").slideDown();  
            }, false);

             if (file) {
                reader.readAsDataURL(file);
             }
        }); 
    });

    $("#diselect-selected-pictures").click(function () {
        document.querySelector("#additional-image").files.length = 0
        $("#image-preview").slideUp('fast');  
    });
    
    scroll_load_more();
    like();
    load_comments();
    feedpost_reposts();
    post_options();
}

window.feed_page = 2;
$.cookie('has_more_to_load', true);
function scroll_load_more () {
    $(document).scroll(function () {
        page = new FormData;
        page.append('page', window.feed_page);
    
        var scrollHeight = $(document).height();
        var scrollPosition = $(window).height() + $(window).scrollTop();
        if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
            if ($.cookie('has_more_to_load') == "true") {
                $.ajax({
                    url : 'general_scripts/feed_posts.php',
                    processData: false,
                    contentType: false,
                    method : "POST",
                    data : page,
                    success : function (e) {
                        if (e.toLowerCase().trim() != 'no posts to display <a href="discover.php" class="discover">discover</a>') {
                            window.feed_page++;
                            
                            div = document.createElement("DIV");
                            document.getElementById("feed-posts-wrap").appendChild(div);
                            div.innerHTML = e;
                            like();
                            load_comments();
                            after_load_eaches();
                        } else {
                            $("#feed_show_more").hide();
                            $.cookie('has_more_to_load', "false");
                        }
                    }
                });
            }
        } 
    });
}

setTimeout(after_load_eaches, 1000);

function like () {
    $.each($(".post-wrap .post-actions .heart"), function () {
        $(this).click(function () {
            $(this).toggleClass("clicked");
            
            if ($(this).find(".fa").hasClass("fa-heart-o")) {
                $(this).find(".fa").removeClass(".fa-heart-o").addClass(".fa-heart");
            } else {
                $(this).find(".fa").addClass(".fa-heart-o").removeClass(".fa-heart");
            }
            
            postinfo = new FormData;
            postinfo.append('postid', $(this).parent().parent().attr('data-postid'));
            
            element = $(this);
            
            $.ajax({
                url: 'general_scripts/post_like.php',
                processData: false,
                contentType: false,
                method : "POST",
                data : postinfo,
                success : function (e) {
                    element.parent().parent().find(".post-comments-section").find(".post-details").find(".num-hearts").find(".sum-hearters").html(e);
                }
            });
        });
    });
}

/* Comments */
function load_comments () {
    $.each($(".post-actions .comment"), function () {
        $(this).unbind('click').click(function () {
            $(this).parent().parent().find(".post-comments-section").find(".comments-wrap").toggle().find(".new-comment-wrap").find(".new-comment-input").find(".comment-text-input").focus();
            
            window.comment_element = $(this);
                        
            data = new FormData;
            data.append('postid', $(this).parent().parent().data('postid'));
            if (window.comment_element.parent().parent().find(".post-comments-section").find(".comments-wrap").find(".post-comment-section").children().length == 0) {
                $.ajax({
                    url: 'general_scripts/get_post_comments.php',
                    processData: false,
                    contentType: false,
                    method : "POST",
                    data : data,
                    success: function (e) {
                        element = $(this).find('formdata');
                        window.comment_element.parent().parent().find(".post-comments-section").find(".comments-wrap").find(".post-comment-section").html(e);
                        $($(".post-wrap[data-postid='" + window.comment_element.parent().parent().data('postid') + "']")).find(".post-comments-section").find(".comments-wrap").find(".post-comment-section")[0].dataset.page = 1;
                        comments();
                        comments_load_more(); // A call to comments() at success
                        comments_load_all(); // A call to comments() at success
                        comments_likes();
                    }
                });
            }
        });
    });
}

function comments_load_more () {
    $.each($(".post-comments-section .comments-actions .comment-show-more"), function () {
        $(this).unbind('click').click(function () {               
            window.show_more_element = $(this);       
            page = $(this).parent().parent().find(".post-comment-section")[0].dataset.page;
            data = new FormData;
            data.append('postid', $(this).parent().parent().parent().parent().data('postid'));
            data.append('page', page);
            if (window.show_more_element.parent().parent().find(".post-comments-section").find(".comments-wrap").find(".post-comment-section").children().length == 0) {
                $.ajax({
                    url: 'general_scripts/get_post_comments.php',
                    processData: false,
                    contentType: false,
                    method : "POST",
                    data : data,
                    success: function (e) {
                        if (e.trim().length > 0) {
                            window.show_more_element.parent().parent().find(".post-comment-section").append(e);
                            window.show_more_element.parent().parent().find(".post-comment-section")[0].dataset.page = parseInt(window.comment_element.parent().parent().find(".post-comment-section")[0].dataset.page) + 1; 
                        } else {
                            window.show_more_element.removeClass("comments-action").find("span").text("No More Comments To Display.");
                            window.show_more_element.parent().find(".comment-show-all").remove();
                        }
                        
                        comments();
                    }
                });
            }
        });
    });
}

function comments_load_all () {
    $.each($(".post-comments-section .comments-actions .comment-show-all"), function () {
        $(this).unbind('click').click(function () {
            window.show_all_element = $(this);
            
            data = new FormData;
            data.append('postid', $(this).parent().parent().parent().parent().data('postid'));
            data.append('show_all', 1);
            if (window.show_all_element.parent().parent().find(".post-comments-section").find(".comments-wrap").find(".post-comment-section").children().length == 0) {
                $.ajax({
                    url: 'general_scripts/get_post_comments.php',
                    processData: false,
                    contentType: false,
                    method : "POST",
                    data : data,
                    success: function (e) {
                        if (e.trim().length > 0) {
                            window.show_all_element.parent().parent().find(".post-comment-section").html(e);
                            window.show_all_element.parent().parent().find(".post-comment-section")[0].dataset.page = parseInt(window.comment_element.parent().parent().find(".post-comment-section")[0].dataset.page) + 1; 
                        } else {
                            window.show_all_element.removeClass("comments-action").find("span").text("No More Comments To Display.");
                            window.show_all_element.parent().find(".comment-show-more").remove();
                        }
                        
                        comments();

                        show_all_element.parent().remove();
                    }
                });
            }
        });
    });
}

function comments () {
    $.each($(".trigger-comment-additional-image"), function () {
        $(this).click(function () {
            $(this).parent().find(".comment-additional-image").click()
        });
    });
    
    $.cookie('comment-preview-post-id', '');
    $.each($(".new-comment-input"), function () {
        post_id = $(this).data('postid');
        
        $(this).find(".comment-additional-image").change(function () {
            var file = $(this)[0].files[0];
            var reader = new FileReader();
            $.cookie('comment-preview-post-id', $(this).closest('.post-wrap').data('postid'));
                
            reader.addEventListener("load", function () {
                $(".post-wrap[data-postid='" + $.cookie('comment-preview-post-id') + "']").find(".new-comment-input").find(".additional-image-preview").slideDown('fast');
                preview_wrap = $(".post-wrap[data-postid='" + $.cookie('comment-preview-post-id') + "']").find(".new-comment-input").find(".additional-image-preview").slideDown('fast').find(".preview-wrap");
                preview_wrap.css('background-image', 'url("' + reader.result + '")');
            }, false);
            
            if (file) {
                reader.readAsDataURL(file);
            }
        });
                
        $(this).find(".diselect-selected-pictures").click(function () {
            $($(this).parents()[4]).find(".new-comment-input").find(".comment-additional-image[type='file']")[0].value = '';
            $(this).parent().css('background-image', '');
            $(this).closest(".additional-image-preview").hide('fast');
        });
                        
        $(this).keypress(function(e) {
            if (e.keyCode == 13 && !e.shiftKey) {
                post_id = $(this).closest(".post-wrap").data('postid');
                 window.comment_input_element = $(this);
                 
                // Comment XHR Submition
                comment_content = $(this).find(".comment-text-input").html();
                
                comment_data = new FormData;
                comment_data.append('postid', post_id);
                comment_data.append('comment_content', comment_content);
                
                if ($(this).find(".comment-additional-image").val() == '') {
                    comment_data.append('additional_image', '');
                } else {
                    comment_data.append('additional_image', $(this).find(".comment-additional-image")[0].files[0]);
                }
                
                // Submit new post comment
                if ($(this).find(".comment-additional-image").val() == '' && comment_content.replace(/(<([^>]+)>)/ig, "").trim().length > 0) {
                    $.ajax({
                        url: 'form_submitions/post_new_comment.php',
                        processData: false,
                        contentType: false,
                        method : "POST",
                        data : comment_data,
                        success : function (e) {
                            $.ajax({
                                url: 'general_scripts/get_new_post_comment.php',
                                processData: false,
                                contentType: false,
                                method: "POST",
                                success: function (e) {
                                    $(window.comment_input_element).parent().parent().find(".post-comment-section").prepend(e);
                                    comments_likes();
                                    window.comment_input_element.find(".comment-additional-image").val('');
                                    window.comment_input_element.find(".comment-additional-image .preview-wrap").hide();
                                } 
                            });
                        }
                    });
                    
                    $(this).find(".comment-text-input").html('');
                    return e.which != 13;
                } else if ($(this).find(".comment-additional-image").val() != '') {
                    $.ajax({
                        url: 'form_submitions/post_new_comment.php',
                        processData: false,
                        contentType: false,
                        method : "POST",
                        data : comment_data,
                        success : function (e) {
                            $.ajax({
                                url: 'general_scripts/get_new_post_comment.php',
                                processData: false,
                                contentType: false,
                                method : "POST",
                                success: function (e) {
                                    $(window.comment_input_element).parent().parent().find(".post-comment-section").prepend(e);
                                    window.comment_input_element.find(".comment-additional-image").val('');
                                    window.comment_input_element.find(".comment-additional-image .preview-wrap").hide();
                                } 
                            });
                        }
                    });
                    
                    $(this).find(".comment-text-input").html('');
                }
            }
        });
    });
}


// Repost
function feedpost_reposts () {
    $.each($(".post-wrap .repost"), function () {
        $(this).click(function () {
            var repost_postid = $(this).parent().parent().data('postid');
            reposts(repost_postid);
        });
    });
}

// Repost other repost
function repost_repost () {
    $.each($(".repost-this-repost"), function () {
        $(this).click(function () {
            var repost_postid = $(this).parent().parent().parent().data('postid');
            reposts(repost_postid);
            console.log('hehehe');
        });
    });
}

function reposts (repost_postid) {
    repost_data = new FormData();
    repost_data.append('postid', repost_postid);

    $.ajax({
        url: 'general_scripts/repost_popup.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: repost_data,
        success: function (e) {
            $("#poups-wrap").addClass("toggled");
            $("#pps_wrap").css('position', 'fixed').html(e);
            popup_close_btn();

            $("#repost-editor-wrap").find("#submit-new-post").click(function () {
                var new_repost_data = new FormData();
                new_repost_data.append('repostid', $("#repost-box").data('postid'));
                new_repost_data.append('new-post-token', $("#new-post-token").val());
                new_repost_data.append('post_input', $("#repost-text-brief-input").find("#post-input").html());
                
                $.ajax({
                    url: 'form_submitions/new_post_submition.php',
                    processData: false,
                    contentType: false,
                    method : "POST",
                    data: new_repost_data,
                    success: function (e) {
                        $("#poups-wrap").removeClass("toggled");
                    }
                });
            })
        } 
    })
}