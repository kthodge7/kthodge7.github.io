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
        // Validate and sanitize inputs
        $website = isset($_POST['website']) ? trim($_POST['website']) : '';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $entry_id = isset($_POST['entry_id']) ? filter_var($_POST['entry_id'], FILTER_SANITIZE_NUMBER_INT) : null;

        // Validate required fields
        if (empty($website) || empty($username) || empty($password)) {
            throw new Exception("All fields are required");
        }

        $conn->begin_transaction();

        if (!empty($entry_id)) {
            // Update existing password - require master password
            if (!isset($_POST['master_password']) || empty($_POST['master_password'])) {
                throw new Exception("Master password is required for updating existing passwords");
            }

            // Verify master password
            if (!verifyMasterPassword($conn, $_SESSION['user_id'], $_POST['master_password'])) {
                throw new Exception("Incorrect master password");
            }

            // Verify ownership before update
            $stmt = $conn->prepare("SELECT user_id FROM passwordtbl WHERE entry_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $entry_id, $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("Password entry not found or access denied");
            }
            $stmt->close();

            // Perform update
            $stmt = $conn->prepare("UPDATE passwordtbl SET website = ?, username = ?, password = ? 
                                  WHERE entry_id = ? AND user_id = ?");
            $stmt->bind_param("sssii", $website, $username, $password, $entry_id, $_SESSION['user_id']);
        } else {
            // Insert new password
            $stmt = $conn->prepare("INSERT INTO passwordtbl (user_id, website, username, password) 
                                  VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $_SESSION['user_id'], $website, $username, $password);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error saving password: " . $stmt->error);
        }

        $conn->commit();
        $response['success'] = true;
        $response['message'] = !empty($entry_id) ? "Password updated successfully" : "Password saved successfully";
        $stmt->close();

    } catch (Exception $e) {
        if (isset($conn)) {
            $conn->rollback();
        }
        $response['success'] = false;
        $response['message'] = $e->getMessage();
        error_log("Error in save_password.php: " . $e->getMessage());
    }
} else {
    $response['message'] = "Invalid request method";
}

echo json_encode($response);
exit;
?>