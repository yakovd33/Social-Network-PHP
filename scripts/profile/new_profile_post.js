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
   
    var post_data = new FormData;
    post_data.append('new-post-token', $("#new-post-token").val());
    post_data.append('post_input', encodeURI($("#post-input").html()));
    post_data.append('post_scope', $("#selected-scope .selected-scope-text").text());
    post_data.append('profile_userid', window.userid);
    
    if (document.querySelector("#additional-image").value == '') {
        post_data.append('additional_image', '');
    } else {
        post_data.append('additional_image', document.querySelector("#additional-image").files[0]);
    }
    
    $.ajax({
        url : 'form_submitions/new_profile_post_submition.php',
        type: 'POST',
        processData: false,
        contentType: false,
        data: post_data,
        success: function (data) {
            setTimeout(
                function () {
                    get_new_post();
                    post_options();
            }, 500)
        }
    });
    
    function get_new_post () {
        var StrippedString = $("#post-input").html().replace(/(<([^>]+)>)/ig,"");
        if (StrippedString.length >= 0) {
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
    }
    
    $(this)[0].reset();
    $("#post-input").html('');
    document.querySelector("#additional-image").files.length = 0
    $("#image-preview").slideUp('fast');
});