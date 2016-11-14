<?php
    require_once('../config.php');
    if (isset($_POST['postid'])) {
        $config->post->delete_post($_POST['postid']);
    }
?>