<?php
    require_once('../config.php');
    
    class form_submition {
        public function validate_token ($token_value, $session_name) {
            if ($token_value == $_SESSION[$session_name]) {
                return true;
            } else {
                return false;
            }
        }
    }
?>