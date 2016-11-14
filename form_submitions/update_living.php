<?php
    require_once('../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['country']) && isset($_POST['city'])) {
            $country = $_POST['country'];
            $city = $_POST['city'];
            
            $user_last_country = $config->link->query("SELECT `country` FROM `users` WHERE `id` = {$_SESSION['user_id']}")->fetch()['country'];
            $user_last_city = $config->link->query("SELECT `city` FROM `users` WHERE `id` = {$_SESSION['user_id']}")->fetch()['city'];
            
            if ($country == '' || $country == 'null' || $country == NULL) {
                $country = $user_last_country;
            }
            
            if ($city == '' || $city == 'null' || $city == NULL) {
                $city = $user_last_city;
            }
            
            $check_city_in_country_stmt = $config->link->query("SELECT * FROM `city` WHERE `Name` = '{$city}' AND `country` = '{$country}'");
                        
            if ($check_city_in_country_stmt->rowCount() > 0) {
                $update_living_stmt = $config->link->query("UPDATE `users` SET `country` = '{$country}', `city` = '{$city}' WHERE `id` = {$_SESSION['user_id']}");
            }
        }
    }
?>