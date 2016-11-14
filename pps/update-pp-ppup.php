<?php
    require_once('../config.php');
    if ($config->user->loggedin()) :
?>
    <link rel="stylesheet" href="pps/css/update-pp-ppup.css">

    <div id="update-pp-ppup">
        <div class="wrap">
            <div id="tab-triggers">
                <div class="tab-trigger toggled" data-tab-index="1"  id="trigger-from-existingtab"><i class="fa fa-list" aria-hidden="true"></i><span class="detail">Select From Pictures</span></div>
                <div class="tab-trigger" data-tab-index="2" id="trigger-upload-tab"><i class="fa fa-cloud-upload" aria-hidden="true"></i><span class="detail">Upload Picture</span></div>
            </div>

            <div id="tabs-wrap">
                <div class="update-pp-ppup-tab toggled" id="main-tab-aka-select-from-uploaded-pictures">
                    <div class="uploded-pictures"></div>
                    <div id="show-more-uploded-pictures">Show More...</div>
                </div>
                
                <div class="update-pp-ppup-tab" id="upload-picture-tab">
                    <input type="file" id="selected-files-to-upload-as-pp" style="display: none" accept="image/*">
                    <div id="trigger-update-pp-choose-from-pc"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Choose File</div>
                    <div id="preview-selected-image-to-upload-as-pp"></div>
                </div>
            </div>
            
            <input type="submit" value="update" id="submit-update-pp">
        </div>
    </div>
    
    <script src="pps/js/update-pp-ppup.js"></script>
<?php endif; ?>