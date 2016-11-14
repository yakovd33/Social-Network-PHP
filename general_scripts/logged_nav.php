<link rel="stylesheet" href="stylesheets/general/nav.css">

<?php
if ($config->user->loggedin()) {
    $link = $config->db->getLink();
?>
    <nav>
        <div id="top-nav">
            <a href="index.php"><div id="website-top-nav-logo"></div></a>

            <div id="menu-search-bar">
                <form id="menu-search-bar-wrap" method="GET" action="search.php">
                    <div id="search-icon"><i class="fa fa-search"></i></div>
                    <input type="text" id="search-input" placeholder="Search..." name="q" autocomplete="off">
                </form>
            </div>

            <div id="nav-links">
                <a class="nav-link" id="menu-user-profile-link" href="profile.php?username=<?php echo $user_info['username']; ?>">
                    <span class="link-content">
                        <img src="media/<?php echo $config->user_profile_picture ?>"><span><?php echo $user_info['fullname']; ?></span>
                    </span>
                </a>

                <a class="nav-link" id="" href="settings.php">
                    <span class="link-content">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                    </span>
                </a>

                <a class="nav-link" id="logout" href="#">
                    <span class="link-content">
                        <i class="fa fa-power-off" aria-hidden="true"></i>
                    </span>
                </a>
            </div>


            <div id="nav-dropdown-items">
                <div id="dropdowns-triggers">
                    <div class="trigger" id="notifics-trigger">
                        <div class="trigger-icon" style="background-image: url('media/icons/notifications.png')"></div>
                        <div class="trigger-text">Notifications</div>
                    </div>
                </div>
                <div class="dropdown-item" id="notifics-dropdown">
                    <div class="dropdown-content-wrap">
                        <div class="dropdown-content">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div id="toggle-mobile-menu">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </div>
        </div>

        <div id="mobile-nav">
            <a class="nav-link" id="menu-user-profile-link" href="profile.php?username=<?php echo $user_info['username']; ?>">
                <span class="link-content">
                    <img src="media/<?php echo $config->user_profile_picture ?>">
                    <span class="link-text"><?php echo $user_info['fullname']; ?></span>
                </span>
            </a>

            <a class="nav-link" id="" href="settings.php">
                <span class="link-content">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    <span class="link-text">Settings</span>
                </span>
            </a>

            <a class="nav-link" id="logout" onclick="logout()" href="#">
                <span class="link-content">
                    <i class="fa fa-power-off" aria-hidden="true"></i>
                    <span class="link-text">Logout</span>
                </span>
            </a>
        </div>
    </nav>
<?php
} else {
    
}
?>

<script src="scripts/general/nav.js"></script>