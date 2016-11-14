$.each($(".tab-trigger"), function () {
    $(this).click(function () {
        $("#update-pp-ppup #tabs-wrap").children().hide();
        $($("#update-pp-ppup #tabs-wrap").children()[$(this).data('tab-index') - 1]).show();
    });
});

window.uploaded_pictures_page = 1;

function load_uploaded_pictures (page) {
    page = new FormData;
    page.append('page', window.uploaded_pictures_page);
    
    $.ajax({
        url: 'pps/includes/get_user_existing_pictures.php',
        processData: false,
        contentType: false,
        method: "POST",
        data: page,
        success: function (data) {
            $("#main-tab-aka-select-from-uploaded-pictures").find(".uploded-pictures").append(data);
            select_from_uploded();
        }
    });
}

load_uploaded_pictures(window.uploaded_pictures_page);

$("#show-more-uploded-pictures").click(function () {
    window.uploaded_pictures_page++;
    load_uploaded_pictures(window.uploaded_pictures_page);
});

// Select one from uploded
function select_from_uploded () {
    $.each($(".uploded-pictures .uploded-picture"), function () {
        $(this).click(function () {
            $.each($(".uploded-pictures .uploded-picture"), function () {
                $(this).data('selected', "false").removeClass("selected");
            });
            
            $(this).data('selected', "true").toggleClass("selected");
        });
    });
}

// Choose Picture from computer
$("#trigger-update-pp-choose-from-pc").click(function () {
    $("#selected-files-to-upload-as-pp").click();
});

$("#selected-files-to-upload-as-pp").change(function () {
    var file = $(this)[0].files[0];
    var reader = new FileReader();

     reader.addEventListener("load", function () {
        $("#preview-selected-image-to-upload-as-pp").css('background-image', 'url(' + reader.result + ')').fadeIn();
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    } 
});

// Submit update profile picture
function update_picture (element) {
    $("#submit-update-pp").click(function () {
        if ($("#main-tab-aka-select-from-uploaded-pictures").css('display') == 'block') {
            window.profile_picture_element = element;
            
            index = new FormData;
            index.append('index', $(".uploded-pictures").find(".selected").data('index'));
            
            $.ajax({
                url: 'form_submitions/update_profile_picture_by_index.php',
                processData: false,
                contentType: false,
                method: "POST",
                data: index,
                success: function (data) {
                    update_picture_element();
                }
            });
        }
        
        if ($("#upload-picture-tab").css('display') == 'block') {
            window.profile_picture_element = element;
            
            file = new FormData;
            file.append('file', $("#selected-files-to-upload-as-pp")[0].files[0]);
            
            $.ajax({
                url: 'form_submitions/update_profile_picture_by_upload.php',
                processData: false,
                contentType: false,
                method: "POST",
                data: file,
                success: function (data) {
                    console.log(data);
                    update_picture_element();
                }
            });
        } 
        
        function update_picture_element () {
            $.ajax({
                url: 'general_scripts/get_user_profile_picture_url.php',
                processData: false,
                contentType: false,
                method: "POST",
                success: function (data) {
                    if (!window.profile_picture_element.is('img')) {
                        $(window.profile_picture_element[0]).css('background-image', 'url("media/' + data + '")');
                    } else {
                        window.profile_picture_element.src = 'media/' + data;
                    }
                }
            }); 
        }
        
        $("#poups-wrap").removeClass("toggled");
    });
}