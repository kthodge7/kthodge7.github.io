<?php
require_once "config.php";

class SessionCleanup {
    private $conn;
    private $inactive_timeout = 1800; // 30 minutes
    private $cleanup_log_file = 'session_cleanup.log';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function cleanup() {
        try {
            // Start transaction
            $this->conn->begin_transaction();

            // Get expired sessions
            $expired_time = time() - $this->inactive_timeout;
            
            // Log cleanup start
            $this->logCleanup("Starting session cleanup...");

            // Delete expired sessions
            $query = "DELETE FROM sessions WHERE last_activity < ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $expired_time);
            $stmt->execute();

            // Log results
            $affected_rows = $stmt->affected_rows;
            $this->logCleanup("Cleaned up $affected_rows expired sessions");

            // Commit transaction
            $this->conn->commit();
            
            return true;

        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            $this->logCleanup("Error during cleanup: " . $e->getMessage());
            return false;
        }
    }

    private function logCleanup($message) {
        $timestamp = date('Y-m-d H:i:s');
        $log_message = "[$timestamp] $message\n";
        error_log($log_message, 3, $this->cleanup_log_file);
    }
}

// Run cleanup if called directly (not included)
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    $cleanup = new SessionCleanup($conn);
    $cleanup->cleanup();
}
?>