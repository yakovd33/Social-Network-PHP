$.each($(".section-content-toggle"), function () {
    $(this).click(function () {
        window.history.pushState({"html": "","pageTitle": ""},"", "settings.php?tab=" + getParameterByName("tab", location) + "&section=" + $(this).data('section'));
        
        $.each($(".section-content-wrap"), function () {
            $(this).hide();
        });

        $(this).parent().find(".section-content-wrap").show();
    })
});

// Update Password
$("#settings-change-password-section .section-content-wrap .update-current-setting").click(function () {
    newpass_form = $(this).parent();
    newpass_data = new FormData();
    newpass_data.append('current_password', newpass_form.find("#current-password-input").val());
    newpass_data.append('new_password', newpass_form.find("#new-password-input").val());
    newpass_data.append('new_password_again', newpass_form.find("#new-password-again-input").val());
    newpass_data.append('update_pass_token', newpass_form.find("#update_pass_token").val());

    $.ajax({
        url: 'form_submitions/update_password.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: newpass_data,
        success: function (e) {
            $("#change_password_feedback").html(e);
        }
    })
});

// Update Email
$("#settings-change-email-section .section-content-wrap .update-current-setting").click(function () {
    newemail_form = $(this).parent();
    newemail_data = new FormData();
    newemail_data.append('new_email', newemail_form.find("#new-email-input").val());
    newemail_data.append('update_email_token', newemail_form.find("#update_email_token").val());

    $.ajax({
        url: 'form_submitions/update_email.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: newemail_data,
        success: function (e) {
            $("#change_email_feedback").html(e);
        }
    })
});

// Update Fullname
$("#settings-change-fullname-section .section-content-wrap .update-current-setting").click(function () {
    newfullname_form = $(this).parent();
    fullname_data = new FormData();
    fullname_data.append('new_fullname', newfullname_form.find("#new-fullname-input").val());
    fullname_data.append('update_fullname_token', newfullname_form.find("#update_fullname_token").val());

    $.ajax({
        url: 'form_submitions/update_fullname.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: fullname_data,
        success: function (e) {
            $("#change_fullname_feedback").html(e);
        }
    })
});

// Update Username
$("#settings-change-username-section .section-content-wrap .update-current-setting").click(function () {
    newusername_form = $(this).parent();
    username_data = new FormData();
    username_data.append('new_username', newusername_form.find("#new-username-input").val());
    username_data.append('update_username_token', newusername_form.find("#update_username_token").val());

    $.ajax({
        url: 'form_submitions/update_username.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: username_data,
        success: function (e) {
            $("#change_username_feedback").html(e);
        }
    })
});

// Update Who can see your posts
$("#settings-who-can-see-your-posts-section .section-content-wrap .update-current-setting").click(function () {
    whoCanSeePosts_form = $(this).parent();
    whoCanSeePosts_data = new FormData();
    whoCanSeePosts_data.append('new_scope', whoCanSeePosts_form.find(".selected-scope-text").html());

    $.ajax({
        url: 'form_submitions/update_profile_posts_scope.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: whoCanSeePosts_data,
        success: function (e) {
            $("#update-profile-posts-scope-feedback").html(e);
        }
    })
});

// Update Who can post on wall
$("#settings-who-can-post-on-your-profile-section .section-content-wrap .update-current-setting").click(function () {
    whoCanPostOnYourWall_form = $(this).parent();
    whoCanPostOnYourWall_data = new FormData();
    whoCanPostOnYourWall_data.append('new_scope', whoCanPostOnYourWall_form.find(".selected-scope-text").html());

    $.ajax({
        url: 'form_submitions/update_profile_who_can_post_on_wall_scope.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: whoCanPostOnYourWall_data,
        success: function (e) {
            $("#update-profile-wall-post-ability-scope-feedback").html(e);
        }
    })
});


// Toggle comments preview
$("#show-comments-preview-section #toggle-comments-preview").click(function () {
    $.ajax({
        url: 'form_submitions/autoload_comments_setting_update.php',
        processData: false,
        contentType: false,
        method : "GET",
        success: function (e) {
            if ($("#toggle-comments-preview").text().trim() == "Turn Option On") {
                $("#toggle-comments-preview").text("Turn Option Off")
            } else {
                $("#toggle-comments-preview").text("Turn Option On")
            }
        }
    })
});