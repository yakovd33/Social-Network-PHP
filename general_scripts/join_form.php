<form id="register-form" class="grid" action="join.php" method="POST">
    <div id="moving-bars" class="row">
        <div id="bars">
            <div data-color="#ffc900" class="moving-bar"></div>
            <div data-color="#D90D11" class="moving-bar"></div>
            <div data-color="#4863A0" class="moving-bar"></div>
            <div data-color="#ffc900" class="moving-bar"></div>
            <div data-color="#D90D11" class="moving-bar"></div>
            <div data-color="#4863A0" class="moving-bar"></div>
        </div>
        
        <div id="form-wrap" class="row">
            <h1 class="form-title">Join Us</h1>
            <div class="row">
                <div class="grid__col grid__col--1-of-2 input-wrap">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                
                <div class="grid__col grid__col--1-of-2 input-wrap">
                    <label>Username</label>
                    <input type="username" name="username">
                </div>
            </div>
            
            <div class="row" id="passwords_row">
                <div class="grid__col grid__col--1-of-2 input-wrap">
                    <label>Pssword</label>
                    <input type="password" name="password">
                </div>
                
                <div class="grid__col grid__col--1-of-2 input-wrap">
                    <label>Repeat Password</label>
                    <input type="password" name="re_pass">
                </div>

                <div id="gender-selection" class="input-wrap">
                    <label>Gender</label>
                    <select id="checked-gender" name="gender">
                        <option value="Male" checked>Male</option>
                        <option value="Female" checked>Female</option>
                        <option value="Other" checked>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="row" id="validation">
                <div class="g-recaptcha" data-sitekey="6LdUdhwTAAAAAFicslBOzUkDLDMcRVh2dPNqBo1z"></div>
            </div>
            
            <div class="row" id="join-form-validation-feedback">
                
            </div>
            
            <div class="row" id="submition">
                <input type="submit" value="Join">
            </div>
        </div>
    </div>
</form>

<script src="scripts/general/join.js"></script>