<?php
    require_once('../../config.php');
    
    if ($config->user->loggedin()) {
        if (isset($_POST['page'])) {
            $page = $_POST['page'];
            
            if ($page == 1) {
                $start = 0;
            } else {
                $start = $page * 12;
            }
            
            $stmt = $config->link->query("
                SELECT * FROM `photos`
                WHERE `user_id` = {$_SESSION['user_id']}
                AND `type` = 'timeline-image' OR `type` = 'profile-picture' OR `type` = 'cover-picture'
                ORDER BY `photos`.`date` DESC
                LIMIT " . $start . ", 12
            ");
            
            while ($image = $stmt->fetch()) {
                $is_big = rand(1, 3);
?>
                <div class="uploded-picture" data-index="<?php echo $image['user_photos_index']; ?>" style="background-image: url('media/<?php echo $image['path']; ?>'); <?php if ($is_big == 1) { echo 'width: ' . rand(110, 400) . 'px'; } ?>"></div>
<?php
            }
        }
    }
?>