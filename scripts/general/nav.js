

// Dropdowns
window.notifics_dropdown_page = 1;

function load_notifications (page) {
    int_page = page;
    page = new FormData;
    page.append('page', int_page);
    
    $.ajax({
        url : 'general_scripts/dropdowns/notifics_dropdown.php',
        processData: false,
        contentType: false,
        method : "POST",
        data : page,
        success : function (notifics) {
            window.notifics_dropdown_page++;
            $("#notifics-dropdown .dropdown-content").append(notifics);
            window.notifics_dropdown_page++;
            
            if (notifics.trim().length == 0) {
                has_more_notifics_to_load = false;
            }
        }
    });
}

load_notifications(window.notifics_dropdown_page);
window.has_more_notifics_to_load = true;

$("#notifics-dropdown .dropdown-content").scroll(function () {
    var scrollHeight = $(document).height();
    var scrollPosition = $(window).height() + $("#notifics-dropdown .dropdown-content").scrollTop();
        if ((scrollHeight - scrollPosition) / scrollHeight <= 0) {
            if (window.has_more_notifics_to_load) {
                load_notifications(window.notifics_dropdown_page);
            }
        }
});

$.each($("#dropdowns-triggers .trigger"), function () {
   $(this).click(function () {
       if ($(this).attr('id') == "notifics-trigger") {
           $("#notifics-dropdown").toggle();
       } 
   });
});

$.each($(".dropdown-content"), function () {
    $(this).mouseover(function () {
        $(this).addClass('hovered');
    });

    $(this).mouseout(function () {
        $(this).removeClass('hovered');
    });
});

$("#toggle-mobile-menu").click(function () {
    $("#mobile-nav").toggle();
});