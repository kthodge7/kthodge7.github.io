<?php
require_once "config.php";
require_once "login_session.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    
    // First check if account is locked
    if (LoginAttemptHandler::isLocked($email)) {
        $login_err = "Account is locked due to multiple failed attempts. Please try again after 30 minutes.";
    } else {
        // Validate credentials
        if (empty($email)) {
            $login_err = "Please enter email.";
        } elseif (empty(trim($_POST["master_password"]))) {
            $login_err = "Please enter password.";
        } else {
            // Verify credentials
            $sql = "SELECT u.user_id, u.email, a.salt, a.hashed_master_password 
                    FROM usertbl u 
                    JOIN authenticationtbl a ON u.user_id = a.user_id 
                    WHERE u.email = ?";
            
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $user_id, $email, $salt, $hashed_password);
                        if (mysqli_stmt_fetch($stmt)) {
                            $input_hashed_password = hash('sha256', $_POST["master_password"] . $salt);
                            
                            if ($input_hashed_password === $hashed_password) {
                                // Password is correct, reset attempts
                                LoginAttemptHandler::reset($email);
                                
                                // Start new session
                                session_start();
                                
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["user_id"] = $user_id;
                                $_SESSION["email"] = $email;
                                
                                // Redirect to dashboard
                                header("location: dashboard.php");
                                exit();
                            } else {
                                // Increment failed attempts
                                LoginAttemptHandler::increment($email);
                                $remaining = LoginAttemptHandler::getRemainingAttempts($email);
                                $login_err = "Invalid email or password. Attempts remaining: " . $remaining;
                            }
                        }
                    } else {
                        // For security, still increment attempts even if email doesn't exist
                        LoginAttemptHandler::increment($email);
                        $remaining = LoginAttemptHandler::getRemainingAttempts($email);
                        $login_err = "Invalid email or password. Attempts remaining: " . $remaining;
                    }
                } else {
                    $login_err = "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Password Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Sign In</h2>
                <p class="mt-2 text-sm text-gray-600">Password Manager</p>
            </div>

            <?php if(!empty($login_err)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <?php echo $login_err; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-6">
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Password Input -->
                <div>
                    <label for="master_password" class="block text-sm font-medium text-gray-700">Master Password</label>
                    <input type="password" name="master_password" id="master_password" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign In
                </button>

                <!-- Registration Link -->
                <div class="text-center text-sm">
                    <p class="text-gray-600">
                        Don't have an account?
                        <a href="register.php" class="font-medium text-blue-600 hover:text-blue-500">
                            Create one
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>