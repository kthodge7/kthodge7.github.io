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
    'message' => '',
    'data' => null
];

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = "User not authenticated";
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    try {
        $entry_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Verify ownership and get password details
        $stmt = $conn->prepare("SELECT website, username, password 
                              FROM passwordtbl 
                              WHERE entry_id = ? AND user_id = ?");
                              
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("ii", $entry_id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            $response = [
                'success' => true,
                'website' => $data['website'],
                'username' => $data['username'],
                'password' => $data['password']
            ];
        } else {
            throw new Exception("Password entry not found or access denied");
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        error_log("Error in get_password.php: " . $e->getMessage());
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = "Invalid request";
}

echo json_encode($response);
exit;
?>