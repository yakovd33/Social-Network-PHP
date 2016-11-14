<?php
    require_once('config.php');
    add_visit();
    if ($config->user->loggedin()) {
?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Account Settings - <?php echo $_Sys->appName; ?></title>
            <meta charset="UTF-8">
            <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
            <title><?php echo $_Sys->appName; ?> - HomePage</title>
            <noscript><meta http-equiv="refresh" content="0; url=error.php?error=_no_js" /></noscript>
            <script src="https://code.jquery.com/jquery-1.12.3.min.js" integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>
            <script src="scripts/general/jquery.cookie.js"></script>
                  
            <link rel="stylesheet" href="stylesheets/general/main.css">
            <link rel="stylesheet" href="stylesheets/general/sidebar.css">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="stylesheets/general/toast.css">
            <link rel="stylesheet" href="stylesheets/settings/settings.css">
        </head>
        <body>
            <?php
                $tab_list = [
                    'general',
                    'security',
                    'experience'
                ];
                
                if (isset($_GET['tab'])) {
                    if (in_array(strToLower($_GET['tab']), $tab_list)) {
                        $tab = strToLower($_GET['tab']);
                    } else {
                        $tab = 'general';
                    }
                } else {
                    $tab = 'general';
                }
                
                    include('general_scripts/logged_nav.php');
                ?>
                <div class="site-wrap grid">
                    <div class="row site-sub-wrap">
                        <div class="grid__col grid__col--3-of-5 grid" id="settings-wrap">
                            <?php if (!isset($_GET['action'])) : ?>
                            <div class="grid__col grid__col--2-of-6">
                                <ul id="settings-menu">
                                    <a href="settings.php?tab=general">
                                        <li class="settings-menu-item <?php if ($tab == 'general') : echo 'selected'; endif; ?>">
                                            <span class="settings-menu-item-icon">
                                                <i class="fa fa-cog" aria-hidden="true"></i>
                                            </span>General
                                        </li>
                                    </a>
                                    
                                    <a href="settings.php?tab=security">
                                        <li class="settings-menu-item <?php if ($tab == 'security') : echo 'selected'; endif; ?>">
                                            <span class="settings-menu-item-icon">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            </span>Security
                                        </li>
                                    </a>

                                    <a href="settings.php?tab=experience">
                                        <li class="settings-menu-item <?php if ($tab == 'experience') : echo 'selected'; endif; ?>">
                                            <span class="settings-menu-item-icon">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </span>Experience
                                        </li>
                                    </a>
                                </ul>
                            </div>
                            <?php endif; ?>

                            <div class="grid__col grid__col--4-of-6">
                                <?php if ($tab == 'general') : ?>
                                    <?php
                                        if (isset($_GET['section'])) {
                                            $allowed_sections = [
                                                'change_password',
                                                'change_email',
                                                'change_fullname',
                                                'change_username',
                                                'change_gender',
                                            ];
                                            
                                            if (in_array(strToLower($_GET['section']), $allowed_sections)) {
                                                $section = $_GET['section'];
                                            } else {
                                                $section = '';
                                            }
                                        } else {
                                            $section = '';
                                        }
                                    ?>

                                        <h5 class="tab-cat">Account Info</h5>
                                        <!-- password section -->
                                        <div class="section" id="settings-change-password-section">
                                            <div class="section-content-toggle" data-section="change_password">
                                                Change Password <div class="edit-icon"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                            </div>
                                            <div class="section-content-wrap" style="<?php if ($section == 'change_password') : echo 'display: block'; endif; ?>">
                                                <input type="password" placeholder="Current Password" id="current-password-input" class="info-update-input">
                                                <input type="password" placeholder="New Password" id="new-password-input" class="info-update-input">
                                                <input type="password" placeholder="New Password Again" id="new-password-again-input" class="info-update-input">
                                                <!-- Security Token -->
                                                <input type="hidden" id="update_pass_token" value="<?php echo $_SESSION['update_password_session'] = md5(uniqid(microtime(), true)); ?>">
                                                <div id="change_password_feedback"></div>
                                                <input type="submit" value="Update" class="update-current-setting">
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Email section -->
                                        <div class="section" id="settings-change-email-section">
                                            <div class="section-content-toggle" data-section="change_email">
                                                Change Email <div class="edit-icon"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                            </div>
                                            <div class="section-content-wrap" style="<?php if ($section == 'change_email') : echo 'display: block'; endif; ?>">
                                                <input type="email" id="new-email-input" value="<?php echo $config->user->getUserInfo()['email']; ?>" class="info-update-input" autocomplete="on">
                                                <input type="hidden" id="update_email_token" value="<?php echo $_SESSION['update_email_session'] = md5(uniqid(microtime(), true)); ?>">
                                                <div id="change_email_feedback"></div>
                                                <input type="submit" value="Update" class="update-current-setting">
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Fullname section -->
                                        <div class="section" id="settings-change-fullname-section">
                                            <div class="section-content-toggle" data-section="change_fullname">
                                                Change Fullname <div class="edit-icon"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                            </div>
                                            <div class="section-content-wrap" style="<?php if ($section == 'change_fullname') : echo 'display: block'; endif; ?>">
                                                <input type="text" id="new-fullname-input" value="<?php echo $config->user->getUserInfo()['fullname']; ?>" class="info-update-input">
                                                <input type="hidden" id="update_fullname_token" value="<?php echo $_SESSION['update_fullname_session'] = md5(uniqid(microtime(), true)); ?>">
                                                <div id="change_fullname_feedback"></div>
                                                <input type="submit" value="Update" class="update-current-setting">
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Username section -->
                                        <div class="section" id="settings-change-username-section">
                                            <div class="section-content-toggle" data-section="change_username">
                                                Change Username <div class="edit-icon"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                            </div>
                                            <div class="section-content-wrap" style="<?php if ($section == 'change_username') : echo 'display: block'; endif; ?>">
                                                <input type="username" id="new-username-input" value="<?php echo $config->user->getUserInfo()['username']; ?>" class="info-update-input" autocomplete="on">
                                                <input type="hidden" id="update_username_token" value="<?php echo $_SESSION['update_username_session'] = md5(uniqid(microtime(), true)); ?>">
                                                <div id="change_username_feedback"></div>
                                                <input type="submit" value="Update" class="update-current-setting">
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                
                                <?php elseif ($tab == 'security') : ?>
                                <?php
                                    if (isset($_GET['section'])) {
                                        $allowed_sections = [
                                            'who_can_see_your_posts',
                                            'who_can_post_on_my_wall',
                                        ];
                                        
                                        if (in_array(strToLower($_GET['section']), $allowed_sections)) {
                                            $section = $_GET['section'];
                                        } else {
                                            $section = '';
                                        }
                                    } else {
                                        $section = '';
                                    }
                                ?>
                                
                                <h5 class="tab-cat">Profile</h5>
                                <!-- Who can see your posts section -->
                                <div class="section" id="settings-who-can-see-your-posts-section">
                                    <div class="section-content-toggle" data-section="who_can_see_your_posts">
                                        Who Can See Your Posts <div class="edit-icon"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                    </div>
                                    <div class="section-content-wrap" style="<?php if ($section == 'who_can_see_your_posts') : echo 'display: block'; endif; ?>">
                                        <div class="select-post-scope">
                                            <div class="selected-scope"><span class="scope-list-item">
                                                <?php if ($config->user->getUserInfo()['profile_posts_scope'] == 'public') : ?><i class="fa fa-globe"></i><span class="selected-scope-text">Public</span></span><i class="fa fa-angle-down"></i><?php endif; ?>
                                                <?php if ($config->user->getUserInfo()['profile_posts_scope'] == 'friends') : ?><i class="fa fa-users"></i><span class="selected-scope-text">Friends</span></span><i class="fa fa-angle-down"></i><?php endif; ?>
                                                <?php if ($config->user->getUserInfo()['profile_posts_scope'] == 'only me') : ?><i class="fa fa-lock"></i><span class="selected-scope-text">Only Me</span></span><i class="fa fa-angle-down"></i><?php endif; ?>
                                            </div>
                                            <div id="list">
                                                <h6>Who Can See This?</h6>
                                                <span class="scope-list-item"><i class="fa fa-globe"></i><span class="selected-scope-text">Public</span></span>
                                                <span class="scope-list-item"><i class="fa fa-users"></i><span class="selected-scope-text">Friends</span></span>
                                                <span class="scope-list-item"><i class="fa fa-lock"></i><span class="selected-scope-text">Only Me</span></span>
                                            </div>
                                        </div>
                                        <div id="update-profile-posts-scope-feedback"></div>
                                        <input type="submit" value="Update" class="update-current-setting">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                
                                <!-- Who can post on your profile -->
                                <div class="section" id="settings-who-can-post-on-your-profile-section">
                                    <div class="section-content-toggle" data-section="who_can_post_on_my_wall">
                                        Who Can Post On Your Profile <div class="edit-icon"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                    </div>
                                    <div class="section-content-wrap" style="<?php if ($section == 'who_can_post_on_my_wall') : echo 'display: block'; endif; ?>">
                                        <div class="select-post-scope">
                                            <div class="selected-scope"><span class="scope-list-item">
                                                <?php if ($config->user->getUserInfo()['post_on_wall_scope'] == 'public') : ?><i class="fa fa-globe"></i><span class="selected-scope-text">Public</span></span><i class="fa fa-angle-down"></i><?php endif; ?>
                                                <?php if ($config->user->getUserInfo()['post_on_wall_scope'] == 'friends') : ?><i class="fa fa-users"></i><span class="selected-scope-text">Friends</span></span><i class="fa fa-angle-down"></i><?php endif; ?>
                                                <?php if ($config->user->getUserInfo()['post_on_wall_scope'] == 'only me') : ?><i class="fa fa-lock"></i><span class="selected-scope-text">Only Me</span></span><i class="fa fa-angle-down"></i><?php endif; ?>
                                            </div>
                                            <div id="list">
                                                <h6>Who Can See This?</h6>
                                                <span class="scope-list-item"><i class="fa fa-globe"></i><span class="selected-scope-text">Public</span></span>
                                                <span class="scope-list-item"><i class="fa fa-users"></i><span class="selected-scope-text">Friends</span></span>
                                                <span class="scope-list-item"><i class="fa fa-lock"></i><span class="selected-scope-text">Only Me</span></span>
                                            </div>
                                        </div>
                                        <div id="update-profile-wall-post-ability-scope-feedback"></div>
                                        <input type="submit" value="Update" class="update-current-setting">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <?php elseif ($tab == 'experience') : ?>
                                    <?php
                                        if (isset($_GET['section'])) {
                                            $allowed_sections = [
                                                'show_comments_preview',
                                            ];
                                            
                                            if (in_array(strToLower($_GET['section']), $allowed_sections)) {
                                                $section = $_GET['section'];
                                            } else {
                                                $section = '';
                                            }
                                        } else {
                                            $section = '';
                                        }
                                    ?>

                                    <h5 class="tab-cat">Posts</h5>
                                    <div class="section" id="show-comments-preview-section">
                                        <div class="section-content-toggle" data-section="show_comments_preview">
                                            Show Post Comments Preview <div class="edit-icon"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                        </div>
                                        <div class="section-content-wrap" style="<?php if ($section == 'show_comments_preview') : echo 'display: block'; endif; ?>">
                                            <div class="toggle-option" id="toggle-comments-preview">
                                                <?php if ($config->user->getUserInfo()['show_comments_preview']) : ?>
                                                    Turn Option Off
                                                <?php else : ?>
                                                    Turn Option On
                                                <?php endif; ?>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    
                    <div class="grid__col grid__col--2-of-5">
                        <?php
                            include 'general_scripts/sidebar.php'; 
                            include 'chat.php';
                        ?>
                    </div>
                </div>
            </div>
            
            <script>window.tab = '<?php echo $tab; ?>'</script>
            <script src="scripts/general/main.js"></script>
            <script src="scripts/general/settings.js"></script>
        </body>
    </html>   
<?php
    } else {
        header("Location: index.php");
    }
?>