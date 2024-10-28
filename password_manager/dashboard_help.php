<!-- dashboard-help.php -->
<?php
require_once "config.php";
require_once "check_session.php";
checkLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Documentation - Password Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .transition-all { transition: all 0.3s ease; }
        .help-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .help-section {
            border-left: 4px solid #3B82F6;
            padding-left: 1rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Password Manager</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-blue-600 hover:text-blue-800 transition-all">
                        Back to Dashboard
                    </a>
                    <span class="text-gray-600">
                        <?php echo htmlspecialchars($_SESSION["email"]); ?>
                    </span>
                    <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-all">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-3xl font-bold mb-8 text-gray-800 border-b pb-4">User Guide</h2>

            <!-- Usage Instructions Section -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Using Your Password Manager</h3>
                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-medium text-blue-600 mb-3">Adding a New Password</h4>
                        <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                            <li>Click the "Add New Password" button in the dashboard</li>
                            <li>Enter the website name</li>
                            <li>Enter your username for that website</li>
                            <li>Enter your password</li>
                            <li>Click Save to store the entry</li>
                        </ol>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-medium text-blue-600 mb-3">Editing a Password</h4>
                        <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                            <li>Click the "Edit" button next to the password entry</li>
                            <li>Modify the desired fields</li>
                            <li>Enter your master password to confirm changes</li>
                            <li>Click Save to update the entry</li>
                        </ol>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-medium text-blue-600 mb-3">Deleting a Password</h4>
                        <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                            <li>Click the "Delete" button next to the password entry</li>
                            <li>Enter your master password to confirm deletion</li>
                            <li>Click Delete to remove the entry</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Features</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="help-card bg-gray-50 p-4 rounded-lg transition-all">
                        <h4 class="font-medium text-blue-600 mb-2">Password Protection</h4>
                        <p class="text-gray-600">Passwords are blurred by default and visible only on hover for enhanced security.</p>
                    </div>
                    <div class="help-card bg-gray-50 p-4 rounded-lg transition-all">
                        <h4 class="font-medium text-blue-600 mb-2">Master Password Security</h4>
                        <p class="text-gray-600">Your master password is required for all sensitive operations.</p>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting Section -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Troubleshooting</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="help-card bg-gray-50 p-4 rounded-lg transition-all">
                        <h4 class="font-medium text-blue-600 mb-2">Account Locked</h4>
                        <p class="text-gray-600">After 3 failed login attempts, your account will be locked for 30 minutes.</p>
                    </div>
                    <div class="help-card bg-gray-50 p-4 rounded-lg transition-all">
                        <h4 class="font-medium text-blue-600 mb-2">Session Timeout</h4>
                        <p class="text-gray-600">For security, you'll be logged out after 30 minutes of inactivity.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Support Section -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Need Additional Help?</h3>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700">Contact our support team at:</p>
                    <p class="mt-3">
                        <a href="mailto:support@example.com" class="text-blue-600 hover:text-blue-800 transition-all">support@example.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>