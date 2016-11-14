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
            
            postid = new FormData;
            postid.append('postid', $(this).parent().parent().data('postid'));

            $.ajax({
                url: 'general_scripts/get_post_comments.php',
                processData: false,
                contentType: false,
                method : "POST",
                data : postid,
                success: function (e) {
                    element = $(this).find('formdata');
                    window.comment_element.parent().parent().find(".post-comments-section").find(".comments-wrap").find(".post-comment-section").html(e);
                    comments();
                }
            });
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
                if ($(this).find(".comment-additional-image").val() == '' && comment_content.replace(/(<([^>]+)>)/ig, "").length > 0) {

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
                                } 
                            });
                        }
                    });
                    
                    $(this).find(".comment-text-input").html('');
                    $(this).find(".comment-text-input").blur();
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
                                } 
                            });
                        }
                    });
                    
                    $(this).find(".comment-text-input").html('');
                    $(this).find(".comment-text-input").blur();
                }
            }
        });
    });
}

like();
load_comments();