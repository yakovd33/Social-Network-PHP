<?php
    require_once('../../config.php');
    
    if (isset($_GET['country_to_list'])) {
        $stmt = $config->link->query("SELECT `Name` FROM `city` WHERE `country` = '{$_GET['country_to_list']}'");
        
        while ($city = $stmt->fetch()) {
            echo '<option value="' . $city['Name'] . '">' . $city['Name'] . '</option>';
        }
    }
?>