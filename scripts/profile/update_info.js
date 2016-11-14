// Update Bio
$(".about-item#bio .toggle-edit").click(function () {
    $("#bio-wrap").toggle();
});

$("#update-bio").submit(function (e) {
    e.preventDefault();
    content = $("#update-bio-input").html().replace(/(<([^>]+)>)/ig, '');
    if (content.length > 0) {
        bio_data = new FormData;
        bio_data.append('bio', $("#update-bio-input").html());
        $.ajax({
            url: 'form_submitions/update_bio.php',
            processData: false,
            contentType: false,
            method : "POST",
            data: bio_data,
            success: function () {
                $("#update-bio").hide();
                $("#bio-wrap").show().html($("#update-bio-input").html());
            }
        });
    } else {
        $("#update-bio-input").css('border', '1px solid #d44950').css('box-shadow', '0px 0px 5px rgb(243, 85, 70) !important')
    }
});

// Update Living
function load_all_countries () {
    $.ajax({
        url: 'https://restcountries.eu/rest/v1/all',
        success: function (data) {
            for (i = 0; i < data.length; i++) {
                $("#filtered-countries").append('<option>' + data[i].name + '</option>');
            }
        }
    });    
}

function filter_countries (_value) {
    $.ajax({
        url: 'https://restcountries.eu/rest/v1/name/' + _value,
        success: function (data) {
            $("#filtered-countries").html('');
            for (i = 0; i < data.length; i++) {
                $("#filtered-countries").append('<option>' + data[i].name + '</option>');
            }
        },
        error: function (error) {
            error_data = JSON.parse(error.responseText);
            if (error_data.status == 404) {
                $("#filter-countries-feedback").text(error_data.message);
            }
        }
    })
}

$(document).ready(function () {
    load_all_countries();
    $("#filter_countries").unbind('keyup').keyup(function () {
        if ($(this).val() != '') {
            filter_countries($(this).val());
        }
    });

    $("#filter_countries").unbind('keypress').keypress(function () {
        $("#filter-countries-feedback").text('');
        if ($(this).val() == '') {
            load_all_countries();
        }
    });
});

$(".edit_about_item #edit_country .title").click(function () {
    // Close cities filter
    $(".edit_about_item #edit_city .selection-box").hide();
    $(".edit_about_item #edit_city .title").removeClass("toggled");
    
    $(this).toggleClass("toggled");
    $(this).parent().find(".selection-box").toggle();
});

// Show Toggle Update
$.each($(".about-item"), function () {
    $(this).mouseover(function () {
       $(this).find(".toggle-edit").show();
    });
    
    $(this).mouseout(function () {
       $(this).find(".toggle-edit").hide();
    });
});

// Toggle updated
$.each($(".toggle-edit"), function () {
    $(this).click(function () {
        $(this).parent().find(".edit_about_item").toggle(); 
    });
});

// Filter Countries
$(".edit_about_item #edit_city .title").click(function () {
    if ($(this).data("country_filled") != false) {
        // Close countries filter
        $(".edit_about_item #edit_country .selection-box").hide();
        $(".edit_about_item #edit_country .title").removeClass("toggled");
        
        $(this).toggleClass("toggled");
        $(this).parent().find(".selection-box").toggle();
    }
});

$("#filtered-countries").change(function () {
   window.country_to_validate = $(this).val();
   $.ajax({
       url: 'https://restcountries.eu/rest/v1/name/' + window.country_to_validate,
       success: function () {
           $(".edit_about_item #edit_city .title").css('opacity', '1').css('cursor', 'pointer').data("country_filled", "true");
           
           // Change country label
           $(".edit_about_item #edit_country .selection").text(window.country_to_validate);
           
           // Close country filter
           $(".edit_about_item #edit_country .selection-box").hide();
           $(".edit_about_item #edit_country .title").removeClass("toggled");
           
           $.ajax({
              url: 'profile/profile_update/cities_list.php?country_to_list=' + window.country_to_validate,
              success: function (cities) {
                  $("#cities-list").html(cities);
              }
           });
       } 
   });
});

// Submit Update Living
$("#update-living").submit(function (e) {
   e.preventDefault();
   
   living_data = new FormData;
   living_data.append('country', $("#filtered-countries").val());
   living_data.append('city', $("#cities-list").val());
   
   $.ajax({
       url: 'form_submitions/update_living.php',
       processData: false,
       contentType: false,
	   method : "POST",
       data: living_data,
       success: function (data) {
           console.log(data);
		   
           $("#update-living").hide();
           if ($("#filtered-countries").val() != null) {
               $("#user-country").text($("#filtered-countries").val());
           }
           
           if ($("#cities-list").val() != null) {
               $("#user-city").text($("#cities-list").val());
           }
      }
   });
});

// Update Relationship Status
$("#edit_relationship").submit(function (e) {
	e.preventDefault();
   
   relationship_data = new FormData;
   relationship_data.append('relationship', $("#relationship-options").val());
   
   $.ajax({
       url: 'form_submitions/update_relationship_status.php',
       processData: false,
       contentType: false,
	   method : "POST",
       data: relationship_data,
       success: function (data) {
		   $("#user-relationship-status").text($("#relationship-options").val());
		   $("#edit_relationship").hide();
           console.log(data);
	   }
   });
});

// Update Profile Picture
$("#trigger-image-update").click(function () {
    $.ajax({
        url: 'pps/update-pp-ppup.php',
        processData: false,
        contentType: false,
        success: function (data) {
           response = data.trim();
           $("#pps_wrap").html(response);
           $("#poups-wrap").addClass('toggled');
           $(".ppup-wrap").html(data);
           
           if ($("body").css('overflow-y') != 'hidden') {
               $("body").css('overflow-y', 'hidden');
           } else {
               $("body").css('overflow-y', 'scroll');
           }
           
           update_picture($("#main-profile-picture"))
        }
    });
});

// Update Cover Picture
$("#trigger-update-cover").click(function () {
    $.ajax({
        url: 'pps/update-cp-ppup.php',
        processData: false,
        contentType: false,
        success: function (data) {
           response = data.trim();
           $("#pps_wrap").html(response);
           $("#poups-wrap").addClass('toggled');
           $(".ppup-wrap").html(data);
           
           if ($("body").css('overflow-y') != 'hidden') {
               $("body").css('overflow-y', 'hidden');
           } else {
               $("body").css('overflow-y', 'scroll');
           }
           
           update_cover_picture($("#cover-picture"))
        }
    });
});

$("#cover-picture").mouseover(function () {
   $(this).find("#trigger-update-cover").css('top', '5px').css('opacity', '1')
});

$("#cover-picture").mouseleave(function () {
   $(this).find("#trigger-update-cover").css('top', '-35px').css('opacity', '0')
});