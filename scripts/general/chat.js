// Update Last Login
function update_last_login () {
    $.ajax({
       url: 'general_scripts/update_last_login.php'
    });
}

update_last_login();
setInterval(function () {
    update_last_login();
}, 30000);