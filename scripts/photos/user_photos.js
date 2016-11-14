window.loaded_pictures_page = 1;
window.has_more_to_load = true;

function load_user_pictures (page) {
    page = new FormData;
    page.append('page', window.loaded_pictures_page);
    
    $.ajax({
        url: 'photos/get_user_photos.php',
        processData: false,
        contentType: false,
        method: "POST",
        data: page,
        success: function (data) {
            $("#user-pictures").append(data);
            console.log(data.trim().length);
            if (data.trim().length == 0) {
                window.has_more_to_load = false;
            }
        }
    });
}

load_user_pictures(window.loaded_pictures_page);

$(document).scroll(function () {
    var scrollHeight = $(document).height();
    var scrollPosition = $(window).height() + $(window).scrollTop();
    if (window.has_more_to_load) {
        if ((scrollHeight - scrollPosition) / scrollHeight <= 0.2) {
            window.loaded_pictures_page++;
            load_user_pictures(window.loaded_pictures_page);
        }
    }
});