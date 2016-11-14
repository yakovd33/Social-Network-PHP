<?php
    class profile_sidebar {
        private $user_details;
        private $link;

        public function __construct ($user_details, $link) {
            $this->user_details = $user_details;
            $this->link = $link;
        }

        public function get_username () {
            return $this->user_details['username'];
        }

        public function check_if_info_filled ($info) {
            if ($this->user_details[$info] != NULL || $this->user_details[$info] != '') {
                return true;
            } else {
                return false;
            }
        }

        public function get_bio () {
            if ($this->user_details['biography'] == NULL || $this->user_details['biography'] == '') {
                if ($this->user_details['id'] == $_SESSION['user_id']) {
                    return 'You\'re Biography Hasn\'t Been Filled Yet.';
                } else {
                    return 'User has no biography.';
                }
            } else {
                return $this->user_details['biography'];
            }
        }

        public function fetch_recent_six_images($user_id) {
            return $this->link->query("SELECT * FROM `photos` WHERE `user_id` = {$user_id} AND `type` <> 'comment-image' LIMIT 6");
        }
    }

    $sidebar = new profile_sidebar($user_details, $config->link);
?>

<div class="sidebar">
    <?php if ($user_details['id'] != $_SESSION['user_id']) : ?>
        <div id="friend-request-actions">
                <?php if (!$config->check_friends_with($user_details['id']) && !$config->check_request_sent_to($user_details['id']) && !$config->check_request_sent_from($user_details['id'])) : ?>
                    <div class="action-wrap">
                        <div id="send-request" class="friendship-action" title="Send Friend Request">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                            <h5>Send A Friend Request</h5>
                        </div>
                    </div>
                <?php endif;  ?>
                
                <?php if ($config->check_request_sent_to($user_details['id'])) : ?>
                    <div class="action-wrap">
                        <div id="cancel-request" class="friendship-action" title="Cancel Friend Request">
                            <i class="fa fa-user-times" aria-hidden="true"></i>
                            <h5>Cancel Friend Request</h5>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($config->check_request_sent_from($user_details['id']) && !$config->check_request_sent_to($user_details['id']) && !$config->check_friends_with($user_details['id'])) : ?>
                    <div id="profile-friendship-approval-wrap">
                        <div class="approval-option" id="profile-approval-approve">
                            
                            <div class="approval-text">
                                Approve
                            </div>
                        </div>
                        <div class="approval-option" id="profile-approval-decline">
                            <div class="approval-text">
                                Decline
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($config->check_friends_with($user_details['id'])) : ?>
                    <div id="toggle-friends-actions-lisy" class="friendship-action">Friends </div>
                    <div id="friends-actions">
                        <div class="friends-action">Unfriend</div>
                        <div class="friends-action">Block User</div>
                    </div>
                <?php endif; ?>

                <div data-username="<?php echo $user_details['username']; ?>" id="trigger-message-from-sidebar" class="friendship-action">Message</div>
            </div>
    <?php endif; ?>

    <div class="sidebar-card" id="about-card">
        <div class="sidebar-card-title">
            <div class="title-icon"><i class="fa fa-globe" aria-hidden="true"></i></div><h3>About</h3>
            <div class="collapse-current-sidebar-tab" data-tab="about" title="Collapse This Tab"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
        </div>

        <div class="sidebar-card-content">
            <div class="about-item" id="bio">
                <div id="bio-wrap"><?php echo $sidebar->get_bio(); ?></div>

                <div class="toggle-edit" title="Edit Bio Status" style="display: none;">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </div>
                
                <?php if ($user_details['id'] == $_SESSION['user_id']) : ?>
                    <form id="update-bio" class="edit_about_item">
                        <div id="update-bio-input" contenteditable="plaintext-only"><?php echo $sidebar->get_bio(); ?></div>

                        <input type="submit" value="Update" class="submit-update-info">
                    </form>
                <?php endif; ?>
                <div class="clearfix"></div>
            </div>

                <div class="about-item" id="living">
                    <div class="item-icon"><i class="fa fa-home" aria-hidden="true"></i></div>
            <?php if ($sidebar->check_if_info_filled('country') || $sidebar->check_if_info_filled('city')) : ?>
                    <?php if ($sidebar->check_if_info_filled('country')) : ?>
                        Lives In <a id="user-country" href="related?country=<?php echo $user_details['country']; ?>"><?php echo $user_details['country']; ?></a>
                    <?php endif; ?>

                    <?php if ($sidebar->check_if_info_filled('city')) : ?>, <a id="user-city" href="related?country=<?php echo $user_details['country']; ?>&city=<?php echo $user_details['city']; ?>"><?php echo $user_details['city']; ?></a>
                    <?php endif; ?>
            <?php else : ?>
                No Living Info
            <?php endif; ?>
                <?php if ($user_details['id'] == $_SESSION['user_id']) : ?>
                    <div class="toggle-edit" title="Edit Living Details">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </div>

                    <form id="update-living" class="edit_about_item">
                        <div id="edit_country">
                            <div class="title">Country <i class="fa fa-angle-down" aria-hidden="true"></i></div><label class="selection"><?php if ($user_details['country'] != NULL) : echo $user_details['country']; endif; ?></label>
                            <div class="selection-box">
                                <input type="text" placeholder="Filter Countries" id="filter_countries">
                                <p class="filter-feedback" id="filter-countries-feedback"></p>
                                <select id="filtered-countries" size="30">
                                </select>
                            </div>
                        </div>

                        <div id="edit_city">
                            <select id="cities-list" <?php if (!$sidebar->check_if_info_filled('country')) : echo 'style="opacity: 0.5; cursor: not-allowed;" title="Select Country First" data-country_filled="false"'; endif;?> class="title">
                                <?php if ($sidebar->check_if_info_filled('country')) : ?>
                                    <option value="<?php echo $user_details['city']; ?>"><?php echo $user_details['city']; ?></option>
                                    <?php
                                        $get_country_cities_stmt = $config->link->query("SELECT `Name` FROM `city` WHERE `country` = '{$user_details['country']}'");
                                        while ($city = $get_country_cities_stmt->fetch()) :
                                    ?>
                                            <option value="<?php echo $city['Name']?>"><?php echo $city['Name']?></option>
                                    <?php
                                        endwhile;
                                    ?>
                                <?php else : ?>
                                    <option selected disabled value="" style="display: none;">City</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <input type="submit" value="Update" class="submit-update-info">
                    </form>
                <?php endif; ?>

                <div class="clearfix"></div>
            </div>

            <div class="about-item" id="relationship">
                <div class="item-icon"><i class="fa fa-heart" aria-hidden="true"></i></div>
                <span id="user-relationship-status"><?php echo $user_details['relationship']; ?></span>

                <div class="toggle-edit" title="Edit Relationship Status" style="display: none;">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </div>

                <?php if ($user_details['id'] == $_SESSION['user_id']) : ?>
                    <form class="edit_about_item" id="edit_relationship">
                        <?php
                            $relationship_options = array(
                                'single' => 'Single',
                                'In a relationship' => 'In a relationship',
                                'Engaged' => 'Engaged',
                                'Married' => 'Married',
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed',
                                'No Interest' => 'No Interest'
                            );

                            //unset($relationship_options[$user_details['relationship']]);
                        ?>
                        <select id="relationship-options">
                            <option value="<?php echo $user_details['relationship']; ?>"><?php echo $user_details['relationship']; ?></option>
                            <?php foreach ($relationship_options as $option) : ?>
                                <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                            <?php endforeach; ?>
                            <input type="submit" value="Update" class="submit-update-info">
                        </select>
                    </form>

                    <div class="clearfix"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

     <div class="sidebar-card" id="images-card">
          <div class="sidebar-card-title">
               <a href="photos.php?username=<?php echo $user_details['username']; ?>"><div class="title-icon"><i class="fa fa-globe" aria-hidden="true"></i></div><h3>Photos</h3></a>
               <div class="collapse-current-sidebar-tab" data-tab="images" title="Collapse This Tab"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
          </div>
          
          <div class="sidebar-card-content">
               <?php $fetched_images = $sidebar->fetch_recent_six_images($user_details['id']);
                    while ($image = $fetched_images->fetch()) :?>
                         <div class="sidebar-images-image" style="background-image: url('media/<?php echo $image['path']; ?>'"></div>
               <?php endwhile; ?>
          </div>
     </div>
     
     <footer>
        <div id="footer-credit">
            The Pub 2016 ©    
        </div>
        
        <div id="footer-links">
            <a href="#" class="footer-link">Terms of use</a>
            <a href="#" class="footer-link">Contact Us</a>
            <a href="#" class="footer-link">Advertise</a>
        </div>
    </footer>
</div>