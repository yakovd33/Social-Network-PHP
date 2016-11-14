<?php
    require_once('config.php');
    require('templates/feed_post_template.php');
    require('general_scripts/get_post_comments_preview.php');
    require('templates/post_comments_template.php');
    add_visit();
?>

<?php if (isset($_GET['user_id']) && isset($_GET['phash']) && !empty($_GET['user_id']) && !empty($_GET['phash'])) : ?>
<?php
    $user_id = $_GET['user_id'];
    $post_details_stmt = $config->link->query("SELECT * FROM `posts` WHERE `user_id` = {$_GET['user_id']} AND `post_hash` = '{$_GET['phash']}'");
    
    if ($post_details_stmt->rowCount() > 0) {
        $post_details = $post_details_stmt->fetch();
        $poster_details = $config->link->query("SELECT * FROM `users` WHERE `id` = {$post_details['user_id']}")->fetch();
        $page_title = 'A post by ' . $poster_details['fullname'] . ' - ' . $_Sys->appName;;
    } else {
        $page_title = '404 Post not found - ' . $_Sys->appName;;
    }
?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title><?php echo $page_title; ?></title>
            <meta charset="UTF-8">
            <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
            <title><?php echo $_Sys->appName; ?> - HomePage</title>
            <noscript><meta http-equiv="refresh" content="0; url=error.php?error=_no_js" /></noscript>
            
            <script src="https://code.jquery.com/jquery-1.12.3.min.js" integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>
            <script src="scripts/general/jquery.cookie.js"></script>
            
            <link rel="stylesheet" href="stylesheets/general/main.css">
            <link rel="stylesheet" href="stylesheets/general/sidebar.css">
            <link rel="stylesheet" href="stylesheets/general/new_post_form.css">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="stylesheets/general/toast.css">
            <link rel="stylesheet" href="stylesheets/general/feed.css">
            <link rel="stylesheet" href="stylesheets/general/post.css">
        </head>
            
        <body>
            <?php if ($config->user->loggedin()) : ?>
                <?php include 'general_scripts/logged_nav.php'; ?>
                <?php include 'chat.php'; ?>
            <?php else : ?>
                <link rel="stylesheet" href="stylesheets/general/guest_main.css">
                <?php include 'general_scripts/guest_nav.php'; ?>
            <?php endif; ?>
            
            <div class="site-wrap">
                <div class="row site-sub-wrap">
                    <?php if ($post_details_stmt->rowCount() > 0) : ?>
                        <div class="grid__col grid__col--3-of-5 feed-wrap">
                            <div id="feed-posts-wrap">
                            <?php                                
                                $post_stmt = $config->link->query("SELECT * FROM `posts` WHERE `user_id` = {$user_id} AND `post_hash` = '{$_GET['phash']}' ORDER BY `posts`.`id` DESC LIMIT 1");
                                $post_details = $post_stmt->fetch();
                                $post_details_posted_date = $config->time_to_text($post_details['posted date']);

                                $post_num_comments = $config->link->query("SELECT * FROM `posts_comments` WHERE `post_id` = {$post_details['id']} AND `active` = 1")->rowCount();

                                feed_post_template($post_details, $post_details_posted_date, $config);
                            ?>
                        </div>
                            
                        <div class="grid__col grid__col--2-of-5 sidebar-wrap">
                            <?php include 'general_scripts/sidebar.php'; ?>
                        </div>
                    <?php else : ?>
                        <?php $_Sys->page_doesnt_exists_404(); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <script>
                data = new FormData;
                data.append('postid', $(".post-wrap").data('postid'));
                data.append('show_all', 1);
                
                $.ajax({
                    url: 'general_scripts/get_post_comments.php',
                    processData: false,
                    contentType: false,
                    method : "POST",
                    data : data,
                    success: function (e) {
                        $(".post-wrap .post-comments-section .comments-wrap").show().find(".post-comment-section").html(e);
                        comments();
                        comments_likes();
                        <?php if (isset($_GET['comment_i'])) : ?>
                            desired_top = $($(".comment")[<?php echo $post_num_comments - $_GET['comment_i'] - 1; ?>]).offset().top;
                            $('body').animate({ scrollTop: desired_top });
                            $($(".comment")[<?php echo $_GET['comment_i'] - $post_num_comments; ?>]).hide();
                        <?php endif; ?>
                    }
                });
            </script>
            
            <script>window.userid = <?php echo $poster_details['id']; ?></script>
            <script src="scripts/general/main.js"></script>
            <script src="scripts/general/pps.js"></script>
            <script src="scripts/general/chat.js"></script>
            <script src="scripts/general/post.js"></script>
        </body>
    </html>
<?php else : header("Location: index.php"); ?>
<?php endif; ?>