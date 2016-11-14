$("#post-input-wrap").click(function () {
   $("#post-input").focus(); 
});

$("#post-input").keydown(function (e) {
    if ($(this).height() >= 400) {
        $(this).css('overflow-y', 'scroll')
    } else {
        $(this).css('overflow-y', 'visible')
    }
});

$("#selected-scope").click(function () {
   $(this).toggleClass("toggled");
   $(this).parent().find("#list").toggleClass("toggled");
});

$("#select-post-scope").find("#list").find(".scope-list-item").click(function () {
    $("#selected-scope").html($(this).html() + '<i class="fa fa-angle-down"></i>')
    $("#selected-scope").removeClass("toggled");
    $("#selected-scope").parent().find("#list").removeClass("toggled");
});

$("#trigger-additional-image").click(function () {
    $("#additional-image").click();
});

$("#additional-image").change(function () {
    var file = document.querySelector("#additional-image").files[0];
    var reader = new FileReader();

    reader.addEventListener("load", function () {
        $("#preview-wrap").css('background-image', 'url("' + reader.result + '")');
        $("#image-preview").slideDown('fast');  
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
});

$("#diselect-selected-pictures").click(function () {
    document.querySelector("#additional-image").value = '';
    $("#image-preview").slideUp('fast');
    $("#preview-wrap").css('background-image', '');
});

// New Post Submition

$("#new-post-form").submit(function (e) {
    e.preventDefault();
    if ($("#post-input").html().replace(/(<([^>]+)>)/ig,"").length >= 1 || $("#additional-image").val() != "") {
    
        var post_data = new FormData;
        post_data.append('new-post-token', $("#new-post-token").val());
        post_data.append('post_input', encodeURI($("#post-input").html()));
        post_data.append('post_scope', $("#selected-scope .selected-scope-text").text());
        
        if (document.querySelector("#additional-image").value == '') {
            post_data.append('additional_image', '');
        } else {
            post_data.append('additional_image', document.querySelector("#additional-image").files[0]);
        }
        
        $.ajax({
            url : 'form_submitions/new_post_submition.php',
            type: 'POST',
            processData: false,
            contentType: false,
            data: post_data,
            success: function () {
                setTimeout(function () {
                    get_new_post();
                }, 500);
            }
        })
        
        function get_new_post () {
            $.ajax({
                url : 'general_scripts/get_new_post.php',
                processData : false,
                contentType : false,
                
                success : function (e) {
                    var div = document.createElement("DIV");
                    
                    new_posts_wrap = $("#user-new-posts");
                    div.innerHTML = e;
                    new_posts_wrap.prepend(div.innerHTML);
                    like();
                    load_comments();
                    setTimeout(post_options(), 500);
                }
            })
        }
        
        $(this)[0].reset();
        $("#post-input").html('');
        document.querySelector("#additional-image").files.length = 0
        $("#image-preview").slideUp('fast');
    }
});

// Post Tags
$("#post-additional #post-tags").click(function () {
   $("#new-post-tags").slideToggle('fast');
   $("#new-post-tags #new-tag").focus(); 
});

$("#new-post-tags").click(function () {
    $(this).find("#new-tag").focus();
});

$("#new-post-tags #new-tag").keyup(function (e) {
    new_tag_value = $(this).text().trim().replace(/,/g, '');
    
    if (new_tag_value.length > 1) {
        if (e.which == 188 || e.which == 32 || e.which == 13) {
            
            $(this).parent().find("#ready-tags").append('<li class="tag" contenteditable="false">' + new_tag_value + '<span class="self-destroy"><i class="fa fa-times" aria-hidden="true"></i></span></li>');
            $(this).html('');
            
            $.each($("#new-post-tags .tag"), function () {
                $(this).click(function () {
                    $(this).attr('contenteditable', 'plaintext-only');
                    $(this).focus();
                });
                
                $(this).blur(function () {
                    $(this).attr('contenteditable', 'false');
                });
            });
            
            $.each($(".self-destroy"), function () {
                $(this).click(function () {
                    $(this).parent().remove(); 
                });
            })
        }
    }
});