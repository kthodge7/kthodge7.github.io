<?php
class SessionManager {
    private $inactive_timeout = 1800; // 30 minutes
    private $absolute_timeout = 14400; // 4 hours
    private $regenerate_interval = 300; // 5 minutes

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function checkSession() {
        try {
            // Check if user is logged in
            if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
                $this->endSession('Please log in to continue.');
                return false;
            }

            // Check for session timeouts
            if ($this->isSessionExpired()) {
                $this->endSession('Your session has expired. Please log in again.');
                return false;
            }

            // Regenerate session ID periodically
            if ($this->shouldRegenerateSession()) {
                $this->regenerateSession();
            }

            // Update last activity time
            $_SESSION['last_activity'] = time();
            
            return true;

        } catch (Exception $e) {
            error_log("Session error: " . $e->getMessage());
            $this->endSession('An error occurred. Please log in again.');
            return false;
        }
    }

    private function isSessionExpired() {
        $current_time = time();

        // Check inactivity timeout
        if (isset($_SESSION['last_activity']) && 
            ($current_time - $_SESSION['last_activity']) > $this->inactive_timeout) {
            return true;
        }

        // Check absolute session timeout
        if (isset($_SESSION['session_started']) && 
            ($current_time - $_SESSION['session_started']) > $this->absolute_timeout) {
            return true;
        }

        return false;
    }

    private function shouldRegenerateSession() {
        return !isset($_SESSION['last_regeneration']) || 
               (time() - $_SESSION['last_regeneration']) > $this->regenerate_interval;
    }

    private function regenerateSession() {
        // Backup session data
        $session_data = $_SESSION;
        
        // Regenerate session ID and delete old session
        session_regenerate_id(true);
        
        // Restore session data and update regeneration time
        $_SESSION = $session_data;
        $_SESSION['last_regeneration'] = time();
    }

    private function endSession($message = '') {
        // Clear session data
        $_SESSION = array();

        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }

        // Destroy the session
        session_destroy();

        // Handle AJAX requests
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'redirect' => 'login.php', 'message' => $message]);
            exit;
        }

        // Handle regular requests
        header("Location: login.php" . (!empty($message) ? "?message=" . urlencode($message) : ""));
        exit;
    }
}

// Create global function for easy access
function checkLogin() {
    static $sessionManager = null;
    if ($sessionManager === null) {
        $sessionManager = new SessionManager();
    }
    return $sessionManager->checkSession();
}

// Set security headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("Content-Security-Policy: default-src 'self' https://cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com;");
?>