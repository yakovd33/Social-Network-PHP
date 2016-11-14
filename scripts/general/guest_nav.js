$("#site-search-wrap").click(function () {
    $("#search-input").focus();
});

// Nav login mask
$("#submit-login").click(function () {
    $(this).parent().find("input[type='submit']").click();
});