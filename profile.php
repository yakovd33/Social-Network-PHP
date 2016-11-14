<?php
    require('config.php');
    require('profile/load_profile_tab.php');
    require('templates/feed_post_template.php');
    require('general_scripts/get_post_comments_preview.php');
    require('templates/post_comments_template.php');

    add_visit();
    
    class profile {
        
    }
    
    if (isset($_GET['username'])) {
        $user_details_stmt = $config->db->getLink();
        $user_details = $user_details_stmt->query("SELECT * FROM `users` WHERE `username` = '{$_GET['username']}'")->fetch();
    } else {
        if ($config->user->loggedin()) {
            header("Location: ?username=" . $config->user->getUserInfo()['username']);
        } else {
            header("Location: index.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $config->user->get_other_user_info_by_username($_GET['username'])['fullname']; ?>'s Profile - <?php echo $_Sys->appName; ?></title>
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <title><?php echo $_Sys->appName; ?> - HomePage</title>
        <noscript><meta http-equiv="refresh" content="0; url=error.php?error=_no_js" /></noscript>
        
        <script src="https://code.jquery.com/jquery-1.12.3.min.js" integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>
        <script src="scripts/general/jquery.cookie.js"></script>
        
        <link rel="stylesheet" href="stylesheets/general/main.css">
        <link rel="stylesheet" href="stylesheets/general/feed.css">
        <link rel="stylesheet" href="stylesheets/profile/sidebar.css">
        <link rel="stylesheet" href="stylesheets/general/new_post_form.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="stylesheets/general/toast.css">
    </head>
    <body>
        <?php if ($config->user->loggedin()) : ?>
            <link rel="stylesheet" href="stylesheets/profile/logged_profile.css">
            <?php include 'general_scripts/logged_nav.php'; ?>
            
            <div id="poups-wrap">
                <div id="poups-bg"></div>
                    <div id="pps_wrap">
                        
                    </div>
                </div>
            </div>
            
            <div class="site-wrap grid">
                <div class="row site-sub-wrap">
                    <div class="grid__col grid__col--3-of-5 feed-wrap" style="word-break: break-word">
                        <div id="main-profile-header" style="position: relative">
                            <div id="cover-picture" style="background-image: url('media/<?php echo $config->user->get_other_users_cover_picture($user_details['id']); ?>')">
                                <?php if ($user_details['id'] == $_SESSION['user_id']) : ?>
                                    <div id="trigger-update-cover" style="top: -35px; opacity: 0;">
                                        <i class="fa fa-camera-retro" aria-hidden="true"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                                
                                <div id="profile-user-details">
                                    <div id="main-profile-picture" style="position: relative; background-image: url(media/<?php echo $config->user->get_other_users_profile_picture($user_details['id']); ?>)">
                                        <?php if ($user_details['id'] == $_SESSION['user_id']) : ?>
                                            <div id="trigger-image-update"><i class="fa fa-camera-retro" aria-hidden="true"></i></div>
                                        <?php endif; ?>
                                        <div id="is-logged" class="<?php if ($config->user->is_other_user_loggedin($user_details['id'])) { echo 'logged'; } else { echo 'disconnected'; } ?>"></div>
                                    </div>
                                    <div id="main-profile-textual">
                                        <div id="fullname"><?php echo $user_details['fullname']; ?></div> <div id="username">@<?php echo $user_details['username']; ?></div>
                                    </div>
                                </div>
                        </div>

                        <div id="profile-nav">
                            <a href="profile.php?username=<?php echo $user_details['username']; ?>" class="nav-link <?php if (!isset($_GET['tab']) || $_GET['tab'] == 'feed') { echo 'active'; } ?>">Feed</a>
                            <a href="#" class="nav-link <?php if (isset($_GET['tab']) && $_GET['tab'] == 'about') { echo 'active'; } ?>">About</a>
                            <a href="#" class="nav-link <?php if (isset($_GET['tab']) && $_GET['tab'] == 'friends') { echo 'active'; } ?>">Friends</a>
                            <a href="#" class="nav-link <?php if (isset($_GET['tab']) && $_GET['tab'] == 'photos') { echo 'active'; } ?>">Photos</a>
                        </div>
                        
                        <div id="profile-tabs">
                            <div id="profile-feed-tab" class="profile-tab">
                                <?php if (!isset($_GET['tab']) || $_GET['tab'] == 'feed') : ?>
                                    <?php
                                        if ($config->user->loggedin() && $config->user_fits_profile_can_post_scope($user_details['post_on_wall_scope'], $user_details['id'])) {
                                            include 'general_scripts/new_post_form.php';
                                        }
                                    ?>
                                
                                    <?php if ($config->user_fits_profile_posts_scope($user_details['profile_posts_scope'], $user_details['id'])) : ?>
                                        <div id="feed-posts-wrap">
                                        </div>
                                        <div id="feed_show_more">
                                            <div class="loader">
                                            
                                            </div>    
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div id="profile-about-tab" class="profile-tab">
                                <?php
                                    if (isset($_GET['tab']) && $_GET['tab'] == 'about') {
                                        load_profile_tab('about', $user_details['username'], $config);
                                    }
                                ?>
                            </div>

                            <div id="profile-friends-tab" class="profile-tab">
                                <?php
                                    if (isset($_GET['tab']) && $_GET['tab'] == 'friends') {
                                        load_profile_tab('friends', $user_details['username'], $config);
                                    }
                                ?>
                            </div>

                            <div id="profile-photos-tab" class="profile-tab">
                                <?php
                                    if (isset($_GET['tab']) && $_GET['tab'] == 'photos') {
                                        load_profile_tab('photos', $user_details['username'], $config);
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="grid__col grid__col--2-of-5 sidebar-wrap">
                        <?php
                            require('profile/profile_sidebar.php');
                            include 'chat.php';
                        ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <link rel="stylesheet" href="stylesheets/general/guest_main.css">
            <?php
                include('general_scripts/guest_nav.php');
            ?>
        <?php endif; ?>
        
        
        
        <?php if ($config->user->loggedin()) : ?>
            <script src="scripts/profile/logged_profile.js"></script>
            <?php if ($user_details['id'] == $_SESSION['user_id']) : ?>
                <script src="scripts/profile/update_info.js"></script>
                <link rel="stylesheet" href="stylesheets/profile/update_info.css">
                <script src="scripts/general/new_post.js"></script>
            <?php else : ?>
                <script src="scripts/profile/new_profile_post.js"></script>
            <?php endif; ?>
            <?php if ($config->user_fits_profile_posts_scope($user_details['profile_posts_scope'], $user_details['id'])) : ?>
                <script src="scripts/profile/profile_feed.js"></script>
            <?php endif; ?>
        <?php endif; ?>
        
        <script>window.userid = <?php echo $user_details['id']; ?></script>
        <script src="scripts/general/main.js"></script>
        <script src="scripts/general/pps.js"></script>
        <script src="scripts/general/chat.js"></script>
        <script src="scripts/profile/profile.js"></script>
    </body>
</html>