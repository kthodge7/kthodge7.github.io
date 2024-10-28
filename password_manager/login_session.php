<?php
class LoginAttemptHandler {
    private static function initLoginAttempts() {
        // First clear any existing scalar value if it exists
        if (isset($_SESSION['login_attempts']) && !is_array($_SESSION['login_attempts'])) {
            unset($_SESSION['login_attempts']);
        }
        
        // Initialize arrays if they don't exist
        if (!isset($_SESSION['login_attempts']) || !is_array($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = array();
        }
        
        if (!isset($_SESSION['lockout_time']) || !is_array($_SESSION['lockout_time'])) {
            $_SESSION['lockout_time'] = array();
        }
    }

    public static function getAttempts($email) {
        self::initLoginAttempts();
        return isset($_SESSION['login_attempts'][$email]) ? $_SESSION['login_attempts'][$email] : 0;
    }

    public static function increment($email) {
        self::initLoginAttempts();
        if (!isset($_SESSION['login_attempts'][$email])) {
            $_SESSION['login_attempts'][$email] = 0;
        }
        $_SESSION['login_attempts'][$email]++;
        
        if ($_SESSION['login_attempts'][$email] >= 3) {
            $_SESSION['lockout_time'][$email] = time();
        }
    }

    public static function reset($email) {
        self::initLoginAttempts();
        // Ensure we're working with arrays before setting values
        if (is_array($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'][$email] = 0;
        }
        if (is_array($_SESSION['lockout_time']) && isset($_SESSION['lockout_time'][$email])) {
            unset($_SESSION['lockout_time'][$email]);
        }
    }

    public static function isLocked($email) {
        self::initLoginAttempts();
        $attempts = self::getAttempts($email);
        if ($attempts >= 3) {
            if (isset($_SESSION['lockout_time'][$email])) {
                // Check if 30 minutes have passed
                if (time() - $_SESSION['lockout_time'][$email] > 1800) {
                    self::reset($email);
                    return false;
                }
                return true;
            }
            return true;
        }
        return false;
    }

    public static function getRemainingAttempts($email) {
        $attempts = self::getAttempts($email);
        return max(0, 3 - $attempts);
    }

    // Add a cleanup method
    public static function cleanup() {
        if (isset($_SESSION['login_attempts']) && is_array($_SESSION['login_attempts'])) {
            foreach ($_SESSION['login_attempts'] as $email => $attempts) {
                if (isset($_SESSION['lockout_time'][$email]) && 
                    (time() - $_SESSION['lockout_time'][$email] > 1800)) {
                    self::reset($email);
                }
            }
        }
    }
}

// Run cleanup when the file is included
LoginAttemptHandler::cleanup();
?>