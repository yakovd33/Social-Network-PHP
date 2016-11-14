<?php
    require_once('../config.php');
    if ($config->user->loggedin()) {
        if (isset($_POST['new_scope'])) {
            $scope = trim(strtolower($_POST['new_scope']));
            if (!in_array($scope, array('public', 'friends', 'only me'))) {
                $scope = 'public';
            }

            $config->link->query("UPDATE `users` SET `profile_posts_scope` = '{$scope}' WHERE `id` = {$_SESSION['user_id']}");
            echo 'Profile Posts Scope Setting Updated.';
        }
    }
?>