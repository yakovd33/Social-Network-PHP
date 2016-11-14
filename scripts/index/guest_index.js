$(window).resize(function () {
    guest_hero_resize();
});

function guest_hero_resize () {
    // Original ration - 531 / 204 = 2.602
    var hero_ratio = 531 / 204;
    $("#index_guest_hero").css('height', $("#index_guest_hero").width() / hero_ratio);
}

guest_hero_resize();