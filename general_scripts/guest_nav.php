<link rel="stylesheet" href="stylesheets/general/guest_nav.css">

<nav id="guest-nav">
    <a href="index.php"><div id="website-top-nav-logo"></div></a>
    <form id="site-search-wrap" method="GET" action="search.php">
        <div id="search-icon"><i class="fa fa-search"></i></div>
        <input type="text" id="search-input" name="q" placeholder="Search...">
    </form>

    <?php
        if (isset($_GET['login_fail'])) {
            $login_fail = true;
        } else {
            $login_fail = false;
        }
    ?>
    
    <form id="login-wrap" class="grid" method="POST" action="login.php">
        <div id="login-info" class="grid__col grid__col--3-of-4">
            <div class="grid__col grid__col--1-of-2">
                <input type="email" name="email" <?php if ($login_fail && isset($_GET['retry_login_email'])) {echo 'class="wrong-field-value" value="' . $_GET['retry_login_email'] . '"';} ?> placeholder="Email">
                <div id="remember-me-wrap">
                    <label><input type="checkbox" name="remember" checked>Remember Me</label>
                </div>
            </div>
            
            <div class="grid__col grid__col--1-of-2">
                <input type="password" name="password" placeholder="Password" <?php if ($login_fail && isset($_GET['retry_login_email'])) {echo 'class="wrong-field-value"'; } ?>>
                <div id="forgot-password">
                    <a href="forgot_pass.php">Forgot Your Password?</a>
                </div>
            </div>
            
        </div>
        
        <input type='hidden' name='page_url' value='<?php echo $_SERVER['REQUEST_URI']; ?>' />
        <div id="submit-login"><i class="fa fa-sign-in" aria-hidden="true"></i></div>
        <input type="submit" style="display: none">
    </form>
</nav>

<script src="scripts/general/guest_nav.js"></script>