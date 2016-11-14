<?php
    require_once('config.php');
?>

<?php 
?>

<?php
    if (isset($_GET['username'])) {
        $user_details_stmt = $config->db->getLink();
        $user_details = $user_details_stmt->query("SELECT * FROM `users` WHERE `username` = '{$_GET['username']}'")->fetch();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php if (isset($_GET['username'])) : ?>
            <title><?php echo $user_details['fullname']; ?>'s Photos - <?php echo $_Sys->appName; ?></title>
        <?php endif; ?>
        <noscript><meta http-equiv="refresh" content="0; url=error.php?error=_no_js" /></noscript>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-1.12.3.min.js" integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>
        <script src="scripts/general/jquery.cookie.js"></script>
        <script src='https://www.google.com/recaptcha/api.js?hl=en'></script>
        <link rel="stylesheet" href="stylesheets/general/sidebar.css">
        <link rel="stylesheet" href="stylesheets/general/main.css">
        <link rel="stylesheet" href="stylesheets/general/toast.css">
        <link rel="stylesheet" href="stylesheets/photos/user_photos.css">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
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
        
        <div class="site-wrap">
            <?php if (isset($_GET['username'])) : ?>
                <div id="user-pictures"></div>
            <?php endif; ?>
        </div>
        
        <?php if ($config->user->loggedin()) : ?>
            <?php if ($user_details['id'] == $_SESSION['user_id']) : ?>
                <?php include 'chat.php'; ?>
            <?php endif; ?>
        <?php endif; ?>
        
        <script>window.userid = <?php echo $user_details['id']; ?></script>
        <script src="scripts/photos/user_photos.js"></script>
        <script src="scripts/general/pps.js"></script>
        <script src="scripts/general/chat.js"></script>
        <script src="scripts/general/main.js"></script>
    </body>
</html>