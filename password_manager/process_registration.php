<?php
require_once "config.php";
session_start();

// Initialize response array
$response = [
    'success' => false,
    'errors' => [],
    'redirect' => ''
];

// Function to validate password
function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    }
    if (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    }
    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain at least one number";
    }
    if (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
        $errors[] = "Password must contain at least one special character";
    }
    
    return $errors;
}

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate and sanitize input
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $master_password = trim($_POST["master_password"]);
        $confirm_password = trim($_POST["confirm_password"]);
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['errors'][] = "Invalid email format";
        }
        
        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_id FROM usertbl WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $response['errors'][] = "Email already exists";
        }
        $stmt->close();
        
        // Validate password
        $password_errors = validatePassword($master_password);
        if (!empty($password_errors)) {
            $response['errors'] = array_merge($response['errors'], $password_errors);
        }
        
        // Check if passwords match
        if ($master_password !== $confirm_password) {
            $response['errors'][] = "Passwords do not match";
        }
        
        // If no errors, proceed with registration
        if (empty($response['errors'])) {
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Insert into usertbl
                $stmt = $conn->prepare("INSERT INTO usertbl (email) VALUES (?)");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $user_id = $stmt->insert_id;
                $stmt->close();
                
                // Generate secure salt
                $salt = bin2hex(random_bytes(16));
                
                // Hash password with salt (using longer hash length)
                $hashed_password = hash('sha256', $master_password . $salt);
                
                // Insert into authenticationtbl
                $stmt = $conn->prepare("INSERT INTO authenticationtbl (user_id, salt, hashed_master_password) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $salt, $hashed_password);
                $stmt->execute();
                $stmt->close();
                
                // Commit transaction
                $conn->commit();
                
                // Set success response
                $response['success'] = true;
                $response['redirect'] = 'login.php';
                
                // Store success message in session
                $_SESSION['registration_success'] = "Account created successfully! Please log in.";
                
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                $response['errors'][] = "Registration failed: " . $e->getMessage();
                error_log("Registration error: " . $e->getMessage());
            }
        }
        
    } catch (Exception $e) {
        $response['errors'][] = "System error: " . $e->getMessage();
        error_log("System error during registration: " . $e->getMessage());
    }
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>