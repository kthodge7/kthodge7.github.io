<?php
// Start session if needed
session_start();

// If user is already logged in, redirect to dashboard
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager - Secure Password Storage Solution</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Password Manager</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium">Login</a>
                    <a href="register.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="pt-16">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h2 class="text-4xl font-extrabold tracking-tight mb-4">
                        Secure Password Management Made Simple
                    </h2>
                    <p class="text-xl text-blue-100 mb-8">
                        Store, manage, and protect your passwords with military-grade encryption
                    </p>
                    <a href="register.php" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-blue-50 transition duration-300">
                        Create Free Account
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl font-bold text-center mb-12">Key Features</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center p-6 rounded-lg shadow-lg bg-gray-50">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-2">Secure Storage</h4>
                    <p class="text-gray-600">
                        Your passwords are encrypted and stored with the highest security standards
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center p-6 rounded-lg shadow-lg bg-gray-50">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-eye-slash"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-2">Privacy Focused</h4>
                    <p class="text-gray-600">
                        Blurred password display and master password protection
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center p-6 rounded-lg shadow-lg bg-gray-50">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-2">Easy Management</h4>
                    <p class="text-gray-600">
                        Simple interface to add, edit, and manage your passwords
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl font-bold text-center mb-12">How It Works</h3>
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                    <h4 class="font-semibold mb-2">Create Account</h4>
                    <p class="text-gray-600">Sign up with your email and create a master password</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                    <h4 class="font-semibold mb-2">Add Passwords</h4>
                    <p class="text-gray-600">Store your website passwords securely</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                    <h4 class="font-semibold mb-2">Manage Easily</h4>
                    <p class="text-gray-600">Edit and organize your stored passwords</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">4</div>
                    <h4 class="font-semibold mb-2">Access Anywhere</h4>
                    <p class="text-gray-600">Access your passwords securely from anywhere</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl font-bold text-center mb-12">Security Features</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex items-start space-x-4">
                    <div class="text-blue-600 mt-1">
                        <i class="fas fa-shield-alt text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-2">Advanced Encryption</h4>
                        <p class="text-gray-600">Your data is protected with industry-standard encryption protocols</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="text-blue-600 mt-1">
                        <i class="fas fa-user-lock text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-2">Secure Authentication</h4>
                        <p class="text-gray-600">Multi-layered security with master password protection</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="text-blue-600 mt-1">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-2">Auto-Logout</h4>
                        <p class="text-gray-600">Automatic session timeout for enhanced security</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="text-blue-600 mt-1">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-2">Account Protection</h4>
                        <p class="text-gray-600">Brute force protection with account lockout</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-3xl font-bold mb-4">Ready to Secure Your Passwords?</h3>
            <p class="text-xl mb-8">Join thousands of users who trust our password manager</p>
            <div class="space-x-4">
                <a href="register.php" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                    Get Started Free
                </a>
                <a href="login.php" class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                    Login
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h4 class="text-lg font-semibold mb-4">Password Manager</h4>
                    <p class="text-sm">Secure, reliable password management solution</p>
                </div>
                <div class="text-right">
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <div class="space-x-4">
                        <a href="login.php" class="hover:text-white">Login</a>
                        <a href="register.php" class="hover:text-white">Register</a>
                        <a href="registration_help.php" class="hover:text-white">Help</a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm">
                <p>&copy; <?php echo date("Y"); ?> Password Manager. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>