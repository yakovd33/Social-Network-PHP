<?php
    /////////// Includes ///////////
    require('config.php');
    add_visit();
    
    if ($config->user->loggedin()) {
        $user_state = 'logged';
    } else {
        $user_state = 'guest';
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <noscript><meta http-equiv="refresh" content="0; url=error.php?error=_no_js" /></noscript>
        
        <title><?php echo $_Sys->appName; ?> - HomePage</title>
        
        <script src="https://code.jquery.com/jquery-1.12.3.min.js" integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>
        <script src="scripts/general/jquery.cookie.js"></script>
        <script src='https://www.google.com/recaptcha/api.js?hl=en'></script>
        
        <link rel="stylesheet" href="stylesheets/general/main.css">
        <link rel="stylesheet" href="stylesheets/index/<?php echo $user_state; ?>_index.css">
        <link rel="stylesheet" href="stylesheets/general/sidebar.css">
        <link rel="stylesheet" href="stylesheets/general/toast.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body>
        <?php
            if ($config->user->loggedin()) {
            ?>
                <link rel="stylesheet" href="stylesheets/general/guest_main.css">
            <?php
                include 'general_scripts/logged_nav.php';
            } else {
                include 'general_scripts/guest_nav.php';
            }
        ?>
        
        <div id="poups-wrap">
            <div id="poups-bg"></div>
            <div id="pps_wrap">
                
            </div>
        </div>
        
        <div class="site-wrap grid">
            <?php if ($config->user->loggedin()) :?>
                    <div class="row site-sub-wrap">
                        <div class="grid__col grid__col--3-of-5 feed-wrap">
                            <div class="row">
                                <?php include 'general_scripts/new_post_form.php'; ?>
                            </div>
                            
                            <div class="row">
                                <?php include 'general_scripts/feed.php' ?>
                            </div>
                        </div>
                        
                        <div class="grid__col grid__col--2-of-5 sidebar-wrap">
                            <?php include 'general_scripts/sidebar.php'; ?>
                        </div>
                    </div>
                    
                    <?php include 'general_scripts/chat.php'; include 'chat.php'; ?>
            <?php else :?>
            
            <div class="grid__col grid__col--3-of-5" id="website-description">
                <h1 id="website-description-title">Welcome to <?php echo $_Sys->appName; ?></h1>
                <p id="website-description-desc">
                    Join <?php echo $config->getUserCount(); ?> other users and socialize with people all around the world
                    <br>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut sed feugiat ex. Pellentesque nisi nunc, condimentum ac leo ut, hendrerit tincidunt massa. Integer luctus ullamcorper augue viverra iaculis. Curabitur imperdiet, est eget ultricies aliquam, velit sapien pulvinar risus, a egestas urna tortor vitae neque. Fusce in nisi sodales, fringilla purus quis, aliquet diam. Sed euismod convallis neque, sed vestibulum elit. In neque ex, commodo eu tortor in, elementum condimentum magna.
                </p>
                <div id="index_guest_hero"></div>
            </div>

            <div class="grid__col grid__col--2-of-5" id="join-form-container">
                <?php include 'general_scripts/join_form.php'; ?>
            </div>
            
            <?php endif; ?>
            <script src="scripts/general/main.js"></script>
            <script src="scripts/general/new_post.js"></script>
            <script src="scripts/index/<?php echo $user_state; ?>_index.js"></script>
            <script src="scripts/general/pps.js"></script>
        </div>
    </body>
</html>