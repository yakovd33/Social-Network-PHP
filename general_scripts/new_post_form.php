<?php
    $new_post_token = md5(uniqid(microtime(), true));
    $_SESSION['new_post_token'] = $new_post_token;
?>

    <form id="new-post-form" onsubmit="">
        <h4 id="new-post-title">New Post</h4>
        
        <div id="editor-wrap">
            <div id="post-input-wrap">
                <div id="user-image">
                    <img src="media/<?php echo $config->user_profile_picture ?>">
                </div>
                <div id="post-input" placeholder="Share Your Thoughts..." contenteditable="plaintext-only"></div>
            </div>
            
            <ul id="new-post-tags">
                <div id="ready-tags">
                </div>
                <li id="new-tag" contenteditable="plaintext-only"></li>
            </ul>
            
            <div id="image-preview">
                <div id="preview-wrap">
                    <span id="diselect-selected-pictures"><i class="fa fa-times"></i></span>
                </div>
            </div>
            
            <div id="new-post-actions">
                <div id="post-additional">
                    <input type="file" id="additional-image">
                    <div class="additional-item" title="Additional Image" id="trigger-additional-image"><i class="fa fa-camera-retro"></i></div>
                    
                    <div id="post-tags" title="Add Tags">
                        <i class="fa fa-hashtag" aria-hidden="true"></i>
                    </div>
                </div>
                
                <div id="submition-actions">
                    <div id="select-post-scope">
                        <div id="selected-scope"><span class="scope-list-item"><i class="fa fa-globe"></i><span class="selected-scope-text">Public</span></span><i class="fa fa-angle-down"></i></div>
                        <div id="list">
                            <h6>Who Can See This?</h6>
                            <span class="scope-list-item"><i class="fa fa-globe"></i><span class="selected-scope-text">Public</span></span>
                            <span class="scope-list-item"><i class="fa fa-users"></i><span class="selected-scope-text">Friends</span></span>
                            <span class="scope-list-item"><i class="fa fa-lock"></i><span class="selected-scope-text">Only Me</span></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="new-post-token" value="<?php echo $new_post_token; ?>">
                    <input type="submit" id="submit-new-post" value="Post">
                </div>
            </div>
        </div>
    </form>