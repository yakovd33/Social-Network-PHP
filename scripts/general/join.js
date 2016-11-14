$(".moving-bar").each(function () {
    $(this).css('background-color', $(this).data('color'))
});

$("#register-form").change(function () {
   $("#join-form-validation-feedback").html('');
   
   var $email = $("#register-form input[name='email']").val();
   var $username = $("#register-form input[name='username']").val();
   var $password = $("#register-form input[name='password']").val();
   var $re_pass = $("#register-form input[name='re_pass']").val();
   
   $.ajax({
       type: 'GET',
       url: 'join.php?xhr_validation&email=' + $email + '&username=' + $username + '&password=' + $password + '&re_pass=' + $re_pass,
       success: function (data) {
           var form_valid = JSON.parse(data);
           
           if (form_valid.email_exists != true) {
               $("#register-form input[name='email']").addClass("field_bad_feedback");
               join_feedback('red', 'Email Already Exists.');
               
           } else {
               $("#register-form input[name='email']").removeClass("field_bad_feedback");
           }
           
           if (form_valid.username_exists != true) {
               $("#register-form input[name='username']").addClass("field_bad_feedback");
               join_feedback('red', 'Username Already Exists.');
               
           } else {
               $("#register-form input[name='username']").removeClass("field_bad_feedback");
           }
           
           if ($password.length > 0) {
                if (form_valid.password_length_greater_than_7 != true) {
                    $("#register-form input[name='password']").addClass("field_bad_feedback");
                    join_feedback('red', 'Password Length Is Less Than 7.');
                } else {
                    $("#register-form input[name='password']").removeClass("field_bad_feedback");
                }
           } else {
                    $("#register-form input[name='password']").removeClass("field_bad_feedback");
            }
           
           if (form_valid.password_length_greater_than_7 == true) {
               if ($re_pass.length > 0) {
                    if (form_valid.password_matching != true) {
                        $("#register-form input[name='password'], #register-form input[name='re_pass']").addClass("field_bad_feedback");
                        join_feedback('red', 'Passwords Don\'t Match.');
                        
                    } else {
                        $("#register-form input[name='password'], #register-form input[name='re_pass']").removeClass("field_bad_feedback");
                    }
               } else {
                        $("#register-form input[name='password'], #register-form input[name='re_pass']").removeClass("field_bad_feedback");
                }
           }
        
            if (form_valid.email_exists == true && form_valid.username_exists == true && form_valid.password_length_greater_than_7 == true && form_valid.password_passwords_matching == true) {
                $("##register-form input[type='submit']").attr('disabled', "false");
                $("#join-form-validation-feedback").html('');
            }
        }
   });
   
   function join_feedback (feedback_color, text) {
       $("#join-form-validation-feedback").append("<span class='form_feedback_" + feedback_color + "'>" + text + "</span><br>");
   }
});