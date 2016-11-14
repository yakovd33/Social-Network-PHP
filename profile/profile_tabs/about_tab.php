<?php
    function load_profile_about_tab ($username, $config) {
?>
        <div class="about-item" id="living-info">
            <div class="item-detail">
                <div class="item-detail-icon">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                </div>
                <div class="item-detail-text">Living Info</div>
            </div>

            <?php
                $user_country = $config->user->get_other_user_info_by_username($username)['country'];
                $user_country_code = $config->link->query("SELECT `code2` FROM `country` WHERE `Name` = '{$user_country}'")->fetch()['code2'];
            ?>

            
            <div class="about-tab-item-sub-item" id="country">
                <div class="item-result">
                    <div id="country-flag">
                        <img src="http://flagpedia.net/data/flags/normal/<?php echo strtolower($user_country_code); ?>.png">
                    </div>

                    <div id="country-name">
                        From <?php echo $config->user->get_other_user_info_by_username($username)['city']; ?>, <?php echo $user_country; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-item" id="living-info">
            <div class="item-detail">
                <div class="item-detail-icon">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                </div>
                <div class="item-detail-text">Relationship Info</div>
            </div>
            
            <div class="about-tab-item-sub-item" id="country">
                <div class="item-result">
                    <p><?php echo $config->user->get_other_user_info_by_username($username)['relationship']; ?></p>
                </div>
            </div>
        </div>
<?php
    }
?>