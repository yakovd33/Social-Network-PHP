<?php
    require_once('config.php');
    add_visit();

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    } else {
        $query = "show me random content";
    }

    if (!isset($_GET['c'])) {
        $category = 'people';
    } else {
        $category = $_GET['c'];
    }

    $special_commands = array('show me random content');

    if (!in_array($query, $special_commands)) {
        if ($category == 'people') {
            $get_people_stmt = $config->link->query("
                SELECT * FROM `users` WHERE `username` LIKE '%{$query}' OR `username` LIKE '{$query}%'
                LIMIT 15
            ");
        } else if ($category == 'posts') {
            if ($config->user->loggedin()) {
                $get_friends_posts_stmt = $config->link->query("
                    SELECT * FROM `posts` WHERE `user_id` IN
                    (SELECT `user_two_id` FROM `friendships` WHERE `user_one_id` = {$_SESSION['user_id']}
                    UNION SELECT `user_one_id` FROM `friendships` WHERE `user_two_id` = {$_SESSION['user_id']} UNION SELECT 1 from friendships)
                    AND `post_text` LIKE '%{$query}' OR `post_text` LIKE '{$query}%'
                    ORDER BY posts.id DESC
                    AND `deleted` = 0
                    LIMIT 15
            ");
            }

            $get_public_posts_stmt = $config->link->query("
                SELECT * FROM `posts` WHERE `user_id`
                AND `post_text` LIKE '%{$query}' OR `post_text` LIKE '{$query}%'
                AND `scope` = 'public'
                ORDER BY posts.id DESC
                AND `deleted` = 0
                LIMIT 15
            ");
        }
    } else {
        if ($query == 'show me random content') {
            $get_friends_posts_stmt = $config->link->query("
                SELECT * FROM `posts` WHERE `user_id` = 1
                ORDER BY posts.id DESC
                AND `deleted` = 0
                LIMIT 15
            ");
        }
    }

    require_once('general_scripts/fetch_post.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Search Results For '<?php echo $query; ?>' - <?php echo $_Sys->appName; ?></title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <title><?php echo $_Sys->appName; ?> - HomePage</title>
        <noscript><meta http-equiv="refresh" content="0; url=error.php?error=_no_js" /></noscript>
        
        <script src="https://code.jquery.com/jquery-1.12.3.min.js" integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>
        <script src="scripts/general/jquery.cookie.js"></script>
        
        <link rel="stylesheet" href="stylesheets/general/main.css">
        <link rel="stylesheet" href="stylesheets/general/sidebar.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="stylesheets/general/toast.css">
        <link rel="stylesheet" href="stylesheets/general/search.css">
        <link rel="stylesheet" href="stylesheets/general/feed.css">
    </head>
    <body>
        <?php if ($config->user->loggedin()) : ?>
            <?php include 'general_scripts/logged_nav.php'; ?>
        <?php else : ?>
            <?php include 'general_scripts/guest_nav.php'; ?>
            <link rel="stylesheet" href="stylesheets/general/guest_main.css">
        <?php endif; ?>
            
        <?php include 'chat.php'; ?>
        <div class="site-wrap">
            <div class="row site-sub-wrap">
                    <div class="grid__col grid__col--3-of-5 results_wrap">
                        <ul id="search-select-cat">
                            <li class="search-cat-item <?php if ($category == 'people') { echo 'selected'; } ?>"><a href="search.php?q=<?php echo $query; ?>&c=people">People</></li>
                            <li class="search-cat-item <?php if ($category == 'posts') { echo 'selected'; } ?>"><a href="search.php?q=<?php echo $query; ?>&c=posts">Posts</a></li>
                            <li class="search-cat-item <?php if ($category == 'photos') { echo 'selected'; } ?>"><a href="search.php?q=<?php echo $query; ?>&c=photos">Photos</a></li>
                        </ul>
                        <?php if ($category == 'people') : ?>
                            <?php if (!in_array($query, $special_commands)) : ?>
                            <?php while ($people = $get_people_stmt->fetch()) : ?>
                                <div class="search-result people" data-uid="<?php echo $people['id']; ?>">
                                    <div class="user-details">
                                        <a href="profile.php?username=<?php echo $config->user->get_other_user_info_by_id($people['id'])["username"]; ?>">
                                            <div class="pp" style="background-image: url('media/<?php echo $config->user->get_other_users_profile_picture($people['id']); ?>')"></div>
                                        </a>
                                        <div class="textual">
                                            <a href="profile.php?username=<?php echo $config->user->get_other_user_info_by_id($people['id'])["username"]; ?>">
                                                <div class="fullname"><?php echo $config->user->get_other_user_info_by_id($people['id'])["fullname"]; ?></div>
                                            </a>

                                            <a href="profile.php?username=<?php echo $config->user->get_other_user_info_by_id($people['id'])["username"]; ?>">
                                                <div class="username"><?php echo $config->user->get_other_user_info_by_id($people['id'])["username"]; ?></div>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="people-options">
                                        <?php if ($config->user->loggedin() && $people['id'] != $_SESSION['user_id']) : ?>
                                            <div class="friend-request-actions people-option">
                                                    <?php if (!$config->check_friends_with($people['id']) && !$config->check_request_sent_to($people['id']) && !$config->check_request_sent_from($people['id'])) : ?>
                                                        <div class="action-wrap">
                                                            <div class="send-request" class="friendship-action" title="Send Friend Request">
                                                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                    <?php endif;  ?>
                                                    
                                                    <?php if ($config->check_request_sent_to($people['id'])) : ?>
                                                        <div class="action-wrap">
                                                            <div class="cancel-request" class="friendship-action" title="Cancel Friend Request">
                                                                <i class="fa fa-user-times" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($config->check_request_sent_from($people['id'])) : ?>
                                                        
                                                    <?php endif; ?>
                                                </div>
                                            <i title="Chat With <?php echo $config->user->get_other_user_info_by_id($people['id'])['fullname']; ?>" class="fa fa-comment chatbox-trigger people-option" aria-hidden="true" data-username="<?php echo $config->user->get_other_user_info_by_id($people['id'])["username"]; ?>"></i>
                                       <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>

                        <?php elseif ($category == 'posts') : ?>
                            <?php if ($config->user->loggedin()) : ?>
                                <?php while ($post = $get_friends_posts_stmt->fetch()) : ?>
                                    <?php
                                        $post_to_fetch = $config->link->query("SELECT * FROM `posts` WHERE `id` = {$post['id']}");
                                        if ($config->user->loggedin()) {
                                            echo fetch_post($post_to_fetch->fetch(), $config);
                                        } else {
                                            echo fetch_guest_post($post_to_fetch->fetch(), $config);
                                        }
                                    ?>
                                <?php endwhile; ?>
                            <?php endif; ?>
                            <h5>Public Posts</h5>                            
                            <?php while ($post = $get_public_posts_stmt->fetch()) : ?>
                                <?php
                                    $post_to_fetch = $config->link->query("SELECT * FROM `posts` WHERE `id` = {$post['id']}");
                                    if ($config->user->loggedin()) {
                                        echo fetch_post($post_to_fetch->fetch(), $config);
                                    } else {
                                        echo fetch_guest_post($post_to_fetch->fetch(), $config);
                                    }
                                ?>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                    <div class="grid__col grid__col--2-of-5 sidebar-wrap">
                        <?php include 'general_scripts/sidebar.php'; ?>
                    </div>
                </div>
            </div>
        </div>

        <script src="scripts/general/main.js"></script>
        <script src="scripts/general/post.js"></script>
        <script src="scripts/general/pps.js"></script>
        <script src="scripts/general/chat.js"></script>
        <script src="scripts/general/search.js"></script>
    </body>
</html>