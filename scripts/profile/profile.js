function load_profile_feed (userid) {
    feed_data = new FormData;
    feed_data.append('userid', userid);
    
    $.ajax({
        url: 'profile/profile_feed.php',
        processData: false,
        contentType: false,
        method : "POST",
        data: feed_data,
        success: function (feed) {
            $("#feed-posts-wrap").html(feed);
        }
    });
}

$(document).ready(function () {
   load_profile_feed(window.userid);
});

// Handle Profile Sidebar Tab Collapsing
if ($.cookie('about_tab_collapsed') == undefined || $.cookie('photos_tab_collapsed') == undefined || $.cookie('friends_tab_collapsed') == undefined) {
    $.cookie('about_tab_collapsed', false);
    $.cookie('images_tab_collapsed', false);
    $.cookie('friends_tab_collapsed', false);
}

$.each($(".collapse-current-sidebar-tab"), function () {
    $(this).click(function () {
        tab = $(this).data('tab');
        if ($.cookie(tab + '_tab_collapsed') == 'false') {
            $.cookie(tab + '_tab_collapsed', true);
            $(this).parent().parent().find(".sidebar-card-content").slideUp();
        } else {
            $.cookie(tab + '_tab_collapsed', false);
            $(this).parent().parent().find(".sidebar-card-content").slideDown();
        }
    });
});

$.each($(".sidebar-card"), function () {
$(this).mouseover(function () {
    $(this).find(".sidebar-card-title").find(".collapse-current-sidebar-tab").show();
});

$(this).mouseleave(function () {
    $(this).find(".sidebar-card-title").find(".collapse-current-sidebar-tab").hide();
});
});

if ($.cookie('about_tab_collapsed') == "true") {
    $("#about-card").find(".sidebar-card-content").hide();
}

if ($.cookie('images_tab_collapsed') == "true") {
    $("#images-card").find(".sidebar-card-content").hide();
}

$.each($("#profile-nav .nav-link"), function () {
    $(this).click(function (e) {
        e.preventDefault();
        window.wanted_profile_tab = $(this).text().toLowerCase();

        var profile_tab_data = new FormData;
        profile_tab_data.append('type', 'id');
        profile_tab_data.append('context', userid);

        profile_tab_data.append('tab', window.wanted_profile_tab);

        $("#profile-nav .nav-link.active").removeClass("active");
        $(this).addClass("active");

        window.history.pushState({"html": "","pageTitle": ""},"", "profile.php?username=" + getParameterByName("username", location) + "&tab=" + window.wanted_profile_tab);

        if ($("#profile-" + window.wanted_profile_tab + "-tab").html().trim().length == 0) {
            $.ajax({
                url: 'profile/load_profile_tab.php',
                processData: false,
                contentType: false,
                method : "POST",
                data: profile_tab_data,
                success: function (tab) {
                    $.each($(".profile-tab"), function () {
                        $(this).hide();
                    });

                    $("#profile-" + window.wanted_profile_tab + "-tab").html(tab).fadeIn();
                    profile_tabs();
                }
            });
        } else {
            $.each($(".profile-tab"), function () {
                $(this).hide();
            });

            $("#profile-" + window.wanted_profile_tab + "-tab").fadeIn();
        }
    })
});

function profile_tabs () {
    $.each($(".tab-choice"), function () {
        $(this).click(function () {
            $.each($(".friends-tab-subtab"), function () {
                $(this).hide();
            });

            if ($(this).attr('id') == 'friend-tab-sub-tab-all-friends') {
                $("#friends-tab-all-subtab").show();
                window.history.pushState({"html": "","pageTitle": ""},"", "profile.php?username=" + getParameterByName("username", location) + "&tab=" + getParameterByName("tab", location) + "&subtab=" + "all");
            }

            if ($(this).attr('id') == 'friend-tab-sub-tab-mutual-friends') {
                $("#friends-tab-mutual-subtab").show("fast");
                window.history.pushState({"html": "","pageTitle": ""},"", "profile.php?username=" + getParameterByName("username", location) + "&tab=" + getParameterByName("tab", location) + "&subtab=" + "mutual");
            }
        });
    });
}

profile_tabs();