<?php
require_once "config.php";
require_once "check_session.php";

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => ''
];

// Debug session
error_log("Session data in delete_password.php: " . print_r($_SESSION, true));

// Verify user is logged in with proper session data
if (!isset($_SESSION['user_id']) || !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    error_log("Authentication failed. Session data: " . print_r($_SESSION, true));
    $response['message'] = "User not authenticated";
    echo json_encode($response);
    exit;
}

function verifyMasterPassword($conn, $user_id, $master_password) {
    try {
        $stmt = $conn->prepare("SELECT salt, hashed_master_password FROM authenticationtbl WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $hashed_input = hash('sha256', $master_password . $row['salt']);
            $stmt->close();
            return $hashed_input === $row['hashed_master_password'];
        }
        $stmt->close();
        return false;
    } catch (Exception $e) {
        error_log("Error in verifyMasterPassword: " . $e->getMessage());
        throw $e;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Log received data (exclude sensitive information)
        error_log("Received POST data for deletion - Entry ID: " . ($_POST['entry_id'] ?? 'not set'));

        if (!isset($_POST['master_password']) || empty($_POST['master_password'])) {
            throw new Exception("Master password is required");
        }

        if (!isset($_POST['entry_id']) || empty($_POST['entry_id'])) {
            throw new Exception("No password entry specified");
        }

        $user_id = $_SESSION['user_id'];
        
        // Verify master password
        if (!verifyMasterPassword($conn, $user_id, $_POST['master_password'])) {
            throw new Exception("Incorrect master password");
        }

        $entry_id = filter_var($_POST['entry_id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Begin transaction
        $conn->begin_transaction();

        // Verify ownership before deletion
        $stmt = $conn->prepare("SELECT user_id FROM passwordtbl WHERE entry_id = ? AND user_id = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare ownership check: " . $conn->error);
        }

        $stmt->bind_param("ii", $entry_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Password entry not found or access denied");
        }
        $stmt->close();

        // Perform deletion
        $stmt = $conn->prepare("DELETE FROM passwordtbl WHERE entry_id = ? AND user_id = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare delete statement: " . $conn->error);
        }

        $stmt->bind_param("ii", $entry_id, $user_id);
        
        if ($stmt->execute()) {
            $conn->commit();
            $response['success'] = true;
            $response['message'] = "Password deleted successfully";
            error_log("Password deleted successfully for entry_id: $entry_id");
        } else {
            throw new Exception("Error deleting password: " . $stmt->error);
        }
        $stmt->close();

    } catch (Exception $e) {
        error_log("Error in delete_password.php: " . $e->getMessage());
        if (isset($conn)) {
            $conn->rollback();
        }
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = "Invalid request method";
}

echo json_encode($response);
exit;
?>