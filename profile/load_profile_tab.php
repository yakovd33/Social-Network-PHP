<?php
    function load_profile_tab ($tab, $username, $config) {
        if ($tab == 'photos') {
            include 'profile_tabs/photos_tab.php';
            load_profile_photos_tab($username, $config);
        }

        if ($tab == 'feed') {
            include 'profile_tabs/feed_tab.php';
            load_profile_feed_tab($username, $config);
        }

        if ($tab == 'about') {
            include 'profile_tabs/about_tab.php';
            load_profile_about_tab($username, $config);
        }

        if ($tab == 'friends') {
            include 'profile_tabs/friends_tab.php';
            load_profile_friends_tab($username, $config);
        }
    }

    if (isset($_POST['tab']) && isset($_POST['type']) && $_POST['context']) {
        set_include_path ('../');
        require_once('../config.php');

        $type = $_POST['type'];

        $tab = $_POST['tab'];
        if ($type == 'username') {
            $username = $_POST['context'];
        } else if ($type == 'id') {
            $username = $config->link->query("SELECT `username` FROM `users` WHERE `id` = {$_POST['context']}")->fetch()['username'];
        }

        load_profile_tab($tab, $username, $config);
    }
?>