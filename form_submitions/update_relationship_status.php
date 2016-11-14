<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['relationship'])) {
            $option = $_POST['relationship'];
            
            $relationship_options = array(
                'Single',
                'In a relationship',
                'Engaged',
                'Married',
                'Divorced',
                'Widowed',
                'No Interest'
            );
            
            if (in_array($option, $relationship_options)) {
                $update_relationship_stmt = $config->link->query("UPDATE `users` SET `relationship` = '{$option}' WHERE `id` = {$_SESSION['user_id']}");
            }
        }
    }
?>