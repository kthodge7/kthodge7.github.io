<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Getting Started - Password Manager</title>
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
        .custom-list li {
            position: relative;
            padding-left: 1.5rem;
        }
        .custom-list li::before {
            content: "•";
            color: #3B82F6;
            font-weight: bold;
            position: absolute;
            left: 0;
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
                    <a href="index.php" class="text-blue-600 hover:text-blue-800 transition-all">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-3xl font-bold mb-8 text-gray-800 border-b pb-4">Getting Started Guide</h2>

            <!-- Introduction Section -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Welcome to Password Manager</h3>
                <div class="bg-blue-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4">Our password manager helps you securely store and manage all your passwords in one place. Follow the steps below to get started.</p>
                </div>
            </div>

            <!-- Registration Steps -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Creating Your Account</h3>
                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-medium text-blue-600 mb-3">Registration Steps</h4>
                        <ol class="list-decimal pl-5 space-y-3 text-gray-700">
                            <li>Click the "Register" button on the homepage</li>
                            <li>Enter your email address</li>
                            <li>Create a strong master password (see guidelines below)</li>
                            <li>Confirm your master password</li>
                            <li>Complete the registration process</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Master Password Guidelines -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Creating a Strong Master Password</h3>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="font-medium text-blue-600 mb-3">Password Requirements</h4>
                    <ul class="custom-list space-y-3 text-gray-700">
                        <li>Minimum 12 characters long</li>
                        <li>Must include at least one uppercase letter</li>
                        <li>Must include at least one lowercase letter</li>
                        <li>Must include at least one number</li>
                        <li>Must include at least one special character</li>
                        <li>Should not be used for any other accounts</li>
                    </ul>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Security Best Practices</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="help-card bg-gray-50 p-4 rounded-lg transition-all">
                        <h4 class="font-medium text-blue-600 mb-2">Remember Your Master Password</h4>
                        <p class="text-gray-600">We cannot recover your master password if you forget it. Make sure to store it securely.</p>
                    </div>
                    <div class="help-card bg-gray-50 p-4 rounded-lg transition-all">
                        <h4 class="font-medium text-blue-600 mb-2">Secure Your Email</h4>
                        <p class="text-gray-600">Use a strong password and enable two-factor authentication on your email account.</p>
                    </div>
                    <div class="help-card bg-gray-50 p-4 rounded-lg transition-all">
                        <h4 class="font-medium text-blue-600 mb-2">Regular Updates</h4>
                        <p class="text-gray-600">Change your master password periodically for enhanced security.</p>
                    </div>
                    <div class="help-card bg-gray-50 p-4 rounded-lg transition-all">
                        <h4 class="font-medium text-blue-600 mb-2">Device Security</h4>
                        <p class="text-gray-600">Always use the password manager on secure, trusted devices.</p>
                    </div>
                </div>
            </div>

            <!-- Support Section -->
            <div class="help-section">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Need Help?</h3>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700">If you need assistance during registration, contact our support team:</p>
                    <p class="mt-3">
                        <a href="mailto:support@example.com" class="text-blue-600 hover:text-blue-800 transition-all">support@example.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>




