<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    
    class _Sys {
        public $appName;
        
        public function __construct ($appName) {
            $this->appName = $appName;
        }
        
        public function page_doesnt_exists_404 () {
            echo '
                <div class="wrap-404">
                    <h2 id="main-header-404">404 Page Does Not Exists</h2>
                    <div id="actions-404">
                        <a href="#" onclick="history.back()">Go Back</a>
                        <a href="index.php">Go Home</a>
                    </div>
                </div>
            ';
        }
    }
    
    /////////// Config class - connects the DB and User classes ///////////
    class Config {
        public $link;
        public $db;
        public $user;
        public $post;
        public $user_profile_picture;
        
        public function __construct () {
            $this->db = new db();
            $this->link = $this->db->getLink();
            $this->a = $this->db->getA();
            
            $this->user = new user();
            $this->post = new post();
        }

        public function getUserCount () {
            return $this->link->query("SELECT * FROM `users`")->rowCount();
        }
        
        public function user_fits_profile_posts_scope ($scope, $userid) {
            if ($scope == 'friends') {
                if ($this->check_friends_with($userid)) {
                    return true;    
                }
            }
            
            if ($userid == $_SESSION['user_id']) {
                return true;
            }
            
            if ($scope == 'public') {
                return true;
            } else {
                return false;
            }
        }
        
        public function user_fits_profile_can_post_scope ($scope, $userid) {
            if ($scope == 'friends') {
                if ($this->check_friends_with($userid)) {
                    return true;    
                }
            }
            
            if ($userid == $_SESSION['user_id']) {
                return true;
            }
            
            if ($scope == 'public') {
                return true;
            } else {
                return false;
            }
        }
        
        
        public function check_friends_with ($user_id) {
            $stmt = $this->link->query("
                SELECT * FROM `friendships` WHERE `request_state` = 'approved'
                AND `user_one_id` = {$_SESSION['user_id']} AND `user_two_id` = {$user_id}
                OR `user_two_id` = {$_SESSION['user_id']} AND `user_one_id` = {$user_id}
                AND `request_state` = 'approved'
            ");
            
            if ($stmt->rowCount() == 1) {
                return true;
            } else {
                return false;
            }            
        }
        
        public function check_request_sent_to ($user_id) {
            $stmt = $this->link->query("
                SELECT * FROM `friendships` WHERE
                `user_one_id` = {$_SESSION['user_id']} AND `user_two_id` = {$user_id}
            ");
            
            if ($stmt->rowCount() == 1) {
                return true;
            } else {
                return false;
            }
        }
        
        public function check_request_sent_from ($user_id) {
            $stmt = $this->link->query("
                SELECT * FROM `friendships` WHERE
                `user_two_id` = {$_SESSION['user_id']} AND `user_one_id` = {$user_id}
            ");
            
            if ($stmt->rowCount() == 1) {
                return true;
            } else {
                return false;
            }
        }
        
        public function time_to_text ($time) {
            $time = strtotime($time) - 10800;
            $time_offset = floor(time() - $time);
            
            // Just Now
            if ($time_offset < 10) {
                return 'Just now';
            }
            
            // Seconds
            if ($time_offset > 10 && $time_offset < 60) {
                return $time_offset . 's';
            }
            
            // Minutes
            if ($time_offset < 60 * 60) {
                if (round($time_offset / 60) . ' minutes' == 1) {
                    return '1 minute';
                } else {
                    return round($time_offset / 60) . ' minutes';
                }
            }
            
            // 5 Hours
            if ($time_offset < 60 * 60 * 5 && $time_offset > 60 * 60) {
                return round($time_offset / 60 / 60) . ' hours';
            }
            
            // Today
            if ($time_offset > 60 * 60 * 5) {
                date_default_timezone_set('Israel');
                return date('G:m', $time);
            }
        }
    }
    
    /////////// DB Class ///////////
    class db {
        public function getLink () {
            // return new PDO("mysql:host=localhost;dbname=yakovd33_social_network", "yakovd33_root", "100201d");
            return new PDO("mysql:host=localhost;dbname=social_network", "root", "");
        }
        
        public function getA () {
            return 1;
        }
    }
    
    /////////// a Class ///////////
    class user {        
        public function loggedin () {            
            if (isset($_SESSION['user_id'])) {
                return true;
            } else {
                return false;
            }
        }
        
        public function is_other_user_loggedin($user_id) {
            $config = new Config;
            $link = $config->db->getLink();
            $get_last_login_stmt = $link->query("SELECT `last login` FROM `users` WHERE `id` = {$user_id}");
            
            if (strtotime($get_last_login_stmt->fetch()['last login']) >= time()) {
                return true;
            } else {
                return false;
            }
        }
        
        public function getUserInfo () {
            $config = new Config();
            $link = $config->db->getLink();
            $stmt = $link->query("SELECT * FROM `users` WHERE `id` = {$_SESSION['user_id']}");
            return $stmt->fetch();
        }
        
        public function get_other_user_info_by_id ($user_id) {
            $config = new Config();
            $link = $config->db->getLink();
            $stmt = $link->query("SELECT * FROM `users` WHERE `id` = {$user_id}");
            return $stmt->fetch();
        }
        
        public function get_other_user_info_by_username ($username) {
            $config = new Config();
            $link = $config->db->getLink();
            $stmt = $link->query("SELECT * FROM `users` WHERE `username` = '{$username}'");
            return $stmt->fetch();
        }
        
        public function get_other_users_profile_picture ($user_id) {
            $config = new Config();
            $link = $config->db->getLink();
            
            $stmt = $link->query("SELECT `profile_photo_index` FROM `users` WHERE `id` = {$user_id}");
            $user_profile_picture = $stmt->fetch()['profile_photo_index'];

            if ($user_profile_picture == NULL) {
                $user_profile_picture = 'site_pictures/default-profile-picture.jpg';
            } else {
                $stmt = $config->link->query("SELECT `path` FROM `photos` WHERE `user_id` = {$user_id} AND `user_photos_index` = {$user_profile_picture}");
                $user_pp_path = $stmt->fetch()['path'];
                if ($user_pp_path != '' && file_exists('media/' . $user_pp_path)) {
                    $user_profile_picture = $user_pp_path;
                } else {
                    $user_profile_picture = 'site_pictures/default-profile-picture.jpg';
                }
            }
            
            return $user_profile_picture;
        }
        
        public function get_other_users_cover_picture ($user_id) {
            $config = new Config();
            $link = $config->db->getLink();
            
            $stmt = $link->query("SELECT `cover_photo_index` FROM `users` WHERE `id` = {$user_id}");
            $user_cover_picture = $stmt->fetch()['cover_photo_index'];
            
            if ($user_cover_picture == NULL) {
                $user_cover_picture = 'site_pictures/default-cover.jpg';
            } else {
                $stmt = $config->link->query("SELECT `path` FROM `photos` WHERE `user_id` = {$user_id} AND `user_photos_index` = {$user_cover_picture}");
                $user_cp_path = $stmt->fetch()['path'];
                if ($user_cp_path != '' && file_exists('media/' . $user_cp_path)) {
                    $user_cover_picture = $user_cp_path;
                } else {
                    $user_cover_picture = 'site_pictures/default-cover.jpg';          
                }
            }
            
            return $user_cover_picture;
        }

        public function get_mutual_friends ($userid) {
            $config = new Config();
            $sum_mutual = 0;
            $get_all_users_friends = $config->link->query("SELECT * FROM `friendships` WHERE `user_one_id` = {$userid} OR `user_two_id` = {$userid} AND `request_state` = 'approved'");
            while ($friendship = $get_all_users_friends->fetch()) {
                if ($friendship['user_one_id'] == $userid) {
                    $friend_id = $friendship['user_two_id'];
                } else {
                    $friend_id = $friendship['user_one_id'];
                }

                if ($config->check_friends_with($friend_id)) {
                    $sum_mutual++;
                }
            }
            
            if ($sum_mutual == 0) {
                return '';
            } else if ($sum_mutual == 1) {
                return '1 Mutual Friend';
            } elseif ($sum_mutual > 0) {
                return $sum_mutual . ' Mutual Friends';
            }
            
            return 'No Mutual Friends';
        }

        public function send_notific_to_user ($type, $to_user_id, $extras) {
            $config = new Config();
            if ($to_user_id != $_SESSION['user_id']) {
                $notific_insert_stmt_query = "";

                if ($type == 'comment_on_post') {
                    $post_comments_times = $config->link->query("SELECT `times` FROM `notifications` WHERE `post_id` = {$extras['post_id']} AND `type` = 'comment_on_post' AND `saw` = 0");
                    if ($post_comments_times->rowCount() == 0) {
                        $notific_insert_stmt_query = "INSERT INTO `notifications`(`id`, `type`, `to_user_id`, `last_from_user_id`, `post_id`, `comment_index`, `date`, `saw`, `clicked`)
                        VALUES (NULL, 'comment_on_post', {$to_user_id}, {$_SESSION['user_id']}, {$extras['post_id']}, {$extras['post_comment_index']}, NOW(), '0', '0')";
                    } else {
                        $new_times = $post_comments_times->fetch()['times'] + 1;
                        $new_total_action_makers = $config->link->query("SELECT `total_action_makers` FROM `notifications` WHERE `post_id` = {$extras['post_id']} AND `type` = 'comment_on_post'")->fetch()['total_action_makers'];
                        
                        $did_comment_stmt = $config->link->query("SELECT * FROM `posts_comments` WHERE `commenter_id` = {$_SESSION['user_id']} AND `post_id` = {$extras['post_id']} AND `active` = 1");
                        if ($did_comment_stmt->rowCount() < 2) {
                            $new_total_action_makers++;
                        }

                        $notific_insert_stmt_query = "UPDATE `notifications` SET `last_from_user_id` = {$_SESSION['user_id']}, `times` = {$new_times}, `total_action_makers` = {$new_total_action_makers}, `saw` = 0, `clicked` = 0 WHERE `post_id` = {$extras['post_id']}";
                    }
                }

                if ($type == 'like_on_post') {
                    /*if ($config->link->query("SELECT `id` FROM `posts_likes` WHERE `liker_id` = {$_SESSION['user_id']} AND `post_id` = {$extras['post_id']}")->rowCount() == 0) {
                        $new_total_action_makers++;
                    }*/

                    $notific_insert_stmt_query = "INSERT INTO `notifications`(`id`, `type`, `to_user_id`, `last_from_user_id`, `post_id`, `comment_index`, `date`, `saw`, `clicked`)
                    VALUES (NULL, 'like_on_post', {$to_user_id}, {$_SESSION['user_id']}, {$extras['post_id']}, NULL, NOW(), '0', '0')";
                }

                $notific_insert_stmt = $config->link->query($notific_insert_stmt_query);
            }
        }

        public function did_like_post ($postid) {
            $config = new Config();
            $did_like_stmt = $config->link->query("SELECT * FROM `posts_likes` WHERE `liker_id` = {$_SESSION['user_id']} AND `post_id` = {$postid} AND `active` = 1");
        
            if ($did_like_stmt->rowCount() == 1) {
                return true;
            } else {
                return false;
            }
        }

        public function did_comment_post ($postid) {
            $config = new Config();
            $did_comment_stmt = $config->link->query("SELECT * FROM `posts_comments` WHERE `commenter_id` = {$_SESSION['user_id']} AND `post_id` = {$postid} AND `active` = 1");
        
            if ($did_comment_stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function get_friendship_date_with_user ($user_id) {
            $config = new Config();
            return $config->link->query("SELECT `approved_date` FROM `friendships`
            WHERE `user_one_id` = {$user_id} AND `user_two_id` = {$_SESSION['user_id']}
            OR `user_one_id` = {$_SESSION['user_id']} AND `user_two_id` = {$user_id}")->fetch()['approved_date'];
        }
    }

    class post {
        function is_post_commentable ($post_id) {
            $config = new Config();
            
            $check_commentable_stmt = $config->link->query("SELECT `comments_enabled` FROM `posts` WHERE `id` = {$post_id}");

            if ($check_commentable_stmt->fetch()['comments_enabled']) {
                return true;
            } else {
                return false;
            }
        }

        function delete_post ($postid) {
            global $config;
            $post_details = $config->link->query("SELECT * FROM `posts` WHERE `id` = {$postid}")->fetch();
            if ($post_details['user_id'] == $_SESSION['user_id']) {
                $post_delete_stmt = $config->link->query("UPDATE `posts` SET `deleted` = 1 WHERE `id` = {$postid}");
            }
        }
    }
    
    $_Sys = new _Sys('Social Network');
    $config = new Config();
    
    if ($config->user->loggedin()) {
        $user_info = $config->user->getUserInfo();
        
        $user_cover = $user_info['cover_photo_index'];
        if ($user_cover == NULL) {
            $user_cover = 'site_pictures/default-cover.jpg';
        } else {
            $stmt = $config->link->query("SELECT `path` FROM `photos` WHERE `user_id` = {$_SESSION['user_id']} AND `user_photos_index` = {$user_cover}");
            if ( $stmt->fetch()['path'] != '' && file_exists('media/' . $stmt->fetch()['path'])) {
                $user_cover = $stmt->fetch()['path'];
            } else {
                $user_cover = 'site_pictures/default-cover.jpg';
            }
        }
        
        $config->user_profile_picture =  $user_info['profile_photo_index'];
        if ($config->user_profile_picture == NULL) {
            $config->user_profile_picture = 'site_pictures/default-profile-picture.jpg';
        } else {
            $stmt = $config->link->query("SELECT `path` FROM `photos` WHERE `user_id` = {$_SESSION['user_id']} AND `user_photos_index` = {$config->user_profile_picture}");
            $user_pp_path =$stmt->fetch()['path'];
            if ($user_pp_path != '' && file_exists('media/' . $user_pp_path)) {
                $config->user_profile_picture = $user_pp_path;
            } else {
                $config->user_profile_picture = 'site_pictures/default-profile-picture.jpg';
            }
        }
    }

    // Log
    $log = fopen("../log.txt", 'a');

    function log_m ($log, $message) {
        $date = getdate()['mday'] . '.' . getdate()['mon'] . '.' . getdate()['year'] . ' ' . getdate()['hours'] . ':' . getdate()['minutes'] . ':' . getdate()['seconds'];
        fwrite($log, $message . " " . $date . "\n");
    }

    function add_visit () {
        // Add view to db
        global $config;
        if(isset($_SERVER['HTTP_REFERER'])) {
            $refer = $_SERVER['HTTP_REFERER'];
        } else {
            $refer = "";
        }

        $config->link->query("INSERT INTO  `yakovd33_social_network`.`visits` (`id` ,`ip` ,`date`, `page`, `refer`)VALUES (NULL ,  '" . $_SERVER['REMOTE_ADDR'] . "', NOW(), '" . $_SERVER['REQUEST_URI'] . "', '" . $refer . "')");
    }
?>