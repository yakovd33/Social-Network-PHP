<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['new_scope'])) {
            
            $scope = trim(strtolower($_POST['new_scope']));
            if ($scope != 'public' && $scope != 'friends' && $scope != 'only me') {
                $scope = 'public';
            }
            
            $config->link->query("UPDATE `users` SET `post_on_wall_scope` = '{$scope}' WHERE `id` = {$_SESSION['user_id']}");
            echo 'Setting Updated.';
        }
    }
?>