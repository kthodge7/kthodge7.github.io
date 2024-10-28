<?php
function verifyPassword($conn, $email, $password) {
    try {
        $stmt = $conn->prepare("SELECT u.user_id, u.email, a.salt, a.hashed_master_password 
                               FROM usertbl u 
                               JOIN authenticationtbl a ON u.user_id = a.user_id 
                               WHERE u.email = ?");
        
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }

        $stmt->bind_param("s", $email);
        
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $hashed_input = hash('sha256', $password . $user['salt']);
            
            if ($hashed_input === $user['hashed_master_password']) {
                return array(
                    'success' => true,
                    'user_id' => $user['user_id'],
                    'email' => $user['email']
                );
            }
        }
        
        return array('success' => false);
    } catch (Exception $e) {
        error_log("Error in verifyPassword: " . $e->getMessage());
        return array('success' => false);
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>